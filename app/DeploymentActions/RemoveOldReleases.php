<?php

namespace App\DeploymentActions;

use App;
use phpDocumentor\Reflection\Types\Integer;

class RemoveOldReleases extends DeploymentActionsAbstract implements RemoveOldReleasesInterface
{
    private $qtyOfReleases = 5;

    /**
     * @return int
     */
    public function getQtyOfReleases(): Integer
    {
        return $this->qtyOfReleases;
    }

    /**
     * @param mixed $qtyOfReleases
     */
    public function setQtyOfReleases(Integer $qtyOfReleases): void
    {
        $this->qtyOfReleases = $qtyOfReleases;
    }

    public function execute(): void
    {
        $responseCollection = App::make('App\DeploymentMessageCollectionSingletonInterface');
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
        $responseCollection->push($this->connection->execute($cmd)->setAttribute('name', 'Remove Old Releases'));
    }
}
