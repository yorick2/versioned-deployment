<?php

namespace App\DeploymentActions;

use App;

class PostDeploymentCommands extends DeploymentActionsAbstract implements PostDeploymentCommandsInterface
{

    public function execute(): void
    {
        $responseCollection = App::make('App\DeploymentMessageCollectionSingletonInterface');;
        $server = $this->deployment->server;
        $cmd = "cd {$server->deploy_location}; {$server->post_deploy_commands}";
        $responseCollection->push($this->connection->execute($cmd)->setAttribute('name','post-deploy custom commands'));
    }
}