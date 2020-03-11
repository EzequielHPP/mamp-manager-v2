@extends('templates.httpd-ssl')

@section('projects')
    @foreach($projects as $project)
        <VirtualHost *:443>
            DocumentRoot "{{$project->directory}}"
            ServerName {{$project->url}}
            ErrorLog "/Applications/MAMP/logs/{{UrlHelper::logFriendly($project->url)}}-error_log"
            CustomLog "/Applications/MAMP/logs/{{UrlHelper::logFriendly($project->url)}}-access_log" common

            SSLEngine on
            SSLProtocol all -SSLv2
            SSLCipherSuite HIGH:MEDIUM:!aNULL:!MD5
            SSLCertificateFile {{$crt_location}}
            SSLCertificateKeyFile {{$key_location}}

            <Directory "{{$project->directory}}">
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
            </Directory>
        </VirtualHost>
    @endforeach
@endsection
