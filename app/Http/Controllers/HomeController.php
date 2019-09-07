<?php

namespace App\Http\Controllers;

use App\SshConnection;
use Couchbase\SearchSortScore;
use Illuminate\Http\Request;

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
        $publicKey = (new SshConnection())->getPublicKey();
        return view('home', compact('publicKey'));
    }
}
