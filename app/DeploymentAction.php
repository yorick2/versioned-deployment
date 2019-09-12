<?php

namespace App;

use App;
use Illuminate\Database\Eloquent\Model;

class DeploymentAction extends Model implements DeploymentActionInterface
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
        $this->responses = App::make('App\DeploymentMessageCollectionSingletonInterface');;
        $server = $deployment->server;
        if($this->getSshConnection($server) === false) {
            return $this->responses;
        }
        (App::make(
            'App\DeploymentActions\PreDeploymentCommandsInterface',
            ['connection'=>$this->connection,'deployment'=>$deployment]
        ))->execute();
        (App::make(
            'App\GitInteractions\GitInterface',
            ['sshConnection'=>$this->connection,'server'=>$server]
        ))->deploy($deployment);
        (App::make(
            'App\DeploymentActions\LinkSharedFilesInterface',
            ['connection'=>$this->connection,'deployment'=>$deployment]
        ))->execute();
        (App::make(
            'App\DeploymentActions\PostDeploymentCommandsInterface',
            ['connection'=>$this->connection,'deployment'=>$deployment]
        ))->execute();
        (App::make(
            'App\DeploymentActions\RemoveOldReleasesInterface',
            ['connection'=>$this->connection,'deployment'=>$deployment]
        ))->execute();
        (App::make(
            'App\DeploymentActions\UpdateCurrentAndPreviousLinksInterface',
            ['connection'=>$this->connection,'deployment'=>$deployment]
        ))->execute();
        $this->responses->success = 1;
        $this->connection->disconnect();
        return $this->responses;
    }


    /**
     * @param Server $server
     * @return bool
     */
    protected function getSshConnection($server){
        $this->connection = App::make(
            'App\SshConnectionInterface',
            ['attributes'=>$server->toArray()]
        );
        $response = $this->connection->connect();
        $this->responses->push($response);
        if ($response->success == 0 ) {
            return 0;
        }
        return 1;
    }

}
