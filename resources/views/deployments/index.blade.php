@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Deployments</div>

                    <div class="card-body">
                        @foreach($server->deployments as $deployment)
                            <li>
                                <a href="{{$deployment->path()}}">
                                    <div class="created">{{$deployment->created_at}} ( {{$deployment->created_at->diffForHumans()}} )</div>
                                    @if ($deployment->success  === 1)
                                        <div class="status success">success</div>
                                    @else
                                        <div class="status fail">failed</div>
                                    @endif
                                    <div class="owner">{{$deployment->owner->name}}</div>
                                </a>
                            </li>
                            <hr/>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


