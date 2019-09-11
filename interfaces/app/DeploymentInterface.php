<?php

namespace Interfaces\App;

interface DeploymentInterface
{
    /**
     * @return string
     */
    public function path();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner();

    /**
     * @return string relative location of the current release
     */
    public function getCurrentReleaseLocation();
}
