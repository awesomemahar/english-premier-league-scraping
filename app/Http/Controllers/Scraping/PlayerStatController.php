<?php

namespace App\Http\Controllers\Scraping;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\Player;
use App\Models\PlayerStat;
use App\Models\Team;
use Carbon\Carbon;

class PlayerStatController extends Controller
{
    protected $controller = "App\Http\Controllers\Scraping\PlayerStatController";

    public function index()
    {
        $player_stats = PlayerStat::all();
        return $player_stats;
    }

    public function get_all()
    {
        //PlayerStat::query()->truncate();
        $players_count = Player::all()->count();
        for ($i = 1; $i < $players_count; $i++) {
            $data = $this->get_player_stats($i);
            $this->save_player_stats($data);
            //$this->update_player_stats($data);
        }
        //$this->save_player_stats($data);
        dd('done');
    }

    public function save_player_stats($data)
    {
        //dd($data);
        $player = Player::where('name', $data['name'])->first();
        //dd($player->toArray());
        $player_id = $player['id'];
        foreach ($data['stats'] as $item) {
            //0-8(11) Home -2
            //2-8(9) Away > 2
            $home_team = "";
            $away_team = "";
            for ($i = 0; $i < strlen($item['Home']) - 2; $i++) {
                $home_team = $home_team . $item['Home'][$i];
            }
            for ($i = 2; $i < strlen($item['Away']); $i++) {
                $away_team = $away_team . $item['Away'][$i];
            }
            //dd($home_team, $away_team);
            //dd(strlen($item['Away']), $item['Away'], $item['Away'][2], $item['Away'][8], $item['Away']);
            //dd(strlen($item['Home']), $item['Home'], $item['Home'][0], $item['Home'][8], $item['Home'][10]);
            $home = Team::where('name', $home_team)->first();
            $away = Team::where('name', $away_team)->first();
            $date = "";
            for ($i = 0; $i < strlen($item['Date']) - 2; $i++) {
                $date = $date . $item['Date'][$i];
            }
            $parsed_date = Carbon::parse($date)->format('Y-m-d');
            /*$fixture = Fixture::where([
                ['date', '=', $parsed_date],
                ['home', '=', $home->id],
                ['away', '=', $away->id],
            ])->get();*/
            //dd($parsed_date, $item['Result'], $home->id, $away->id, $fixture);
            /*dd($date,$parse);
            dd($parse, $item['Date'], $home->toArray(), $away->toArray());*/
            if (isset($home) && isset($away)) {
                if ($home != null && $away != null) {
                    $player_stat = PlayerStat::where([
                        ['player_id', '=', $player_id],
                        ['date', '=', $parsed_date],
                        ['result', '=', $item['Result']],
                        ['home', '=', $home->id],
                        ['away', '=', $away->id],
                        ['scored_for', '=', $player['team_id']],
                        ['value', '=', $item['Goals']]
                    ])->exists();
                    if ($player_stat == false) {
                        $player_stats = new PlayerStat();
                        $player_stats->player_id = $player_id;
                        $player_stats->date = $parsed_date;
                        $player_stats->result = $item['Result'];
                        $player_stats->home = $home->id;
                        $player_stats->away = $away->id;
                        $player_stats->scored_for = $player['team_id'];
                        $player_stats->value = $item['Goals'];
                        $player_stats->type = 'goals';
                        $player_stats->save();
                    }
                }
            }
        }
        //}
    }

    /*public function update_player_stats($data)
    {
        $player = Player::where('name', $data['name'])->first();
        $player_id = $player['id'];
        foreach ($data['stats'] as $item) {
            //0-8(11) Home -2
            //2-8(9) Away > 2
            $home_team = "";
            $away_team = "";
            for ($i = 0; $i < strlen($item['Home']) - 2; $i++) {
                $home_team = $home_team . $item['Home'][$i];
            }
            for ($i = 2; $i < strlen($item['Away']); $i++) {
                $away_team = $away_team . $item['Away'][$i];
            }
            //dd($home_team, $away_team);
            //dd(strlen($item['Away']), $item['Away'], $item['Away'][2], $item['Away'][8], $item['Away']);
            //dd(strlen($item['Home']), $item['Home'], $item['Home'][0], $item['Home'][8], $item['Home'][10]);
            $home = Team::where('name', $home_team)->first();
            $away = Team::where('name', $away_team)->first();
            dd($item, $player, $player->id, $home->toArray(), $away->toArray());
            $player_stats = new PlayerStat();
            $player_stats->player_id = $player_id;
            $player_stats->date = $item['Date'];
            $player_stats->result = $item['Result'];
            $player_stats->home = $home->team_id;
            $player_stats->away = $away->team_id;
            $player_stats->goals = $item['Goals'];
            $player_stats->save();
        }
        //}
    }*/

    public function get_player_stats($id)
    {
        $httpClient = new \Goutte\Client();
        $url = "https://www.soccerstats.com/player.asp?league=england&p=" . (string)$id;
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
        //dd(count($final_data),count($teams->players));
        if (isset($team->players) && $team->players != null) {
            if (count($final_data) > count($team->players))
                for ($i = 0; $i < count($final_data) - count($team->players); $i++) {
                    $player_stats[$i] = $final_data[$i];
                }
        }
        $player_data['name'] = $name;
        $player_data['teams'] = $player_team;
        $player_data['stats'] = $player_stats;
        //dd($player_data);
        return $player_data;
    }

    /*public function get_all_player_stats()
    {
        $active = "Player Stats";
        $httpClient = new \Goutte\Client();
        $all_players_data = array();
        for ($i = 1; $i < 10; $i++) {
            $url = "https://www.soccerstats.com/player.asp?league=england&p=" . (string)$i;
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
            $teams = Team::where('name', $player_team)->with('players')->first();
            if ($final_data != null) {
                if (isset($teams->players) && $teams->players != null) {
                    if (count($final_data) > count($teams->players)) {
                        for ($i = 0; $i < count($final_data) - count($teams->players) - 1; $i++) {
                            $player_stats[$i] = $final_data[$i];
                        }
                    }
                }
            }else{
                array_push($player_stats, "");
            }
            array_push($all_players_data, $player_stats);

        }

        dd($all_players_data, $player_stats, count($teams->players), count($final_data));
        //dd($name, $player_team, count($final_data), $player_stats, count($teams->players));
        return view('scraping.player', compact('name', 'player_team', 'table_header', 'active', 'player_stats'));

    }*/

}
