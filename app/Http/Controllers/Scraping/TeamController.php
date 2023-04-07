<?php

namespace App\Http\Controllers\Scraping;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\League;
use App\Models\Result;
use App\Models\Team;
use Illuminate\Http\Request;
use League\Flysystem\File;

class TeamController extends Controller
{
    protected $controller = "App\Http\Controllers\Scraping\TeamController";

    public function index()
    {
        $teams = Team::with('players')->orderBy('name')->get();
        //dd($teams->toArray());
        $active = "Teams";
        return view('scraping.teams', compact('teams', 'active'));
    }

    public function get_players_by_team($team)
    {
        //dd($teams);
        $team_data = Team::where('name', $team)->with('players')->first();
        //dd($team_data->toArray());
        return view('scraping.teams', compact('team', 'team_data'));
    }

    public function get_team_fixtures($team)
    {
        $today = today()->format('Y-m-d');
        $team_data = Team::where('team_id', $team)->first();
        //dd($team_data->toArray());


        $home_fixtures = Fixture::where([
            ['home', $team_data->team_id],
            ['date', '>=', $today],
            ['result', '!=', 'pp.'],
        ])->orderBy('date', 'asc')->with('home_team')->get();
        $away_fixtures = Fixture::where([
            ['away', $team_data->team_id],
            ['date', '>=', $today],
            ['result', '!=', 'pp.'],
        ])->orderBy('date', 'asc')->with('away_team')->get();
        //dd($away_fixtures->toArray());
        return view('scraping.teamfixtures', compact('team_data', 'home_fixtures', 'away_fixtures'));
    }

    public function get_all_teams_stats()
    {
        $teams = Team::all();
        foreach ($teams as $team) {
            $team['stats'] = $this->get_team_results($team->id);
        }
        dd($teams->toArray());
    }

    public function get_team_results($team_id)
    {
        $team_data = Team::where('team_id', $team_id)->first();
        $today = today()->format('Y-m-d');
        $home_results = Fixture::where([['date', '<', $today], ['home', '=', $team_id]])->orderBy('date', 'desc')->with('home_team', 'away_team')->get();
        $away_results = Fixture::where([['date', '<', $today], ['away', '=', $team_id]])->orderBy('date', 'desc')->with('home_team', 'away_team')->get();

        $team_data['home_result'] = $this->get_win_loss_draw($home_results);
        $team_data['away_result'] = $this->get_win_loss_draw($away_results);

        $data['games_played'] = $team_data['home_result']['games_played'] + $team_data['away_result']['games_played'];
        $data['wins'] = $team_data['home_result']['wins'] + $team_data['away_result']['wins'];
        $data['loss'] = $team_data['home_result']['loss'] + $team_data['away_result']['loss'];
        $data['draw'] = $team_data['home_result']['draw'] + $team_data['away_result']['draw'];

        $team_data['overall_result'] = $data;

        return $team_data->toArray();
        //dd($team_data->toArray(), $home_results->toArray(), $away_results->toArray());
        //return view('scraping.teamresults', compact('team_data', 'home_results', 'away_results'));
    }

    public function get_result($home, $away)
    {
        if ($home == $away) {
            return 'Draw';
        } elseif ($home > $away) {
            return 'Home Win';

        } elseif ($home < $away) {
            return 'Away Win';
        }
        //dd($home, $away);
    }

    public function get_win_loss_draw($team_data)
    {
        $wins = 0;
        $loss = 0;
        $draw = 0;
        foreach ($team_data as $value) {
            $explode_result = explode('-', str_replace(' ', '', $value->time_result));
            if (count($explode_result) >= 2) {
                //Actual Home and Away Score
                $home = $explode_result[0];
                $away = $explode_result[1];
                $result = $this->get_result($home, $away);
                if ($result == "Home Win") {
                    $wins += 1;
                } elseif ($result == 'Away Win') {
                    $loss += 1;
                } elseif ($result == 'Draw') {
                    $draw += 1;
                }
            }
            //dd($value->time_result,);
        }
        $data['games_played'] = count($team_data);
        $data['wins'] = $wins;
        $data['loss'] = $loss;
        $data['draw'] = $draw;
        return $data;
    }

    public function get_teams()
    {
        $httpClient = new \Goutte\Client();
        $team_data = array();
        $sub_array = array();
        $url = "https://www.soccerstats.com/homeaway.asp?league=england";
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
        $count = $data->filter('tr')->eq(1)->filter('td')->count();

        //dd($data->filter('tr')->eq(45)->filter('td')->filter('a')->attr('href'));
        for ($i = 40; $i < 60; $i++) {
            $href = str_replace('teams.asp?league=england&stats=', "", $data->filter('tr')->eq($i)->filter('td')->filter('a')->attr('href'));

            array_push($sub_array, $href);
        }
        //$team_data = explode('-',$sub_array[0],2);
        foreach ($sub_array as $value) {
            $explode = explode('-', $value, 2);
            $team_data[$explode[0]] = str_replace('-', ' ', $explode[1]);
        }
        ksort($team_data);
        $this->save_teams_to_db($team_data);
        dd($sub_array, $team_data);
    }

    public function save_teams_to_db($teams)
    {
        $league = League::where([['name', '=', 'premier league'], ['country', '=', 'england']])->first();
        foreach ($teams as $key => $value) {
            $team = Team::where('name', $value)->first();
            //dd($value,$team->toArray(), $league->toArray());
            if (!$team) {
                $team = new Team();
                //$team->team_id = $key;
                $team->name = ucwords($value);
                if (isset($league) && $league != null)
                    $team->league_id = $league->id;
                $team->save();
            }
        }
    }
}
