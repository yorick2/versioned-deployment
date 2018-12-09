@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    In order to deploy to a server you will need to add this key to your servers authorized_keys file
                    <hr/>
                    <div>{{$publicKey}}</div>
                    <hr/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
