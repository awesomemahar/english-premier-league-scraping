<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopScorersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('top_scorers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->integer('goals')->default('0');
            $table->integer('home')->default('0');
            $table->integer('away')->default('0');
            $table->integer('min_01_15')->default('0')->comment('goals during 01-15min');
            $table->integer('min_16_30')->default('0')->comment('goals during 16-30min');
            $table->integer('min_31_45')->default('0')->comment('goals during 31-45min');
            $table->integer('min_46_60')->default('0')->comment('goals during 46-60min');
            $table->integer('min_61_75')->default('0')->comment('goals during 61-75min');
            $table->integer('min_76_90')->default('0')->comment('goals during 76-90min');
            $table->date('last_goal_date');
            $table->string('percent_team')->comment('player goals in % of total goals scored by the teams');
            $table->timestamps();
            $table->softDeletes();
            //Foreign Keys
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('top_scorers');
    }
}
