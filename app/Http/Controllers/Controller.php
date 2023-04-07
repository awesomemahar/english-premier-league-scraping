<?php

namespace App\Http\Controllers;

use App\Models\ErrorLog;
use App\Models\Fixture;
use App\Models\League;
use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use App\Models\UserPrediction;
use Carbon\Carbon;
use Faker\Core\File;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $controller = "App\Http\Controllers\Controller";

    public function welcome()
    {
        $users = User::all()->count();
        $teams = Team::all()->count();
        $fixtures = Fixture::all()->count();
        $players = Player::all()->count();
        $predictions = UserPrediction::all()->count();
        $leagues = League::all()->count();

        return view('scraping.welcome', compact('users', 'teams', 'fixtures', 'players', 'predictions', 'leagues'));
    }
    public function get_home_away_table()
    {
        $httpClient = new \Goutte\Client();
        $url = "https://www.soccerstats.com/homeaway.asp?league=england";
        $final_data = array();
        $sub_array = array();
        $home_array = array();
        $away_array = array();
        $relative = array();
        $table_header = array();
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
        $table_header_data = $response->evaluate('//table[@id="btable"]//tr');
        for ($i = 0; $i < 10; $i++) {
            if ($i == 0)
                $table_header[$i] = "#";
            elseif ($i == 1)
                $table_header[$i] = "Team";
            else
                array_push($table_header, $table_header_data->filter('tr')->filter('td')->eq($i)->text());
        }
        foreach ($data as $key => $item) {
            if ($data->filter('tr')->eq($key)->filter('td')->count() == 10) {
                for ($i = 0; $i < 10; $i++) {
                    $sub_array[$table_header[$i]] = $data->filter('tr')->eq($key)->filter('td')->eq($i)->text();
                }
                array_push($final_data, $sub_array);
                $sub_array = array();
            }
        }
        if (isset($final_data) && count($final_data) <= 60) {
            foreach ($final_data as $key => $item) {
                if ($key < 20) {
                    array_push($home_array, $item);
                } elseif ($key < 40) {
                    array_push($away_array, $item);
                } elseif ($key < 60) {
                    array_push($relative, $item);
                }
            }
        }
        //dd($home_array,$away_array);
        $home = $home_array;
        $away = $away_array;
        $active = "Home Away";
        /*dd("Home Table: ", $home_array,
            "Away Table: ", $away_array,
            "Relative Home / Away Performance", $relative);*/
        //return $final_data;
        return view('scraping.home', compact('home', 'away', 'relative', 'table_header', 'active'));
    }
}
