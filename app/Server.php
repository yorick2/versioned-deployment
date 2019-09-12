<?php

namespace App;

use App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Server extends Model implements ServerInterface
{

    protected $guarded = [];

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->project()->first()->path().'/servers/'.$this->{$this->getRouteKeyName()};
    }

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(App::make('App\ProjectInterface'));
    }

    /**
     * @return HasMany
     */
    public function deployments(): HasMany
    {
        return $this->hasMany(App::make('App\DeploymentInterface'));
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(App::make('App\UserInterface'),'user_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @param string $value
     */
    public function setNameAttribute($value): void
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
     * @return string
     */
    public function incrementSlug($name): string
    {
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


    public function executeDeployment(array $deploymentData): Deployment
    {
        $deployments = $this->deployments();
        $deployment = $deployments->create([
            'server_id' => $this->id,
            'user_id' => $deploymentData['user_id'],
            'commit' => $deploymentData['commit'],
            'success' => null,
            'notes' => $deploymentData['notes']
        ]);
        try {
            $deployment->fresh();
            $response = (App::make('App\DeploymentAction'))->execute($deployment);
            $deployment->update([
                'success' => $response->success,
                'output' => $response->collection->toJson()
            ]);
        } catch (Exception $e) {
            $deployment->update([
                'success' => 0,
                'output' => ''
            ]);
        }
        return $deployment;
    }

}
