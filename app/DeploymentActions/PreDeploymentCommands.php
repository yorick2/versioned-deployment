<?php

namespace App\DeploymentActions;

use App;

class PreDeploymentCommands extends DeploymentActionsAbstract implements PreDeploymentCommandsInterface
{
    public function execute(): void
    {
        $responseCollection = App::make('App\DeploymentMessageCollectionSingletonInterface');
        $cmd = "cd {$this->deployment->getCurrentReleaseLocation()} && {$this->deployment->server->pre_deploy_commands}";
        $response = $this->connection->execute($cmd);
        $responseCollection->push($response->setAttribute('name', 'pre-deploy custom commands'));
    }
}
