<?php

use App\Deployment;
use tests\codeception\acceptance\standardPageTests;

class DeploymentShowPageCest extends standardPageTests
{

    protected $page;
    protected $deployment;

    public function _before(AcceptanceTester $I)
    {
        $this->deployment = Deployment::select()->first();
        $this->page = route(
            'ShowDeployment',
            [
                $this->deployment->server->project,
                $this->deployment->server,
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
}
