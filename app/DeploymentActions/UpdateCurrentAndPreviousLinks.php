<?php

namespace App\DeploymentActions;

class UpdateCurrentAndPreviousLinks extends DeploymentActionsAbstract implements DeploymentActionInterface
{
    /**
     * @return array
     */
    public function execute()
    {
        $cmd = <<<BASH
        function UpdateCurrentAndPreviousLinks(){
            cd {$this->deployment->server->deploy_location} &&
            echo removing previous &&
            rm previous || true &&
            echo moving current to previous &&
            mv current previous || true &&
            echo creating current link &&
            ln -s {$this->deployment->getCurrentReleaseLocation()} current &&
            if [ -d "current" ]; then echo current link updated successfully; fi
        }
        UpdateCurrentAndPreviousLinks
BASH;
        $response = $this->connection->execute($cmd);
        $response['success'] = (strpos($response['message'],'current link updated successfully') === false) ? 0 : 1;
        return $response;
    }
}
