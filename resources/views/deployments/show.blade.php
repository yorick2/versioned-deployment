@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$deployment->created_at}} ( {{$deployment->created_at->diffForHumans()}} )</div>
                    <div class="card-body">
                        <div>
                            <div>deployed by:</div>
                            <div class="owner">{{$deployment->owner->name}}</div>
                        </div>
                        <div>
                            <div>status:</div>
                            @if ($deployment->success  === 1)
                                <div class="status success">success</div>
                            @else
                                <div class="status fail">failed</div>
                            @endif
                        </div>
                        <div>
                            <div>notes:</div>
                            <div>
                                {{$deployment->notes}}
                            </div>
                        </div>
                        <div>
                            <div>output:</div>
                            <div>
                                {{$deployment->output}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

