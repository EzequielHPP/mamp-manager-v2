<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAsset extends Model
{

    protected $table = 'project_assets';

    protected $fillable = ['project_id', 'preview'];

    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id', 'id');
    }
}
