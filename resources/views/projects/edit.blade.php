@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Project:</div>
                    <div class="card-body">
                        <form method="post" action="{{route('SubmitEditProject',['project'=>$project])}}">
                            @csrf
                            @method('PATCH')
                            <div class="">
                                <div class="name form-group row">
                                    <label for="name" class="col-md-3 col-form-label text-md-right">name</label>
                                    <input name="name" class="col-md-7" type="text" value="{{$project->name}}"/>
                                </div>
                                <div class="repository form-group row">
                                    <label for="repository" class="col-md-3 col-form-label text-md-right">repository</label>
                                    <input name="repository" class="col-md-7" type="text" placeholder="git@github.com:w3c/csswg-test.git" value="{{$project->repository}}" required/>
                                </div>
                                <div class="notes">
                                    <textarea name="notes" class="form-control" placeholder="notes" value="{{$project->notes}}"></textarea>
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


