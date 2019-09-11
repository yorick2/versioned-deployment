<?php

namespace App\Http\Controllers;

use App;
use App\Project;
use App\Server;

class ServerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        $serversCollection = $project->servers()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('servers.index',compact('serversCollection','project'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        $gitBranches  = (App::make('App\GitInteractions\GitLocalInterface'))
            ->getGitBranches($project->repository);
        return view('servers.create',compact('project','gitBranches'));
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
            'slug' => request('name'),
            'user_id' => auth()->id(),
            'name' => request('name'),
            'deploy_host' => request('deploy_host'),
            'deploy_port' => request('deploy_port'),
            'deploy_location' => request('deploy_location'),
            'deploy_user' => request('deploy_user'),
            'deploy_branch' => request('deploy_branch'),
            'shared_files' => request('shared_files'),
            'pre_deploy_commands' => request('pre_deploy_commands'),
            'post_deploy_commands' => request('post_deploy_commands'),
            'notes' => request('notes')
        ]);
        return redirect(route('ServersIndex',compact('project')));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Server $server)
    {
        return view('servers.show', compact('project', 'server'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Project $project
     * @param Server $server
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Project $project, Server $server)
    {
        $gitBranches  = (App::make('App\GitInteractions\GitLocalInterface'))
            ->getGitBranches($project->repository);
        return view('servers.edit', compact('project', 'server','gitBranches'));
    }

    /**
     * Show the form for deleting the specified resource.
     *
     * @param Project $project
     * @param Server $server
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Project $project, Server $server)
    {
        return view('servers.delete', compact('project', 'server'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Project $project
     * @param Server $server
     * @return Server
     */
    public function update(Project $project, Server $server)
    {
        $server->update(request([
            'name',
            'deploy_host',
            'deploy_port',
            'deploy_location',
            'deploy_user',
            'deploy_branch',
            'shared_files',
            'pre_deploy_commands',
            'post_deploy_commands',
            'notes'
        ]));
        return redirect(route('ServersIndex',compact('project')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @param Server $server
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Project $project, Server $server)
    {
        if(!request('confirm')){
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'confirm' => ['Please confirm you want to delete'],
            ]);
            throw $error;
        }
        $returnRoute = route('ServersIndex',compact('project')); // this is loaded here as deletion can mess with the route
        $server->deployments()->delete();
        $server->delete();
        if(request()->wantsJson()) {
            return response([],204);
        }
        return redirect($returnRoute);
    }
}
