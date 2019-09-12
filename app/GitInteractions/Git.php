<?php


namespace App\GitInteractions;

Use App;
use App\DeploymentInterface;
use App\ServerInterface;
use App\SshConnectionInterface;

class Git
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
     * @var GitMirror
     */
    protected $gitMirror;

    /**
     * Git constructor.
     * @param SshConnectionInterface $sshConnection
     * @param ServerInterface $server
     */
    public function __construct(SshConnectionInterface $sshConnection, ServerInterface $server){
        $this->connection = $sshConnection;
        $this->server = $server;
        $this->deployLocation = $this->server->deploy_location;
        $this->repository = $this->server->project->repository;
        $this->refFolder = $this->deployLocation.'/gitcache/'.preg_replace("/[^a-zA-Z0-9]/", "-", $this->repository);
        $this->gitMirror = App::make(
            'App\GitInteractions\GitMirrorInterface',
            ['sshConnection'=>$sshConnection, 'server'=>$server]
        );
    }

    /**
     * @param DeploymentInterface $deployment
     */
    public function deploy(DeploymentInterface $deployment): void
    {
        $this->responses = App::make('App\DeploymentMessageCollectionSingletonInterface');;
        $this->gitMirror->update();
        $this->makeReleaseAndSharedDirectories($deployment);
        $this->cloneAndCheckout($deployment);
    }

    /**
     * @return array
     */
    public function getGitLog(): array
    {
        $this->gitMirror->update();
        $cmd = "cd {$this->refFolder} && git rev-list --max-count=20 --pretty='%H ; %h : %s' {$this->server->deploy_branch}";
        $res = $this->connection->execute($cmd);
        $log = explode("\n", $res['message']);
        for($i=0;$i<count($log);$i++){
            if (strpos( $log[$i], ';') === false) {
                continue;
            }
            list($key, $val) = explode(' ; ', $log[$i]);
            $gitLogArray[$key] = $val;
        }
        return $gitLogArray;
    }


    /**
     * @param DeploymentInterface $deployment
     */
    protected function makeReleaseAndSharedDirectories(DeploymentInterface $deployment): void
    {
        # its a bit safer to cd into the location folder and create the release folder from there
        $cmd = <<<BASH
        mkdir -p {$deployment->getCurrentReleaseLocation()} && 
        cd {$this->deployLocation} &&
        mkdir -p shared &&
        echo folders created
BASH;
        $response = $this->connection->execute($cmd);
        $response->name = 'make release folder';
        $response->success = (strpos($response->message,'folders created') === false) ? 0 : 1;
        $this->responses->push($response);
    }

    /**
     * @param DeploymentInterface $deployment
     */
    protected function cloneAndCheckout(DeploymentInterface $deployment): void
    {
        $commitRef = $deployment->commit;
        $cmd = <<<'BASH'
        function deployGit() {
            local repositoryUrl="${1}";
            local refFolder="${2}";
            local commitRef="${3}";
            local releaseFolder="${4}";
            git clone --no-checkout --reference $refFolder $repositoryUrl $releaseFolder &&
            cd $releaseFolder &&
            git checkout $commitRef;
            if [ "$(git rev-parse HEAD)" = "$commitRef" ] ; then
               echo git clone created;
            else
               echo git clone failed
            fi
        }
BASH;
        $cmd .= "\n deployGit $this->repository $this->refFolder $commitRef {$deployment->getCurrentReleaseLocation()}";
        $response = $this->connection->execute($cmd);
        $response->name = 'clone into release folder using the mirror repository';
        $response->success = (strpos($response->message,'git clone created') === false) ? 0 : 1;
        $this->responses->push($response);
    }

    /**
     * @param $commitRef string
     * @return string
     */
    public function getGitDiff(string $commitRef): string
    {
        $cmd = <<<'BASH'
        function gitDiff() {
            local deployLocation="${1}";
            local commitRef="${2}";
            cd ${deployLocation}/current
            $(git pull origin master)
            git diff --name-only $commitRef
        }
BASH;
        $cmd .= "\n gitDiff {$this->server->deploy_location} {$commitRef}";
        $response = $this->connection->execute($cmd);
        return $response->message;
    }
}
