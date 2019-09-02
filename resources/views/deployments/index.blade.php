@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <h1>Deployments</h1>
                            <div class="float-right">
                                <a href="{{ route('CreateDeployment',['project'=> $project,'server'=> $server]) }}" class="btn btn-default">Deploy</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($deploymentsCollection as $deployment)
                                <li class="list-group-item">
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
                            @endforeach
                        </ul>
                        {!! $deploymentsCollection->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


