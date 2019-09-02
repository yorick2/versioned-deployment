<?php

namespace App;

use App\DeploymentActions\DeploymentActionInterface;
use App\DeploymentActions\LinkSharedFiles;
use App\DeploymentActions\PreDeploymentCommands;
use App\DeploymentActions\PostDeploymentCommands;
use App\DeploymentActions\RemoveOldReleases;
use App\DeploymentActions\UpdateCurrentAndPreviousLinks;
use App\GitInteractions\Git;
use Illuminate\Database\Eloquent\Model;

class DeploymentAction extends Model
{
//    use \Illuminate\Database\Eloquent\Concerns\HasAttributes;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $responses;

    /**
     * @var SshConnection
     */
    protected $connection;

    /**
     * @var Git
     */
    protected $git;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    /**
     * @param Deployment $deployment
     * @return array
     */
    public function execute(Deployment $deployment)
    {
        $this->responses = DeploymentMessageCollectionSingleton::getInstance();
        if($this->getSshConnection($deployment->server) === false) {
            return $this->responses;
        }
        $this->runCommand(
            new PreDeploymentCommands($this->connection,$deployment),
            'pre-deploy custom commands'
        );
        $this->runGit($deployment);
        $this->runCommand(
            new LinkSharedFiles($this->connection,$deployment),
            'links files from shared folder'
        );
        $this->runCommand(
            new PostDeploymentCommands($this->connection,$deployment),
            'post-deploy custom commands'
        );
        $this->runCommand(
            new RemoveOldReleases($this->connection,$deployment),
            'remove oldest release'
        );
        $this->runCommand(
            new UpdateCurrentAndPreviousLinks($this->connection,$deployment),
            'update current and previous links'
        );
        $this->responses->success = true;
        $this->responses->collection->each(function($item){
            if(!$item->success){
                $this->responses->success = 0;
            }
        });
        $this->connection->disconnect();
        return $this->responses;
    }

    /**
     * @param DeploymentActionInterface $class
     * @param string $name
     */
    protected function runCommand($class, $name){
        $response = $class->execute();
//        $response->setAttribute('name',$name);
//        $this->responses->push($response);
    }

    /**
     * @param Server $server
     * @return bool
     */
    protected function getSshConnection($server){
        $this->connection = new SshConnection($server->toArray());
        $response = $this->connection->connect();
        $this->responses->push($response);
        if ($response->success == 0 ) {
            return false;
        }
        return true;
    }

    protected function runGit($deployment){
        $this->git = new Git(
            $this->connection,
            $deployment->server
        );
        $this->git->deploy($deployment);
    }
}
