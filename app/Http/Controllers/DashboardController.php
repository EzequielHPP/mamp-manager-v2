<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
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
}
