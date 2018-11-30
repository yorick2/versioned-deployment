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
        return '/projects/'.$this->slug;
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
        $servers = $this->servers();
        $server = $servers->create($serverData);
        return redirect($server->path());
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
        if(static::whereSlug($slug = str_slug($value))->exists()){
            $slug = $this->incrementSlug($value);
        }
        $this->attributes['slug'] = $slug;
    }

    /**
     * @param string $name
     * @return string|string[]|null
     */
    public function incrementSlug($name){
        $maxSlug = static::whereName($name)->latest('id')->value('slug');
        if (is_numeric(substr($maxSlug,-1))){
            return preg_replace_callback('/(\d+)$/',function ($matches) {
                return $matches[1] + 1;
            }, $maxSlug);
        }
        $slug = str_slug($name);
        return "{$slug}-2";
    }

}
