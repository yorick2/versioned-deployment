<?php

use App\Project;
use tests\codeception\acceptance\standardPageTests;

class ProjectShowPageCest extends standardPageTests
{
    protected $page;
    protected $project;

    public function _before(AcceptanceTester $I)
    {
        $this->project = factory('App\Project')->create();
        $this->page = route('ShowProject', [$this->project], false);
    }

    public function _after(AcceptanceTester $I)
    {
        $this->project->owner->delete();
        $this->project->delete();
    }

    public function see_project_details(AcceptanceTester $I)
    {
        $I->wantTo('see my projects details');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->assertContains(
            (string) $this->project->created_at,
            $I->grabValueFrom('#created_at')
        );
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
}
