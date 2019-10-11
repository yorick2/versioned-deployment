<?php

namespace App\DeploymentActions;

interface PostDeploymentCommandsInterface extends DeploymentActionsInterface
{
    public function execute(): void;
}
