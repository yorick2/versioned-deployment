<?php

namespace Interfaces\App;

use App\DeploymentActions\PreDeploymentCommandsInterface;
use App\DeploymentActions\PostDeploymentCommandsInterface;
use App\DeploymentActions\RemoveOldReleasesInterface;
use App\DeploymentActions\UpdateCurrentAndPreviousLinksInterface;
use App\GitInteractions\GitInterface;

interface DeploymentActionInterface
{


    public function __construct(array $attributes = []);

    /**
     * @param DeploymentInterface $deployment
     * @return array
     */
    public function execute(

    );

}
