<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectSetting extends Model
{
    protected $table = 'project_settings';

    protected $fillable = ['project_id','url','https','directory','icon','status'];
}
