@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$project->name}}</div>

                    <div class="card-body">
                        <div>
                            <span>created at:</span>
                            <div>
                                {{$project->created_at}} ({{$project->created_at->diffForHumans()}})
                            </div>
                        </div>
                        <div>
                            <span>repository:</span>
                            <div>
                                {{$project->repository}}
                            </div>
                        </div>
                        <div>
                            <span>notes:</span>
                            <div>
                                {{$project->notes}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


