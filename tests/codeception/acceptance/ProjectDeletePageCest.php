<?php

use tests\codeception\acceptance\standardPageTests;

class ProjectDeletePageCest extends standardPageTests
{

    protected $page;
    protected $project;

    public function _before(AcceptanceTester $I)
    {
        $this->project = factory('App\Project')->create([]);
        $this->page = route('DeleteProject', [$this->project], false);
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

    public function deleting_a_project_forces_confirmation(AcceptanceTester $I)
    {
        $I->wantTo('see an error message prompting me to select the confirm checkbox');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals($this->page);
        $I->see('Please confirm you want to delete');
        $I->seeRecord('projects', ['id' => $this->project->id], 'we dont want to delete the item without confirmation');
    }

    public function delete_a_project(AcceptanceTester $I)
    {
        $I->wantTo('delete a project');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->checkOption('[name=confirm]');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals(route('ProjectsIndex', [], false));
        $I->dontSeeRecord('projects', ['id' => $this->project->id], 'we dont want to delete the item without confirmation');
    }
}
