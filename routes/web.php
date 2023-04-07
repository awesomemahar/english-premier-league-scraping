<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();


use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralMail;

Route::get('/', [App\Http\Controllers\Controller::class, 'welcome'])->name('welcome');


//Scraping Steps
//1.
Route::get('/get-all-leagues', [\App\Http\Controllers\Scraping\LeagueController::class, 'get_leagues'])->name('get-all-leagues');
//2.
Route::get('/scrapallseasons', [\App\Http\Controllers\Scraping\FixturesController::class, 'scrap_all_seasons']);
//3.
Route::get('/get-teams', [\App\Http\Controllers\Scraping\TeamController::class, 'get_teams'])->name('get-teams');
//4.
Route::get('/scrapdata', [\App\Http\Controllers\Scraping\FixturesController::class, 'scrap_league']);


Route::get('/scrapteams', [\App\Http\Controllers\Scraping\FixturesController::class, 'get_teams']);

Route::get('/worldcup-fixtures', [\App\Http\Controllers\Scraping\FixturesController::class, 'worldcup_fixtures']);
Route::get('/worldcup-teams', [\App\Http\Controllers\Scraping\FixturesController::class, 'worldcup_teams']);
Route::get('/old-worldcup-teams', [\App\Http\Controllers\Scraping\FixturesController::class, 'old_worldcup_teams']);
Route::get('/old-worldcup-group-stage-results', [\App\Http\Controllers\Scraping\FixturesController::class, 'worldcup_history_group_stage']);
Route::get('/old-worldcup-finals-results', [\App\Http\Controllers\Scraping\FixturesController::class, 'worldcup_history_final_stage']);


//Route::get('/scrapdata', [\App\Http\Controllers\Scraping\FixturesController::class, 'scrap_fixtures']);

//Users
Route::get('/users', [\App\Http\Controllers\Scraping\UserController::class, 'index'])->name('users');

//User Predictions
Route::get('/user-predictions/{username}', [\App\Http\Controllers\Scraping\UserPredictionController::class, 'get_user_predictions'])->name('user-predictions');
Route::get('/all-predictions', [\App\Http\Controllers\Scraping\UserPredictionController::class, 'get_all_predictions'])->name('all-predictions');

//Calculation
Route::get('/calculate-points', [\App\Http\Controllers\Scraping\UserPredictionResultController::class, 'calculate_points'])->name('calculate');

//Leaders Dashboard
Route::get('/leaders-dashboard', [\App\Http\Controllers\Scraping\UserPredictionController::class, 'leaders_dashboard'])->name('leaders-dashboard');

//Leagues
Route::get('/leagues', [\App\Http\Controllers\Scraping\LeagueController::class, 'index'])->name('leagues');

//Fixtures
Route::get('/premier-league-fixtures', [\App\Http\Controllers\Scraping\FixturesController::class, 'index'])->name('fixtures');
Route::get('/next-48-hours-fixtures', [\App\Http\Controllers\Scraping\FixturesController::class, 'get_48_hours_fixtures'])->name('48-fixtures');
Route::get('/predict-score/{id}', [\App\Http\Controllers\Scraping\FixturesController::class, 'get_fixture_by_id'])->name('predict-score');
Route::post('/save-prediction', [\App\Http\Controllers\Scraping\FixturesController::class, 'save_prediction'])->name('save-prediction');

//Results
Route::get('/premier-league-results', [\App\Http\Controllers\Scraping\ResultController::class, 'index'])->name('results');

//Main Controller
Route::get('/home-away-table', [\App\Http\Controllers\Controller::class, 'get_home_away_table'])->name('home-away');
Route::get('/wide-table', [\App\Http\Controllers\Controller::class, 'get_wide_table'])->name('wide-table');

//Top Scorers
Route::get('/top-scorers-table', [\App\Http\Controllers\Scraping\TopScorerController::class, 'index'])->name('top-scorers');
Route::get('/scrap-top-scorers-table', [\App\Http\Controllers\Scraping\TopScorerController::class, 'get_top_scorers_table'])->name('get-top-scorers');

