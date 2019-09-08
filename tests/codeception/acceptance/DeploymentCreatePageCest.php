<?php

use App\Deployment;
use tests\codeception\acceptance\standardPageTests;

class DeploymentCreatePageCest extends standardPageTests
{

    protected $page;
    protected $server;
    protected $project;
    protected $deployment;

    public function _before(AcceptanceTester $I)
    {
        $this->server = factory('App\Server')->create();
        $this->project = $this->server->project;
        $this->page = route('CreateDeployment', [$this->project, $this->server], false);
    }

    public function _after(AcceptanceTester $I)
    {
        if($this->deployment){
            $this->deployment->owner->delete();
        }
        $this->server->owner->delete();
        $this->project->owner->delete();
        $this->project->delete();
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
        $this->deployment = Deployment::where(
                [ 'server_id' => $this->server->id, 'notes' => $notes ]
            )
            ->first();
        $I->seeCurrentUrlEquals(
            route('ShowDeployment', [$this->project, $this->server, $this->deployment], false)
        );
    }
}
