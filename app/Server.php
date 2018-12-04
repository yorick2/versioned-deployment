<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Null_;

class Server extends Model
{

    protected $guarded = [];

    /**
     * @return string
     */
    public function path()
    {
        return $this->project()->first()->path().'/servers/'.$this->slug;
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

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setDeployPasswordAttribute($value){
        if (!strlen($value)){
            return;
        }
        $this->attributes['deploy_password'] = $value;
        $connection = new SshConnection($this->toArray());
        $connection->connectWithPassword();
        $connection->addSshKey();
    }

    /**
     * @param string $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if($this->getOriginal('name') == $this->getAttribute('name')) {
            return;
        }
        $slug = str_slug($value);
        $slugExists = static::where([
            ['slug', '=', $slug],
            ['project_id', '=', $this->project_id]
        ])->exists();
        if($slugExists){
            $slug = $this->incrementSlug($value);
        }
        $this->attributes['slug'] = $slug;
    }

    /**
     * @param string $name
     * @return string|string[]|null
     */
    public function incrementSlug($name){
        $maxSlug = static::where([
            ['name', '=', $name],
            ['project_id', '=', $this->project_id],
        ])->latest('id')->value('slug');
        if (is_numeric(substr($maxSlug,-1))){
            return preg_replace_callback('/(\d+)$/',function ($matches) {
                return $matches[1] + 1;
            }, $maxSlug);
        }
        $slug = str_slug($name);
        return "{$slug}-2";
    }

    public function executeDeployment(array $deploymentData)
    {
        $project = $this->project()->first();
        $deployments = $this->deployments();
        $deployment = $deployments->create([
            'server_id' => $this->id,
            'user_id' => $deploymentData['user_id'],
            'success' => null,
            'notes' => $deploymentData['notes']
        ]);
        try {
            $deployment->fresh();
            $deploymentMethod = new DeploymentMethod();
            $response = $deploymentMethod->execute($deployment);
            $this->patch(
                route('SubmitEditDeployment',['deployment'=>$deployment,'server'=>$this, 'project'=> $project])
                ,['output' => $response['output'],'success' => $response['success']]
            );
        } catch (Exception $e) {
            $this->patch(
                route('SubmitEditDeployment',['deployment'=>$deployment,'server'=>$this, 'project'=> $project])
                ,['output' => $e, 'success' => 'fail']
            );
        }
    }

}
