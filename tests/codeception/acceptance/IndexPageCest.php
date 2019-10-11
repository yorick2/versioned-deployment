<?php

class IndexPageCest
{
    protected $page;

    public function _before(AcceptanceTester $I)
    {
        $this->page = '';
    }

    public function i_can_see_brand(AcceptanceTester $I)
    {
        $I->wantTo('see brand name');
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->see('version deployment');
    }

    public function i_can_go_to_login(AcceptanceTester $I)
    {
        $I->wantTo('login as a user');
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->dontSee('Logout');
        $I->seeLink(
            'Login',
            route('login', [], false)
        );
        $I->click('Login');
        $I->seeCurrentUrlEquals(
            route('login', [], false)
        );
    }
}
