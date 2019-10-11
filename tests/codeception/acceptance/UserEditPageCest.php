<?php

use tests\codeception\acceptance\standardPageTests;

class UserEditPageCest extends standardPageTests
{
    protected $page;
    protected $user;

    public function _before(AcceptanceTester $I)
    {
        $this->user = factory('App\User')->create();
        $this->page = route('users.edit', [$this->user], false);
    }

    public function _after()
    {
        $this->user->delete();
    }

    public function see_a_link_to_the_users_list(AcceptanceTester $I)
    {
        $I->wantTo('see a link for the servers list');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeLink('Users', route('users', [], false));
    }

    // the current setup dosnt work with js, so can test this atm
//    public function edit_a_user(AcceptanceTester $I)
//    {
//        $I->wantTo('edit a server');
//        $I->loginAsTheTestUser();
//        $I->amOnPage($this->page);
//        $I->seeCurrentUrlEquals($this->page);
//        $newData = [
//            'name' => 'test two',
//            'email' => 'test@test_two.com'
//        ];
//        foreach($newData as $key => $data){
//            $I->fillField('[name="'.$key.'"]', $data);
//        }
//        $I->click('button.btn-save');
//        $I->see('Success');
//        $newData['id'] = $this->user->id;
//        $I->seeRecord('servers',$newData);
//    }

// the current setup dosnt work with js, so can test this atm
//    public function change_user_password(AcceptanceTester $I)
//    {
//    }
}
