<?php 

class LoginCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function i_can_see_the_brand_name(AcceptanceTester $I){
        $I->wantTo('see the brand name');
        $I->amOnPage('/login');
        $I->seeLink('Version Deployment','');
    }

    public function i_can_reset_my_password(AcceptanceTester $I) {
        $I->wantTo('see the login fields');
        $I->amOnPage('/login');
        $I->seeLink('Forgot Your Password?', '/password/reset');
    }

    public function i_can_login(AcceptanceTester $I)
    {
        $I->wantTo('login as a user');
        $I->amOnPage('/login');
        $I->haveRecord('users', [
            'name' =>  'john doe',
            'email' =>  'john@doe.com',
            'password' => bcrypt('password'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        $I->fillField('email', 'john@doe.com');
        $I->fillField('password', 'password');
        $I->dontSeeCheckboxIsChecked('Remember Me');
        $I->checkOption('Remember Me');
        $I->click('button[type=submit]');
        $I->amOnPage('/home');
    }
}
