<?php

namespace App\Http\Controllers;

use App\Project;
use App\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        return view('servers.index',compact('project'));
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
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Project $project)
    {
        $project->addServer([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'name' => request('name'),
            'deploy_host' => request('deploy_host'),
            'deploy_port' => request('deploy_port'),
            'deploy_location' => request('deploy_location'),
            'deploy_user' => request('deploy_user'),
            'deploy_password' => request('deploy_password'),
            'notes' => request('notes')
        ]);
        return back(); // redirect back
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function show($product_id,Server $server)
    {
        return view('servers.show', compact('server'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function edit(Server $server)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Server $server)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server)
    {
        //
    }
}
