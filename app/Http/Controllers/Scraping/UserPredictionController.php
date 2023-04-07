<?php

namespace App\Http\Controllers\Scraping;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPrediction;
use App\Models\UserPredictionResult;
use Illuminate\Http\Request;
use function PHPUnit\Framework\stringContains;

class UserPredictionController extends Controller
{
    protected $controller = "App\Http\Controllers\Scraping\UserPredictionController";

    public function leaders_dashboard()
    {
        $active = "Leaders Dashboard";
        $users = User::with('predictions', 'predictions.prediction_result')->has('predictions')->get();

        foreach ($users as $user) {
            $total_points = 0;
            $strikes = 0;
            //dd($user->toArray());
            foreach ($user->predictions as $prediction) {
                //dd($prediction->prediction_result->correct_score);
                if (isset($prediction->prediction_result)) {
                    $total_points += (integer)$prediction->prediction_result->correct_score;
                    $total_points += $prediction->prediction_result->correct_result;
                    $total_points += $prediction->prediction_result->correct_under_over;
                    $total_points += $prediction->prediction_result->exception_points;
                    if ($prediction->prediction_result->strike == "1") {
                        $strikes += 1;
                    }
                }
            }
            $user['total_predictions'] = count($user->predictions);
            $user['total_points'] = $total_points;
            $user['strikes'] = $strikes;
            //dd($total_points, $strikes);
        }
        //dd(count($users[0]->predictions), $users->toArray());
        return view('scraping.leaders_dashboard', compact('active', 'users'));
    }

    public function get_user_predictions($username)
    {
        $active = 'Users';
        $user = User::where('username', $username)->first();
        //$user_predictions = UserPrediction::where('user_id', $user->id)->with('fixture', 'fixture.home_team', 'fixture.away_team')->get();
        $user_predictions_history = UserPrediction::where('user_id', $user->id)->with(['fixture' => function ($query) {
            $query->where('time_result', 'not like', '%:%');
        }, 'fixture.home_team', 'fixture.away_team'])->get()->sortBy('fixture.date');

        $user_predictions = UserPrediction::where('user_id', $user->id)->with(['fixture' => function ($query) {
            $query->where('time_result', 'like', '%:%');
        }, 'fixture.home_team', 'fixture.away_team'])->get()->sortBy('fixture.date');
        //dd($user_predictions_history->toArray());
        return view('scraping.user.user_predictions', compact('user', 'user_predictions', 'user_predictions_history', 'active'));
        //dd($user_predictions_history->toArray());
    }

    public function get_all_predictions()
    {
        $active = 'Predictions';
        $user_predictions_history = UserPrediction::with(['fixture' => function ($query) {
            $query->where('time_result', 'not like', '%:%');
        }, 'fixture.home_team', 'fixture.away_team', 'user', 'prediction_result'])->get()->sortByDesc('fixture.date');

        $user_predictions = UserPrediction::with(['fixture' => function ($query) {
            $query->where('time_result', 'like', '%:%');
        }, 'fixture.home_team', 'fixture.away_team', 'user', 'prediction_result'])->get()->sortBy('fixture.date');
        return view('scraping.user.all_predictions', compact('user_predictions', 'user_predictions_history', 'active'));
        //dd($user_predictions_history->toArray());
    }

}
