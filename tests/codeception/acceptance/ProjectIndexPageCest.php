<?php

use tests\codeception\acceptance\standardPageTests;

class ProjectIndexPageCest extends standardPageTests
{

    protected $page;
    protected $projectCollection;

    public function _before(AcceptanceTester $I)
    {
        $this->page = route('ProjectsIndex', [], false);
        $this->projectCollection = factory('App\Project', 5)
            ->create();
    }

    public function _after(AcceptanceTester $I)
    {
        foreach ($this->projectCollection as $project){
            $project->owner->delete();
            $project->delete();
        }
    }

    public function see_projects_list(AcceptanceTester $I)
    {
        $I->wantTo('see my projects');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        foreach($this->projectCollection as $project){
            $I->seeLink($project->name,$project->slug);
        }
    }

}
