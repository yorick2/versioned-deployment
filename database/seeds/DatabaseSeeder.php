<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

    /**
     * @return $this
     */
    protected function addDeploymentData(){
        $projects = factory('App\Project', 5)->create();
        $projects->each(function ($project){
            $servers = factory('App\Server', 5)->create(['project_id'=>$project->id]);
            $servers->each(function ($server){
                factory('App\Deployment', 5)->create(['server_id'=>$server->id]);
            });
        });
        return $this;
    }

    /**
     * @return $this
     */
    protected function addMyUser(){
        $password = 'password1';
        $user = User::where('email','test@test.com')->first();
        if($user){
            $user->password = Hash::make($password);
            $user->save();
            return $this;
        }
        DB::table('users')->insert([
            'name' => 'Mr Test Tester',
            'email' => 'test@test.com',
            'password' => Hash::make($password)
        ]);
        return $this;
    }
}
