<?php

use tests\codeception\acceptance\standardPageTests;

class ProjectEditPageCest extends standardPageTests
{

    protected $page;
    protected $project;

    public function _before(AcceptanceTester $I)
    {
        $originalProjectData = [
            'name' => 'test',
            'repository' => 'https://github.com/octocat/Hello-World',
            'notes' => 'some test notes goe here'
        ];
        $this->project = factory('App\Project')->create($originalProjectData);
        $I->seeRecord('projects',$originalProjectData);
        $this->page = route( 'EditProject', [$this->project],false);
    }

    public function _after(AcceptanceTester $I)
    {
        $this->project->owner->delete();
        $this->project->delete();
    }

    public function see_a_link_to_the_projects_list(AcceptanceTester $I)
    {
        $I->wantTo('see a link for the projects list');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeLink(
            'projects',
            route('ProjectsIndex', [], false)
        );
    }

    public function edit_a_project(AcceptanceTester $I)
    {
        $I->wantTo('edit a project');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $newData = [
            'name' => 'test_two',
            'repository' => 'https://github.com/test/test',
            'notes' => 'some test new notes goe here, replacing old ones'
        ];
        foreach($newData as $key => $data){
            $I->fillField("[name={$key}]", $data);
        }
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals(
            route('ProjectsIndex', [], false)
        );
        $newData['id'] = $this->project->id;
        $I->seeRecord('projects',$newData);
    }


}
