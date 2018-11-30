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

    /**
     * @param string $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
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

}
