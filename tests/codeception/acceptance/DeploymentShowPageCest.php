<?php

use App\Deployment;
use tests\codeception\acceptance\standardPageTests;

class DeploymentShowPageCest extends standardPageTests
{

    protected $page;
    protected $deployment;
    protected $server;

    public function _before(AcceptanceTester $I)
    {
        $this->deployment = Deployment::select()->first();
        $this->server = $this->deployment->server;
        $this->page = route(
            'ShowDeployment',
            [
                $this->server->project,
                $this->server,
                $this->deployment
            ],
            false
        );
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
