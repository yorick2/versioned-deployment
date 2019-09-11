<?php

namespace App\DeploymentActions;

interface PreDeploymentCommandsInterface extends DeploymentActionsInterface
{
    public function execute(): void;
}