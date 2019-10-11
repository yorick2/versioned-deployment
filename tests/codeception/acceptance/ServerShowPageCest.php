<?php

use App\Server;
use tests\codeception\acceptance\standardPageTests;

class ServerShowPageCest extends standardPageTests
{
    protected $page;
    protected $server;
    protected $project;

    public function _before(AcceptanceTester $I)
    {
        $this->server = factory('App\Server')->create();
        $this->project = $this->server->project;
        $this->page = route('ShowServer', [$this->project, $this->server], false);
    }

    public function _after(AcceptanceTester $I)
    {
        $this->server->owner->delete();
        $this->project->delete();
    }

    public function see_server_details(AcceptanceTester $I)
    {
        $I->wantTo('see my servers details');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeInField('form input[name=name]', $this->server->name);
    }

    public function see_a_link_to_the_servers_list(AcceptanceTester $I)
    {
        $I->wantTo('see a link for the servers list');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeLink(
            'servers',
            route('ServersIndex', [$this->project], false)
        );
    }
}
