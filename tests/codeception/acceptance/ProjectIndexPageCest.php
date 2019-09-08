<?php

use App\Project;
use tests\codeception\acceptance\standardPageTests;

class ProjectIndexPageCest extends standardPageTests
{

    protected $page;

    public function _before(AcceptanceTester $I)
    {
        $this->page = route('ProjectsIndex', [], false);
    }

    public function see_projects_list(AcceptanceTester $I)
    {
        $I->wantTo('see my projects');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $projectCollection = Project::select()
            ->orderBy('id','desc')
            ->take(5)
            ->get();
        $I->assertTrue($projectCollection->count()>0);
        foreach($projectCollection as $project){
            $I->seeLink($project->name,$project->slug);
        }
    }

}
