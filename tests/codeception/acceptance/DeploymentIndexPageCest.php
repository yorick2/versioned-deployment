<?php

use App\Server;
use App\Deployment;
use tests\codeception\acceptance\standardPageTests;

class DeploymentIndexPageCest extends standardPageTests
{

    protected $page;
    protected $server;

    public function _before(AcceptanceTester $I)
    {
        $this->server = Server::select()->first();
        $this->page = route('DeploymentsIndex', [$this->server->project,$this->server], false);
    }

    public function see_deployments_list(AcceptanceTester $I)
    {
        $I->wantTo('see my deployments for a server');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $deploymentCollection = Deployment::where('server_id',$this->server->id)
            ->orderBy('id','desc')
            ->take(5)
            ->get();
        $I->assertTrue($deploymentCollection->count()>0);
        foreach($deploymentCollection as $deployment){
            $I->seeLink($deployment->created_at,$deployment->id);
        }
    }
}
