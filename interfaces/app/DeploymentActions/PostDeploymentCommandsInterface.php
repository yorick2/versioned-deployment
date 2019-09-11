<?php

namespace Interfaces\App\DeploymentActions;

interface PostDeploymentCommandsInterface extends DeploymentActionInterface
{

    public function execute();
}