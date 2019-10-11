<?php

namespace App\DeploymentActions;

use App;
use App\DeploymentMessageInterface;

class LinkSharedFiles extends DeploymentActionsAbstract implements LinkSharedFilesInterface
{
    protected $server;

    public function execute(): void
    {
        $responseCollection = App::make('App\DeploymentMessageCollectionSingletonInterface');
        $this->server = $this->deployment->server;
        $files = $this->server->shared_files;

        // remove white space
        $files = preg_replace('/\s*,\s*/', ',', trim($files));
        // replace \ with /
        $files = preg_replace('/\\+ /', '/ ', $files);
        // replace multiple / with single /
        $filesArray = explode(',', preg_replace('/\/+ /', '/ ', $files));
        $responseCollection->push($this->createFolders($filesArray));
        $responseCollection->push($this->linkFiles($filesArray));
    }

    /**
     * @param array $files
     * @return DeploymentMessageInterface
     */
    protected function createFolders(array $files): DeploymentMessageInterface
    {
        $command = '';
        for ($i=0;$i<count($files);$i++) {
            $fileName = ltrim($files[$i], '/');
            $fileName = preg_replace('/\/+[^\/]*$/', '', $fileName);
            if (!strlen($fileName)) {
                continue;
            }
            $command .= (strlen($command)) ? ' && ' : '' ;
            $command .= "mkdir -p {$this->deployment->getCurrentReleaseLocation()}/{$fileName}";
        }
        $command .= '&& echo "folders created successfully"';
        return $this->connection->execute($command);
    }

    /**
     * @param array $files
     * @return DeploymentMessageInterface
     */
    protected function linkFiles(array $files): DeploymentMessageInterface
    {
        $location = $this->server->deploy_location;
        $command = '';
        for ($i=0;$i<count($files);$i++) {
            $fileName = ltrim($files[$i], '/');
            $command .= (strlen($command)) ? ' && ' : '' ;
            $command .= "ln -s {$location}/shared/{$fileName} {$this->deployment->getCurrentReleaseLocation()}/{$fileName}";
        }
        $command .= '&& echo "files linked successfully"';
        return $this->connection->execute($command);
    }
}
