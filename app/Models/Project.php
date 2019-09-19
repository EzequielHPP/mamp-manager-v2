<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $table = 'project';

    protected $fillable = ['title'];

    public function settings()
    {
        return $this->hasOne('App\Models\ProjectSetting', 'project_id', 'id');
    }
}
