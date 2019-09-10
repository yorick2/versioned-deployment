@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Git Diff:</div>
                    <div class="card-body">
                        <div class="name">
                            <label for="name" >Server</label>
                            <input name="name" type="text" value="{{$server->name}}" readonly/>
                        </div>
                        <hr>
                        <form method="post" action="{{route('SubmitCreateDeployment',['server'=>$server, 'project'=>$project])}}">
                            @csrf
                            <div class="repository form-group row">
                                <label for="server"  class="col-sm-2 col-form-label text-md-right">Server</label>
                                <div class="col-sm-10">
                                    <input name="server" type="text" value="{{$server->name}}" readonly />
                                </div>
                            </div>
                            <div class="repository form-group row">
                                <label for="commit"  class="col-sm-2 col-form-label text-md-right">Commit</label>
                                <div class="col-sm-10">
                                    <input name="commit" type="text" value="{{$commitRef}}" readonly/>
                                </div>
                            </div>
                            <div class="gitdiff">
                                <label for="gitdiff"  class="col-form-label">Changed files</label>
                                    <textarea name="gitdiff" class="form-control" readonly>{{$gitDiff}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="notes"  class="col-form-label">Notes</label>
                                <div class="notes">
                                    <textarea name="notes" class="form-control" placeholder="notes"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-default  float-right">deploy</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection