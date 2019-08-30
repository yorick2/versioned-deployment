<?php

namespace App\DeploymentActions;

class PreDeploymentCommands extends DeploymentActionsAbstract implements DeploymentActionInterface
{
    /**
     * @return array
     */
    public function execute()
    {
        $cmd = "cd {$this->deployment->getCurrentReleaseLocation()} && {$this->deployment->server->pre_deploy_commands}";
        return $this->connection->execute($cmd);
    }
}