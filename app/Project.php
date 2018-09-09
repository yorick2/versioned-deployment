<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    /**
     * @return string
     */
    public function path()
    {
        return '/projects/'.$this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servers()
    {
        return $this->hasMany(Server::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * @param array $serverData
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addServer(array $serverData)
    {
        $server = $this->servers()->create($serverData);
        return redirect($server->path());
    }
}
