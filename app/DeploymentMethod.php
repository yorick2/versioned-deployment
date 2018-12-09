<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeploymentMethod extends Model
{
    protected $guarded = [];

    /**
     * @param $deployment
     * @return array
     */
    public function execute($deployment)
    {
        $server = $deployment->server;
        $project = $server->project()->first();
        $serverData = $deployment->server->toArray();
        $serverData['deploy_host']='172.21.0.4'; // deleteme
        $commitRef = "7fd1a60b01f91b314f59955a4e4d4e80d8edf11d"; //deleteme
        $location = $server['deploy_location'];
        $repository = $project['repository'];

        $connection = new SshConnection($serverData);
        $responses[] = $connection->connect();
        if ($responses[0]['success'] == 0 ) {
            return $responses;
        }
        $responses[] = $res = $connection->execute("date +%F_%H-%M-%S");
        $date = str_replace_last("\n",'',$res['message']);
        $cmd = "cd {$location} && mkdir -p releases/{$date} && mkdir -p shared && echo folders created";
        $responses[] = $connection->execute($cmd);
        $cmd = <<<'EOF'
        function deployGitClone() {
            local repositoryUrl="${1}";
            local commitRef="${2}";
            local releaseFolder="${3}";
            local refFolder="${releaseFolder}/../../gitcache/references/${repositoryUrl//[^[:alnum:]]/-}" &&
            if [[ ! -d "$refFolder" ]]; then
                git clone --mirror $repositoryUrl $refFolder 
            else
                cd refFolder &&
                git fetch --all 
            fi
            git clone --no-checkout --reference $refFolder $repositoryUrl $releaseFolder &&
            cd $releaseFolder &&
            git checkout $commitRef &&
            echo git clone created;
        }
        
EOF;
        $releaseLocation = "{$location}/releases/{$date}";
        $cmd .= "deployGitClone $repository $commitRef $releaseLocation";
        $responses[] = $connection->execute($cmd);

        $responses[] = $connection->execute("###### links files from shared ######");
        $responses[] = $connection->execute("###### do their cmds ######");
        $responses[] = $connection->execute("cd $location && rm previous ");
        $responses[] = $connection->execute("cd $location && mv current previous");
        $responses[] = $connection->execute("cd $location && ln -s $location/releases/$date current");

        $connection->disconnect();

        return $responses;
    }

    public function cloneRepository ($repository)
    {

    }

}
