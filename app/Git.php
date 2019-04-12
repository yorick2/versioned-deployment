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
        $this->responses[] = array_merge(['name'=>'make mirror (cache) folder'], $this->connection->execute($cmd));
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
        $cmd .= "\n cloneGit $repository $refFolder";
        $this->responses[] = array_merge(['name'=>'clone into mirror (cache) folder'], $this->connection->execute($cmd));
        return $this->responses;
    }

    protected function getServerDate(){
        $location = $this->server->deploy_location;
        $this->responses[] = $res = array_merge(
            ['name'=>'get server date'],
            $this->connection->execute("date +%F_%H-%M-%S")
        );
        $this->serverDate = str_replace_last("\n",'',$res['message']);
    }

    protected function cloneAndCheckout(){
        $location = $this->server->deploy_location;
        $repository = $this->server->project()->first()->repository;
        $commitRef = "7fd1a60b01f91b314f59955a4e4d4e80d8edf11d"; //deleteme

        # its a bit safer to cd into the location folder and create the release folder from there
        $cmd = "cd {$location} && mkdir -p releases/{$this->serverDate} && mkdir shared && echo folders created";
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
        $releaseLocation = "{$location}/releases/{$this->serverDate}";
        $refFolder = "${location}/.gitcache/".preg_replace("/[^a-zA-Z0-9]/", "-", $repository);
        $cmd .= "\n deployGit $repository $refFolder $commitRef $releaseLocation";
        $this->responses[] = array_merge(
            ['name'=>'clone into release folder using the mirror repository'],
            $this->connection->execute($cmd)
        );
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

        $this->responses[] = array_merge(
            ['name'=>'links files from shared folder --> to do'],
            $this->connection->execute("###### links files from shared ######")
        );
        $this->responses[] = array_merge(
            ['name'=>'custom commands --> to do'],
            $this->connection->execute("###### do their cmds ######")
        );
        $this->responses[] = array_merge(
            ['name'=>'remove oldest release --> to do'],
            $this->connection->execute("###### remove oldest release if threshold reached ######")
        );
        $cmd = "cd $location \
        && rm previous \
        && mv current previous \
        && ln -s $location/releases/$this->serverDate current";
        $this->responses[] = array_merge(
            ['name'=>'update current and previous links'],
            $this->connection->execute($cmd)
        );
        $this->connection->disconnect();

        return ['output' => $this->responses, 'success' => true];
    }
}