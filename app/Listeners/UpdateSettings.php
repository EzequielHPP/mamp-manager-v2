<?php

namespace App\Listeners;

use App\Events\ProjectAltered;
use App\Helpers\LogHelper;
use App\Models\Project;
use App\Models\ProjectSetting;
use App\Models\Settings;
use App\Services\ProjectService;

class UpdateSettings
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProjectAltered  $event
     *
     * @return void
     */
    public function handle(ProjectAltered $event)
    {
        $project_setting_id = Project::find($event->projectId)->settings->id;
        (new ProjectService())->updateSettings(
            $event->projectId,
            $project_setting_id,
            $event->settings
        );

    }
}
