<?php

namespace App\Listeners;

use App\Events\ProjectAltered;
use App\Events\ResetProjects;
use App\Helpers\LogHelper;
use App\Models\Project;
use App\Models\ProjectSetting;
use App\Models\Settings;

class PopulateAllVhosts
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
     * @param  ResetProjects  $event
     *
     * @return void
     */
    public function handle(ResetProjects $event)
    {
        try {
            $this->handleProjectsOfType();
            $this->handleProjectsOfType(true);
        } catch (\Throwable $exception) {
            LogHelper::throwError($exception);
        }

    }

    private function handleProjectsOfType(bool $https = false): void
    {
        $settings = (new Settings());
        $projects = ProjectSetting::where('https', ($https ? '1' : '0'))
                                  ->get();

        $location = $settings->getSetting('basic.mamp_location.apache');
        $crt_location = $settings->getSetting('basic.certificate.crt');
        $key_location = $settings->getSetting('basic.certificate.key');
        if (!$https) {
            $file = $settings->getSetting('basic.mamp_vhosts_file_name');
            $template = $settings->getSetting('basic.template_http');
        } else {
            $file = $settings->getSetting('basic.mamp_ssl_file_name');
            $template = $settings->getSetting('basic.template_https');
        }
        if (!is_null($location) && !is_null($file) && !is_null($template)) {
            $storeInPath = rtrim($location, '/').'/'.$file;
            $render = view($template,
                compact(['projects', 'crt_location', 'key_location'])
            )->render();

            file_put_contents($storeInPath, $render);
        }
    }
}
