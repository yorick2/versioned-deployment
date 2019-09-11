<?php

namespace App\DeploymentActions;

use App\DeploymentMessageCollectionSingleton;
use Interfaces\App\DeploymentActions\PostDeploymentCommandsInterface;

class PostDeploymentCommands extends DeploymentActionsAbstract implements PostDeploymentCommandsInterface
{

    public function execute()
    {
        $responseCollection = DeploymentMessageCollectionSingleton::getInstance();
        $server = $this->deployment->server;
        $cmd = "cd {$server->deploy_location}; {$server->post_deploy_commands}";
        $responseCollection->push($this->connection->execute($cmd)->setAttribute('name','post-deploy custom commands'));
    }
}