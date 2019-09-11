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
        $server = $deployment->server;
        if($this->getSshConnection($server) === false) {
            return $this->responses;
        }
        (new PreDeploymentCommands($this->connection,$deployment))->execute();
        (new Git($this->connection, $server))->deploy($deployment);
        (new LinkSharedFiles($this->connection,$deployment))->execute();
        (new PostDeploymentCommands($this->connection,$deployment))->execute();
        (new RemoveOldReleases($this->connection,$deployment))->execute();
        (new UpdateCurrentAndPreviousLinks($this->connection,$deployment))->execute();
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

}
