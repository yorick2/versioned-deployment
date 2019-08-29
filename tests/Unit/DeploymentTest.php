<?php

namespace Tests\Unit;

use App\DeploymentActions\LinkSharedFiles;
use App\DeploymentActions\PreDeploymentCommands;
use App\DeploymentActions\PostDeploymentCommands;
use App\GitInteractions\Git;
use App\GitInteractions\GitMirror;
use App\SshConnection;
use Tests\ReflectionClasses\ReflectedGitMirrorClass;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeploymentTest extends TestCase
{
    use DatabaseMigrations;

    protected $project;
    protected $server;
    protected $deployment;
    protected $connection;

    public function setup()
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
        $this->server = factory('App\Server')->create(['project_id'=>$this->project->id]);
        $this->deployment = factory('App\Deployment')->create(['server_id'=>$this->server->id]);
        $this->connection = new SshConnection([
            'deploy_host' => 'example.com',
            'deploy_user' => 'test',
            'deploy_port' => 22
        ]);
        $this->connection->connect();
    }

    public function testItHasAnOwner()
    {
        $this->assertInstanceOf('App\User',$this->deployment->owner);
    }

    public function testItHasAUrlPath(){
        $this->assertEquals(
            route(
                'ShowDeployment',
                [
                    'deployment'=>$this->deployment,
                    'server'=> $this->server,
                    'project' => $this->project
                ],
                false
            ),
            $this->deployment->path()
        );
    }

    public function testCanCreateSshConnection()
    {
        $response = $this->connection->execute('echo foo');
        $this->assertStringStartsWith('foo', $response['message']);
    }

    public function testCreateMirrorWorks(){
        $reflectedGitMirrorClass = new ReflectedGitMirrorClass($this->connection, $this->server);
        $mirrorFolder = $reflectedGitMirrorClass->getRefFolder();
        $gitMirror = new GitMirror(
            $this->connection,
            $this->server
        );
        $gitMirror->clear();
        $response = $this->connection->execute("ls $mirrorFolder");
        $this->assertEmpty($response['message']);
        $gitMirror->update();
        $response = $this->connection->execute("ls $mirrorFolder");
        $this->assertRegExp('/HEAD/',$response['message']);
        $this->assertRegExp('/branches/',$response['message']);
    }

    public function testGitCloneWorks()
    {
        $git = new Git(
            $this->connection,
            $this->server
        );
        $deployment_one = factory('App\Deployment')->create([
            'server_id' => $this->server->id,
            'commit' => '553c2077f0edc3d5dc5d17262f6aa498e69d6f8e',
        ]);
        $git->deploy($deployment_one);
        $response = $this->connection->execute("cd {$deployment_one->getCurrentReleaseLocation()} && git rev-parse HEAD");
        $this->assertStringStartsWith('553c2077f0edc3d5dc5d17262f6aa498e69d6f8e', $response['message']);
        $git_two = new Git(
            $this->connection,
            $this->server
        );
        $deployment_two = factory('App\Deployment')->create([
            'server_id' => $this->server->id,
            'commit' => '7fd1a60b01f91b314f59955a4e4d4e80d8edf11d',
        ]);
        $git_two->deploy($deployment_two);
        $response = $this->connection->execute("cd {$deployment_two->getCurrentReleaseLocation()} && git rev-parse HEAD");
        $this->assertStringStartsWith('7fd1a60b01f91b314f59955a4e4d4e80d8edf11d', $response['message']);
    }

    public function testPreDeploymentCustomCommandsWorks(){
        $git = new Git(
            $this->connection,
            $this->server
        );
        $deployment = factory('App\Deployment')->create([
            'server_id' => $this->server->id,
            'commit' => '553c2077f0edc3d5dc5d17262f6aa498e69d6f8e',
        ]);
        $location = $deployment->getCurrentReleaseLocation();
        $git->deploy($deployment);
        $this->assertStringStartsNotWith(
            "{$location}/placeholder.txt",
            $this->connection->execute("ls  {$location}/placeholder.txt")['message']
        );
        $preDeploymentCommand = new PreDeploymentCommands($this->connection, $deployment);
        $preDeploymentCommand->execute();
        $this->assertStringStartsWith(
            "{$location}/placeholder.txt",
            $this->connection->execute("ls  {$location}/placeholder.txt")['message']
        );
    }

    public function testPostDeploymentCustomCommandsWorks(){
        $location = $this->server->deploy_location;
        $this->connection->execute("touch {$location}/placeholder.txt");
        $this->assertStringStartsWith(
            "{$location}/placeholder.txt",
            $this->connection->execute("ls  {$location}/placeholder.txt")['message']
        );
        $postDeploymentCommand = new PostDeploymentCommands($this->connection, $this->deployment);
        $postDeploymentCommand->execute();
        $this->assertStringStartsNotWith(
            "{$location}/placeholder.txt",
            $this->connection->execute("ls  {$location}/placeholder.txt")['message']
        );
    }

    public function testSharedFilesLinked(){
        $git = new Git(
            $this->connection,
            $this->server
        );
        $deployment = factory('App\Deployment')->create([
            'server_id' => $this->server->id,
            'commit' => '553c2077f0edc3d5dc5d17262f6aa498e69d6f8e',
        ]);
        $releaseLocation = $deployment->getCurrentReleaseLocation();
        $git->deploy($deployment);
        $rootLocation = $this->server->deploy_location;
        $this->connection->execute(
            <<<EOF
            cd $rootLocation &&
            mkdir -p pub &&
            rm -rf test &&
            rm pub/test.txt 
            mkdir -p shared/test/testFolder &&
            mkdir -p shared/pub &&
            touch shared/test/test.txt &&
            touch shared/test/test2.txt &&
            touch shared/pub/test.txt
EOF
        );
        // test files/folders dont exist already
        $this->assertEmpty(
            $this->connection->execute("ls -d {$releaseLocation}/test/testFolder")['message'],
            "{$releaseLocation}/test/testfolder exists already"
        );
        $this->assertEmpty(
            $this->connection->execute("ls -d  {$releaseLocation}/test/test.txt")['message'],
            "{$releaseLocation}/test/test.txt exists already"
        );
        $this->assertEmpty(
            $this->connection->execute("ls -d  {$releaseLocation}/test/test2.txt")['message'],
            "{$releaseLocation}/test/test2.txt exists already"
        );
        $this->assertEmpty(
            $this->connection->execute("ls -d  {$releaseLocation}/pub/test.txt")['message'],
            "{$releaseLocation}/pub/test.txt exists already"
        );
        $linkSharedFiles = new LinkSharedFiles($this->connection, $deployment);
        $linkSharedFiles->execute();
        // test files/folders exist
        $this->assertStringStartsWith(
            "{$releaseLocation}/test/testFolder",
            $this->connection->execute("ls -d {$releaseLocation}/test/testFolder")['message'],
            "{$releaseLocation}/test/testFolder dose not exist"
        );
        $this->assertStringStartsWith(
            "{$releaseLocation}/test/test.txt",
            $this->connection->execute("ls -d  {$releaseLocation}/test/test.txt")['message'],
            "{$releaseLocation}/test/test.txt dose not exist"
        );
        $this->assertStringStartsWith(
            "{$releaseLocation}/test/test2.txt",
            $this->connection->execute("ls -d  {$releaseLocation}/test/test2.txt")['message'],
            "{$releaseLocation}/test/test2.txt dose not exist"
        );
        $this->assertStringStartsWith(
            "{$releaseLocation}/pub/test.txt",
            $this->connection->execute("ls -d  {$releaseLocation}/pub/test.txt")['message'],
            "{$releaseLocation}/pub/test.txt dose not exist"
        );
        $this->assertStringStartsWith(
            'true',
            $this->connection->execute('if [ -d "'.$releaseLocation.'/test/testFolder" ]; then echo true; fi')['message'],
            "{$releaseLocation}/test/testFolder is not a directory"
        );
        $this->assertStringStartsWith(
            'true',
            $this->connection->execute('if [ -f "'.$releaseLocation.'/test/test.txt" ]; then echo true; fi')['message'],
            "{$releaseLocation}/test/test.txt is not a file"
        );
        $this->assertStringStartsWith(
            'true',
            $this->connection->execute('if [ -f "'.$releaseLocation.'/test/test2.txt" ]; then echo true; fi')['message'],
            "{$releaseLocation}/test/test2.txt is not a file"
        );
        $this->assertStringStartsWith(
            'true',
            $this->connection->execute('if [ -f "'.$releaseLocation.'/pub/test.txt" ]; then echo true; fi')['message'],
            "{$releaseLocation}/pub/test.txt is not a file"
        );
    }

//    public function testCurrentSymlinksWork(){}
//    public function testPreviousSymlinksWork(){}
//    public function testOldReleasesAreRemoved(){}
}
