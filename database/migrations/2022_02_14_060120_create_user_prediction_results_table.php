<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPredictionResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_prediction_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_prediction_id');
            $table->enum('correct_score', [0, 5])->default(0);
            $table->enum('correct_result', [0, 5])->default(0);
            $table->enum('correct_under_over', [0, 5])->default(0);
            $table->enum('exception_points', [0, 5])->default(0);
            $table->enum('strike', [0, 1])->comment('0 for false/miss, 1 for true/hit');
            $table->timestamps();
            $table->softDeletes();
            //Foreign Keys
            $table->foreign('user_prediction_id')->references('id')->on('user_predictions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_prediction_results');
    }
}
