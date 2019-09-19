<?php

namespace App\Listeners;

use App\Events\ProjectAltered;
use App\Helpers\LogHelper;
use App\Models\Project;

class AddProjectsToHosts
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
     * @return void
     */
    public function handle()
    {
        try {
            $template = file_get_contents(resource_path().'/hosts.php');
            Project::where('status', true)
                   ->get()
                   ->each(function ($project) use (&$template) {
                       $template .= view('templates.hosts_entry',['project' => $project])->render();
                   });
            $this->saveToHosts($template);
        } catch (\Throwable $exception) {
            LogHelper::throwError($exception);
        }
    }

    private function saveToHosts(string $content): void
    {
        if (substr(sprintf('%o', fileperms('/etc/hosts')), -4) === '0777') {
            @file_put_contents('/etc/hosts', $content);
        }
    }
}
