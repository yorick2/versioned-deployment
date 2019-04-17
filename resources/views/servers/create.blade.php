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
                                    <label for="name" class="col-sm-2 col-form-label text-md-right">name</label>
                                    <div class="col-sm-10">
                                        <input name="name" class="form-control" type="text"/>
                                    </div>
                                </div>
                                <div class="deploy_host form-group row">
                                    <label for="deploy_host" class="col-md-2 col-form-label text-md-right">host</label>
                                    <div class="col-sm-10">
                                        <input name="deploy_host" class="form-control" type="text" placeholder="https://example.com" required/>
                                    </div>
                                </div>
                                <div class="deploy_port form-group row">
                                    <label for="deploy_port" class="col-md-2 col-form-label text-md-right">port</label>
                                    <div class="col-sm-10">
                                        <input name="deploy_port" class="form-control" type="number" placeholder="22" value="22"/>
                                    </div>
                                </div>
                                <div class="deploy_location form-group row">
                                    <label for="deploy_location" class="col-md-2 col-form-label text-md-right">folder</label>
                                    <div class="col-sm-10">
                                        <input name="deploy_location" class="form-control" type="text" placeholder="/vaw/www" value="/vaw/www" required/>
                                    </div>
                                </div>
                                <div class="deploy_user form-group row">
                                    <label for="deploy_user" class="col-md-2 col-form-label text-md-right">user</label>
                                    <div class="col-sm-10">
                                        <input name="deploy_user" class="form-control" type="text" required/>
                                    </div>
                                </div>
                                <div class="deploy_branch form-group row">
                                    <label for="deploy_branch" class="col-md-2 col-form-label text-md-right">branch name</label>
                                    <div class="col-sm-10">
                                        <select name="deploy_branch" class="form-control form-control-md">
                                            @foreach($gitBranches as $branch)
                                                <option value="{{$branch}}">{{$branch}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
{{--                                <div class="shared">--}}
{{--                                    <label for="shared" class="col-md-2 col-form-label text-md-right">shared</label>--}}
{{--                                    <textarea name="shared" class="form-control" placeholder="cache,/media,config.php"></textarea>--}}
{{--                                </div>--}}
{{--                                <div class="pre_commands">--}}
{{--                                    <label for="pre_deploy_commands" class="col-md-2 col-form-label text-md-right">commands run before deployment</label>--}}
{{--                                    <textarea name="pre_deploy_commands" class="form-control" placeholder="touch placeholder.txt"></textarea>--}}
{{--                                </div>--}}
{{--                                <div class="post_commands">--}}
{{--                                    <label for="post_deploy_commands" class="col-md-2 col-form-label text-md-right">Commands run after deployment</label>--}}
{{--                                    <textarea name="post_deploy_commands" class="form-control" placeholder="rm -r cache/*; rm ./placeholder.txt;"></textarea>--}}
{{--                                </div>--}}
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


