<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('short_key');
            $table->longText('title');
            $table->longText('redirect_to');
            $table->longText('note')->nullable();
            $table->unsignedBigInteger('link_group_id');  // link_group table id
            $table->unsignedBigInteger('app_id');   // app table id
            $table->string('ip');
            $table->text('user_agent');
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('links');
    }
}
