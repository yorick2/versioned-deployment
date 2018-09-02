@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Projects</div>

                    <div class="card-body">
                        <ul>
                            @foreach($projectsCollection as $project)
                                <li><a href="{{$project->path()}}">{{$project->name}}</a></li>
                                <hr/>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


