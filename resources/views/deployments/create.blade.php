@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Start Deployment:</div>
                    <div class="card-body">
                        <div class="name form-group row">
                            <label for="name" class="col-sm-2 col-form-label text-md-right" >Server</label>
                            <div class="col-sm-10">
                                <input name="name" class="form-control" type="text" value="{{$server->name}}" readonly/>
                            </div>
                        </div>
                        <hr>
                        <form method="post" action="{{route('SubmitCreateDeployment',['server'=>$server, 'project'=>$project])}}">
                            @csrf
                            <div class="repository form-group row">
                                <label for="commit" class="col-sm-2 col-form-label text-md-right">commit:</label>
                                <div class="col-sm-10">
                                    <select name="commit" class="form-control form-control-md" id="exampleFormControlSelect1">
                                        @foreach($gitLog as $ref => $text)
                                            <option value="{{$ref}}">{{$text}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="notes">
                                    <textarea name="notes" class="form-control" placeholder="notes"></textarea>
                                </div>
                            </div>
                            <div class="float-right">
                                <button type="submit" class="btn btn-default">deploy</button>
                                <button type="submit"  class="btn btn-default" formaction="{{route('GitDiffDeployment', ['server'=>$server, 'project'=>$project])}}">git diff</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


