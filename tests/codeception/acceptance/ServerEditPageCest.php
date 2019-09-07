<?php

use tests\codeception\acceptance\standardPageTests;

class ServerEditPageCest extends standardPageTests
{

    protected $page;
    protected $server;
    protected $project;

    public function _before(AcceptanceTester $I)
    {
        $this->project = factory('App\Project')->create();
        $originalServerData = [
            'project_id' => $this->project->id,
            'name' => 'test',
            'deploy_host' => 'test.com',
            'deploy_port' => '22',
            'deploy_location' => '/var/www',
            'deploy_user' => 'test_user',
            'deploy_branch' => 'master',
            'shared_files' => 'test/testFolder, test/testFolder2, pub/testFolder, test/test.txt, test/test2.txt, pub/test.txt',
            'pre_deploy_commands' => 'touch placeholder.txt',
            'post_deploy_commands' => 'rm placeholder.txt',
            'notes' => 'some test notes goe here'
        ];
        $this->server = factory('App\Server')->create($originalServerData);
        $I->seeRecord('servers',$originalServerData);
        $this->page = route( 'EditServer', [$this->project, $this->server],false);
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

    public function edit_a_server(AcceptanceTester $I)
    {
        $I->wantTo('edit a server');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $newData = [
            'name' => 'test two',
            'deploy_host' => 'test_two.com',
            'deploy_port' => '2020',
            'deploy_location' => '/var/www/two',
            'deploy_user' => 'test_user_two',
            'deploy_branch' => 'test',
            'shared_files' => 'test/testFolder/two',
            'pre_deploy_commands' => 'touch placeholder_two.txt',
            'post_deploy_commands' => 'rm placeholder_two.txt',
            'notes' => 'some test new notes goe here, replacing old ones'
        ];
        foreach($newData as $key => $data){
            if($key == 'deploy_branch'){
                continue;
            }
            $I->fillField('[name="'.$key.'"]', $data);
        }
        $I->selectOption("[name=deploy_branch]", $newData['deploy_branch']); // this can fail an issue if $this->project->repository is a url to a github repo that dosnt have a test git branch
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals(
            route('ServersIndex', [$this->project], false)
        );
        $newData['id'] = $this->server->id;
        $I->seeRecord('servers',$newData);
    }


}
