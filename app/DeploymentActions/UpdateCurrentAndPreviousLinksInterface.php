<?php

namespace App\DeploymentActions;

interface UpdateCurrentAndPreviousLinksInterface extends DeploymentActionsInterface
{
    public function execute(): void;
}
