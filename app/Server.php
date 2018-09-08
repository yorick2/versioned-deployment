<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    public function path()
    {
        return '/projects/'.$this->project_id.'/servers/'.$this->id;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
