<?php

class HomepageCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function i_can_go_to_login(AcceptanceTester $I)
    {
        $I->wantTo('login as a user');
        $I->amOnPage('/');
        $I->dontSee('Logout');
        $I->seeLink('Login','/login');
        $I->click('Login');
        $I->amOnPage('/login');
    }

    public function i_can_see_brand(AcceptanceTester $I)
    {
        $I->wantTo('see brand name');
        $I->amOnPage('/');
        $I->see('version deployment');
    }
}
