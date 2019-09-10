@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add Project:</div>
                    <div class="card-body">
                        <form method="post" action="{{route('SubmitCreateProject')}}">
                            @csrf

                            <div class="name form-group row">
                                <label for="name" class="col-md-3 col-form-label text-md-right">name</label>
                                <input name="name" class="col-md-7" type="text" />
                            </div>
                            <div class="repository form-group row">
                                <label for="repository" class="col-md-3 col-form-label text-md-right">repository</label>
                                <input name="repository" class="col-md-7" type="text" placeholder="git@github.com:w3c/csswg-test.git"  required/>
                            </div>
                            <div class="notes">
                                <textarea name="notes" class="form-control" placeholder="notes" ></textarea>
                            </div>
                            <button type="submit" class="btn btn-default float-right">save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


