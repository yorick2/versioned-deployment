@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add Server:</div>
                    <div class="card-body">
                        <div class="name">
                            <label for="name">Project</label>
                            <input name="name" type="text" value="{{$project->name}}" readonly/>
                        </div>
                        <hr>
                        <form method="post" action="{{route('SubmitCreateServer',['project'=>$project])}}">
                            @csrf
                            <div class="">
                                <div class="name form-group row">
                                    <label for="name" class="col-md-3 col-form-label text-md-right">name</label>
                                    <input name="name" class="col-md-7" type="text"/>
                                </div>
                                <div class="deploy_host form-group row">
                                    <label for="deploy_host" class="col-md-3 col-form-label text-md-right">host</label>
                                    <input name="deploy_host" class="col-md-7" type="text" placeholder="https://example.com" required/>
                                </div>
                                <div class="deploy_port form-group row">
                                    <label for="deploy_port" class="col-md-3 col-form-label text-md-right">port</label>
                                    <input name="deploy_port" class="col-md-7" type="number" placeholder="22" value="22"/>
                                </div>
                                <div class="deploy_location form-group row">
                                    <label for="deploy_location" class="col-md-3 col-form-label text-md-right">folder</label>
                                    <input name="deploy_location" class="col-md-7" type="text" placeholder="/vaw/www" value="/vaw/www" required/>
                                </div>
                                <div class="deploy_user form-group row">
                                    <label for="deploy_user" class="col-md-3 col-form-label text-md-right">user</label>
                                    <input name="deploy_user" class="col-md-7" type="text" required/>
                                </div>
                                <div class="notes">
                                    <textarea name="notes" class="form-control" placeholder="notes"></textarea>
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


