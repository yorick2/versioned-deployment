<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('servers')){
            return;
        }
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->integer('project_id')->unsigned();
            $table->integer('user_id');
            $table->string('name');
            $table->string('deploy_host');
            $table->string('deploy_port')->nullable();
            $table->string('deploy_location');
            $table->string('deploy_user');
            $table->string('deploy_branch');
            $table->string('shared_files')->nullable();
            $table->string('pre_deploy_commands')->nullable();
            $table->string('post_deploy_commands')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        Schema::table('servers', function ($table) {
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
