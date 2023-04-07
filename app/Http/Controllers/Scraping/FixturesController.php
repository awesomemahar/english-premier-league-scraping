<?php

namespace App\Http\Controllers\Scraping;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\League;
use App\Models\Season;
use App\Models\Team;
use App\Models\Worldcup;
use App\Models\WorldcupFixture;
use App\Models\WorldcupTeam;
use App\Models\UserPrediction;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class FixturesController extends Controller
{
    protected $controller = "App\Http\Controllers\Scraping\FixturesController";

    public function index()
    {
        $today = today()->format('Y-m-d');
        $fixtures = Fixture::where('date', '>=', $today)->orderBy('date', 'asc')->with('home_team', 'away_team')->get();

        $headers = ['Date', 'Home', 'Time', 'Away'];
        $active = "Fixtures";
        // dd($fixtures->toArray());
        return view('scraping.fixtures', compact('fixtures', 'headers', 'active'));
    }

    public function get_48_hours_fixtures()
    {
        $headers = ['Date', 'Home', 'Time', 'Away'];
        $active = "Fixtures";
        $dateS = Carbon::today()->toDateString();
        $dateE = Carbon::now()->addHours('48')->toDateString();
        $fixtures = Fixture::whereBetween('date', [$dateS, $dateE])->with('home_team', 'away_team')->get();
        return view('scraping.next2daysfixtures', compact('fixtures', 'headers', 'active'));
    }

    public function get_fixture_by_id($id)
    {
        $fixture = Fixture::whereId($id)->with('home_team', 'away_team')->first();
        dd($fixture->toArray());
    }

    public function save_prediction(Request $request)
    {
        $input = $request->all();
        $user_prediction = new UserPrediction();
        $user_prediction->user_id = (int)$input['user_id'];
        $user_prediction->fixture_id = (int)$input['fixture_id'];
        $user_prediction->home_score = $input['home_score'];
        $user_prediction->away_score = $input['away_score'];
        $user_prediction->save();
        dd('yo');
    }


    public function scrap_fixtures()
    {
        //$data = $this->get_monthly_data(2, "Fixtures");
        $fixtures = array();
        $urls = [
            /*'2021/22' => 'https://www.soccerstats.com/results.asp?league=england&pmtype=bydate',*/
            '2020/21' => 'https://www.soccerstats.com/results.asp?league=england_2021&pmtype=bydate',
            '2019/20' => 'https://www.soccerstats.com/results.asp?league=england_2020&pmtype=bydate',
        ];
        $urls_old = [
            '2018/19' => 'https://www.soccerstats.com/results.asp?league=england_2019&pmtype=bydate',
            '2017/18' => 'https://www.soccerstats.com/results.asp?league=england_2018&pmtype=bydate',
            '2016/17' => 'https://www.soccerstats.com/results.asp?league=england_2017&pmtype=bydate',
            '2015/16' => 'https://www.soccerstats.com/results.asp?league=england_2016&pmtype=bydate',
            '2014/15' => 'https://www.soccerstats.com/results.asp?league=england_2015&pmtype=bydate',
            '2013/14' => 'https://www.soccerstats.com/results.asp?league=england_2014&pmtype=bydate'
        ];
        foreach ($urls as $key => $url) {
            //dd($url, $key);
            $data = $this->get_fixtures($key, $url);
            //dd($data);
            array_push($fixtures, $data);
        }
        /*foreach ($urls_old as $key => $url) {
            //dd($url, $key);
            $data = $this->get_old_fixtures($key, $url);
            dd($data);
            array_push($fixtures, $data);
        }*/
        foreach ($fixtures as $fixture) {
            //dd($fixture['data']);
            $this->save_fixtures($fixture);
        }
        dd($fixtures);
        $active = "Fixtures";
        //dd($data);
        //$this->save_fixtures($fixtures);
        //$this->update_fixtures($fixtures);
        //$get_fix = Fixture::all();
        //dd($data, $get_fix->toArray());
    }

    public function scrap_all_seasons()
    {
        $urls_old = [
            '2021' => 'https://www.soccerstats.com/results.asp?league=brazil_2021&pmtype=bydate',
            '2020' => 'https://www.soccerstats.com/results.asp?league=brazil_2020&pmtype=bydate',
            '2019' => 'https://www.soccerstats.com/results.asp?league=brazil_2019&pmtype=bydate',
            '2018' => 'https://www.soccerstats.com/results.asp?league=brazil_2018&pmtype=bydate',
            '2017' => 'https://www.soccerstats.com/results.asp?league=brazil_2017&pmtype=bydate',
        ];
        foreach ($urls_old as $key => $item) {
            $this->get_fixtures($key, $item);
        }
    }

    public function get_old_norway_fixtures($season, $url)
    {
        $httpClient = new \Goutte\Client();
        //$url = "https://www.soccerstats.com/results.asp?league=england&pmtype=month" . (string)$month;
        //$url = "https://www.soccerstats.com/results.asp?league=england&pmtype=bydate";
        $final_data = array();
        $sub_array = array();
        $headers = ['Date', 'Home', 'Result', 'Away'];
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
        foreach ($data as $key => $item) {
            //dd($test->filter('tr')->eq(8)->filter('td')->eq(0)->text());
            //dd($test->filter('tr')->eq(2)->filter('td')->count());
            $date = $data->filter('tr')->eq($key)->filter('td')->eq(0)->text();
            $teams = $data->filter('tr')->eq($key)->filter('td')->eq(2)->text();
            $time_result = $data->filter('tr')->eq($key)->filter('td')->eq(3)->text();
            $explode = explode('-', $teams);
            $home_team = trim($explode[0]);
            $away_team = trim($explode[1]);
            //dd($date, $teams, $time_result, trim($home_team), trim($away_team));

            //dd($home, $home_team, $away, $away_team);
            $sub_array[$headers[0]] = $date;
            $sub_array[$headers[1]] = $home_team;
            $sub_array[$headers[2]] = $time_result;
            $sub_array[$headers[3]] = $away_team;
            array_push($final_data, $sub_array);
            $sub_array = array();
        }
        dd($final_data);
        $data = array();
        $data['season'] = $season;
        $data['headers'] = $headers;
        $data['data'] = $final_data;
        return $data;
    }

    public function scrap_league()
    {
        $httpClient = new \Goutte\Client();
        //$url = "https://www.soccerstats.com/results.asp?league=england&pmtype=month" . (string)$month;
        $url = "https://www.soccerstats.com/results.asp?league=england&pmtype=round";
        $final_data = array();
        $sub_array = array();
        for ($j = 1; $j <= 38; $j++) {
            $final_url = $url . $j;
            $headers = ['Date', 'Home', 'Result', 'Away', 'Round'];
            $response = $httpClient->request('GET', $final_url);
            $data = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
            foreach ($data as $key => $item) {
                //dd($test->filter('tr')->eq(8)->filter('td')->eq(0)->text());
                //dd($test->filter('tr')->eq(2)->filter('td')->count());
                if (
                    $data->filter('tr')->eq($key)->filter('td')->count() == 7 ||
                    $data->filter('tr')->eq($key)->filter('td')->count() == 9
                ) {
                    $date = $data->filter('tr')->eq($key)->filter('td')->eq(0)->text();
                    $home = $data->filter('tr')->eq($key)->filter('td')->eq(1)->text();
                    $time_result = $data->filter('tr')->eq($key)->filter('td')->eq(2)->text();
                    $away = $data->filter('tr')->eq($key)->filter('td')->eq(3)->text();
                    $home_team = "";
                    $away_team = "";
                    for ($i = 0; $i < strlen($home) - 2; $i++) {
                        $home_team = $home_team . $home[$i];
                    }
                    for ($i = 2; $i < strlen($away); $i++) {
                        $away_team = $away_team . $away[$i];
                    }
                    //dd($home, $home_team, $away, $away_team);
                    $sub_array[$headers[0]] = $date;
                    $sub_array[$headers[1]] = $home_team;
                    $sub_array[$headers[2]] = $time_result;
                    $sub_array[$headers[3]] = $away_team;
                    $sub_array[$headers[4]] = $j;
                    array_push($final_data, $sub_array);
                    //var_dump($j);
                    $sub_array = array();
                }
            }
        }

        //dd($final_data);
        $data = array();
        $data['season'] = '2022/23';
        $data['headers'] = $headers;
        $data['data'] = $final_data;
        //dd($data);
        //return $data;
        //create season
        //$league = League::where([['name', '=', 'Serie A'], ['country', '=', 'brazil']])->first();
        $season = Season::where([
            ['season', '2022/23'],
            ['league_id', '109'],
        ])->first();
        if (!$season) {
            $season = new Season();
            $season->season = '2022/23';
            $season->league_id = 109;
            $season->save();
        }
        foreach ($final_data as $item) {
            $home = Team::where('name', $item['Home'])->first();
            $away = Team::where('name', $item['Away'])->first();
            $date = $item['Date'];
            if (str_contains($date, 'Mo'))
                $date = str_replace('Mo', 'Mon', $date);
            if (str_contains($date, 'Tu'))
                $date = str_replace('Tu', 'Tue', $date);
            if (str_contains($date, 'We'))
                $date = str_replace('We', 'Wed', $date);
            if (str_contains($date, 'Th'))
                $date = str_replace('Th', 'Thu', $date);
            if (str_contains($date, 'Fr'))
                $date = str_replace('Fr', 'Fri', $date);
            if (str_contains($date, 'Sa'))
                $date = str_replace('Sa', 'Sat', $date);
            if (str_contains($date, 'Su'))
                $date = str_replace('Su', 'Sun', $date);

            $month = Carbon::parse(substr($date, 0, -6))->month;
            $day = Carbon::parse(substr($date, 0, -6))->day;

            if (strlen($day) == 1) {
                $day = '0' . $day;
            }
            if (strlen($month) == 1)
                $month = '0' . $month;
            // $month = $this->get_date_month($date);
            if ($month < 7) {
                $current_season = '2023';
            } else {
                $current_season = '2022';
            }
            $new_date =  $current_season . '-' . $month . '-' . $day;

            // $new_date = $this->get_date($date, $current_season);
            $fixture = new Fixture();
            $fixture->date = $new_date;
            if (isset($home) && $home != null)
                $fixture->home = $home->id;
            $fixture->time_result = $item['Result'];
            if ($item['Result'] == 'pp.')
                $fixture->is_postponed = '1';
            if (isset($away) && $away != null)
                $fixture->away = $away->id;
            if (isset($item['Round']) && $item['Round'] != null)
                $fixture->round = $item['Round'];
            $fixture->season_id = $season->id;
            $fixture->save();
        }
        dd($data);
        return $data;
    }

    public function get_teams()
    {
        $httpClient = new \Goutte\Client();
        $team_data = array();
        $sub_array = array();
        //$url = "https://www.soccerstats.com/table.asp?league=brazil&tid=rp";
        $url = "https://www.soccerstats.com/homeaway.asp?league=brazil_2021";
        //$url = "https://www.soccerstats.com/homeaway.asp?league=brazil_2020";
        //$url = "https://www.soccerstats.com/homeaway.asp?league=brazil_2019";
        //$url = "https://www.soccerstats.com/homeaway.asp?league=brazil_2018";
        //$url = "https://www.soccerstats.com/homeaway.asp?league=brazil_2017";
        //$url = "https://www.soccerstats.com/table.asp?league=norway2&tid=re";
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
        $count = $data->filter('tr')->eq(1)->filter('td')->count();
        //dd($data->filter('tr')->eq(1));
        //dd($data->filter('tr')->eq(1)->filter('td')->filter('a')->attr('href'));
        for ($i = 0; $i < 20; $i++) {
            $team = $data->filter('tr')->eq($i)->filter('td')->eq(1)->text();

            //$href = str_replace('team.asp?league=norway2&stats=', "", $data->filter('tr')->eq($i)->filter('td')->filter('a')->attr('href'));

            array_push($sub_array, $team);
        }
        //$team_data = explode('-',$sub_array[0],2);
        //ksort($sub_array);
        //dd($sub_array);
        /*foreach ($sub_array as $value) {
            $team_name = '';

            for ($i = 2; $i < strlen($value) - 2; $i++) {
                $team_name = $team_name . $value[$i];
            }
            array_push($team_data, $team_name);*/
        /*$explode = explode('-', $value, 2);
        $team_data[$explode[0]] = str_replace('-', ' ', $explode[1]);
        */
        /*}*/
        //ksort($team_data);
        //$this->save_teams_to_db($team_data);
        $this->save_teams_to_db($sub_array);
        dd($sub_array, $team_data);
    }

    public function save_teams_to_db($teams)
    {
        $league = League::where([['name', '=', 'Serie A'], ['country', '=', 'brazil']])->first();
        //dd($league->toArray());
        foreach ($teams as $key => $value) {
            //dd($value, $league->toArray());
            $check = Team::where([['name', $value], ['league_id', $league->id]])->first();
            if ($check) {
                continue;
            } else {
                $team = new Team();
                $team->name = ucwords($value);
                if (isset($league) && $league != null)
                    $team->league_id = $league->id;
                $team->save();
            }
        }
    }

    public function get_fixtures($season, $url)
    {
        $httpClient = new \Goutte\Client();
        //$url = "https://www.soccerstats.com/results.asp?league=england&pmtype=month" . (string)$month;
        //$url = "https://www.soccerstats.com/results.asp?league=norway2_2021&pmtype=bydate";
        $final_data = array();
        $sub_array = array();
        $headers = ['Date', 'Home', 'Result', 'Away'];
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
        foreach ($data as $key => $item) {
            //dd($test->filter('tr')->eq(8)->filter('td')->eq(0)->text());
            //dd($test->filter('tr')->eq(2)->filter('td')->count());
            if (
                $data->filter('tr')->eq($key)->filter('td')->count() == 7 ||
                $data->filter('tr')->eq($key)->filter('td')->count() == 9
            ) {
                $date = $data->filter('tr')->eq($key)->filter('td')->eq(0)->text();
                $home = $data->filter('tr')->eq($key)->filter('td')->eq(1)->text();
                $time_result = $data->filter('tr')->eq($key)->filter('td')->eq(2)->text();
                $away = $data->filter('tr')->eq($key)->filter('td')->eq(3)->text();
                $home_team = "";
                $away_team = "";
                for ($i = 0; $i < strlen($home) - 2; $i++) {
                    $home_team = $home_team . $home[$i];
                }
                for ($i = 2; $i < strlen($away); $i++) {
                    $away_team = $away_team . $away[$i];
                }
                //dd($home, $home_team, $away, $away_team);
                $sub_array[$headers[0]] = $date;
                $sub_array[$headers[1]] = $home_team;
                $sub_array[$headers[2]] = $time_result;
                $sub_array[$headers[3]] = $away_team;
                array_push($final_data, $sub_array);
                $sub_array = array();
            }
        }
        //dd($final_data);
        $data = array();
        $data['season'] = $season;
        $data['headers'] = $headers;
        $data['data'] = $final_data;
        //dd($data);
        $this->save_fixtures($data);
        //dd($data);
        //return $data;
        //dd($final_data);
    }

    public function get_old_fixtures($season, $url)
    {
        $httpClient = new \Goutte\Client();
        //$url = "https://www.soccerstats.com/results.asp?league=england&pmtype=month" . (string)$month;
        //$url = "https://www.soccerstats.com/results.asp?league=england&pmtype=bydate";
        $final_data = array();
        $sub_array = array();
        $headers = ['Date', 'Home', 'Result', 'Away'];
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//table[@id="btable"]//tr[@class="trow3"]');
        foreach ($data as $key => $item) {
            //dd($test->filter('tr')->eq(8)->filter('td')->eq(0)->text());
            //dd($test->filter('tr')->eq(2)->filter('td')->count());
            $date = $data->filter('tr')->eq($key)->filter('td')->eq(0)->text();
            $teams = $data->filter('tr')->eq($key)->filter('td')->eq(2)->text();
            $time_result = $data->filter('tr')->eq($key)->filter('td')->eq(3)->text();
            $explode = explode('-', $teams);
            $home_team = trim($explode[0]);
            $away_team = trim($explode[1]);
            //dd($date, $teams, $time_result, trim($home_team), trim($away_team));

            //dd($home, $home_team, $away, $away_team);
            $sub_array[$headers[0]] = $date;
            $sub_array[$headers[1]] = $home_team;
            $sub_array[$headers[2]] = $time_result;
            $sub_array[$headers[3]] = $away_team;
            array_push($final_data, $sub_array);
            $sub_array = array();
        }
        //dd($final_data);
        $data = array();
        $data['season'] = $season;
        $data['headers'] = $headers;
        $data['data'] = $final_data;
        return $data;
    }

    public function save_fixtures($fixtures)
    {
        //$year = explode('/', $fixtures['season']);
        //dd($fixtures['season'],$year,$year[0]);
        $season = Season::where([['season', $fixtures['season']], ['league_id', '52']])->first();
        if (!$season) {
            $season = new Season();
            $season->season = $fixtures['season'];
            $season->league_id = '52';
            $season->save();
        }
        foreach ($fixtures['data'] as $item) {
            $home = Team::where('name', $item['Home'])->first();
            $away = Team::where('name', $item['Away'])->first();
            $fixture = new Fixture();
            // $month = Carbon::parse($item['Date'])->month;
            // $day = Carbon::parse($item['Date'])->day;
            //$day = explode(' ', $item['Date']);

            // //dd(strlen($month));
            // if (strlen($month) == 1)
            //     $month = '0' . $month;
            // if (strlen($day) == 1)
            //     $day = '0' . $day;
            // $new_date = $fixtures['season'] . '-' . $month . '-' . $day;
            $new_date = $this->get_date($item['Date'], $fixtures['season']);
            $fixture->date = $new_date;

            //$fixture->date = Carbon::parse($item['Date'])->format('Y-m-d');
            if (isset($home) && $home != null)
                $fixture->home = $home->id;
            $fixture->time_result = $item['Result'];
            if ($item['Result'] == 'pp.')
                $fixture->is_postponed = '1';
            if (isset($away) && $away != null)
                $fixture->away = $away->id;
            $fixture->season_id = $season->id;
            $fixture->save();
        }
    }

    public
    function update_fixtures($fixtures)
    {
        $count = Fixture::all()->count();
        //dd($count,count($fixtures));
        if ($count == count($fixtures)) {
            foreach ($fixtures as $key => $item) {
                //dd($item);
                //dd($key+1);
                $home = Team::where('name', $item['Home'])->first();
                $away = Team::where('name', $item['Away'])->first();
                $fixture = Fixture::find($key + 1);
                $date = Carbon::parse($item['Date'])->toDateString();
                $month = Carbon::parse($item['Date'])->month;
                $day = Carbon::parse($item['Date'])->day;
                $year = Carbon::parse($item['Date'])->year;
                if ($month > 7) {
                    $new_date = '2021-' . $month . '-' . $day;
                    $fixture->date = $new_date;
                } else {
                    $fixture->date = Carbon::parse($item['Date'])->format('Y-m-d');
                }
                if (isset($home) && $home != null)
                    $fixture->home = $home->team_id;
                $fixture->time_result = $item['Result'];
                if (isset($away) && $away != null)
                    $fixture->away = $away->team_id;
                $fixture->save();
            }
        }
    }

    public
    function store_fixtures()
    {
        $premier_league_url = "https://www.soccerstats.com/results.asp?league=england";
        /*$serie_A_url = "https://www.soccerstats.com/results.asp?league=italy";
        $bundesliga_url = "https://www.soccerstats.com/results.asp?league=germany";*/

        $pl_data = $this->scrap_league_data($premier_league_url);
        /*$sa_data = $this->scrap_league_data($serie_A_url);
        $b_data = $this->scrap_league_data($bundesliga_url);*/
        //dd($pl_data,$sa_data,$b_data);

        if ($pl_data != null) {
            foreach ($pl_data as $key => $item) {
                //dd($item[0],$item[1],$item[2],$item[3]);
                if (count($item) > 3) {
                    $fixture = new Fixture();
                    $fixture->date = $item[0];
                    $fixture->home = $item[1];
                    $fixture->time = $item[2];
                    $fixture->away = $item[3];
                    $fixture->league = "Premier League";
                    $fixture->save();
                }
            }
        }
        dd('Success');
        /*if ($sa_data != null) {
            foreach ($sa_data as $key => $item) {
                //dd($item[0],$item[1],$item[2],$item[3]);
                if (count($item) > 3) {
                    $fixture = new Fixture();
                    $fixture->date = $item[0];
                    $fixture->home = $item[1];
                    $fixture->time = $item[2];
                    $fixture->away = $item[3];
                    $fixture->league = "Serie A";
                    $fixture->save();
                }
            }
        }

        if ($b_data != null) {
            foreach ($b_data as $key => $item) {
                if (count($item) > 3) {
                    $fixture = new Fixture();
                    $fixture->date = $item[0];
                    $fixture->home = $item[1];
                    $fixture->time = $item[2];
                    $fixture->away = $item[3];
                    $fixture->league = "Bundesliga";
                    $fixture->save();
                }
            }
        }*/
    }

    public
    function scrap_league_data($url)
    {
        $httpClient = new \Goutte\Client();
        $results = array();
        $final_data = array();
        $sub_array = array();
        $response = $httpClient->request('GET', $url);
        $test = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]//td');
        foreach ($test as $odd) {
            if ($odd->textContent != "") {
                array_push($results, $odd->textContent);
            }
        }
        foreach ($results as $key => $result) {
            if ($result != "h2h") {
                array_push($sub_array, $result);
                //echo $result;
            } else {
                if (count($sub_array) > 0) {
                    array_push($final_data, $sub_array);
                    $sub_array = array();
                }
            }
        }
        //dd($final_data, $results);
        return $final_data;
    }

    public
    function scrap_data()
    {
        $httpClient = new \Goutte\Client();
        $url = "https://www.soccerstats.com/latest.asp?league=england";
        $results = array();
        $response = $httpClient->request('GET', $url);
        $result = $response->evaluate('//table[@class="btable"]')->first();
        dd($result->text());
    }

    public
    function get_data_by_league()
    {
        $data = Fixture::all()->groupBy('league');
        dd($data->toArray());
    }


    /*public function get_monthly_fixtures()
    {
        $httpClient = new \Goutte\Client();
        $url = "https://www.soccerstats.com/results.asp?league=england&pmtype=month2";
        $final_data = array();
        $sub_array = array();
        $response = $httpClient->request('GET', $url);
        $test = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
        foreach ($test as $key => $item) {
            //dd($test->filter('tr')->eq(8)->filter('td')->eq(0)->text());
            //dd($test->filter('tr')->eq(2)->filter('td')->count());
            if ($test->filter('tr')->eq($key)->filter('td')->count() == 7) {
                array_push($sub_array, $test->filter('tr')->eq($key)->filter('td')->eq(0)->text());
                array_push($sub_array, $test->filter('tr')->eq($key)->filter('td')->eq(1)->text());
                array_push($sub_array, $test->filter('tr')->eq($key)->filter('td')->eq(2)->text());
                array_push($sub_array, $test->filter('tr')->eq($key)->filter('td')->eq(3)->text());
                array_push($final_data, $sub_array);
                $sub_array = array();
            }
        }
        dd($final_data);
    }

    public function get_monthly_results()
    {
        $httpClient = new \Goutte\Client();
        $url = "https://www.soccerstats.com/results.asp?league=england&pmtype=month1";
        $final_data = array();
        $sub_array = array();
        $response = $httpClient->request('GET', $url);
        $test = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
        foreach ($test as $key => $item) {
            //dd($test->filter('tr')->eq(8)->filter('td')->eq(0)->text());
            //dd($test->filter('tr')->eq($key)->filter('td')->count());
            if ($test->filter('tr')->eq($key)->filter('td')->count() == 9) {
                array_push($sub_array, $test->filter('tr')->eq($key)->filter('td')->eq(0)->text());
                array_push($sub_array, $test->filter('tr')->eq($key)->filter('td')->eq(1)->text());
                array_push($sub_array, $test->filter('tr')->eq($key)->filter('td')->eq(2)->text());
                array_push($sub_array, $test->filter('tr')->eq($key)->filter('td')->eq(3)->text());
                array_push($final_data, $sub_array);
                $sub_array = array();
            }
        }
        dd($final_data);
    }*/

    public function test_fixtures()
    {
        $httpClient = new \Goutte\Client();
        //$url = "https://www.soccerstats.com/results.asp?league=england&pmtype=month" . (string)$month;
        //$url = "https://www.soccerstats.com/results.asp?league=england&pmtype=bydate";
        $final_data = array();
        $sub_array = array();
        for ($j = 1; $j <= 38; $j++) {
            $url = 'https://www.soccerstats.com/results.asp?league=england&pmtype=round' . $j;
            $headers = ['Date', 'Home', 'Result', 'Away', 'Round'];
            $response = $httpClient->request('GET', $url);
            $data = $response->evaluate('//table[@id="btable"]//tr[@class="odd"]');
            foreach ($data as $key => $item) {
                //dd($test->filter('tr')->eq(8)->filter('td')->eq(0)->text());
                //dd($test->filter('tr')->eq(2)->filter('td')->count());
                if (
                    $data->filter('tr')->eq($key)->filter('td')->count() == 7 ||
                    $data->filter('tr')->eq($key)->filter('td')->count() == 9
                ) {
                    $date = $data->filter('tr')->eq($key)->filter('td')->eq(0)->text();
                    $home = $data->filter('tr')->eq($key)->filter('td')->eq(1)->text();
                    $time_result = $data->filter('tr')->eq($key)->filter('td')->eq(2)->text();
                    $away = $data->filter('tr')->eq($key)->filter('td')->eq(3)->text();
                    $home_team = "";
                    $away_team = "";
                    for ($i = 0; $i < strlen($home) - 2; $i++) {
                        $home_team = $home_team . $home[$i];
                    }
                    for ($i = 2; $i < strlen($away); $i++) {
                        $away_team = $away_team . $away[$i];
                    }
                    //dd($home, $home_team, $away, $away_team);
                    $sub_array[$headers[0]] = $date;
                    $sub_array[$headers[1]] = $home_team;
                    $sub_array[$headers[2]] = $time_result;
                    $sub_array[$headers[3]] = $away_team;
                    $sub_array[$headers[4]] = $j;
                    array_push($final_data, $sub_array);
                    var_dump($j);
                    $sub_array = array();
                }
            }
        }

        //dd($final_data);
        $data = array();
        //$data['season'] = $season;
        $data['headers'] = $headers;
        $data['data'] = $final_data;
        //return $data;
        dd($final_data, $data);
    }


    public function worldcup_fixtures()
    {
        $httpClient = new \Goutte\Client();
        $url = "https://www.soccerstats.com/leagueview.asp?league=worldcup";
        $final_data = array();
        $sub_array = array();
        $headers = ['Date', 'Home', 'Away'];
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//table[@id="btable"]//tr[@class="odd"][@height="34"]');
        foreach ($data as $key => $item) {
            if (
                $data->filter('tr')->eq($key)->filter('td')->count() == 4 ||
                $data->filter('tr')->eq($key)->filter('td')->count() == 5
            ) {

                $date = $data->filter('tr')->eq($key)->filter('td')->eq(0)->text();
                $home = $data->filter('tr')->eq($key)->filter('td')->eq(1)->text();
                //$time_result = $data->filter('tr')->eq($key)->filter('td')->eq(2)->text();
                $away = $data->filter('tr')->eq($key)->filter('td')->eq(3)->text();

                //dd($home, $home_team, $away, $away_team);
                $sub_array[$headers[0]] = $date;
                $sub_array[$headers[1]] = $home;
                $sub_array[$headers[2]] = $away;
                array_push($final_data, $sub_array);
                $sub_array = array();
            }
        }
        $data = array();
        $data['season'] = '2022';
        $data['headers'] = $headers;
        $data['data'] = $final_data;
        $this->save_world_cup_fixtures($data);

        dd($data);
        //return $data;
    }

    public function save_world_cup_fixtures($data)
    {
        //Save WorldCup Fixtures
        $world_cup = Worldcup::where('season', $data['season'])->first();
        if ($world_cup) {
            foreach ($data['data'] as $datum) {
                $date = $this->get_date($datum['Date'], $data['season']);
                $time = substr($datum['Date'], '-5');
                $team1 = WorldcupTeam::where('name', $datum['Home'])->pluck('id')->first();
                $team2 = WorldcupTeam::where('name', $datum['Away'])->pluck('id')->first();

                $fixture = WorldcupFixture::where([
                    ['date', $date],
                    ['time_result', $time],
                    ['team_1', $team1],
                    ['team_2', $team2],
                    ['worldcup', $world_cup->id],
                ])->first();
                if (!$fixture) {
                    $fixture = new WorldcupFixture();
                    $fixture->date = $date;
                    $fixture->time_result = $time;
                    $fixture->team_1 = $team1;
                    $fixture->team_2 = $team2;
                    $fixture->worldcup = $world_cup->id;
                    $fixture->save();
                }
            }
        }
    }

    public function save_old_world_cup_results($data)
    {
        //Save WorldCup Fixtures
        $world_cup = Worldcup::where('season', $data['season'])->first();
        if ($world_cup) {
            foreach ($data['data'] as $datum) {
                $date = Carbon::parse($datum['Date'])->format('Y-m-d');
                //dd($date,$datum);
                //$date = $this->get_date($datum['Date'], $data['season']);
                //$time = substr($datum['Date'], '-5');

                $result = $datum['Result'];
                $team1 = WorldcupTeam::where('name', $datum['Home'])->pluck('id')->first();
                $team2 = WorldcupTeam::where('name', $datum['Away'])->pluck('id')->first();

                $fixture = WorldcupFixture::where([
                    ['date', $date],
                    ['time_result', $result],
                    ['team_1', $team1],
                    ['team_2', $team2],
                    ['worldcup', $world_cup->id],
                ])->first();
                if (!$fixture) {
                    $fixture = new WorldcupFixture();
                    $fixture->date = $date;
                    $fixture->time_result = $result;
                    $fixture->team_1 = $team1;
                    $fixture->team_2 = $team2;
                    $fixture->worldcup = $world_cup->id;
                    $fixture->save();
                }
            }
        }
    }

    public function get_date($date, $season)
    {
        if (str_contains($date, 'Mo'))
            $date = str_replace('Mo', 'Mon', $date);
        if (str_contains($date, 'Tu'))
            $date = str_replace('Tu', 'Tue', $date);
        if (str_contains($date, 'We'))
            $date = str_replace('We', 'Wed', $date);
        if (str_contains($date, 'Th'))
            $date = str_replace('Th', 'Thu', $date);
        if (str_contains($date, 'Fr'))
            $date = str_replace('Fr', 'Fri', $date);
        if (str_contains($date, 'Sa'))
            $date = str_replace('Sa', 'Sat', $date);
        if (str_contains($date, 'Su'))
            $date = str_replace('Su', 'Sun', $date);

        $month = Carbon::parse(substr($date, 0, -6))->month;
        $day = Carbon::parse(substr($date, 0, -6))->day;
        if (strlen($day) == 1)
            $day = '0' . $day;
        if (strlen($month) == 1)
            $month = '0' . $month;

        return $season . '-' . $month . '-' . $day;
    }

    public function get_date_month($date)
    {
        if (str_contains($date, 'Mo'))
            $date = str_replace('Mo', 'Mon', $date);
        if (str_contains($date, 'Tu'))
            $date = str_replace('Tu', 'Tue', $date);
        if (str_contains($date, 'We'))
            $date = str_replace('We', 'Wed', $date);
        if (str_contains($date, 'Th'))
            $date = str_replace('Th', 'Thu', $date);
        if (str_contains($date, 'Fr'))
            $date = str_replace('Fr', 'Fri', $date);
        if (str_contains($date, 'Sa'))
            $date = str_replace('Sa', 'Sat', $date);
        if (str_contains($date, 'Su'))
            $date = str_replace('Su', 'Sun', $date);

        $month = Carbon::parse(substr($date, 0, -6))->month;
        $day = Carbon::parse(substr($date, 0, -6))->day;
        if (strlen($day) == 1)
            $day = '0' . $day;
        // if (strlen($month) == 1)
        //     $month = '0' . $month;

        return $month;
    }

    public function worldcup_teams()
    {
        $httpClient = new \Goutte\Client();
        $url = "https://www.soccerstats.com/leagueview.asp?league=worldcup";
        $final_data = array();
        $response = $httpClient->request('GET', $url);
        $data = $response->evaluate('//div[@class="dropdown-content"][@style="left:0; width:120px;font-size:13px;"]');
        for ($i = 0; $i < 32; $i++) {
            array_push($final_data, $data->filter('a')->eq($i)->text());
        }
        $data = array();
        $data['season'] = '2022';
        $data['data'] = $final_data;
        //Save to db
        $worldcup = Worldcup::where('season', $data['season'])->first();
        if (!$worldcup) {
            $worldcup = new Worldcup();
            $worldcup->season = $data['season'];
            $worldcup->save();
        }
        foreach ($data['data'] as $datum) {
            $team = WorldcupTeam::where('name', $datum)->first();
            if (!$team) {
                $team = new WorldcupTeam();
                $team->name = $datum;
                $team->worldcup_id = $worldcup->id;
                $team->save();
            }
        }
        dd($data);
        //return $data;
    }

    public function old_worldcup_teams()
    {
        $worldcups = ['2018', '2014', '2010'];

        $worldcup_teams = array();
        foreach ($worldcups as $key => $value) {
            $worldcup = Worldcup::where('season', $value)->first();
            if (!$worldcup) {
                $worldcup = new Worldcup();
                $worldcup->season = $value;
                $worldcup->save();
            }
            $httpClient = new \Goutte\Client();
            $url = "https://www.soccerstats.com/leagueview.asp?league=worldcup_" . $value;
            $response = $httpClient->request('GET', $url);
            $teams = array();
            for ($i = 1; $i <= 32; $i++) {
                $path = '//a[@href="leagueview_team.asp?league=worldcup_' . $value . '&team1id=' . $i . '"]';
                $data = $response->evaluate($path);
                array_push($teams, $data->html());
                $team = WorldcupTeam::where('name', $data->html())->first();
                if (!$team) {
                    $team = new WorldcupTeam();
                    $team->name = $data->html();
                    $team->worldcup_id = $worldcup->id;
                    $team->save();
                }
            }
            $worldcup_teams[$value] = $teams;
        }

        dd($worldcup_teams);
        //return $data;
    }

    public function worldcup_history_group_stage()
    {
        $worldcups = ['2018', '2014', '2010'];
        $worldcup_group_stage_history = array();
        foreach ($worldcups as $key => $value) {
            $httpClient = new \Goutte\Client();
            $url = "https://www.soccerstats.com/leagueview.asp?league=worldcup_" . $value;
            $group_data = array();
            $sub_array = array();
            $headers = ['Date', 'Result', 'Home', 'Away'];
            $response = $httpClient->request('GET', $url);
            $data_groups = $response->evaluate('//table[@width="100%"][@style="margin-top:10px;margin-bottom:5px;"]//table[@id="btable"]//tr[@class="odd"][@height="28"]');

            foreach ($data_groups as $key => $item) {
                if ($data_groups->filter('tr')->eq($key)->filter('td')->count() == 4 || $data_groups->filter('tr')->eq($key)->filter('td')->count() == 5) {
                    $date = $data_groups->filter('tr')->eq($key)->filter('td')->eq(0)->text();
                    $home = $data_groups->filter('tr')->eq($key)->filter('td')->eq(1)->text();
                    $time_result = substr($data_groups->filter('tr')->eq($key)->filter('td')->eq(2)->filter('a')->text(), '0', '5');
                    $away = $data_groups->filter('tr')->eq($key)->filter('td')->eq(3)->text();

                    if ($away == 'Bosnia-Herze.')
                        $away = 'Bosnia-Herzegov';
                    //dd($home, $home_team, $away, $away_team);
                    $sub_array[$headers[0]] = $date;
                    $sub_array[$headers[1]] = $time_result;
                    $sub_array[$headers[2]] = $home;
                    $sub_array[$headers[3]] = $away;
                    array_push($group_data, $sub_array);
                    $sub_array = array();
                }
            }
            $data = array();
            $data['season'] = $value;
            $data['headers'] = $headers;
            $data['data'] = $group_data;
            $worldcup_group_stage_history[$value] = $data;

            //Save WorldCup Fixtures
            $this->save_old_world_cup_results($data);
        }

        dd($worldcup_group_stage_history);
        //return $data;
    }

    public function worldcup_history_final_stage()
    {

        $worldcups = ['2018', '2014', '2010'];

        $worldcup_final_stage_history = array();
        foreach ($worldcups as $key => $value) {
            $httpClient = new \Goutte\Client();
            $url = "https://www.soccerstats.com/leagueview.asp?league=worldcup_" . $value;
            $finals_data = array();
            $sub_array = array();
            $headers = ['Date', 'Result', 'Home', 'Away'];
            $response = $httpClient->request('GET', $url);
            $data_finals = $response->evaluate('//table[@id="btable"]//tr[@class="odd"][@height="28"]');

            foreach ($data_finals as $key => $item) {
                if ($key < 16)
                    if ($data_finals->filter('tr')->eq($key)->filter('td')->count() == 4 || $data_finals->filter('tr')->eq($key)->filter('td')->count() == 5) {
                        $date = $data_finals->filter('tr')->eq($key)->filter('td')->eq(0)->text();
                        $home = $data_finals->filter('tr')->eq($key)->filter('td')->eq(1)->text();
                        $time_result = substr($data_finals->filter('tr')->eq($key)->filter('td')->eq(2)->filter('a')->text(), '0', '5');
                        $away = $data_finals->filter('tr')->eq($key)->filter('td')->eq(3)->filter('a')->text();

                        $sub_array[$headers[0]] = $date;
                        $sub_array[$headers[1]] = $time_result;
                        $sub_array[$headers[2]] = $home;
                        $sub_array[$headers[3]] = $away;
                        array_push($finals_data, $sub_array);
                        $sub_array = array();
                    }
            }

            $data = array();
            $data['season'] = $value;
            $data['headers'] = $headers;
            $data['data'] = $finals_data;
            $worldcup_final_stage_history[$value] = $data;

            //Save WorldCup Fixtures
            $this->save_old_world_cup_results($data);
        }

        dd($worldcup_final_stage_history);
        //return $data;
    }
}
