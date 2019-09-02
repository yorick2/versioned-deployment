@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <h1>Servers</h1>
                            <div class="float-right">
                                <a href="{{ route('CreateServer',['project'=> $project]) }}" class="btn btn-default">Create</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($serversCollection as $server)
                                <li class="list-group-item">
                                    <a href="{{$server->path()}}">{{$server->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                        {!! $serversCollection->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection