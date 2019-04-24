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

                            <div class="">
                                <div class="name form-group row">
                                    <label for="name" class="col-sm-2 col-form-label text-md-right">name</label>
                                    <div class="col-sm-10">
                                        <input readonly name="name" class="form-control" type="text" value="{{$server->name}}"/>
                                    </div>
                                </div>
                                <div class="deploy_host form-group row">
                                    <label for="deploy_host" class="col-md-2 col-form-label text-md-right">host</label>
                                    <div class="col-sm-10">
                                        <input readonly name="deploy_host" class="form-control" value="{{$server->deploy_host}}"/>
                                    </div>
                                </div>
                                <div class="deploy_port form-group row">
                                    <label for="deploy_port" class="col-md-2 col-form-label text-md-right">port</label>
                                    <div class="col-sm-10">
                                        <input readonly name="deploy_port" class="form-control" type="number" placeholder="22"  value="{{$server->deploy_port}}"/>
                                    </div>
                                </div>
                                <div class="deploy_location form-group row">
                                    <label for="deploy_location" class="col-md-2 col-form-label text-md-right">folder</label>
                                    <div class="col-sm-10">
                                        <input readonly name="deploy_location" class="form-control" type="text" placeholder="/vaw/www" value="{{$server->deploy_location}}"/>
                                    </div>
                                </div>
                                <div class="deploy_user form-group row">
                                    <label for="deploy_user" class="col-md-2 col-form-label text-md-right">user</label>
                                    <div class="col-sm-10">
                                        <input readonly name="deploy_user" class="form-control" type="text" value="{{$server->deploy_user}}"/>
                                    </div>
                                </div>
                                <div class="deploy_branch form-group row">
                                    <label for="deploy_branch" class="col-md-2 col-form-label text-md-right">branch name</label>
                                    <div class="col-sm-10">
                                        <input readonly name="deploy_branch" class="form-control" type="text" value="{{$server->deploy_branch}}"/>
                                    </div>
                                </div>
                                <div class="shared_files form-group">
                                    <label for="shared_files" class="col-form-label">shared files (e.g. "test/text.txt, test/test2.txt, pub/test.txt")</label>
                                    <textarea readonly name="shared_files" class="form-control" placeholder="cache,/media,config.php">{{$server->shared_files}}</textarea>
                                </div>
                                <div class="pre_commands form-group">
                                    <label for="pre_deploy_commands" class="col-form-label">commands run before deployment (run from the user folder)</label>
                                    <textarea readonly name="pre_deploy_commands" class="form-control" placeholder="mysqldump --defaults-extra-file=mysql.cnf my_database > backup.sql">{{$server->pre_deploy_commands}}</textarea>
                                </div>
                                <div class="post_commands form-group">
                                    <label for="post_deploy_commands" class="col-form-label">commands run after deployment (run from the release folder)</label>
                                    <textarea readonly name="post_deploy_commands" class="form-control" placeholder="rm -r cache/*; rm ./placeholder.txt;">{{$server->post_deploy_commands}}</textarea>
                                </div>
                                <div class="notes form-group">
                                    <label for="notes" class="col-form-label">notes</label>
                                    <textarea readonly name="notes" class="form-control" placeholder="notes">{{$server->notes}}</textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


