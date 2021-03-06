<?php

namespace tests\codeception\acceptance;

/**
 * Class standardPageTests
 * @package tests\codeception\acceptance
 *
 * I use some custom actions defined in my module tests/codeception/_support/Helper/Acceptance.php
 *
 */
abstract class standardPageTests
{
    /**
     * @var string
     */
    protected $page;

    public function can_see_the_top_navigation_links(\AcceptanceTester $I)
    {
        $I->wantTo('see the top navigation links');
        $I->assertNotNull($this->page);
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $links = [
            'Version Deployment' => '',
            'Home' => route('home', [], false),
            'Users' => route('users', [], false),
            'Projects' => route('ProjectsIndex', [], false),
            'Logout' => route('logout', [], false),
        ];
        $keys = array_keys($links);
        for ($j=0;$j<count($links);$j++) {
            $I->seeLink($keys[$j], $links[$keys[$j]]);
        }
    }
}
