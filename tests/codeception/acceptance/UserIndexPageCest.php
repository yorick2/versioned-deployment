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
        $I->seeElement('[href="'.route('users.create').'"]');
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
        $I->seeElement('a[href="'.route('users.show', [$this->user]).'"]');
        $I->seeElement('a[href="'.route('users.edit', [$this->user]).'"]');
        $I->seeElement('form[action="'.route('user.destroy', [$this->user]).'"] button[data-title="Delete User"]');
    }

    // the current setup dosnt work with popups, so can test this atm
//    public function can_delete_a_user(AcceptanceTester $I)
//    {
//        $I->wantTo('delete a user');
//        $I->loginAsTheTestUser();
//        $I->amOnPage($this->page);
//        $I->seeCurrentUrlEquals($this->page);
//        try {
//        $I->click('ul.pagination li.page-item:nth-last-child(2) a');
//        } catch (Exception $e) {
//        }
//        $I->seeRecord('users', ['id' => $this->user->id]);
//        $I->click('form[action="'.route('user.destroy', [$this->user]).'"] button[data-title="Delete User"]');
//        $I->waitForElementVisible('#confirmDelete', 15);
//        $I->click('#confirm');
//        $I->waitForElementNotVisible('#confirmDelete', 15);
//        $I->dontSeeRecord('users', ['id' => $this->user->id]);
//    }
}
