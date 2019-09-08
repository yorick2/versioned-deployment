<?php

use App\Server;
use tests\codeception\acceptance\standardPageTests;

class ServerCreatePageCest extends standardPageTests
{

    protected $page;
    protected $project;
    protected $server;

    public function _before(AcceptanceTester $I)
    {
        $this->project = $this->server = factory('App\Project')->create();
        $this->server = factory('App\Server')->make(['project_id' => $this->project->id, 'deploy_branch' => 'test']);
        $this->page = route('CreateServer', [$this->project], false);
    }

    public function _after(AcceptanceTester $I)
    {
        $this->server->owner->delete();
        $this->project->owner->delete();
        $this->project->delete();
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

    protected function removeFieldsNotOnForm($data){
        unset(
            $data['project_id'],
            $data['user_id'],
            $data['slug'],
            $data['id'],
            $data['created_at'],
            $data['updated_at']
        );
        return $data;
    }

    public function create_a_server(AcceptanceTester $I)
    {
        $I->wantTo('create a server');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $data = $this->removeFieldsNotOnForm($this->server->toArray());
        foreach($data as $key => $value){
            if($key == 'deploy_branch') {
                continue;
            }
            $I->fillField("[name={$key}]", $value);
        }
        $I->selectOption("[name=deploy_branch]", $data['deploy_branch']); // this can fail an issue if $this->project->repository is a url to a github repo that dosnt have a test git branch
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals(
            route('ServersIndex', [$this->project], false)
        );
        $loadedServer = Server::select()
            ->orderBy('created_at', 'desc')
            ->first()
            ->toArray();
        $I->assertEmpty(
            array_diff_assoc($data, $this->removeFieldsNotOnForm($loadedServer))
        );
    }
}
