<?php

namespace App;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface ProjectInterface
{

    /**
     * @return string
     */
    public function path(): string;

    /**
     * @return HasMany
     */
    public function servers(): HasMany;

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo;

    /**
     * @param array $serverData
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addServer(array $serverData);

    /**
     * @return string
     */
    public function getRouteKeyName(): string;

    /**
     * @param string $value
     */
    public function setNameAttribute(string $value): void;

    /**
     * @param string $name
     * @return string
     */
    public function incrementSlug(string $name): string;

}
