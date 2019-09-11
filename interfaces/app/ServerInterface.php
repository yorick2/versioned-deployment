<?php

namespace Interfaces\App;


interface ServerInterface
{
    /**
     * @return string
     */
    public function path();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deployments();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner();

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


    public function executeDeployment(array $deploymentData);

}
