<?php

use App\Deployment;
class DeploymentGitDiffPageCest
{

    protected $page;
    protected $deployPage;
    protected $server;
    protected $project;
    protected $deployment;

    public function _before(AcceptanceTester $I)
    {
        $this->server = factory('App\Server')->create(['deploy_branch'=>'test']);
        $this->project = $this->server->project;
        $this->page = route('GitDiffDeployment', [$this->project, $this->server], false);
        $this->deployPage = route('CreateDeployment', [$this->project, $this->server], false);
    }

    public function _after(AcceptanceTester $I)
    {
        if($this->deployment){
            $this->deployment->owner->delete();
        }
        $this->server->owner->delete();
        $this->project->owner->delete();
        $this->project->delete();
    }

//      the test browser dosnt understand the formaction property so cant run test atm
//    public function see_git_diff(AcceptanceTester $I)
//    {
//        $I->wantTo('see git diff');
//        $I->loginAsTheTestUser();
//        $this->deployCommit($I, '7fd1a60b01f91b314f59955a4e4d4e80d8edf11d');
//        $I->amOnPage($this->deployPage);
//        $I->seeCurrentUrlEquals($this->deployPage);
//        $I->selectOption(
//            '[name=commit]',
//            'b3cbd5bbd7e81436d2eee04537ea2b4c0cad4cdf'
//        );
//        $I->click('git diff');
//        $I->seeCurrentUrlEquals(
//            $this->page,
//            'I cant see the git diff page'
//        );
//        $I->see('CONTRIBUTING.md','i didnt see the git diff (name of the file that has changed between the commits)');
//        $I->wantTo('see a link back to the deployments index');
//        $I->seeLink(
//            'deployments',
//            route('DeploymentsIndex', [$this->project, $this->server], false)
//        );
//    }

    protected function deployCommit(AcceptanceTester $I, string $commit)
    {
        $I->amOnPage($this->deployPage);
        $I->seeCurrentUrlEquals($this->deployPage);
        $time = (new DateTime('NOW'))->format("Y-m-d H:i:s u");
        $notes = 'test deployment started: '.$time;
        $I->fillField('[name=notes]', $notes);
        $I->selectOption(
            '[name=commit]',
            $commit
        );
        $I->click('deploy');
        $I->seeRecord('deployments', [ 'server_id' => $this->server->id, 'notes' => $notes ]);
        $this->deployment = Deployment::where(
            [ 'server_id' => $this->server->id, 'notes' => $notes ]
        )
            ->first();
        $I->seeCurrentUrlEquals(
            route('ShowDeployment', [$this->project, $this->server, $this->deployment], false),
            'deployment failed'
        );
    }

}
