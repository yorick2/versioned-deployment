@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Start Deployment:</div>
                    <div class="card-body">
                        <div class="name">
                            <label for="name">Server</label>
                            <input name="name" type="text" value="{{$server->name}}" readonly/>
                        </div>
                        <hr>
                        <form method="post" action="{{route('SubmitCreateDeployment',['server'=>$server, 'project'=>$project])}}">
                            @csrf
                            <div class="">
                                <div class="notes">
                                    <textarea name="notes" class="form-control" placeholder="notes"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-default">deploy</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


