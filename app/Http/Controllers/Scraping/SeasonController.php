<?php

namespace App\Http\Controllers\Scraping;

use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\Season;
use App\Models\SeasonTeam;
use App\Models\Team;
use Illuminate\Http\Request;

class SeasonController extends Controller
{

    protected $controller = "App\Http\Controllers\Scraping\SeasonController";

    public function index()
    {
        $seasons = Season::with('season_teams.team')->get();
        dd($seasons->toArray());
        //return $seasons;
    }

    public function get_seasons()
    {
        $httpClient = new \Goutte\Client();
        $url = "https://www.soccerstats.com/latest.asp?league=england";
        $final_data = array();
        $sub_array = array();
        $seasons = array();
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//div[@class="dropdown-content"]//a');
        //dd($data->text());
        //$count = $data->filter('tr')->eq(1)->filter('td')->count();
        //dd(count($data));
        for ($i = 0; $i < 9; $i++) {
            $sub_array['href'] = $data->filter('a')->eq($i)->attr('href');
            $sub_array['a'] = $data->filter('a')->eq($i)->text();
            array_push($seasons, $data->filter('a')->eq($i)->text());
            array_push($final_data, $sub_array);

        }
        $this->store_all_seasons($seasons);

        foreach ($final_data as $final_datum) {
            $this->get_teams($final_datum['a'], $final_datum['href']);

        }
        dd($seasons, $sub_array, $final_data);
    }

    public function get_teams($season, $href)
    {
        //dd($season, $href);
        $httpClient = new \Goutte\Client();
        $teams = array();
        $url = "https://www.soccerstats.com/" . $href;
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//div[@class="dropdown-content"]');
        for ($i = 0; $i < 20; $i++) {
            array_push($teams, $data->eq('3')->filter('a')->eq($i)->text());
        }
        $this->save_teams_to_db($season, $teams);
    }

    public function save_teams_to_db($season, $teams)
    {
        $league = League::where([['name', '=', 'premier league'], ['country', '=', 'england']])->first();
        foreach ($teams as $value) {
            $get_team = Team::where('name', $value)->first();
            $get_season = Season::where('season', $season)->first();
            if ($get_team != null) {
                //if team found in db, get team id and store in season teams
                $season_team = new SeasonTeam();
                $season_team->team_id = $get_team->id;
                $season_team->season_id = $get_season->id;
                $season_team->save();

            } else {
                //if team not found in db, create one.
                $team = new Team();
                $team->name = ucwords($value);
                if (isset($league) && $league != null)
                    $team->league_id = $league->id;
                $team->save();

                //
                $season_team = new SeasonTeam();
                $season_team->team_id = $team->id;
                $season_team->season_id = $get_season->id;
                $season_team->save();
            }
            //dd($value, $league->toArray());
        }
    }


    public function store_all_seasons($data)
    {
        //$seasons = Season::all();
        $league = League::where([['name', '=', 'premier league'], ['country', '=', 'england']])->first();
        foreach ($data as $datum) {
            $season = new Season();
            $season->season = $datum;
            $season->league_id = $league->id;
            $season->save();
        }
    }

    public function store_single_season($season)
    {
        //$seasons = Season::all();
        $league = League::where([['name', '=', 'premier league'], ['country', '=', 'england']])->first();
        $season = new Season();
        $season->season = $season;
        $season->league_id = $league->id;
        $season->save();
    }
}
