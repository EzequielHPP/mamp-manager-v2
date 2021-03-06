@if((UrlHelper::isSecure() ? 'https://' : 'http://').$project->settings->url !== env('APP_URL'))
    @php($image = 'https://picsum.photos/200/200?grayscale?random='.$index)
    @if(!is_null($project->asset->preview ?? null))
        @php($image = $project->asset->preview)
    @endif
    <div>
        <div class="uk-card uk-card-default uk-card-body uk-box-shadow-small uk-box-shadow-hover-large uk-padding">
            <img src="{{$image}}" class="uk-border-circle" width="200" height="200">
            <h3 class="uk-card-title">@if($project->settings->https)
                    <span class="uk-badge"><span uk-icon="lock"></span></span>
                @endif{{$project->title}}</h3>
            <hr class="uk-divider-icon">
            <div>
                <a class="uk-button uk-button-default"
                   href="http{{$project->settings->https ? 's':''}}://{{$project->settings->url}}"
                   target="_blank" uk-icon="push"></a>
                <a class="uk-button uk-button-default" href="#project-settings-{{$project->id}}" uk-toggle><span
                            uk-icon="icon: cog"></span></a>
                <a class="uk-button uk-button-danger" href="#project-delete-{{$project->id}}" uk-toggle><span
                            uk-icon="trash"></span></a>
            </div>
        </div>

        @include('modals.project-settings',['project'=>$project])
        @include('modals.project-delete',['project'=>$project])
    </div>
@endif
