<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\DashboardService;
use App\Services\ProjectService;

class DashboardController extends Controller
{

    public function dashboard()
    {

        $pageData = (new DashboardService())->handleDashboardLandingPage();

        return view('application', $pageData);
    }

    public function createProject(CreateProjectRequest $request)
    {
        $project = (new ProjectService())->create(
            $request->title,
            $request->url,
            $request->folder,
            $request->active == 'true' ? true : false,
            $request->secureUrl == 'true' ? true : false
        );

        return redirect()->to(route('dashboard'));
    }

    public function updateProject(UpdateProjectRequest $request, $projectId)
    {
        $updateProject = (new ProjectService())->update($projectId, $request->toArray());
        if ($updateProject['status']) {
            $project_setting_id = Project::find($projectId)->settings->id;
            $updateProject = (new ProjectService())->updateSettings(
                $projectId,
                $project_setting_id,
                $request->toArray()
            );

            if($updateProject['status']) {
                return redirect()->to(route('dashboard'));
            }
        }

        return back();

    }
}
