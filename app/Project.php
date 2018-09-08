<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function path()
    {
        return '/projects/'.$this->id;
    }

    public function servers()
    {
        return $this->hasMany(Server::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
