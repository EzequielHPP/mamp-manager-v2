<?php


namespace App\Services;


use App\Models\Project;

class DashboardService
{

    public function handleDashboardLandingPage(): array
    {
        $output = [];

        $output['projects'] = Project::orderBy('title','ASC')->get();

        return $output;
    }
}
