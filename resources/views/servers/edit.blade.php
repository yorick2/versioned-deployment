@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Server:</div>
                    <div class="card-body">
                        <div class="name">
                            <label for="name">Project</label>
                            <input name="name" type="text" value="{{$project->name}}" readonly/>
                        </div>
                        <hr>
                        <form method="post" action="{{route('SubmitEditServer',['project'=>$project,'server'=>$server])}}">
                            @csrf
                            @method('PATCH')
                            <div class="">
                                <div class="name form-group row">
                                    <label for="name" class="col-md-3 col-form-label text-md-right">name</label>
                                    <input name="name" class="col-md-7" type="text" value="{{$server->name}}"/>
                                </div>
                                <div class="deploy_host form-group row">
                                    <label for="deploy_host" class="col-md-3 col-form-label text-md-right">host</label>
                                    <input name="deploy_host" class="col-md-7" type="text" placeholder="https://example.com" value="{{$server->deploy_host}}" required/>
                                </div>
                                <div class="deploy_port form-group row">
                                    <label for="deploy_port" class="col-md-3 col-form-label text-md-right">port</label>
                                    <input name="deploy_port" class="col-md-7" type="number" placeholder="22" value="{{$server->deploy_port}}"/>
                                </div>
                                <div class="deploy_location form-group row">
                                    <label for="deploy_location" class="col-md-3 col-form-label text-md-right">folder</label>
                                    <input name="deploy_location" class="col-md-7" type="text" placeholder="/vaw/www"  value="{{$server->deploy_location}}" required/>
                                </div>
                                <div class="deploy_user form-group row">
                                    <label for="deploy_user" class="col-md-3 col-form-label text-md-right">user</label>
                                    <input name="deploy_user" class="col-md-7" type="text"  value="{{$server->deploy_user}}" required/>
                                </div>
                                <div class="deploy_password form-group row">
                                    <label for="deploy_password" class="col-md-3 col-form-label text-md-right">password</label>
                                    <input name="deploy_password" class="col-md-7" type="password" value="{{$server->deploy_password}}"/>
                                </div>
                                <div class="notes">
                                    <textarea name="notes" class="form-control" placeholder="notes" value="{{$server->notes}}"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-default">save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


