<?php

namespace App\Http\Controllers;

use App\Deployment;
use App\DeploymentMethod;
use App\Project;
use App\Server;
use App\Git;
use App\SshConnection;
use Illuminate\Http\Request;

class DeploymentController extends Controller
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
    public function index(Project $project, Server $server)
    {
        return view('deployments.index',compact('project','server'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param Project $project
     * @param Server $server
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Project $project, Server $server)
    {
        $gitLog = [];
        $connection = new SshConnection($server->toArray());
        $response = $connection->connect();
        if ($response['success'] != 0 ) {
            $git = new Git(
                $connection,
                $server
            );
            $gitLog = $git->getGitLog();
        }
        return view('deployments.create',compact('server','project','gitLog'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Server $server
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Project $project, Server $server)
    {
        $deployment = $server->executeDeployment([
            'user_id' => auth()->id(),
            'notes' => request('notes'),
            'commit' => request('commit')
        ]);
        return view('deployments.show', compact('project','server', 'deployment'));
//        return redirect(route('DeploymentsIndex',compact('server','project')));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deployment  $deployment
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project,Server $server,Deployment $deployment)
    {
        return view('deployments.show', compact('project','server', 'deployment'));
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
