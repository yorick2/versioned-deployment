<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface ServerInterface
{
    /**
     * @return string
     */
    public function path(): string;

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo;

    /**
     * @return HasMany
     */
    public function deployments(): HasMany;

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo;

    /**
     * @return string
     */
    public function getRouteKeyName(): string;

    /**
     * @param string $value
     */
    public function setNameAttribute($value): void;

    /**
     * @param string $name
     * @return string
     */
    public function incrementSlug($name): string;


    public function executeDeployment(array $deploymentData): Deployment;
}
