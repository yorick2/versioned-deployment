<?php

namespace App\GitInteractions;

use App;
use App\DeploymentMessageCollectionSingletonInterface;
use App\ServerInterface;
use App\SshConnectionInterface;

class GitMirror
{
    /**
     * @var SshConnection
     */
    protected $connection;

    /**
     * @var array
     */
    protected $responses = [];

    /**
     * @var string
     */
    protected $deployLocation;

    /**
     * @var string
     */
    protected $repository;

    /**
     * @var string
     */
    protected $refFolder;

    /**
     * @var Server
     */
    protected $server;

    /**
     * GitMirror constructor.
     * @param SshConnectionInterface $sshConnection
     * @param ServerInterface $server
     */
    public function __construct(SshConnectionInterface $sshConnection, ServerInterface $server)
    {
        $this->connection = $sshConnection;
        $this->server = $server;
        $this->deployLocation = $this->server->deploy_location;
        $this->repository = $this->server->project->repository;
        $this->refFolder = $this->deployLocation.'/gitcache/'.preg_replace("/[^a-zA-Z0-9]/", "-", $this->repository);
    }

    /**
     * make the git reference folder to use as a mirror. So the whole repo isn't downloaded each time. Reducing download time
     */
    public function update(): void
    {
        $this->responses = App::make('App\DeploymentMessageCollectionSingletonInterface');
        $this->deployLocation = $this->server->deploy_location;
        $repository = $this->server->project->repository;
        $cmd = "cd {$this->deployLocation} && mkdir -p {$this->refFolder} && echo folders created";

        $response = $this->connection->execute($cmd)
            ->setAttribute('name', 'make mirror (cache) folder');
        $this->responses->push($response);

        $cmd = <<<'BASH'
        function cloneGit() {
            local repositoryUrl="${1}";
            local refFolder="${2}";
            if [[ ! -d "${refFolder}/branches" ]]; then
                echo cloning git mirror
                git clone --mirror $repositoryUrl $refFolder 
            else
                echo updating git mirror
                cd ${refFolder} &&
                git fetch --all 
            fi 
            echo mirror creation complete
        }        
BASH;
        $cmd .= "\n cloneGit $repository $this->refFolder";
        $response = $this->connection->execute($cmd)
            ->setAttribute('name', 'update git mirror (cache) folder');
        $this->responses->push($response);
    }

    /**
     * @return DeploymentMessageCollectionSingletonInterface
     */
    public function clear(): DeploymentMessageCollectionSingletonInterface
    {
        $this->responses = App::make('App\DeploymentMessageCollectionSingletonInterface');
        $success = 0;
        $cmd = <<<'BASH'
        function clearGitMirrorFolder() {
            local refFolder="${1}";
            cd $refFolder
            if [[ -d "${refFolder}/branches" ]] && [[ -f "${refFolder}/HEAD" ]]; then
                rm -rf ./*
            fi
        }
BASH;
        $cmd .= "\n clearGitMirrorFolder $this->refFolder";
        $response = $this->connection->execute($cmd);
        if ($response->success == true) {
            $response_two = $this->connection->execute('ls '.$this->refFolder);
            $success = (strlen($response_two->message))? 0 : 1 ;
        }
        $response->success = $success;
        $response->name = 'clone into mirror (cache) folder';
        $this->responses->push($response);
        return $this->responses;
    }
}
