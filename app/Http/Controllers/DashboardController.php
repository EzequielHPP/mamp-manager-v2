<?php

namespace App\Http\Controllers;

use App\Events\ResetProjects;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\DashboardService;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    /**
     * Display the dasboard / homescreen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {

        $pageData = (new DashboardService())->handleDashboardLandingPage();

        return view('application', $pageData);
    }

    /**
     * Create a new project
     *
     * @param  CreateProjectRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createProject(CreateProjectRequest $request)
    {
        $project = (new ProjectService())->create(
            $request->title,
            $request->url,
            $request->folder,
            $request->active == 'true' ? true : false,
            $request->secureUrl == 'true' ? true : false,
            $request->asset ?? null
        );

        return redirect()->to(route('dashboard'));
    }

    /**
     * Update the project
     *
     * @param  UpdateProjectRequest  $request
     * @param int $projectId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProject(UpdateProjectRequest $request, int $projectId)
    {
        $updateProject = (new ProjectService())->update($projectId, $request->toArray());
        if ($updateProject['status']) {
            $project_setting_id = Project::find($projectId)->settings->id;
            $updateProject = (new ProjectService())->updateSettings(
                $projectId,
                $project_setting_id,
                $request->toArray()
            );

            if ($updateProject['status']) {
                return redirect()->to(route('dashboard'));
            }
        }

        return back();

    }

    /**
     * Delete a project
     *
     * @param  Request  $request
     * @param  int  $projectId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteProject(Request $request, int $projectId)
    {
        $project = (new ProjectService())->delete($projectId);

        return redirect()->to(route('dashboard'));
    }

    /**
     * Reset all projects
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetProjects(Request $request)
    {
        event(new ResetProjects());

        return redirect()->to(route('dashboard'));
    }
}
