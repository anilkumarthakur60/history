<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up():void
    {
        Schema::create('model_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('model');
            $table->nullableMorphs('user');
            $table->string('message');
            $table->text('meta')->nullable();
            $table->timestamp('performed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down():void
    {
        Schema::dropIfExists('model_histories');
    }
}
