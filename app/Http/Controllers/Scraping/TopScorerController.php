<?php

namespace App\Http\Controllers\Scraping;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\TopScorer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TopScorerController extends Controller
{
    protected $controller = "App\Http\Controllers\Scraping\TopScorerController";

    public function index()
    {
        $active = "Top Scorers";
        $top_scorers = TopScorer::with('player', 'player.teams')->get();
        //dd($top_scorers->toArray());
        return view('scraping.topscorer', compact('active', 'top_scorers'));

    }

    public function get_top_scorers_table()
    {
        $httpClient = new \Goutte\Client();
        $url = "https://www.soccerstats.com/scorers.asp?league=england";
        $final_data = array();
        $sub_array = array();
        $table_header = array();
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
        $table_header_data = $response->evaluate('//table[@id="btable"]//tr');
        //dd($table_header_data->filter('tr')->filter('th')->eq(3)->text());
        $count = $data->filter('tr')->eq(2)->filter('td')->count();
        for ($i = 0; $i < $count; $i++) {
            array_push($table_header, $table_header_data->filter('tr')->filter('th')->eq($i)->text());
        }
        //dd($table_header);
        //dd($table_header_data->filter('tr')->filter('td')->eq(3)->text());
        foreach ($data as $key => $item) {
            //dd($test->filter('tr')->eq(8)->filter('td')->eq(0)->text());
            //dd($data->filter('tr')->eq(2)->filter('td')->count());
            if ($data->filter('tr')->eq($key)->filter('td')->count() == $count) {
                for ($i = 0; $i < $count; $i++) {
                    $sub_array[$table_header[$i]] = $data->filter('tr')->eq($key)->filter('td')->eq($i)->text();
                }
                array_push($final_data, $sub_array);
                $sub_array = array();
            }
        }
        $this->save_top_scorers($final_data);
        //$this->update_top_scorers($final_data);
        $top_scorers = TopScorer::all();
        dd("Top Scorers: ", $top_scorers->toArray());
    }

    public function save_top_scorers($data)
    {
        foreach ($data as $value) {
            //dd($value['Last'],Carbon::parse($value['Last'])->format('Y-m-d'));
            //dd($value['% team']);
            //get player id
            $player = Player::where('name', $value['Player'])->first();
            //dd($player->toArray());
            //Save Top Scrore
            if (isset($player) && $player != null) {
                $top_scorer = new TopScorer();
                $top_scorer->player_id = $player->id;
                $top_scorer->goals = $value['Goals'];
                $top_scorer->home = $value['Home'];
                $top_scorer->away = $value['Away'];
                $top_scorer->min_01_15 = $value['115'];
                $top_scorer->min_16_30 = $value['1630'];
                $top_scorer->min_31_45 = $value['3145'];
                $top_scorer->min_46_60 = $value['4660'];
                $top_scorer->min_61_75 = $value['6175'];
                $top_scorer->min_76_90 = $value['7690'];
                $top_scorer->last_goal_date = Carbon::parse($value['Last'])->format('Y-m-d');
                $top_scorer->percent_team = $value['% team'];
                $top_scorer->save();
            }
        }
    }

    public function update_top_scorers($data)
    {
        $count = TopScorer::all()->count();
        if ($count == count($data)) {
            foreach ($data as $key => $value) {
                //get player id
                $player = Player::where('name', $value['Player'])->first();
                //update Top Scrore
                if (isset($player) && $player != null) {
                    $top_scorer = TopScorer::find($key + 1);
                    $top_scorer->player_id = $player->player_id;
                    $top_scorer->goals = $value['Goals'];
                    $top_scorer->home = $value['Home'];
                    $top_scorer->away = $value['Away'];
                    $top_scorer->min_01_15 = $value['115'];
                    $top_scorer->min_16_30 = $value['1630'];
                    $top_scorer->min_31_45 = $value['3145'];
                    $top_scorer->min_46_60 = $value['4660'];
                    $top_scorer->min_61_75 = $value['6175'];
                    $top_scorer->min_76_90 = $value['7690'];
                    $top_scorer->last_goal_date = Carbon::parse($value['Last'])->format('Y-m-d');
                    $top_scorer->percent_team = $value['% teams'];
                    $top_scorer->save();
                }
            }
        }
    }
}
