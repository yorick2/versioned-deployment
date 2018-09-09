<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    /**
     * @return string
     */
    public function path()
    {
        $server = $this->server()->first();
        return '/projects/'.$server->project_id.'/servers/'.$this->server_id.'/deployments/'.$this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
