<?php

namespace App;

use App\DeploymentActions\LinkSharedFiles;
use App\DeploymentActions\PreDeploymentCommands;
use App\DeploymentActions\PostDeploymentCommands;
use App\DeploymentActions\RemoveOldReleases;
use App\DeploymentActions\UpdateCurrentAndPreviousLinks;
use App\GitInteractions\Git;
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
        $PreDeploymentCommands = new PreDeploymentCommands($this->connection,$deployment);
        $this->responses[] = array_merge(
            ['name'=>'pre-deploy custom commands'],
            $PreDeploymentCommands->execute()
        );
        $this->git = new Git(
            $this->connection,
            $server
        );
        $gitDeploymentResponses = $this->git->deploy($deployment);
        for($i=0;$i<count($gitDeploymentResponses);$i++){
            $this->responses[] = $gitDeploymentResponses[$i];
        }
        $linkSharedFiles = new LinkSharedFiles($this->connection,$deployment);
        $linkSharedFilesResponses = $linkSharedFiles->execute();
        for($i=0;$i<count($linkSharedFilesResponses);$i++) {
            $this->responses[] = array_merge(
                ['name' => 'links files from shared folder'],
                $linkSharedFilesResponses[$i]
            );
        }
        $PostDeploymentCommands = new PostDeploymentCommands($this->connection,$deployment);
        $this->responses[] = array_merge(
            ['name'=>'post-deploy custom commands'],
            $PostDeploymentCommands->execute()
        );
        $removeOldReleases = new RemoveOldReleases($this->connection,$deployment);
        $this->responses[] = array_merge(
            ['name'=>'remove oldest release'],
            $removeOldReleases->execute()
        );
        $updateCurrentAndPreviousLinks = new UpdateCurrentAndPreviousLinks($this->connection,$deployment);
        $this->responses[] = array_merge(
            ['name'=>'update current and previous links'],
            $updateCurrentAndPreviousLinks->execute()
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
}
