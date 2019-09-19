<div>
    <div class="uk-card uk-card-default uk-card-body uk-box-shadow-small uk-box-shadow-hover-large uk-padding">
        <img src="https://picsum.photos/200/200?grayscale?random={{$index}}" class="uk-border-circle">
        <h3 class="uk-card-title">{{$project->title}}</h3>
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
