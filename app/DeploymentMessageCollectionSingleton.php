<?php

namespace App;

use Illuminate\Support\Collection;

class DeploymentMessageCollectionSingleton extends Singleton {

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
