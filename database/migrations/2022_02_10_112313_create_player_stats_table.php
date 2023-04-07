<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->date('date')->nullable();
            $table->text('result')->nullable();
            $table->bigInteger('value')->nullable();
            $table->text('type')->nullable()->comment('goals,assists,kicks,etc');
            $table->unsignedBigInteger('home')->comment('home team');
            $table->unsignedBigInteger('away')->comment('away team');
            $table->unsignedBigInteger('scored_for')->comment('played for');
            $table->timestamps();
            $table->softDeletes();
            //Foreign Keys
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');//Foreign Keys
            $table->foreign('home')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('away')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('scored_for')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_stats');
    }
}
