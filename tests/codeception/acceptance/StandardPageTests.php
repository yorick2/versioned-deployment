<?php

namespace tests\codeception\acceptance;

abstract class standardPageTests
{
    /**
     * @var string
     */
    protected $page;

    public function can_see_the_top_navigation_links(\AcceptanceTester $I) {
        $I->wantTo('see the top navigation links');
        $I->assertNotNull($this->page);
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $links = [
            'Version Deployment' => '',
            'Home' => route('home',[],false),
            'Users' => route('users',[],false),
            'Projects' => route('Projects',[],false),
            'Logout' => route('logout',[],false),
        ];
        $keys = array_keys($links);
        for($j=0;$j<count($links);$j++){
            $I->seeLink($keys[$j], $links[$keys[$j]]);
        }
    }
}