<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{env('APP_NAME')}}</title>

    <link rel="apple-touch-icon" sizes="57x57" href="/img/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/img/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/img/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/img/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/img/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/img/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/img/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/img/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/img/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="manifest" href="/img/manifest.json">
    <meta name="msapplication-TileColor" content="#413C58">
    <meta name="msapplication-TileImage" content="/img/ms-icon-144x144.png">
    <meta name="theme-color" content="#413C58">

    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.2.0/css/uikit.min.css"/>
    <link rel="stylesheet" href="/css/normalize.css"/>
    <link rel="stylesheet" href="/css/app.css"/>
</head>
<body>
<div>
    <nav class="uk-navbar-container  uk-box-shadow-large uk-margin" uk-navbar uk-sticky="bottom: #offset">
        <div class="uk-navbar-center">

            <div class="uk-navbar-center-left">
                <div>
                    <ul class="uk-navbar-nav">
                        <li class="uk-active"><a href="#create-project" uk-toggle>Create</a></li>

                    </ul>
                </div>
            </div>
            <a class="uk-navbar-item uk-logo" href="/"><img src="/img/logo.png" width="200"></a>
            <div class="uk-navbar-center-right">
                <div>
                    <ul class="uk-navbar-nav">
                        <li><a href="{{route('reset')}}">Reset</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </nav>
    @if (count($errors) > 0)
        <div class="main--container">
            <div class="uk-alert-warning uk-animation-fade uk-transform-origin-top-center" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <ul>
                    @foreach ($errors->all() as $field => $error)
                        <li>{{ $field.' => '.$error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="main--container uk-grid-match uk-grid-column-small uk-grid-row-large uk-child-width-1-3@s uk-text-center"
         uk-grid>
        @foreach($projects as $index => $project)
            @include('project-card',['project' => $project])
        @endforeach
        @include('project-card-placeholder')
    </div>


    @include('modals.create-project')
</div>
<!-- UIkit JS -->
<script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.2.0/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.2.0/js/uikit-icons.min.js"></script>
<script src="/js/app.js"></script>
</body>
</html>
