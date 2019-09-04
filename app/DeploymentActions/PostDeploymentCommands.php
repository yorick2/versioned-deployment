<?php

namespace App\DeploymentActions;

use App\DeploymentMessageCollectionSingleton;

class PostDeploymentCommands extends DeploymentActionsAbstract implements DeploymentActionInterface
{

    public function execute()
    {
        $responseCollection = DeploymentMessageCollectionSingleton::getInstance();
        $server = $this->deployment->server;
        $cmd = "cd {$server->deploy_location}; {$server->post_deploy_commands}";
        $responseCollection->push($this->connection->execute($cmd)->setAttribute('name','post-deploy custom commands'));
    }
}