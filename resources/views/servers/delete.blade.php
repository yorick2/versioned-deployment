@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Delete Server:</div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Name:</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control" id="created_by" value="{{$server->name}}">
                            </div>
                        </div>
                        <form action="{{$server->path()}}" method="post">
                            {{csrf_field()}}
                            {{method_field('DELETE')}}
                            <div class="form-check">
                                <input type="checkbox" name="confirm" class="form-check-input">
                                <label for="confirm" class="form-check-label ">Are you sure you want to permanently delete the project?</label>
                                @if ($errors->has('confirm'))
                                    <div class="small alert-danger">
                                        {{ $errors->first('confirm') }}
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="mt-3 btn btn-danger float-right btn btn-block">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


