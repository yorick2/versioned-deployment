@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <h1>Projects</h1>
                            <div class="float-right">
                                <a href="{{ route('CreateProject') }}" class="btn btn-default">Create</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($projectsCollection as $project)
                                <li class="list-group-item">
                                    <a href="{{$project->path()}}">{{$project->name}}</a>
                                    </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


