@extends('templates.httpd-vhosts')

@section('projects')
    @foreach($projects as $project)
        <VirtualHost *:80>
            ServerAdmin email@email.com
            DocumentRoot "{{$project->directory}}"
            ServerName {{$project->url}}
            ErrorLog "/Applications/MAMP/logs/{{UrlHelper::logFriendly($project->url)}}-error_log"
            CustomLog "/Applications/MAMP/logs/{{UrlHelper::logFriendly($project->url)}}-access_log" common
            <Directory "{{$project->directory}}">
                Options Indexes FollowSymLinks
                AllowOverride All
            </Directory>
        </VirtualHost>
    @endforeach
@endsection
