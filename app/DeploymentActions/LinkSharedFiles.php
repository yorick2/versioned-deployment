<?php

namespace App\DeploymentActions;

class LinkSharedFiles extends DeploymentActionsAbstract implements DeploymentActionInterface
{
    protected $server;

    /**
     * @return array
     */
    public function execute()
    {
        $this->server = $this->deployment->server;
        $files = $this->server->shared_files;

        // remove white space
        $files = preg_replace('/\s*,\s*/',',',trim($files));
        // replace \ with /
        $filesArray = explode(',', preg_replace('/\\+ /','/ ',$files));
        // replace multiple / with single /
        $filesArray = explode(',', preg_replace('/\/+ /','/ ',$files));
        $response[] = $this->createFolders($filesArray);
        $response[] = $this->linkFiles($filesArray);
        return $response;
    }

    /**
     * @param array $files
     * @return array
     */
    protected function createFolders(array $files){
        $command = '';
        for($i=0;$i<count($files);$i++){
            $fileName = ltrim($files[$i],'/');
            $fileName = preg_replace('/\/+[^\/]*$/','',$fileName);
            if(!strlen($fileName)){
                continue;
            }
            $command .= (strlen($command)) ? ' && ' : '' ;
            $command .= "mkdir -p {$this->deployment->getCurrentReleaseLocation()}/{$fileName}";
        }
        $command .= '&& echo "folders created successfully"';
        $response = $this->connection->execute($command);
        return $response;
    }

    /**
     * @param array $files
     * @return array
     */
    protected function linkFiles(array $files){
        $location = $this->server->deploy_location;
        $command = '';
        for($i=0;$i<count($files);$i++){
            $fileName = ltrim($files[$i],'/');
            $command .= (strlen($command)) ? ' && ' : '' ;
            $command .= "ln -s {$location}/shared/{$fileName} {$this->deployment->getCurrentReleaseLocation()}/{$fileName}";
        }
        $command .= '&& echo "files linked successfully"';
        $response = $this->connection->execute($command);
        return $response;
    }

}