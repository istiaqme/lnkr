<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkVisitQueryLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_visit_query_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('link_visit_id');
            $table->string('link_short_key');

            $table->longText('query');
            $table->longText('data')->nullable();
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
        Schema::dropIfExists('link_visit_query_logs');
    }
}
