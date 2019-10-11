<?php

use App\Server;
use App\User;
use tests\codeception\acceptance\standardPageTests;

class UserCreatePageCest extends standardPageTests
{
    protected $page;
    protected $user;

    public function _before(AcceptanceTester $I)
    {
        $this->user = factory('App\User')->make();
        $this->page = route('users.create', [], false);
    }

    public function _after(AcceptanceTester $I)
    {
        $this->user->delete();
    }

    public function see_a_link_to_the_users_list(AcceptanceTester $I)
    {
        $I->wantTo('see a link for the users list');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $I->seeLink(
            'users',
            route('users', [], false)
        );
    }

    protected function removeFieldsNotOnForm($data)
    {
        unset(
            $data['id'],
            $data['password'],
            $data['remember_token'],
            $data['created_at'],
            $data['updated_at']
        );
        return $data;
    }

    public function create_a_user(AcceptanceTester $I)
    {
        $I->wantTo('create a user');
        $I->loginAsTheTestUser();
        $I->amOnPage($this->page);
        $I->seeCurrentUrlEquals($this->page);
        $data = $this->removeFieldsNotOnForm($this->user->toArray());
        foreach ($data as $key => $value) {
            $I->fillField("[name={$key}]", $value);
        }
        $I->fillField("[name=password]", 'password');
        $I->fillField("[name=password_confirmation]", 'password');
        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals(
            route('users', [], false)
        );
        $loadedUser = User::select()
            ->orderBy('created_at', 'desc')
            ->first()
            ->toArray();
        $I->assertEmpty(
            array_diff_assoc($data, $this->removeFieldsNotOnForm($loadedUser))
        );
    }
}
