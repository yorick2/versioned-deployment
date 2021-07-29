<?php

use tests\codeception\acceptance\standardPageTests;

class UserShowPageCest extends standardPageTests
{
    protected $page;
    protected $user;

    public function _before(AcceptanceTester $I)
    {
        $this->user = factory('App\User')->create();
        $this->page = route('users.show', [$this->user], false);
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

    public function see_user_details(AcceptanceTester $I)
    {
        $I->wantTo('see the users');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->see($this->user->id);
        $I->see($this->user->name);
        $I->see($this->user->email);
    }

    public function see_show_edit_delete_user_links(AcceptanceTester $I)
    {
        $I->wantTo('see a link to show, edit or delete a user');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeElement('a[href="'.route('users.edit', [$this->user], false).'"]');
        $I->seeElement('form[action$="'.route('user.destroy', [$this->user],false).'"] button[data-title="Delete User"]');
    }
}
