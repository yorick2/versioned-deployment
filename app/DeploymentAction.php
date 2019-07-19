<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeploymentAction extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $responses = [];

    /**
     * @var SshConnection
     */
    protected $connection;

    /**
     * @var Git
     */
    protected $git;

    /**
     * @var string
     */
    protected $location;

    /**
     * @param Deployment $deployment
     * @return array
     */
    public function execute(Deployment $deployment)
    {
        if($this->getSshConnection($deployment->server) === false) {
            return ['output' => $this->responses, 'success' => false];
        }

        $server = $deployment->server;
        $this->location = $server->deploy_location;
        $this->git = new Git(
            $this->connection,
            $server
        );
        $this->responses[] = array_merge(
            ['name'=>'pre-deploy custom commands'],
            $this->connection->execute("cd {$this->server->deploy_location}; {$server->pre_deploy_commands}")
        );
        $gitDeploymentResponses = $this->git->deploy($deployment);
        for($i=0;$i<count($gitDeploymentResponses);$i++){
            $this->responses[] = $gitDeploymentResponses[$i];
        }
        $this->responses[] = array_merge(
            ['name'=>'links files from shared folder'],
            $this->linkSharedFiles($server->shared_files)
        );
        $this->responses[] = array_merge(
            ['name'=>'pre-deploy custom commands'],
            $this->connection->execute("cd  {$this->git->getCurrentReleaseLocation()} && "
                . $server->post_deploy_commands)
        );
        $this->responses[] = array_merge(
            ['name'=>'remove oldest release'],
            $this->removeOldReleases()
        );
        $this->responses[] = array_merge(
            ['name'=>'update current and previous links'],
            $this->updateCurrentAndPreviousLinks()
        );
        $this->connection->disconnect();

        return ['output' => $this->responses, 'success' => true];
    }

    /**
     * @param Server $server
     * @return bool
     */
    protected function getSshConnection($server){
        $this->connection = new SshConnection($server->toArray());
        $this->responses[] = $this->connection->connect();
        if ($this->responses[0]['success'] == 0 ) {
            return false;
        }
        return true;
    }

    /**
     * @param string $files
     * @return array
     *
     */
    protected function linkSharedFiles($files){
        $command = '';
        $files = preg_replace('/\s*,\s*/',',',trim($files));
        $filesArray = explode(',', preg_replace('/\\? /','\ ',$files));
        for($i=0;$i<count($filesArray);$i++){
            $fileName = ltrim($filesArray[$i],'/');
            if(strlen($command)){
               $command .= ' && ';
            }
            $command .= "ln -s {$this->location}/shared/{$fileName} {$this->git->getCurrentReleaseLocation()}/{$fileName}";
        }
        return $this->connection->execute($command);
    }

    /**
     * @return array
     */
    protected function removeOldReleases(){
        $cmd = 'function removeOldReleases() {
            local i f;
            i=1;
            cd '.$this->location.';
            for folder in $(ls -r releases); do
                if [[ ! -d releases/${folder} ]]; then
                    continue;
                fi
                if [[ ${i} -gt 5 ]]; then 
                    rm -rf releases/${folder};
                fi;
                ((i++));
            done;
        }
        removeOldReleases;';
        return $this->connection->execute($cmd);
    }


    /**
     * @return array
     */
    protected function updateCurrentAndPreviousLinks(){
        return $this->connection->execute(
            "cd $this->location \
            && rm previous \
            && mv current previous \
            && ln -s {$this->git->getCurrentReleaseLocation()} current"
        );
    }
}
