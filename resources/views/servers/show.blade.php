@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$server->name}}</div>
                    <div class="card-body">
                        <div>
                            <div>
                                <span>created at:</span>
                                <div>
                                    {{$server->created_at}} ({{$server->created_at->diffForHumans()}})
                                </div>
                            </div>
                            <div>
                                <div>created by:</div>
                                <div class="owner">{{$server->owner->name}}</div>
                            </div>
                            <div><a href="{{$server->path()}}/deployments">Go to deployments</a></div>
                            <span>notes:</span>
                            <div>
                                {{$server->notes}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


