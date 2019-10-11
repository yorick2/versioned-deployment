<?php

use tests\codeception\acceptance\standardPageTests;

class DeploymentShowPageCest extends standardPageTests
{
    protected $page;
    protected $deployment;
    protected $server;
    protected $project;

    public function _before(AcceptanceTester $I)
    {
        $this->deployment = $this->server = factory('App\Deployment')->create();
        $this->server = $this->deployment->server;
        $this->project = $this->server->project;
        $this->page = route(
            'ShowDeployment',
            [
                $this->project,
                $this->server,
                $this->deployment
            ],
            false
        );
    }

    public function _after(AcceptanceTester $I)
    {
        $this->deployment->owner->delete();
        $this->server->owner->delete();
        $this->project->owner->delete();
        $this->project->delete();
    }

    public function see_deployment_details(AcceptanceTester $I)
    {
        $I->wantTo('see my deployments details');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->see($this->deployment->created_at);
        $I->see($this->deployment->notes);
    }

    public function see_a_link_to_the_deployments_list(AcceptanceTester $I)
    {
        $I->wantTo('see a link for the deployments list');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeLink(
            'deployments',
            route('DeploymentsIndex', [$this->server->project, $this->server], false)
        );
    }
}
