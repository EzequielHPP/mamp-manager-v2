<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('setting');
            $table->string('value');
            $table->timestamps();
        });

        Schema::create('project', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('project_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->index();
            $table->string('url');
            $table->boolean('https');
            $table->string('directory');
            $table->string('icon');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('project')->onDelete('cascade');
        });

        Schema::create('project_assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->index();
            $table->string('preview');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('project')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_assets');
        Schema::dropIfExists('project_settings');
        Schema::dropIfExists('project');
        Schema::dropIfExists('settings');
    }
}
