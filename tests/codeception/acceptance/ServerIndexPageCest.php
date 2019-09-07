<?php

use App\Project;
use App\Server;
use tests\codeception\acceptance\standardPageTests;

class ServerIndexPageCest extends standardPageTests
{

    protected $page;
    protected $project;

    public function _before(AcceptanceTester $I)
    {
        $this->project = Project::select()->first();
        $this->page = route('ServersIndex', [$this->project], false);
    }

    public function see_servers_list(AcceptanceTester $I)
    {
        $I->wantTo('see my servers for a project');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $serverCollection = Server::where('project_id',$this->project->id)
            ->orderBy('id','desc')
            ->take(5)
            ->get();
        $I->assertTrue($serverCollection->count()>0);
        foreach($serverCollection as $server){
            $I->seeLink($server->name,$server->slug);
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
