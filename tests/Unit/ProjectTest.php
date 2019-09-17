<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\ProjectSetting;
use App\Models\Settings;
use App\Services\ProjectService;
use Faker\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class ProjectTest extends TestCase
{

    /**
     * Test creating a project
     *
     * @return void
     */
    public function testCreateProject()
    {
        $randomTitle = Factory::create()
                              ->slug();
        $project = (new ProjectService())->create(
            $randomTitle,
            'http://'.$randomTitle.'.com',
            'folder',
            true,
            true
        );
        $this->assertTrue($project['status']);
    }

    /**
     * Test creating a project and updating the
     *
     * @return void
     */
    public function testCreateProjectAndUpdate()
    {
        $randomTitle = Factory::create()
                              ->slug();
        $project = (new ProjectService())->create(
            $randomTitle,
            'http://'.$randomTitle.'.com',
            'folder',
            true,
            true
        );

        $settings = (new Settings());
        $location = $settings->getSetting('basic.mamp_location.apache');
        $file = $settings->getSetting('basic.mamp_ssl_file_name');
        $fullPath = rtrim($location, '/').'/'.$file;
        $this->assertTrue(file_exists($fullPath));

        $this->assertTrue(strpos(file_get_contents($fullPath), $randomTitle) !== false);
    }

    /**
     * Test updating a project
     *
     * @return void
     */
    public function testUpdateProject()
    {
        $randomTitle = Factory::create()
                              ->slug();
        $project = (new ProjectService())->create(
            $randomTitle,
            'http://'.$randomTitle.'.com',
            'folder',
            true,
            true
        );

        if ($project['status']) {
            $randomTitle = Factory::create()
                                  ->slug();
            $newUpdate = [
                'title' => $randomTitle,
                'active' => true,
            ];
            $updateStatus = (new ProjectService())->update(
                $project['id'],
                $newUpdate
            );
            $this->assertTrue($updateStatus['status']);
        }
        $this->assertTrue($project['status']);
    }

    /**
     * Test updating a project
     *
     * @return void
     */
    public function testUpdateProjectSettings()
    {
        $randomTitle = Factory::create()
                              ->slug();
        $project = (new ProjectService())->create(
            $randomTitle,
            'http://'.$randomTitle.'.com',
            'folder',
            true,
            true
        );

        if ($project['status']) {
            $randomTitle = Factory::create()
                                  ->slug();

            $projectSetting = Project::find($project['id'])->settings->first();
            $newUpdate = [
                'url' => 'http://'.$randomTitle.'.com',
                'folder' => __DIR__,
                'icon' => 'heart',
                'secureUrl' => false,
                'status' => true
            ];
            $updateStatus = (new ProjectService())->updateSettings(
                $project['id'],
                $projectSetting->id,
                $newUpdate
            );
            $this->assertTrue($updateStatus['status']);

            $projectSetting = Project::find($project['id'])->settings->first();

            $this->assertTrue($projectSetting->url == $newUpdate['url']);

        }
        $this->assertTrue($project['status']);
    }

    /**
     * Test updating a project
     *
     * @return void
     */
    public function testCreateProjectSettings()
    {
        $randomTitle = Factory::create()
                              ->slug();
        $project = (new ProjectService())->create(
            $randomTitle,
            'http://'.$randomTitle.'.com',
            'folder',
            true,
            true
        );

        if ($project['status']) {
            $randomTitle = Factory::create()
                                  ->slug();
            $newSetting = [
                'url' => 'http://'.$randomTitle.'.com',
                'folder' => __DIR__,
                'icon' => 'heart',
                'secureUrl' => false,
                'status' => true
            ];
            $createStatus = (new ProjectService())->createSettings(
                $project['id'],
                $newSetting['url'],
                $newSetting['folder'],
                $newSetting['secureUrl'],
                $newSetting['status']
            );
            $this->assertTrue($createStatus['status']);

            try {
                $projectSetting = Project::find($project['id'])->settings->where('url',
                    $newSetting['url'])->first();

                $found = $projectSetting->url == $newSetting['url'];
            } catch (ModelNotFoundException $exception){
                $found = false;
            }
            $this->assertTrue($found);

        }
        $this->assertTrue($project['status']);
    }

    /**
     * Test deleting a project
     *
     * @return void
     */
    public function testDeleteProject()
    {
        $randomTitle = Factory::create()
                              ->slug();
        $project = (new ProjectService())->create(
            $randomTitle,
            'http://'.$randomTitle.'.com',
            'folder',
            true,
            true
        );

        if ($project['status']) {
            $updateStatus = (new ProjectService())->delete(
                $project['id']
            );
            $this->assertTrue($updateStatus['status']);

            $stillThere = true;
            try {
                $projectOutput = Project::where('id',$project['id'])->firstOrFail();
            } catch (ModelNotFoundException $exception){
                $stillThere = false;
            }
            $this->assertFalse($stillThere);


        }
        $this->assertTrue($project['status']);
    }

    /**
     * Test deleting a project
     *
     * @return void
     */
    public function testDeleteProjectSetting()
    {
        $randomTitle = Factory::create()
                              ->slug();
        $project = (new ProjectService())->create(
            $randomTitle,
            'http://'.$randomTitle.'.com',
            'folder',
            true,
            true
        );

        if ($project['status']) {
            $projectSetting = Project::find($project['id'])->settings->first();
            $updateStatus = (new ProjectService())->deleteSetting(
                $project['id'],
                $projectSetting->id
            );
            $this->assertTrue($updateStatus['status']);

            $stillThere = true;
            try {
                $projectSetting = ProjectSetting::where('id',$projectSetting->id)->firstOrFail();
            } catch (ModelNotFoundException $exception){
                $stillThere = false;
            }
            $this->assertFalse($stillThere);


        }
        $this->assertTrue($project['status']);
    }
}
