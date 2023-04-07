<?php

namespace App\Http\Controllers\Scraping;

use App\Models\User;
use App\Models\UserPrediction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    protected $controller = "App\Http\Controllers\Scraping\UserController";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $active = 'Users';
        $users = User::all();
        return view('scraping.user.index', compact('active', 'users'));
    }


}
