<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileLinkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_linker', function (Blueprint $table){
            $table->increments('id');
            $table->integer('profile_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->integer('checklist_template_id')->references('id')->on('checklist_template')->onDelete('cascade');;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_linker');
    }
}
