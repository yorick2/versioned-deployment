<?php


namespace App;


class Git
{
    protected $connection;
    protected $responses;
    protected $serverDate;

    /**
     * @var array
     */
    protected $server;

    /**
     * @return bool
     */
    protected function testSshConnection(){
        $this->connection = new SshConnection($this->server->toArray());
        $this->responses[] = $this->connection->connect();
        if ($this->responses[0]['success'] == 0 ) {
            return false;
        }
        return true;
    }

    public function updateCache(){
        if ($this->testSshConnection() === false) {
            return ['output' => $this->responses, 'success' => false];
        }
        $location = $this->server->deploy_location;
        $repository = $this->server->project()->first()->repository;
        $refFolder = "${location}/.gitcache/".preg_replace("/[^a-zA-Z0-9]/", "-", $repository);
        $cmd = "cd {$location} && mkdir -p $refFolder && echo folders created";
        $this->responses[] = $this->connection->execute($cmd);
        $cmd = <<<'EOF'
        function cloneGit() {
            local repositoryUrl="${1}";
            local refFolder="${2}";
            if [[ ! -d "${refFolder}/branches" ]]; then
                echo cloning git mirror
                git clone --mirror $repositoryUrl $refFolder 
            else
                echo updating git mirror
                cd refFolder &&
                git fetch --all 
            fi 
            echo mirror creation complete
        }
EOF;
        $refFolder = "${location}/.gitcache/".preg_replace("/[^a-zA-Z0-9]/", "-", $repository);
        $cmd .= "\n cloneGit $repository $refFolder";
        $this->responses[] = $this->connection->execute($cmd);
        return $this->responses;
    }

    protected function getServerDate(){
        $location = $this->server->deploy_location;
        $this->responses[] = $res = $this->connection->execute("date +%F_%H-%M-%S");
        $this->serverDate = str_replace_last("\n",'',$res['message']);
        $cmd = "cd {$location} && mkdir -p releases/{$this->serverDate} && mkdir shared && echo folders created";
        $this->responses[] = $this->connection->execute($cmd);
    }

    protected function cloneAndCheckout(){
        $location = $this->server->deploy_location;
        $repository = $this->server->project()->first()->repository;
//        $commitRef = "7fd1a60b01f91b314f59955a4e4d4e80d8edf11d"; //deleteme

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
        $releaseLocation = "{$location}/releases/{$this->serverDate}";
        $refFolder = "${location}/.gitcache/".preg_replace("/[^a-zA-Z0-9]/", "-", $repository);
        $cmd .= "\n deployGit $repository $refFolder $commitRef $releaseLocation";
        $this->responses[] = $this->connection->execute($cmd);
    }

    public function deploy($deployment){
        $this->server = $deployment->server;
        $location = $this->server->deploy_location;

        if ($this->testSshConnection() === false) {
            return ['output' => $this->responses, 'success' => false];
        }

        $this->updateCache();
        $this->getServerDate();
        $this->cloneAndCheckout();

        $this->responses[] = $this->connection->execute("###### links files from shared ######");
        $this->responses[] = $this->connection->execute("###### do their cmds ######");
        $this->responses[] = $this->connection->execute("cd $location && rm previous ");
        $this->responses[] = $this->connection->execute("cd $location && mv current previous");
        $this->responses[] = $this->connection->execute("cd $location && ln -s $location/releases/$this->serverDate current");

        $this->connection->disconnect();

        return ['output' => $this->responses, 'success' => true];
    }
}