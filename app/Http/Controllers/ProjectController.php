<?php

namespace App\Http\Controllers;

use App;
use App\ProjectInterface;

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
        $projectsCollection = (App::make('App\ProjectInterface'))::latest()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('projects.index', compact('projectsCollection'));
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
        (App::make('App\ProjectInterface'))::create([
            'user_id' => auth()->id(),
            'slug' => request('name'),
            'name' => request('name'),
            'repository' => request('repository'),
            'notes' => request('notes')
        ]);
        return redirect(route('ProjectsIndex'));
    }

    /**
     * Display the specified resource.
     * @param ProjectInterface $project
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectInterface $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProjectInterface $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ProjectInterface $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Show the form for deleting the specified resource.
     *
     * @param ProjectInterface $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(ProjectInterface $project)
    {
        return view('projects.delete', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProjectInterface $project
     * @return ProjectInterface
     */
    public function update(ProjectInterface $project)
    {
        $project->update(request([
            'name',
            'repository',
            'notes'
        ]));
        return redirect(route('ProjectsIndex'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProjectInterface $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(ProjectInterface $project)
    {
        if (!request('confirm')) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'confirm' => ['Please confirm you want to delete'],
            ]);
            throw $error;
        }
        $project->servers()->each(function ($project) {
            $project->deployments()->delete();
        });
        $project->servers()->delete();
        $project->delete();
        if (request()->wantsJson()) {
            return response([], 204);
        }
        return redirect(route('ProjectsIndex'));
    }
}
