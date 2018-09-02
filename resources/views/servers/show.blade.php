@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$server->name}}</div>
                    <div class="card-body">
                        <div>
                            <span>notes:</span>
                            <div>
                                {{$server->notes}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


