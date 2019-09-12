<?php

namespace App;

use App;
use Illuminate\Database\Eloquent\Model;

class Deployment extends Model implements DeploymentInterface
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $serverDate;

    /**
     * @var string
     */
    protected $releaseLocation = null;

    /**
     * @return string
     */
    public function path()
    {
        return $this->server()->first()->path().'/deployments/'.$this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(App::make('App\ServerInterface'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(App::make('App\UserInterface'),'user_id');
    }

    /**
     * @return string relative location of the current release
     */
    public function getCurrentReleaseLocation(){
        if ($this->releaseLocation===null){
            $this->releaseLocation = "{$this->server->deploy_location}/releases/{$this->getServerDate()}";
        }
        return $this->releaseLocation;
    }

    /**
     * @return string
     */
    protected function getServerDate(){
        if($this->serverDate){
            return $this->serverDate;
        }
        $this->serverDate = date("Y-m-d_H-i-s");
        return $this->serverDate;
    }
}
