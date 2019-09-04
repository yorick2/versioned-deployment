<?php

namespace App\Http\Controllers;

use App\Project;

class ProjectController extends Controller
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
    public function index()
    {
        $projectsCollection = Project::latest()->get();
        return view('projects.index',compact('projectsCollection'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        Project::create([
            'user_id' => auth()->id(),
            'slug' => request('name'),
            'name' => request('name'),
            'repository' => request('repository'),
            'notes' => request('notes')
        ]);
        return redirect(route('Projects'));
    }

    /**
     * Display the specified resource.
     * @param Project $project
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Project $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Project $project
     * @return Project
     */
    public function update(Project $project)
    {
        $project->update(request([
            'name',
            'repository',
            'notes'
        ]));
        return redirect(route('Projects'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Project $project)
    {
        $project->servers()->each(function ($project) {
            $project->deployments()->delete();
        });
        $project->servers()->delete();
        $project->delete();
        if(request()->wantsJson()) {
            return response([],204);
        }
        return redirect(route('Projects'));
    }
}
