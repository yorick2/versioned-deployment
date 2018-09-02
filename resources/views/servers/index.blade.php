@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Servers</div>

                    <div class="card-body">
                        @foreach($project->servers as $server)
                            <li><a href="{{$server->path()}}">{{$server->name}}</a></li>
                            <hr/>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