//Players
Route::get('/player-stats', [\App\Http\Controllers\Scraping\PlayerController::class, 'get_player_stats'])->name('player-stats');
Route::get('/get-all-player-stats', [\App\Http\Controllers\Scraping\PlayerStatController::class, 'get_all'])->name('get-all-player-stats');
Route::get('/get-player-stats/{id}', [\App\Http\Controllers\Scraping\PlayerStatController::class, 'get_player_stats'])->name('get-player-stats-by-id');
Route::get('/get-players', [\App\Http\Controllers\Scraping\PlayerController::class, 'get_all_players'])->name('get-players');
Route::get('/allplayers', [\App\Http\Controllers\Scraping\PlayerController::class, 'index'])->name('players');
Route::get('/player/stats/{name}', [\App\Http\Controllers\Scraping\PlayerController::class, 'get_player_stats_by_name'])->name('player-stat-by-name');
Route::get('/get-teams-players', [\App\Http\Controllers\Scraping\PlayerController::class, 'get_team_players'])->name('get-all-teams-players');

//Teams
Route::get('/get-teams', [\App\Http\Controllers\Scraping\TeamController::class, 'get_teams'])->name('get-teams');
Route::get('/teams-index', [\App\Http\Controllers\Scraping\TeamController::class, 'index'])->name('teams');
Route::get('/teams-players/{teams}', [\App\Http\Controllers\Scraping\TeamController::class, 'get_players_by_team'])->name('teams-players');
Route::get('/teams-fixtures/{teams}', [\App\Http\Controllers\Scraping\TeamController::class, 'get_team_fixtures'])->name('teams-fixtures');
Route::get('/teams-results/{teams}', [\App\Http\Controllers\Scraping\TeamController::class, 'get_team_results'])->name('teams-results');
Route::get('/get_all_teams_stats', [\App\Http\Controllers\Scraping\TeamController::class, 'get_all_teams_stats'])->name('teams-stats');
//Seasons
Route::get('/show-seasons', [\App\Http\Controllers\Scraping\SeasonController::class, 'index'])->name('show.seasons');
Route::get('/scrap-seasons', [\App\Http\Controllers\Scraping\SeasonController::class, 'get_seasons'])->name('scrap.seasons');

//Misc Routes for testing purpose

Route::get('/testfixtures', [\App\Http\Controllers\Scraping\FixturesController::class, 'test_fixtures']);
Route::get('/getdata', [\App\Http\Controllers\Scraping\FixturesController::class, 'get_data_by_league']);
Route::get('/scraptest', [\App\Http\Controllers\Scraping\FixturesController::class, 'scrap_data']);
Route::get('/monthly-data', [\App\Http\Controllers\Scraping\FixturesController::class, 'index']);
Route::get('/monthly-results', [\App\Http\Controllers\Scraping\FixturesController::class, 'get_monthly_results']);
Route::get('/monthly-fixtures', [\App\Http\Controllers\Scraping\FixturesController::class, 'get_monthly_fixtures']);
Route::get('/store-fixtures', [\App\Http\Controllers\Scraping\FixturesController::class, 'store_fixtures']);
Route::get('/store-results', [\App\Http\Controllers\Scraping\ResultController::class, 'get_results']);

Route::get('/get-standings', [\App\Http\Controllers\Scraping\StandingController::class, 'get_standings']);
Route::get('/get-players-data', [\App\Http\Controllers\Scraping\StandingController::class, 'get_players']);

Route::get('/save-image', [\App\Http\Controllers\Controller::class, 'save_image']);
Route::get('/test', [\App\Http\Controllers\Controller::class, 'test']);
Route::get('/testing', [\App\Http\Controllers\Controller::class, 'testing']);
Route::get('/testing/{id}', [\App\Http\Controllers\Controller::class, 'get_team_stats']);
Route::get('/fixtures-scraping', [\App\Http\Controllers\Controller::class, 'doFixturesScraping']);
Route::get('/scrap', [\App\Http\Controllers\Controller::class, 'doScrapping']);
Route::get('/footy', [\App\Http\Controllers\Controller::class, 'footyRoom']);
Route::get('/goaldotcom', [\App\Http\Controllers\Controller::class, 'goalDotCom']);
Route::get('/soccerstats', [\App\Http\Controllers\Controller::class, 'soccerstats']);
Route::get('/soccerstats-all-matches', [\App\Http\Controllers\Controller::class, 'soccerstats_all_matches']);
Route::get('/livescore', [\App\Http\Controllers\Controller::class, 'liveScore']);
//Route::get('/whoScored', [\App\Http\Controllers\Controller::class, 'whoScored']);


//Route::get('/test-email', [\App\Http\Controllers\HomeController::class, 'test_email']);*/
