@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <h1>{{$server->name}}</h1>
                            <div class="float-left">
                                <a  class="btn btn-default pl-0" href="{{$server->path()}}/deployments">Deployments</a>
                            </div>
                            <div class="float-right">
                                <form action="{{$server->path()}}" method="post">
                                    {{csrf_field()}}
                                    {{method_field('DELETE')}}
                                    <button type="submit" class="btn btn-link">Delete</button>
                                </form>
                            </div>
                            <div class="float-right">
                                <a href="{{ route('EditServer',['project'=> $project,'server'=>$server]) }}" class="btn btn-default">Edit</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="created_at" class="col-sm-2 col-form-label">created at:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control" id="created_at" value="{{$server->created_at}} ({{$server->created_at->diffForHumans()}})">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="created_by" class="col-sm-2 col-form-label">created by:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control" id="created_by" value="{{$server->owner->name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="notes" class="col-sm-2 col-form-label">notes:</label>
                                <div class="col-sm-10">
                                    <textarea type="text" readonly class="form-control status success" id="notes">{{$server->notes}}</textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


