<?php

use tests\codeception\acceptance\standardPageTests;

class UserIndexPageCest extends standardPageTests
{
    protected $page;
    protected $user;

    public function _before(AcceptanceTester $I)
    {
        $this->user = factory('App\User')->create();
        $this->page = route('users', [], false);
    }

    public function _after()
    {
        $this->user->delete();
    }

    public function see_users_list(AcceptanceTester $I)
    {
        $I->wantTo('see the users');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        try {
            $I->click('ul.pagination li.page-item:nth-last-child(2) a');
        } catch (Exception $e) {
        }
        $I->see($this->user->email);
    }

    public function see_add_users_link(AcceptanceTester $I)
    {
        $I->wantTo('see a link to add a user');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeLink(
            'New User',
            route('users.create',[],false)
        );
    }

    public function see_show_edit_delete_user_links(AcceptanceTester $I)
    {
        $I->wantTo('see a link to show, edit or delete a user');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        try {
            $I->click('ul.pagination li.page-item:nth-last-child(2) a');
        } catch (Exception $e) {
        }
        $I->seeLink(
            'Show User',
            route('users.show', [$this->user],false)
        );
        $I->seeLink(
            'Edit User',
            route('users.edit', [$this->user],false)
        );
        $I->seeElement('form[action$="'.route('user.destroy', [$this->user], false).'"] button[data-title="Delete User"]');
    }
}
