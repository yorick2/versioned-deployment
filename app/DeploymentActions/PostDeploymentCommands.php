<?php

namespace App\DeploymentActions;

class PostDeploymentCommands extends DeploymentActionsAbstract implements DeploymentActionInterface
{
    /**
     * @return array
     */
    public function execute()
    {
        $server = $this->deployment->server;
        $cmd = "cd {$server->deploy_location}; {$server->post_deploy_commands}";
        return $this->connection->execute($cmd);
    }
}