<?php

namespace App;

use Illuminate\Support\Collection;

interface DeploymentMessageCollectionSingletonInterface {

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success);

    public function clearCollection();

    /**
     * @param DeploymentMessageInterface $item
     * @return Collection
     */
    function push(DeploymentMessageInterface $item): Collection;

}
