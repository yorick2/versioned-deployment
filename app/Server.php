<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    public function path()
    {
        return '/projects/'.$this->project_id.'/servers/'.$this->id;
    }

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }
}
