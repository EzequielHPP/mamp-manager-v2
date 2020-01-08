<?php


namespace App\Services;


use App\Events\ProjectAltered;
use App\Helpers\LogHelper;
use App\Helpers\UrlHelper;
use App\Models\Project;
use App\Models\ProjectAsset;
use App\Models\ProjectSetting;
use App\Models\Settings;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ProjectService
{

    protected $errors = [];

    /**
     * Preparation for the create project handler
     *
     * @param  string  $title
     * @param  string  $url
     * @param  string  $folder
     * @param  bool  $active
     * @param  bool  $secureUrl
     * @param  string|null  $asset
     *
     * @return array
     */
    public function create(
        string $title,
        string $url,
        string $folder,
        bool $active = true,
        bool $secureUrl = false,
        string $asset = null
    ): array {
        $output = ['status' => false];

        if ($this->validateProjectValues($title, $url, $folder)) {
            $project = $this->createProject($title, $active);
            if ($project['status']) {
                $projectSettings = $this->createSettings(
                    $project['id'],
                    $url,
                    $folder,
                    $secureUrl,
                    true,
                    $asset
                );
                if ($projectSettings['status']) {
                    $output['status'] = true;
                    $output['id'] = $project['id'];
                    event(new ProjectAltered($project['id'],$projectSettings));
                } else {
                    $output['message'] = $projectSettings['message'];
                }
            } else {
                $output['message'] = 'Couldn\'t create main project row';
            }
        } else {
            $output['message'] = 'Invalid project values';
        }

        return $output;
    }

    /**
     * Prepares to update the project
     *
     * @param  int  $project_id
     * @param  array  $newData
     *
     * @return array
     */
    public function update(int $project_id, array $newData): array
    {
        $output = ['status' => false];
        if (
            $this->validateProject($project_id) &&
            $this->validateUpdateKeys($newData)
        ) {
            $output = $this->updateProject($project_id, $newData['title'], $newData['status']);
            event(new ProjectAltered($project_id, $newData));
        } else {
            if (!$this->validateProject($project_id)) {
                $output['message'] = 'Not a valid project '.$project_id;
            }
            if (!$this->validateUpdateKeys($newData)) {
                $output['message'] = 'No valid data';
            }
        }

        return $output;
    }

    /**
     * Deletes the project
     *
     * @param  int  $project_id
     *
     * @return array
     */
    public function delete(int $project_id): array
    {
        $output = ['status' => false];
        if (
        $this->validateProject($project_id)
        ) {
            Project::find($project_id)
                   ->delete();
            $output['status'] = true;
        }

        return $output;
    }

    /**
     * Deletes the project
     *
     * @param  int  $project_id
     * @param  int  $project_setting_id
     *
     * @return array
     */
    public function deleteSetting(int $project_id, int $project_setting_id): array
    {
        $output = ['status' => false];
        if (
            $this->validateProject($project_id) &&
            $this->validateProjectSetting($project_setting_id)
        ) {
            ProjectSetting::find($project_setting_id)
                          ->delete();
            $output['status'] = true;
        }

        return $output;
    }

    /**
     * Prepares to update the project
     *
     * @param  int  $project_id
     * @param  int  $project_setting_id
     * @param  array  $newData
     *
     * @return array
     */
    public function updateSettings(int $project_id, int $project_setting_id, array $newData): array
    {
        $output = ['status' => false];
        if (
            $this->validateProject($project_id) &&
            $this->validateProjectSetting($project_setting_id) &&
            $this->validateUpdateSettingKeys($newData)
        ) {
            $output = $this->updateProjectSettings($project_setting_id, $newData);
        } else {
            if (!$this->validateProject($project_id)) {
                $output['message'] = 'Not a valid project '.$project_id;
            }
            if (!$this->validateProjectSetting($project_setting_id)) {
                $output['message'] = 'Not a valid setting';
            }
            if (!$this->validateUpdateSettingKeys($newData)) {
                $output['message'] = 'No valid data';
            }
        }

        return $output;
    }

    /**
     * Creates the project with the already validated title and active status
     *
     * @param  string  $title
     * @param  bool  $active
     *
     * @return array
     */
    protected function createProject(string $title, bool $active): array
    {
        $output = [
            'status' => false
        ];

        try {
            $project = new Project();
            $project->title = $title;
            $project->status = $active;
            $project->save();

            $output['id'] = $project->id;
            $output['status'] = true;

        } catch (\Throwable $exception) {
            LogHelper::throwError($exception);
        }

        return $output;
    }

    /**
     * Update processor for the project
     *
     * @param  int  $projectId
     * @param  string  $title
     * @param  bool  $active
     *
     * @return array
     */
    protected function updateProject(int $projectId, string $title, bool $active): array
    {
        $output = [
            'status' => false
        ];
        $v = Validator::make([], []);

        try {
            $project = Project::find($projectId);
            $project->title = $title;
            $project->status = $active;
            $project->save();

            $output['id'] = $project->id;
            $output['status'] = true;

        } catch (\Throwable $exception) {
            LogHelper::throwError($exception);
            $v->getMessageBag()
              ->add('updateProject', $exception->getMessage());
        }

        return $output;
    }

    /**
     * Create a new settings entry
     *
     * @param  int  $projectId
     * @param  string  $url
     * @param  string  $folder
     * @param  bool  $secureUrl
     * @param  bool  $status
     * @param  string|null  $asset
     *
     * @return array
     */
    public function createSettings(
        int $projectId,
        string $url,
        string $folder,
        bool $secureUrl = false,
        bool $status = true,
        string $asset = null
    ): array {
        $output = [
            'status' => false,
            'message' => ''
        ];

        try {
            $setting = new ProjectSetting();
            $setting->project_id = $projectId;
            $setting->url = $url;
            $setting->https = $secureUrl == 'true' ? true : false;;
            $setting->directory = $folder;
            $setting->icon = (new Settings())->getSetting('basic.settings_default_icon');
            $setting->status = $status;

            $setting->save();

            $output['status'] = true;
            $output['id'] = $setting->id;

            if (!is_null($asset)) {
                $assetCreated = $this->handleAssetForProject($projectId, $asset);
                if (!$assetCreated['status']) {
                    $output['status'] = false;
                }
            }

        } catch (\Throwable $exception) {
            LogHelper::throwError($exception);
            $output['message'] = $exception->getMessage();
        }

        return $output;
    }

    /**
     * Update processor for the project settings
     *
     * @param  int  $projectSettingId
     * @param  array  $data
     *
     * @return array
     */
    protected function updateProjectSettings(int $projectSettingId, array $data):
    array {
        $output = [
            'status' => false
        ];
        $v = Validator::make([], []);

        try {
            $projectSetting = ProjectSetting::find($projectSettingId);
            $projectSetting->url = $data['url'];
            $projectSetting->https = $data['secureUrl'] == 'true' ? true : false;
            $projectSetting->directory = $data['folder'];
            if (array_key_exists('icon', $data)) {
                $projectSetting->icon = $data['icon'];
            }
            $projectSetting->status = $data['status'] == 'true' ? true : false;
            $projectSetting->save();

            $output['id'] = $projectSetting->id;
            $output['status'] = true;

            if (array_key_exists('asset', $data) && !is_null($data['asset'])) {
                $assetCreated = $this->handleAssetForProject($projectSetting->project_id, $data['asset']);
                if (!$assetCreated['status']) {
                    $output['status'] = false;
                }
            } else {
                $assetDeleted = $this->deleteAssetFromProject($projectSetting->project_id);
            }

        } catch (\Throwable $exception) {
            LogHelper::throwError($exception);
            $v->getMessageBag()
              ->add('updateProject', $exception->getMessage());
        }

        return $output;
    }

    /**
     * Attach an asset to a project
     *
     * @param  int  $projectId
     * @param  string  $asset
     *
     * @return array
     */
    protected function handleAssetForProject(int $projectId, string $asset): array
    {
        $output = [
            'status' => false
        ];
        $v = Validator::make([], []);

        try {
            $foundAsset = ProjectAsset::where('project_id', $projectId)
                                      ->firstOrFail();
            $output = $this->updateAsset($foundAsset->id, $asset);
        } catch (ModelNotFoundException $exception) {
            $output = $this->createAsset($projectId, $asset);
        }

        return $output;
    }

    /**
     * Attach an asset to a project
     *
     * @param  int  $projectId
     * @param  string  $asset
     *
     * @return array
     */
    protected function createAsset(int $projectId, string $asset): array
    {
        $output = [
            'status' => false
        ];
        $v = Validator::make([], []);

        try {
            $newAsset = new ProjectAsset();
            $newAsset->project_id = $projectId;
            $newAsset->preview = $asset;
            $newAsset->save();

            $output['id'] = $newAsset->id;
            $output['status'] = true;

        } catch (\Throwable $exception) {
            LogHelper::throwError($exception);
            $v->getMessageBag()
              ->add('createAsset', $exception->getMessage());
        }

        return $output;
    }

    /**
     * Remove an asset from a project
     *
     * @param  int  $projectId
     * @param  string  $asset
     *
     * @return array
     */
    protected function deleteAssetFromProject(int $projectId): array
    {
        $output = [
            'status' => false
        ];
        $v = Validator::make([], []);

        try {
            $asset = ProjectAsset::where('project_id',$projectId)->firstOrFail();
            $asset->delete();

            $output['status'] = true;

        } catch (ModelNotFoundException $exception) {
            // Do nothing
        }

        return $output;
    }

    /**
     * Update asset url
     *
     * @param  int  $assetId
     * @param  string  $asset
     *
     * @return array
     */
    protected function updateAsset(int $assetId, string $asset): array
    {
        $output = [
            'status' => false
        ];
        $v = Validator::make([], []);

        try {
            $assetUpdate = ProjectAsset::find($assetId);
            $assetUpdate->preview = $asset;
            $assetUpdate->save();

            $output['status'] = true;

        } catch (\Throwable $exception) {
            LogHelper::throwError($exception);
            $v->getMessageBag()
              ->add('updateAsset', $exception->getMessage());
        }

        return $output;
    }

    /**
     * Validates the provided fields
     *
     * @param  string  $title
     * @param  string  $url
     * @param  string  $folder
     *
     * @return bool
     */
    private function validateProjectValues(
        string $title,
        string $url,
        string $folder
    ): bool {
        $valid = true;
        $v = Validator::make([], []);

        if (empty($title)) {
            $v->getMessageBag()
              ->add('title', 'Empty title');
            $valid = false;
        }
        if (empty($url)) {
            $v->getMessageBag()
              ->add('url', 'Empty url');
            $valid = false;
        }
        if (empty($folder)) {
            $v->getMessageBag()
              ->add('folder', 'Empty folder');
            $valid = false;
        }

        if (!UrlHelper::validate($url)) {
            $v->getMessageBag()
              ->add('url', 'Invalid url');
            $valid = false;
        }

        return $valid;
    }

    /**
     * Goes through the fields that are required to be submitted on update
     *
     * @param  array  $array
     *
     * @return bool
     */
    private function validateUpdateKeys(array $array): bool
    {
        $return = true;
        if (!array_key_exists('title', $array)) {
            $return = false;
        }
        if (!array_key_exists('status', $array)) {
            $return = false;
        }

        return $return;
    }

    /**
     * Goes through the fields that are required to be submitted on update setting
     *
     * @param  array  $array
     *
     * @return bool
     */
    private function validateUpdateSettingKeys(array $array): bool
    {
        $return = true;

        if (!array_key_exists('url', $array)) {
            $return = false;
        }
        if (!array_key_exists('folder', $array)) {
            $return = false;
        }
        if (!array_key_exists('secureUrl', $array)) {
            $return = false;
        }

        return $return;
    }

    /**
     * Does the project exist?
     *
     * @param  int  $projectId
     *
     * @return bool
     */
    private function validateProject(int $projectId): bool
    {
        $return = false;
        try {
            $project = Project::where('id', $projectId)
                              ->firstOrFail();
            $return = true;
        } catch (ModelNotFoundException $exception) {
            LogHelper::throwError($exception);
        }

        return $return;
    }

    /**
     * Does the project setting exist?
     *
     * @param  int  $project_setting_id
     *
     * @return bool
     */
    private function validateProjectSetting(int $project_setting_id): bool
    {
        $return = false;
        try {
            $projectSetting = ProjectSetting::where('id', $project_setting_id)
                                            ->firstOrFail();
            $return = true;
        } catch (ModelNotFoundException $exception) {
            LogHelper::throwError($exception);
        }

        return $return;
    }
}
