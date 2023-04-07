<?php

namespace App\Http\Controllers\Scraping;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    protected $controller = "App\Http\Controllers\Scraping\PlayerController";

    public function index()
    {
        $players = Player::where('name', '!=', '')->with('teams')->paginate(10);
        //dd($players->toArray());
        $active = "Players";
        return view('scraping.players', compact('active', 'players'));
    }

    public function get_player_stats_by_name($name)
    {
        $player_data = Player::where('name', $name)->with('stats', 'stats.home_team', 'stats.away_team', 'teams')->first();
        //dd($name, $player_data->toArray());
        $active = "Players";
        return view('scraping.playerstats', compact('name', 'active', 'player_data'));
    }

    public function get_all_players()
    {
        $httpClient = new \Goutte\Client();
        $players_list = array();
        for ($i = 378; $i < 516; $i++) {
            $url = "https://www.soccerstats.com/player.asp?league=england&p=" . $i;
            $response = $httpClient->request('GET', $url);
            $player_name = $response->evaluate('//h1');
            $player_team = $response->evaluate('//table//tr//td//h3')->eq('3')->text();
            $team_data = Team::where('name', $player_team)->first();
            //dd($team_data['team_id']);
            $players_list[$i] = $player_name->text();
            $player = new Player();
            $player->name = $player_name->text();
            if ($team_data != null)
                $player->team_id = $team_data['id'];
            else
                dd($team_data,$player_team);
            $player->save();
        }
        dd($players_list);
    }

    public function get_team_players()
    {
        $teams = Team::all();
        //dd($teams[0]['team_id'] . '-' . $teams[0]['name']);
        $httpClient = new \Goutte\Client();
        $players_list = array();
        $sub_array = array();
        $final_data = array();
        foreach ($teams as $team) {
            $url = "https://www.soccerstats.com/teams.asp?league=england&stats=" . $team['team_id'] . '-' . $team['name'];
            $response = $httpClient->request('GET', $url);
            $data = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
            $count = $data->filter('tr')->eq(20)->filter('td')->count();
            //dd($count);
            foreach ($data as $key => $item) {
                if ($data->filter('tr')->eq($key + 15)->filter('td')->count() === 7) {
                    for ($i = 0; $i < $count - 1; $i++) {
                        array_push($sub_array, $data->filter('tr')->eq($key + 15)->filter('td')->eq($i)->text());
                    }
                    array_push($final_data, $sub_array);
                    $sub_array = array();
                }
            }
            $players_list[$team['name']] = $final_data;
            $final_data = array();
        }
        dd($players_list);
    }

    public function get_player_stats()
    {
        $active = "Player Stats";
        $httpClient = new \Goutte\Client();
        $url = "https://www.soccerstats.com/player.asp?league=england&p=2";
        $final_data = array();
        $sub_array = array();
        $player_stats = array();
        $table_header = ['Date', 'Home', 'Result', 'Away', 'Goals'];
        $response = $httpClient->request('GET', $url);
        $player_name = $response->evaluate('//h1');
        $player_team = $response->evaluate('//table//tr//td//h3')->eq('3')->text();
        //dd($player_team);
        $data = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
        $count = $data->filter('tr')->eq(1)->filter('td')->count();
        foreach ($data as $key => $item) {
            if ($data->filter('tr')->eq($key)->filter('td')->count() === $count) {
                for ($i = 0; $i < $count - 1; $i++) {
                    $sub_array[$table_header[$i]] = $data->filter('tr')->eq($key)->filter('td')->eq($i)->text();
                }
                array_push($final_data, $sub_array);
                $sub_array = array();
            }
        }
        //dd("Player 200 : ", $final_data);
        $name = $player_name->text();
        $team = Team::where('name', $player_team)->with('players')->first();
        if (isset($team->players) && $team->players != null) {
            if (count($final_data) > count($team->players))
                for ($i = 0; $i < count($final_data) - count($team->players) - 1; $i++) {
                    $player_stats[$i] = $final_data[$i];
                }
        }
        $player_data['name'] = $name;
        $player_data['teams'] = $player_team;
        $player_data['stats'] = $player_stats;
        //dd($player_data, count($final_data), count($teams->players));
        //dd($name, $player_team, count($final_data), $player_stats, count($teams->players));
        return view('scraping.player', compact('name', 'player_team', 'table_header', 'active', 'player_stats'));

    }
}
