<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected $servers;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->addDeploymentData();
        $this->addMyUser();
    }

    protected function addDeploymentData(){
        $projects = factory('App\Project',12)->create();
        $projects->each(function ($project){
            $servers = factory('App\Server', 12)->create(['project_id'=>$project->id]);
            $servers->each(function ($server){
                factory('App\Deployment', 12)->create(['server_id'=>$server->id]);
            });
        });
        return $this;
    }

    protected function addMyUser(){
        DB::table('users')->insert([
            'name' => 'Mr Test Tester',
            'email' => 'test@test.com',
            'password' => bcrypt('password1')
        ]);
        return $this;
    }
}
