<?php

namespace App\Http\Controllers;

use App;
use App\DeploymentInterface;
use App\ProjectInterface;
use App\ServerInterface;

class DeploymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param ProjectInterface $project
     * @param ServerInterface $server
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ProjectInterface $project, ServerInterface $server)
    {
        $deploymentsCollection = $server->deployments()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('deployments.index', compact('deploymentsCollection', 'project', 'server'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param ProjectInterface $project
     * @param ServerInterface $server
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ProjectInterface $project, ServerInterface $server)
    {
        $gitLog = [];
        $connection = App::make(
            'App\SshConnectionInterface',
            ['attributes'=>$server->toArray()]
        );
        $response = $connection->connect();
        if ($response['success'] != 0) {
            $git = App::make(
                'App\GitInteractions\Git',
                ['sshConnection'=>$connection, 'server'=>$server]
            );
            $gitLog = $git->getGitLog();
        }
        return view('deployments.create', compact('server', 'project', 'gitLog'));
    }

    /**
     * Store a newly created resource in storage.
     * @param ProjectInterface $project
     * @param ServerInterface $server
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ProjectInterface $project, ServerInterface $server)
    {
        $deployment = $server->executeDeployment([
            'user_id' => auth()->id(),
            'notes' => request('notes'),
            'commit' => request('commit')
        ]);
        // used as it shows the right url path in the breadcrumbs
        return redirect(route('ShowDeployment', compact('server', 'project', 'deployment')));
    }

    /**
     * Display the specified resource.
     *
     * @param ProjectInterface $project
     * @param ServerInterface $server
     * @param DeploymentInterface $deployment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ProjectInterface $project, ServerInterface $server, DeploymentInterface $deployment)
    {
        return view('deployments.show', compact('project', 'server', 'deployment'));
    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  \App\Deployment  $deployment
//     * @return \Illuminate\Http\Response
//     */
//    public function edit(Deployment $deployment)
//    {
//        //
//    }
//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  \App\Deployment  $deployment
//     * @return \Illuminate\Http\Response
//     */
//    public function update(Request $request, Deployment $deployment)
//    {
//        //
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  \App\Deployment  $deployment
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy(Deployment $deployment)
//    {
//        //
//    }

    /**
     * Show git diff
     *
     * @param ProjectInterface $project
     * @param ServerInterface $server
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function gitDiff(ProjectInterface $project, ServerInterface $server)
    {
        $gitDiff = [];
        $commitRef = request('commit');
        $connection = App::make(
            'App\SshConnectionInterface',
            ['attributes'=>$server->toArray()]
        );
        $response = $connection->connect();
        if ($response['success'] != 0) {
            $git = App::make(
                'App\GitInteractions\GitInterface',
                ['sshConnection'=>$connection, 'server'=>$server]
            );
            $gitDiff = $git->getGitDiff($commitRef);
        }
        return view('deployments.gitDiff', compact('server', 'project', 'commitRef', 'gitDiff'));
    }
}
