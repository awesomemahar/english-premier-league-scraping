<?php

namespace App\Http\Controllers\Scraping;

use App\Http\Controllers\Controller;
use App\Models\League;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    protected $controller = "App\Http\Controllers\Scraping\LeagueController";

    public function index()
    {
        $leagues = League::all();
        $active = "Leagues";
        return view('scraping.leagues', compact('leagues', 'active'));
    }

    public function get_leagues()
    {
        $httpClient = new \Goutte\Client();
        $url = "https://www.soccerstats.com/leagues.asp";
        $final_data = array();
        $sub_array = array();
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//table//tr[@class="trow8"]');
        $count = $data->filter('tr')->eq(1)->filter('td')->count();
        //dd($count);
        foreach ($data as $key => $value) {
            if ($data->filter('tr')->eq($key)->filter('td')->count() == $count) {
                //dd($value->textContent);
                $test = $data->filter('tr')->eq($key)->filter('td')->text();
                $explode = explode('-', $test, 2);
                $country = "";
                $league_name = "";
                //dd(strlen($explode[1]),$explode[1][0],$explode[1][1]);
                for ($i = 2; $i < strlen($explode[0]) - 1; $i++) {
                    $country = $country . $explode[0][$i];
                }
                for ($i = 1; $i < strlen($explode[1]); $i++) {
                    $league_name = $league_name . $explode[1][$i];
                }
                $league_data['country'] = $country;
                $league_data['name'] = $league_name;
                array_push($sub_array, $league_data);
            }
        }
        $this->save_leagues($sub_array);
        dd($sub_array);
    }

    public function save_leagues($leagues)
    {
        foreach ($leagues as $key => $value) {
            $league = new League();
            $league->country = $value['country'];
            $league->name = $value['name'];
            $league->save();
        }
    }
}
