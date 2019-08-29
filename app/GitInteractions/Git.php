<?php


namespace App\GitInteractions;


use App\Deployment;
use App\Server;
use App\SshConnection;

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
     * @param SshConnection $sshConnection
     * @param Server $server
     */
    public function __construct(SshConnection $sshConnection, Server $server){
        $this->connection = $sshConnection;
        $this->server = $server;
        $this->deployLocation = $this->server->deploy_location;
        $this->repository = $this->server->project->repository;
        $this->refFolder = $this->deployLocation.'/gitcache/'.preg_replace("/[^a-zA-Z0-9]/", "-", $this->repository);
        $this->gitMirror = new GitMirror($sshConnection, $server);
    }

    /**
     * @param Deployment $deployment
     * @return array
     */
    public function deploy(Deployment $deployment){
        $this->gitMirror->update();
        $this->cloneAndCheckout($deployment);
        return $this->responses;
    }

    /**
     * @return array
     */
    public function getGitLog(){
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
     * @param Deployment $deployment
     */
    protected function cloneAndCheckout(Deployment $deployment){
        $commitRef = $deployment->commit;

        # its a bit safer to cd into the location folder and create the release folder from there
        $cmd = "mkdir -p {$deployment->getCurrentReleaseLocation()} && cd {$this->deployLocation} && mkdir shared && echo folders created";
        $this->responses[] = array_merge(['name'=>'make release folder'], $this->connection->execute($cmd));

        $cmd = <<<'EOF'
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
EOF;
        $cmd .= "\n deployGit $this->repository $this->refFolder $commitRef {$deployment->getCurrentReleaseLocation()}";
        $this->responses[] = array_merge(
            ['name'=>'clone into release folder using the mirror repository'],
            $this->connection->execute($cmd)
        );
    }
}
