<?php

use App\Project;
use tests\codeception\acceptance\standardPageTests;

class ProjectShowPageCest extends standardPageTests
{

    protected $page;
    protected $project;

    public function _before(AcceptanceTester $I)
    {
        $this->project = Project::select()->first();
        $this->page = route( 'ShowProject', [$this->project],false);
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
}
