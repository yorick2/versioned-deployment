<?php

namespace App\DeploymentActions;

use phpDocumentor\Reflection\Types\Integer;

interface RemoveOldReleasesInterface extends DeploymentActionsInterface
{
    /**
     * @return int
     */
    public function getQtyOfReleases(): Integer;

    /**
     * @param $qtyOfReleases
     * @return int
     */
    public function setQtyOfReleases(Integer $qtyOfReleases): void;

    public function execute(): void;
}
