<?php

use tests\codeception\acceptance\standardPageTests;

class ServerDeletePageCest extends standardPageTests
{

    protected $page;
    protected $server;
    protected $project;

    public function _before(AcceptanceTester $I)
    {
        $this->server = factory('App\Server')->create([]);
        $this->project = $this->server->project;
        $this->page = route('DeleteServer', [$this->project, $this->server], false);
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

    public function deleting_a_server_forces_confirmation(AcceptanceTester $I)
    {
        $I->wantTo('see an error message prompting me to select the confirm checkbox');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals($this->page);
        $I->see('Please confirm you want to delete');
        $I->seeRecord('servers', ['id' => $this->server->id], 'we dont want to delete the item without confirmation');
    }

    public function delete_a_server(AcceptanceTester $I)
    {
        $I->wantTo('delete a server');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->checkOption('[name=confirm]');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals(route('ServersIndex', [$this->project], false));
        $I->dontSeeRecord('servers', ['id' => $this->server->id], 'we dont want to delete the item without confirmation');
    }
}
