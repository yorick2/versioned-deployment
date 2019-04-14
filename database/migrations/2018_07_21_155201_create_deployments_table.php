<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeploymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deployments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('server_id')->unsigned();
            $table->integer('user_id');
            $table->text('commit');
            $table->boolean('success')->nullable();
            $table->text('notes')->nullable();
            $table->text('output')->nullable();
            $table->timestamps();
        });
        Schema::table('deployments', function($table) {
            $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deployments');
    }
}
