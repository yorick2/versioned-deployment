<?php

namespace App;

use Illuminate\Support\Collection;

class DeploymentMessageCollectionSingleton extends SingletonAbstract {

    /**
     * @var array
     */
    protected $fillable = ['success'];

    /**
     * @var bool
     */
    protected $success = false;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * Make constructor private/protected, so nobody can call "new Class".
     */
    protected function __construct()
    {
        $this->collection = collect(new DeploymentMessage());
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function clearCollection(): void
    {
        $this->collection = collect(new DeploymentMessage());
    }

    /**
     * @param DeploymentMessage $item
     * @return Collection
     */
    function push(DeploymentMessage $item): Collection
    {
        return $this->collection->push($item);
    }

}
