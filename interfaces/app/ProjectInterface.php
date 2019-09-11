<?php

namespace Interfaces\App;


interface ProjectInterface
{

    /**
     * @return string
     */
    public function path();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servers();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner();

    /**
     * @param array $serverData
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addServer(array $serverData);

    /**
     * @return string
     */
    public function getRouteKeyName();

    /**
     * @param string $value
     */
    public function setNameAttribute($value);

    /**
     * @param string $name
     * @return string|string[]|null
     */
    public function incrementSlug($name);

}
