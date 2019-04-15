<?php


namespace App;


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
    protected $serverDate;

    /**
     * @var string
     */
    protected $location;

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
     * Git constructor.
     * @param SshConnection $sshConnection
     * @param Server $server
     */
    public function __construct(SshConnection $sshConnection, Server $server){
        $this->connection = $sshConnection;
        $this->server = $server;
        $this->location = $this->server->deploy_location;
        $this->repository = $this->server->project()->first()->repository;
        $this->refFolder = $this->location.'/gitcache/'.preg_replace("/[^a-zA-Z0-9]/", "-", $this->repository);
    }

    /**
     * @param Deployment $deployment
     * @return array
     */
    public function deploy(Deployment $deployment){
        $this->updateCache();
        $this->cloneAndCheckout($deployment);
        return $this->responses;
    }

    /**
     * @return string relative location of the current release
     */
    public function getCurrentReleaseLocation(){
        return "{$this->location}/releases/{$this->getServerDate()}";
    }

    /**
     * @return array
     */
    public function getGitLog(){
        $this->updateCache();
        $cmd = "cd {$this->refFolder} && git rev-list --max-count=20 --pretty='%H ; %h : %s' master";
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
     * @return array
     */
    protected function updateCache(){
        $this->location = $this->server->deploy_location;
        $repository = $this->server->project()->first()->repository;
        $cmd = "cd {$this->location} && mkdir -p $this->refFolder && echo folders created";
        $this->responses[] = array_merge(
            $this->connection->execute($cmd),
            ['name'=>'make mirror (cache) folder']
        );
        $cmd = <<<'EOF'
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
EOF;
        $cmd .= "\n cloneGit $repository $this->refFolder";
        $this->responses[] = array_merge(
            $this->connection->execute($cmd),
            ['name'=>'clone into mirror (cache) folder']
        );
        return $this->responses;
    }

    protected function getServerDate(){
        if($this->serverDate){
            return $this->serverDate;
        }
        $this->responses[] = $res = array_merge(
            ['name'=>'get server date'],
            $this->connection->execute("date +%F_%H-%M-%S")
        );
        $this->serverDate = str_replace_last("\n",'',$res['message']);
        return $this->serverDate;
    }

    /**
     * @param Deployment $deployment
     */
    protected function cloneAndCheckout(Deployment $deployment){
        $repository = $this->server->project()->first()->repository;
        $commitRef = $deployment->commit;

        # its a bit safer to cd into the location folder and create the release folder from there
        $cmd = "cd {$this->location} && mkdir -p {$this->getCurrentReleaseLocation()} && mkdir shared && echo folders created";
        $this->responses[] = array_merge(['name'=>'make release folder'], $this->connection->execute($cmd));

        $cmd = <<<'EOF'
        function deployGit() {
            local repositoryUrl="${1}";
            local refFolder="${2}";
            local commitRef="${3}";
            local releaseFolder="${4}";
            git clone --no-checkout --reference $refFolder $repositoryUrl $releaseFolder &&
            cd $releaseFolder &&
            git checkout $commitRef &&
            echo git clone created;
        }
EOF;
        $releaseLocation = "{$this->location}/{$this->getCurrentReleaseLocation()}";
        $cmd .= "\n deployGit $repository $this->refFolder $commitRef $releaseLocation";
        $this->responses[] = array_merge(
            ['name'=>'clone into release folder using the mirror repository'],
            $this->connection->execute($cmd)
        );
    }
}
