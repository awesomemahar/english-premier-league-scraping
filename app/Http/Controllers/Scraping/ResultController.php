<?php

namespace App\Http\Controllers\Scraping;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    protected $controller = "App\Http\Controllers\Scraping\ResultController";

    public function index()
    {
        $active = 'Results';
        $today = today()->format('Y-m-d');
        $results = Fixture::where('date', '<', $today)->orderBy('date', 'desc')->with('home_team', 'away_team')->get();
        //dd($results->toArray());
        //$results = Result::orderBy('date', 'desc')->get();
        //dd($results->toArray());
        $headers = ['Date', 'Home', 'Result', 'Away'];
        return view('scraping.results', compact('results', 'active', 'headers'));
    }

    public function get_results()
    {
        //$data = $this->get_monthly_data(1, "Results");
        $data = $this->get_monthly_data(2, "Results");
        $results = $data['data'];
        $headers = $data['headers'];
        $active = "Results";
        //dd($results);
        $this->save_results($data['data']);
        dd("Results: ", $this->get_monthly_data(2, "Results"));
        return view('scraping.results', compact('results', 'headers', 'active'));
    }

    public function save_results($data)
    {
        //dd($data);
        if ($data != null) {
            foreach ($data as $key => $item) {
                //dd($item);
                if (count($item) > 3) {
                    $fixture = new Result();
                    $fixture->date = Carbon::parse($item['Date'])->format('Y-m-d');
                    $fixture->home = $item['Home'];
                    $fixture->time_result = $item['Result'];
                    $fixture->away = $item['Away'];
                    $fixture->league = "Premier League";
                    $fixture->save();
                }
            }
        }
    }

    /*public function store_results()
    {
        $results = $this->get_monthly_data(1, 'Results');
        if ($results != null) {
            foreach ($results as $key => $item) {
                if (count($item) > 3) {
                    $fixture = new Result();
                    $fixture->date = $item[0];
                    $fixture->home = $item[1];
                    $fixture->time_result = $item[2];
                    $fixture->away = $item[3];
                    $fixture->league = "Premier League";
                    $fixture->save();
                }
            }
        }
    }*/
}
