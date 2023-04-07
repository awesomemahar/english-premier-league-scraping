<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->text('date');
            $table->text('time_result');
            $table->unsignedBigInteger('home')->comment('home team');
            $table->unsignedBigInteger('away')->comment('away team');
            $table->unsignedBigInteger('season_id');
            $table->integer('round')->nullable();
            $table->enum('is_postponed', [0, 1])->default(0);
            $table->timestamps();
            $table->softDeletes();
            //Foreign Keys
            $table->foreign('home')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('away')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('season_id')->references('id')->on('seasons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixtures');
    }
}
