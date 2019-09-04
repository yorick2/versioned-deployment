<?php

namespace App\DeploymentActions;

use App\DeploymentMessageCollectionSingleton;

class PreDeploymentCommands extends DeploymentActionsAbstract implements DeploymentActionInterface
{
    public function execute()
    {
        $responseCollection = DeploymentMessageCollectionSingleton::getInstance();
        $cmd = "cd {$this->deployment->getCurrentReleaseLocation()} && {$this->deployment->server->pre_deploy_commands}";
        $response = $this->connection->execute($cmd);
        $responseCollection->push($response->setAttribute('name','pre-deploy custom commands'));
    }
}