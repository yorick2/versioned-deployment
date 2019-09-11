<?php

namespace Interfaces\App\DeploymentActions;

interface RemoveOldReleasesInterface extends DeploymentActionInterface
{
    /**
     * @return mixed
     */
    public function getQtyOfReleases();

    /**
     * @param mixed $qtyOfReleases
     */
    public function setQtyOfReleases($qtyOfReleases);

    public function execute();
}
