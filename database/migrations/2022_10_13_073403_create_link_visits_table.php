<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_visits', function (Blueprint $table) {
            $table->id();
            $table->string('link_short_key');
            $table->unsignedBigInteger('app_id');   // app table id 
            $table->longText('http_referer');
            $table->longText('token');
            $table->string('ip');
            $table->text('user_agent');
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
        Schema::dropIfExists('link_visits');
    }
}
