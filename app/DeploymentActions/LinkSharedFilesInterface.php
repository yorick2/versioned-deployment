<?php

namespace App\DeploymentActions;

interface LinkSharedFilesInterface extends DeploymentActionsInterface
{
    public function execute(): void;

}