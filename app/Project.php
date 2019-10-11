<?php

namespace App;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model implements ProjectInterface
{
    protected $guarded = [];

    /**
     * @return string
     */
    public function path(): string
    {
        return '/projects/'.$this->slug;
    }

    /**
     * @return HasMany
     */
    public function servers(): HasMany
    {
        return $this->hasMany(App::make('App\ServerInterface'));
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(App::make('App\UserInterface'), 'user_id');
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @param string $value
     */
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = $value;
        if ($this->getOriginal('name') == $this->getAttribute('name')) {
            return;
        }
        if (static::whereSlug($slug = Str::slug($value))->exists()) {
            $slug = $this->incrementSlug($value);
        }
        $this->attributes['slug'] = $slug;
    }

    /**
     * @param string $name
     * @return string
     */
    public function incrementSlug(string $name): string
    {
        $maxSlug = static::whereName($name)->latest('id')->value('slug');
        if (is_numeric(substr($maxSlug, -1))) {
            return preg_replace_callback('/(\d+)$/', function ($matches) {
                return $matches[1] + 1;
            }, $maxSlug);
        }
        $slug = Str::slug($name);
        return "{$slug}-2";
    }
}
