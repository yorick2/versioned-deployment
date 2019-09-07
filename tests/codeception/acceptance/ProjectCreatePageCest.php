<?php

use App\Project;
use tests\codeception\acceptance\standardPageTests;

class ProjectCreatePageCest extends standardPageTests
{

    protected $page;
    protected $project;

    public function _before(AcceptanceTester $I)
    {
        $this->project = factory('App\Project')->make([]);
        $this->page = route('CreateProject', [$this->project], false);
    }

    public function see_a_link_to_the_projects_list(AcceptanceTester $I)
    {
        $I->wantTo('see a link for the projects list');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeLink(
            'projects',
            route('Projects', [], false)
        );
    }

    protected function removeFieldsNotOnForm($data){
        unset(
            $data['user_id'],
            $data['slug'],
            $data['id'],
            $data['created_at'],
            $data['updated_at']
        );
        return $data;
    }

    public function create_a_project(AcceptanceTester $I)
    {
        $I->wantTo('create a project');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $data = $this->removeFieldsNotOnForm($this->project->toArray());
        foreach($data as $key => $value){
            if($key == 'deploy_branch') {
                continue;
            }
            $I->fillField("[name={$key}]", $value);
        }
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals(
            route('Projects', [], false)
        );
        $loadedProject = Project::select()
            ->orderBy('created_at', 'desc')
            ->first()
            ->toArray();
        $I->assertEmpty(
            array_diff_assoc($data, $this->removeFieldsNotOnForm($loadedProject))
        );
    }
}
