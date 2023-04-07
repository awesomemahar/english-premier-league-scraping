<?php

namespace App\Http\Controllers\Scraping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StandingController extends Controller
{
    protected $controller = "App\Http\Controllers\Scraping\StandingController";

    public function get_standings()
    {
        $httpClient = new \Goutte\Client();
        $url = "https://www.soccerstats.com/widetable.asp?league=england";
        $final_data = array();
        $sub_array = array();
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
        foreach ($data as $key => $item) {
            //dd($data->filter('tr')->eq(20)->filter('td')->eq(1)->text());

            if ($data->filter('tr')->eq($key)->filter('td')->count() > 0) {
                array_push($sub_array, $data->filter('tr')->eq($key)->filter('td')->eq(0)->text());
                array_push($sub_array, $data->filter('tr')->eq($key)->filter('td')->eq(1)->text());
                array_push($sub_array, $data->filter('tr')->eq($key)->filter('td')->eq(2)->text());
                array_push($sub_array, $data->filter('tr')->eq($key)->filter('td')->eq(3)->text());
                array_push($final_data, $sub_array);
                $sub_array = array();
            }
        }
        return $final_data;
        //dd($final_data);
    }

    public function get_players()
    {
        $httpClient = new \Goutte\Client();
        //$url = "https://www.premierleague.com/players";
        $url = "https://www.premierleague.com/players?pageSize=30&compSeasons=418&altIds=true&page=32&type=player&id=-1&compSeasonId=418";
        $final_data = array();
        $sub_array = array();
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//table//tr');
        for ($i = 0; $i < 99; $i++) {
            //dd($data->filter('tr')->eq(20)->filter('td')->eq(1)->text());

            if ($data->filter('tr')->eq($i)->filter('td')->count() > 0) {
                array_push($sub_array, $data->filter('tr')->eq($i)->filter('td')->eq(0)->text());
                array_push($sub_array, $data->filter('tr')->eq($i)->filter('td')->eq(1)->text());
                array_push($sub_array, $data->filter('tr')->eq($i)->filter('td')->eq(2)->text());
                array_push($final_data, $sub_array);
                $sub_array = array();
            }
        }


        //return $final_data;
        dd($final_data);
    }
}
