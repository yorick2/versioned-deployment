@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$deployment->created_at}} ( {{$deployment->created_at->diffForHumans()}} )</div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">deployed by:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control" id="name" value="{{$deployment->owner->name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="status" class="col-sm-2 col-form-label">status:</label>
                                <div class="col-sm-10">
                                    @if ($deployment->success  === 1)
                                        <input type="text" readonly class="form-control status success" id="status" value="success">
                                    @else
                                        <input type="text" readonly class="form-control status failed" id="status" value="failed">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="notes" class="col-sm-2 col-form-label">notes:</label>
                                <div class="col-sm-10">
                                    <textarea type="text" readonly class="form-control status success" id="notes" value="{{$deployment->notes}}"></textarea>
                                </div>
                            </div>
                            <div>
                                <h2>output:</h2>
                                <hr/>
                                <div>
                                    @foreach(json_decode($deployment->output) as $key => $output)
                                        <section>
                                            @if(isset($output->name))
                                                <h1 class="h5">{{$output->name}}</h1>
                                            @endif
                                            <div class="form-group row">
                                                @if ($deployment->success  === 1)
                                                    <label for="message-{{$key}}" class="col-sm-2 col-form-label mini-status success">success:</label>
                                                @else
                                                    <label for="message-{{$key}}" class="col-sm-2 col-form-label mini-status success">failed:</label>
                                                @endif
                                                <div class="col-sm-10">
                                                    <textarea type="text" readonly class="form-control status success" id="message-{{$key}}">{{$output->message}}</textarea>
                                                </div>
                                            </div>
                                        </section>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

