<?php

namespace App\Http\Controllers;

use App;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $publicKey = App::make('App\SshConnectionInterface')->getPublicKey();
        return view('home', compact('publicKey'));
    }
}
