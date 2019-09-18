<!DOCTYPE html>
<html lang="en" class="uk-height-1-1">
<head>
    <meta charset="UTF-8">
    <title>{{env('APP_NAME')}}</title>
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.2.0/css/uikit.min.css"/>
    <link rel="stylesheet" href="/css/normalize.css"/>
    <link rel="stylesheet" href="/css/app.css"/>
</head>
<body class="uk-height-1-1">
<div>
    <div class="main--container uk-grid-large" uk-grid>
        @foreach($projects as $project)
            @include('project-card',['project' => $project])
        @endforeach
    </div>
</div>
<!-- UIkit JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.2.0/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.2.0/js/uikit-icons.min.js"></script>
<script src="/app/js/app.js"></script>
</body>
</html>
