<?php

use tests\codeception\acceptance\standardPageTests;

class ServerIndexPageCest extends standardPageTests
{
    protected $page;
    protected $project;
    protected $serverCollection;

    public function _before(AcceptanceTester $I)
    {
        $this->project = factory('App\Project')->create();
        $this->serverCollection = factory('App\Server', 5)
            ->create(['project_id' => $this->project->id]);
        $this->page = route('ServersIndex', [$this->project], false);
    }

    public function _after(AcceptanceTester $I)
    {
        foreach ($this->serverCollection as $server) {
            $server->owner->delete();
        }
        $this->project->delete();
    }

    public function see_servers_list(AcceptanceTester $I)
    {
        $I->wantTo('see my servers for a project');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        foreach ($this->serverCollection as $server) {
            $I->seeLink($server->name, $server->slug);
        }
    }

    public function see_project_link(AcceptanceTester $I)
    {
        $I->wantTo('see a link for my project');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeLink(
            $this->project->name,
            route('ShowProject', [$this->project], false)
        );
    }
}
