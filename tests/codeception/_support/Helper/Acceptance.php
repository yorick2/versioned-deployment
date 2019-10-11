<?php
namespace Helper;

use App\User;
use Illuminate\Support\Facades\Hash;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
    public function getTestUserDataArray()
    {
        return [
            'name' => 'Mr Test Tester',
            'email' => 'test@test.com',
            'password' => 'password1'
        ];
    }

    /**
     * @return User
     */
    public function createOrUpdateTestUser()
    {
        $userData = $this->getTestUserDataArray();
        $user = User::where('email', $userData['email'])
            ->first();
        if (!$user) {
            $user = new User($userData);
        }
        $user->password = Hash::make($userData['password']);
        $user->save();
        return $user;
    }

    /**
     * @return User
     * @throws \Codeception\Exception\ModuleException
     */
    public function loginAsTheTestUser()
    {
        $phpBrowserModule = $this->getModule('PhpBrowser');
        $userData = $this->getTestUserDataArray();
        $user = $this->createOrUpdateTestUser();
        $phpBrowserModule->amOnPage(route('login', [], false));
        $phpBrowserModule->seeCurrentUrlEquals(route('login', [], false));
        $phpBrowserModule->fillField('email', $userData['email']);
        $phpBrowserModule->fillField('password', $userData['password']);
        $phpBrowserModule->click('button[type=submit]');
        $phpBrowserModule->seeCurrentUrlEquals(route('home', [], false));
        return $user;
    }
}
