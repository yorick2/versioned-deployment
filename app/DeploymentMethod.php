<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeploymentMethod extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $responses = [];

    /**
     * @var SshConnection
     */
    protected $connection;

    /**
     * @param $deployment
     * @return array
     */
    public function execute(Deployment $deployment)
    {
        if($this->getSshConnection($deployment->server) === false) {
            return ['output' => $this->responses, 'success' => false];
        }

        $server = $deployment->server;
        $location = $server->deploy_location;
        $git = new Git(
            $this->connection,
            $server
        );

        $this->responses = array_merge($this->responses, $git->deploy($deployment));

        $this->responses[] = array_merge(
            ['name'=>'links files from shared folder --> to do'],
            $this->connection->execute("###### links files from shared ######")
        );
        $this->responses[] = array_merge(
            ['name'=>'custom commands --> to do'],
            $this->connection->execute("###### do their cmds ######")
        );
        $cmd = 'function removeOldReleases() {
            local i f;
            i=1;
            cd '.$location.';
            for folder in $(ls -r releases); do
                if [[ ! -d releases/${folder} ]]; then
                    continue;
                fi
                if [[ ${i} -gt 5 ]]; then 
                    rm -rf releases/${folder};
                fi;
                ((i++));
            done;
        }
        removeOldReleases;';
        $this->responses[] = array_merge(
            ['name'=>'remove oldest release'],
            $this->connection->execute($cmd)
        );
        $cmd = "cd $location \
        && rm previous \
        && mv current previous \
        && ln -s $location/{$git->getCurrentReleaseLocation()} current";
        $this->responses[] = array_merge(
            ['name'=>'update current and previous links'],
            $this->connection->execute($cmd)
        );
        $this->connection->disconnect();

        return ['output' => $this->responses, 'success' => true];
    }

    /**
     * @param $server
     * @return bool
     */
    protected function getSshConnection($server){
        $this->connection = new SshConnection($server->toArray());
        $this->responses[] = $this->connection->connect();
        if ($this->responses[0]['success'] == 0 ) {
            return false;
        }
        return true;
    }
}
