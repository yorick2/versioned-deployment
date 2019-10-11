<?php

class LoginPageCest
{
    protected $page;

    public function _before(AcceptanceTester $I)
    {
        $this->page = route('login', [], false);
    }

    public function i_can_see_the_brand_name(AcceptanceTester $I)
    {
        $I->wantTo('see the brand name');
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeLink('Version Deployment', '');
    }

    public function i_can_reset_my_password(AcceptanceTester $I)
    {
        $I->wantTo('see the login fields');
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeLink('Forgot Your Password?', '/password/reset');
    }

    public function i_can_login(AcceptanceTester $I)
    {
        $I->wantTo('login as a user');
        $I->loginAsTheTestUser();
    }
}
