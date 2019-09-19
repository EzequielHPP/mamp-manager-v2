<div id="project-delete-{{$project->id}}" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">{{$project->title}}</h2>

        <form action="{{route('delete-project',['id' => $project->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <p>Are you sure you want to delete this project?</p>

            <p class="uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-danger" type="submit">Delete</button>
            </p>
        </form>
    </div>
</div>
