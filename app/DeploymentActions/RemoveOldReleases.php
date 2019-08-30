<?php

namespace App\DeploymentActions;

class RemoveOldReleases extends DeploymentActionsAbstract implements DeploymentActionInterface
{
    private $qtyOfReleases = 5;

    /**
     * @return mixed
     */
    public function getQtyOfReleases()
    {
        return $this->qtyOfReleases;
    }

    /**
     * @param mixed $qtyOfReleases
     */
    public function setQtyOfReleases($qtyOfReleases): void
    {
        $this->qtyOfReleases = $qtyOfReleases;
    }

    /**
     * @return array
     */
    public function execute()
    {
        $cmd = 'function removeOldReleases() {
            local i f;
            i=1;
            cd '.$this->deployment->server->deploy_location.';
            for folder in $(ls -r releases); do
                if [[ ! -d releases/${folder} ]]; then
                    continue;
                fi
                if [[ ${i} -gt '.$this->qtyOfReleases.' ]]; then 
                    rm -rf releases/${folder};
                fi;
                ((i++));
            done;
            echo 
        }
        removeOldReleases;';
        return $this->connection->execute($cmd);
    }
}
