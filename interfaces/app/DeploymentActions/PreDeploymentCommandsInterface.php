<?php

namespace Interfaces\App\DeploymentActions;

use Interfaces\App\DeploymentActionInterface;

interface PreDeploymentCommandsInterface
//    extends DeploymentActionInterface
{
    public function execute();
}