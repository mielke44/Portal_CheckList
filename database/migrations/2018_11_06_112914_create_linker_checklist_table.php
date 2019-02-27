<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkerChecklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linker_checklist', function (Blueprint $table) {
            $table->increments('id');
            $table->String('task_id')->references('id')->on('task');
            $table->String('checklist_template_id')->references('id')->on('checklist_template');
            $table->String('task_id_below')->references('id')->on('task')->nullable();
            $table->integer('level')->nullable();
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
        Schema::dropIfExists('linker_checklist');
    }
}
