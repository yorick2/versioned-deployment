<?php

namespace App\Http\Controllers;

use App\Deployment;
use App\Project;
use App\Server;
use Illuminate\Http\Request;

class DeploymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project,Server $server)
    {
        return view('deployments.index',compact('server'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deployment  $deployment
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project,Server $server,Deployment $deployment)
    {
        return view('deployments.show', compact('deployment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deployment  $deployment
     * @return \Illuminate\Http\Response
     */
    public function edit(Deployment $deployment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deployment  $deployment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deployment $deployment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deployment  $deployment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deployment $deployment)
    {
        //
    }
}
