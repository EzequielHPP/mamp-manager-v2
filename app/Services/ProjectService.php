<?php


namespace App\Services;


use App\Events\ProjectAltered;
use App\Helpers\LogHelper;
use App\Helpers\UrlHelper;
use App\Models\Project;
use App\Models\ProjectSetting;
use App\Models\Settings;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProjectService
{

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
                    event(new ProjectAltered($project['id']));
                }
            }
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
            $output = $this->updateProject($project_id, $newData['title'], $newData['active']);
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

        try {
            $project = Project::find($projectId);
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
    protected function createSettings(
        int $projectId,
        string $url,
        string $folder,
        bool $secureUrl = false,
        bool $status = true,
        string $asset = null
    ): array {
        $output = [
            'status' => false
        ];

        try {
            $setting = new ProjectSetting();
            $setting->project_id = $projectId;
            $setting->url = $url;
            $setting->https = $secureUrl;
            $setting->directory = $folder;
            $setting->icon = (new Settings())->getSetting('basic.settings_default_icon');
            $setting->status = $status;

            $setting->save();

            $output['status'] = true;
            $output['id'] = $setting->id;

        } catch (\Throwable $exception) {
            LogHelper::throwError($exception);
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
        try {
            $projectSetting = ProjectSetting::find($projectSettingId);
            $projectSetting->url = $data['url'];
            $projectSetting->https = $data['secureUrl'];
            $projectSetting->directory = $data['folder'];
            $projectSetting->icon = $data['icon'];
            $projectSetting->status = $data['status'];
            $projectSetting->save();

            $output['id'] = $projectSetting->id;
            $output['status'] = true;

        } catch (\Throwable $exception) {
            LogHelper::throwError($exception);
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

        if (empty($title) || empty($url) || empty($folder)) {
            $valid = false;
        }

        if (!UrlHelper::validate($url)) {
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
        if (!array_key_exists('active', $array)) {
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
        if (!array_key_exists('icon', $array)) {
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
