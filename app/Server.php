<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{

    protected $guarded = [];

    /**
     * @return string
     */
    public function path()
    {
        return '/projects/'.$this->project_id.'/servers/'.$this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
