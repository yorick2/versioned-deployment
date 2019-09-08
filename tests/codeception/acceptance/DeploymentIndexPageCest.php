<?php

use tests\codeception\acceptance\standardPageTests;

class DeploymentIndexPageCest extends standardPageTests
{

    protected $page;
    protected $project;
    protected $server;
    protected $deploymentCollection;

    public function _before(AcceptanceTester $I)
    {
        $this->server = factory('App\Server')->create();
        $this->project = $this->server->project;
        $this->deploymentCollection = factory('App\Deployment',5)
            ->create(['server_id' => $this->server->id]);
        $this->page = route('DeploymentsIndex', [$this->project,$this->server], false);
    }

    public function _after(AcceptanceTester $I)
    {
        foreach($this->deploymentCollection as $deployment){
            $deployment->owner->delete();

        }
        $this->server->owner->delete();
        $this->project->owner->delete();
        $this->project->delete();
    }

    public function see_deployments_list(AcceptanceTester $I)
    {
        $I->wantTo('see my deployments for a server');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        foreach($this->deploymentCollection as $deployment){
            $I->seeLink($deployment->created_at,$deployment->id);
        }
    }

    public function see_Server_link(AcceptanceTester $I)
    {
        $I->wantTo('see a link for my server');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeLink(
            $this->server->name,
            route('ShowServer', [$this->project, $this->server], false)
        );
    }
}
