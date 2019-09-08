@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <h1>{{$project->name}}</h1>
                            <div class="float-left">
                                <a class="btn btn-default pl-0" href="{{$project->path()}}/servers">Servers</a>
                            </div>
                            <div class="float-right">
                                <a class="btn btn-default pl-0" href="{{route('DeleteProject',['project'=> $project]) }}">Delete</a>
                            </div>
                            <div class="float-right">
                                <a href="{{ route('EditProject',['project'=> $project]) }}" class="btn btn-default">Edit</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="created_at" class="col-sm-2 col-form-label">created at:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control" id="created_at" value="{{$project->created_at}} ({{$project->created_at->diffForHumans()}})">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="created_by" class="col-sm-2 col-form-label">created by:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control" id="created_by" value="{{$project->owner->name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="repository" class="col-sm-2 col-form-label">repository:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control" id="repository" value="{{$project->repository}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="notes" class="col-sm-2 col-form-label">notes:</label>
                                <div class="col-sm-10">
                                    <textarea type="text" readonly class="form-control status success" id="notes">{{$project->notes}}</textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


