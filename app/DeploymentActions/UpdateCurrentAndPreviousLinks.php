<?php

namespace App\DeploymentActions;

class UpdateCurrentAndPreviousLinks extends DeploymentActionsAbstract implements DeploymentActionInterface
{
    /**
     * @return array
     */
    public function execute()
    {
        return $this->connection->execute(
            "cd {$this->deployment->server->deploy_location} \
            && rm previous \
            && mv current previous \
            && ln -s {$this->deployment->getCurrentReleaseLocation()} current"
        );
    }
}