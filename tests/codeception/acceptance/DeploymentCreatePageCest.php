<?php

use App\Deployment;
use App\Server;
use tests\codeception\acceptance\standardPageTests;

class DeploymentCreatePageCest extends standardPageTests
{

    protected $page;
    protected $server;
    protected $project;

    public function _before(AcceptanceTester $I)
    {
        $this->server = Server::select()->first();
        $this->project = $this->server->project;
        $this->page = route('CreateDeployment', [$this->project, $this->server], false);
    }

    public function see_a_link_to_the_Deployments_list(AcceptanceTester $I)
    {
        $I->wantTo('see a link for the Deployments list');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeLink(
            'deployments',
            route('DeploymentsIndex', [$this->project, $this->server], false)
        );
    }


    public function run_a_deployment(AcceptanceTester $I){
        $I->wantTo('run a Deployment');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $time = (new DateTime('NOW'))->format("Y-m-d H:i:s u");
        $notes = 'test deployment started: '.$time;
        $I->fillField('[name=notes]', $notes);
        $I->click('button[type=submit]');
        $I->seeRecord('deployments', [ 'server_id' => $this->server->id, 'notes' => $notes ]);
        $deployment = Deployment::where(
                [ 'server_id' => $this->server->id, 'notes' => $notes ]
            )
            ->first();
        $I->seeCurrentUrlEquals(
            route('ShowDeployment', [$this->project, $this->server, $deployment], false)
        );
    }

}
