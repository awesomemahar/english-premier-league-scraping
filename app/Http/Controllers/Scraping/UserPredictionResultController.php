<?php

namespace App\Http\Controllers\Scraping;

use App\Http\Controllers\Controller;
use App\Models\UserPrediction;
use App\Models\UserPredictionResult;
use Illuminate\Http\Request;

class UserPredictionResultController extends Controller
{
    protected $controller = "App\Http\Controllers\Scraping\UserPredictionResultController";

    public function calculate_points()
    {
        $user_prediction = UserPrediction::doesntHave('prediction_result')->with('fixture')->get();
        foreach ($user_prediction as $key => $value) {
            if (isset($value->fixture->time_result)) {
                if (strpos($value->fixture->time_result, ':') !== false) {
                    //Game Results are not Available as result field contains time.
                    var_dump('Match results are not available yet.');
                } elseif (strpos($value->fixture->time_result, 'pp') !== false) {
                    //Game Postponed, Exception Points should be given in this case.
                    $exception_points = 5;
                    //Create Prediction Results
                    $user_prediction_result = new UserPredictionResult();
                    $user_prediction_result->user_prediction_id = $value->id;
                    $user_prediction_result->exception_points = (string)$exception_points;
                    $user_prediction_result->strike = '0';
                    $user_prediction_result->save();
                    var_dump('Postponed');
                } elseif (strpos($value->fixture->time_result, '-') !== false) {
                    //Game results available
                    $explode_result = explode('-', str_replace(' ', '', $value->fixture->time_result));
                    //Actual Home and Away Score
                    $home_result = $explode_result[0];
                    $away_result = $explode_result[1];
                    //Predicted Home and Away Score
                    $home_predicted_score = $value->home_score;
                    $away_predicted_score = $value->away_score;
                    //Check if User strike (Predicted and Actual result are equal)
                    $check_strike = $this->check_strike($home_result, $home_predicted_score, $away_result, $away_predicted_score);
                    if ($check_strike == true) {
                        //Strike
                        $correct_score_points = 5;
                        $correct_result_points = 5;
                        $correct_u_o_points = 5;
                    } else {
                        $correct_score_points = 0;
                        $correct_result_points = 0;
                        $correct_u_o_points = 0;
                        //Check if result is correct
                        $actual_result = $this->get_result($home_result, $away_result);
                        $predicted_result = $this->get_result($home_predicted_score, $away_predicted_score);
                        if ($actual_result == $predicted_result) {
                            //5 points for Result
                            $correct_result_points = 5;
                        }
                        //Check Under/over
                        $check_actual_under_over = $this->check_under_over($home_result, $away_result);
                        $check_predicted_under_over = $this->check_under_over($home_predicted_score, $away_predicted_score);
                        if ($check_actual_under_over == $check_predicted_under_over) {
                            $correct_u_o_points = 5;
                        }
                    }
                    //Create Prediction Results
                    $user_prediction_result = new UserPredictionResult();
                    $user_prediction_result->user_prediction_id = $value->id;
                    $user_prediction_result->correct_score = (string)$correct_score_points;
                    $user_prediction_result->correct_result = (string)$correct_result_points;
                    $user_prediction_result->correct_under_over = (string)$correct_u_o_points;
                    if ($check_strike)
                        $user_prediction_result->strike = '1';
                    else
                        $user_prediction_result->strike = '0';
                    $user_prediction_result->save();
                    var_dump('Success, Calculated User points.');
                }
            }
        }
    }
}
