<div id="project-settings-{{$project->id}}" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">{{$project->title}}</h2>

        <form action="{{route('update-project',['id' => $project->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <fieldset class="uk-fieldset uk-grid-small" uk-grid>

                <div class=" uk-width-1-1">
                    <label class="uk-form-label" for="create-title">Title</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="create-title" name="title" type="text" placeholder="Project title" value="{{$project->title}}" required>
                    </div>
                </div>

                <div class="uk-width-1-1">
                    <label class="uk-form-label" for="create-url">Url</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="create-url" name="url" type="text"
                               placeholder="something.local.com" required value="{{$project->settings->url}}">
                    </div>
                </div>

                <div class="uk-width-1-1">
                    <label class="uk-form-label" for="create-folder">Project Folder</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="create-folder" name="folder" type="text"
                               placeholder="/var/www/project/public" required value="{{$project->settings->directory}}">
                    </div>
                </div>

                <div class="uk-width-1-2@s uk-display-inline">
                    <div class="uk-form-label">Is the project active?</div>
                    <div class="uk-form-controls">
                        <label><input class="uk-radio" type="radio" name="status" value="true" @if($project->status){{'checked'}}@endif > Yes</label><br>
                        <label><input class="uk-radio" type="radio" name="status" value="false" @if(!$project->status){{'checked'}}@endif > No</label>
                    </div>
                </div>

                <div class="uk-width-1-2@s uk-display-inline">
                    <div class="uk-form-label">Should the project be on https?</div>
                    <div class="uk-form-controls">
                        <label><input class="uk-radio" type="radio" name="secureUrl" value="true" @if($project->settings->https){{'checked'}}@endif > Yes</label><br>
                        <label><input class="uk-radio" type="radio" name="secureUrl" value="false" @if(!$project->settings->https){{'checked'}}@endif > No</label>
                    </div>
                </div>


            </fieldset>

            <p class="uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary" type="submit">Save</button>
            </p>
        </form>
    </div>
</div>
