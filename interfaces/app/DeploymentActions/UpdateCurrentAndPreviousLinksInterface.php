<?php

namespace Interfaces\App\DeploymentActions;

interface UpdateCurrentAndPreviousLinksInterface extends DeploymentActionInterface
{

    public function execute();
}
