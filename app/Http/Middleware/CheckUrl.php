<?php

namespace App\Http\Middleware;

use App\Models\ProjectSetting;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CheckUrl
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $localUrl = env('APP_URL');
        $requestUrl = str_replace(
            ($request->secure() ? 'https://' : 'http://'),
            '',
            $request->url()
        );
        if ($requestUrl !== $localUrl) {
            try {
                $project = ProjectSetting::where('url', $requestUrl)
                                         ->firstOrFail();
                if ($project->https == 1 && !$request->secure()) {
                    return redirect()->to('https://'.$project->url);
                } elseif ($project->https == 0 && $request->secure()) {
                    return redirect()->to('http://'.$project->url);
                }
            } catch (ModelNotFoundException $exception) {
                abort(404);
            }
            abort(404);
        }
        return $next($request);
    }
}
