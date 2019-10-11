<?php

use tests\codeception\acceptance\standardPageTests;

class HomePageCest extends standardPageTests
{
    protected $page;

    public function _before(AcceptanceTester $I)
    {
        $this->page = route('home', [], false);
    }

    public function see_authorised_key(AcceptanceTester $I)
    {
        $I->wantTo('to see our public_key');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $publicKey = (new App\SshConnection)->getPublicKey();
        $I->see($publicKey);
    }
}
