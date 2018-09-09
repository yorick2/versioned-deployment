@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$project->name}}</div>
                    <div class="card-body">
                        <form method="post" action="{{route('SubmitCreateServer',['project'=>$project])}}">
                            @csrf
                            <div class="name">
                                <label for="name">name</label>
                                <input name="name" type="text"/>
                            </div>
                            <div class="deploy_host">
                                <label for="deploy_host">host</label>
                                <input name="deploy_host" type="text" placeholder="https://example.com" required/>
                            </div>
                            <div class="deploy_port">
                                <label for="deploy_port">port</label>
                                <input name="deploy_port" type="number" placeholder="22" value="22"/>
                            </div>
                            <div class="deploy_location">
                                <label for="deploy_location">folder</label>
                                <input name="deploy_location" type="text" placeholder="/vaw/www" value="/vaw/www" required/>
                            </div>
                            <div class="deploy_user">
                                <label class="form-control"  for="deploy_user">user</label>
                                <input class="form-control"  name="deploy_user" type="text" required/>
                            </div>
                            <div class="deploy_password">
                                <label class="form-control"  for="deploy_password">password</label>
                                <input class="form-control"  name="deploy_password" type="text" required/>
                            </div>
                            <div class="notes">
                                <textarea name="notes" class="form-control" placeholder="notes"></textarea>
                            </div>
                            <input type="submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


