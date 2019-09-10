<?php

namespace Tests\phpunit\Unit;

use App\DeploymentActions\LinkSharedFiles;
use App\DeploymentActions\PreDeploymentCommands;
use App\DeploymentActions\PostDeploymentCommands;
use App\DeploymentActions\RemoveOldReleases;
use App\DeploymentActions\UpdateCurrentAndPreviousLinks;
use App\GitInteractions\Git;
use App\GitInteractions\GitMirror;
use App\SshConnection;
use Tests\phpunit\ReflectionClasses\ReflectedGitMirrorClass;
use Tests\phpunit\TestCase;
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

    public function setup() : void
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
        $this->server = factory('App\Server')->create(['project_id'=>$this->project->id]);
        $this->deployment = factory('App\Deployment')->create(['server_id'=>$this->server->id]);
        $this->connection = new SshConnection($this->server->toArray());
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

    public function testSharedFoldersLinked(){
        $sharedFiles = [
            "test/testFolder",
            "test/testFolder2",
            "pub/testFolder"
        ];
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
        $cmd = "cd $rootLocation";
        // ensure the files are not already linked
        for($i=0;$i<sizeof($sharedFiles);$i++) {
            $cmd .= " && rm -rf {$releaseLocation}/".preg_replace('/\/+[^\/]*$/','',$sharedFiles[$i]);
        }
        // create shared files
        for($i=0;$i<sizeof($sharedFiles);$i++) {
            $cmd .= " && mkdir -p shared/{$sharedFiles[$i]}";
        }
        $this->connection->execute($cmd);
        for($i=0;$i<sizeof($sharedFiles);$i++){
            $this->assertEmpty(
                $this->connection->execute("ls -d {$releaseLocation}/{$sharedFiles[$i]}")['message'],
                "{$releaseLocation}/{$sharedFiles[$i]} exists already. So the test is invalid"
            );
        }
        $linkSharedFiles = new LinkSharedFiles($this->connection, $deployment);
        $linkSharedFiles->execute();
        for($i=0;$i<sizeof($sharedFiles);$i++) {
            $this->assertStringStartsWith(
                "{$releaseLocation}/{$sharedFiles[$i]}",
                $this->connection->execute("ls -d {$releaseLocation}/{$sharedFiles[$i]}")['message'],
                "{$releaseLocation}/{$sharedFiles[$i]} dose not exist"
            );
        }
        for($i=0;$i<sizeof($sharedFiles);$i++){
            $this->assertStringStartsWith(
                'true',
                $this->connection->execute(
                    <<<EOF
                    if [ -d "{$releaseLocation}/{$sharedFiles[$i]}" ]; then echo true; fi
EOF
                )['message'],
                "{$releaseLocation}/{$sharedFiles[$i]} is not a directory"
            );
        }
    }

    public function testSharedFilesLinked(){
        $sharedFiles = [
            "/test/test.txt",
            "/test/test2.txt",
            "/pub/test.txt"
        ];
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
        $cmd = "cd $rootLocation";
        // ensure the files are not already linked
        for($i=0;$i<sizeof($sharedFiles);$i++) {
            $cmd .= " && rm -f {$releaseLocation}/".preg_replace('/\/+[^\/]*$/','',$sharedFiles[$i]);
        }
        // create shared files
        for($i=0;$i<sizeof($sharedFiles);$i++) {
            $cmd .= ' && mkdir -p shared/'.preg_replace('/\/+[^\/]*$/','',$sharedFiles[$i]);
        }
        for($i=0;$i<sizeof($sharedFiles);$i++) {
            $cmd .= " && touch shared/{$sharedFiles[$i]}";
        }
        $this->connection->execute($cmd);
        for($i=0;$i<sizeof($sharedFiles);$i++){
            $this->assertEmpty(
                $this->connection->execute("ls -d {$releaseLocation}/{$sharedFiles[$i]}")['message'],
                "{$releaseLocation}/{$sharedFiles[$i]} exists already. So the test is invalid"
            );
        }
        $linkSharedFiles = new LinkSharedFiles($this->connection, $deployment);
        $linkSharedFiles->execute();
        for($i=0;$i<sizeof($sharedFiles);$i++) {
            $this->assertStringStartsWith(
                "{$releaseLocation}/{$sharedFiles[$i]}",
                $this->connection->execute("ls -d {$releaseLocation}/{$sharedFiles[$i]}")['message'],
                "{$releaseLocation}/{$sharedFiles[$i]} dose not exist"
            );
        }
        for($i=0;$i<sizeof($sharedFiles);$i++){
            $this->assertStringStartsWith(
                'true',
                $this->connection->execute(
                    <<<EOF
                    if [ -f "{$releaseLocation}/$sharedFiles[$i]" ]; then echo true; fi
EOF
                )['message'],
                "{$releaseLocation}/{$sharedFiles[$i]} is not a file"
            );
        }
    }

    public function testCurrentAndPreviousSymlinksWork(){
        $git = new Git(
            $this->connection,
            $this->server
        );
        $deployment = factory('App\Deployment')->create([
            'server_id' => $this->server->id,
            'commit' => '553c2077f0edc3d5dc5d17262f6aa498e69d6f8e',
        ]);
        $releaseLocation = $deployment->getCurrentReleaseLocation();
        $rootLocation = $this->server->deploy_location;
        $this->assertStringStartsNotWith(
            $releaseLocation,
            $this->connection->execute("cd -P {$rootLocation}/current && pwd")['message'],
            "current symlink already goes to our release already"
        );
        $git->deploy($deployment);
        $linkSharedFiles = new UpdateCurrentAndPreviousLinks($this->connection, $deployment);
        $linkSharedFiles->execute();
        $this->assertStringStartsWith(
            $releaseLocation,
            $this->connection->execute("cd -P {$rootLocation}/current && pwd")['message'],
            "current symlink doesn't go to our release"
        );
    }


    public function testOldReleasesAreRemoved(){
        $deployment = null;
        $rootLocation = $this->server->deploy_location;
        $git = new Git(
            $this->connection,
            $this->server
        );
        for($i=0; $i<7; $i++){
            $deployment = factory('App\Deployment')->create([
                'server_id' => $this->server->id,
                'commit' => '553c2077f0edc3d5dc5d17262f6aa498e69d6f8e',
            ]);
            $git->deploy($deployment);
            $releaseLocation = $deployment->getCurrentReleaseLocation();
            $this->assertStringStartsWith(
                $releaseLocation,
                $this->connection->execute("ls -d $releaseLocation")['message'],
                "$releaseLocation not created. The test isn't valid failed"
            );
        }
        $removeOldReleases = new RemoveOldReleases($this->connection, $deployment);
        $removeOldReleases->execute();
        $this->assertStringStartsWith(
            5,
            $this->connection->execute("ls -1 {$rootLocation}/releases | egrep  -c '' ")['message'],
            "old directories are not being deleted"
        );
    }

    public function testGitDiff()
    {
        $newCommit = 'b1b3f9723831141a31a1a7252a213e216ea76e56';
        $deployment = factory('App\Deployment')->create([
            'server_id'=>$this->server->id,
            'commit' => '7fd1a60b01f91b314f59955a4e4d4e80d8edf11d'
        ]);
        $git = new Git(
            $this->connection,
            $this->server
        );
        $git->deploy($deployment);
        $linkSharedFiles = new UpdateCurrentAndPreviousLinks($this->connection, $deployment);
        $linkSharedFiles->execute();
        $releaseLocation = $deployment->getCurrentReleaseLocation();
        $this->assertStringStartsWith(
            $releaseLocation,
            $this->connection->execute("cd -P {$this->server->deploy_location}/current && pwd")['message'],
            "current symlink doesn't go to our release"
        );
        $diff = $git->getGitDiff($newCommit);
        $this->assertStringStartsWith('README', $diff);
    }
}
