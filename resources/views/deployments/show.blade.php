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
                                <label for="name" class="col-sm-2 col-form-label text-md-right">deployed by:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control" id="name" value="{{$deployment->owner->name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="commit" class="col-sm-2 col-form-label text-md-right">commit hash:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control"  id="commit" value="{{$deployment->commit}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="status" class="col-sm-2 col-form-label text-md-right">status:</label>
                                <div class="col-sm-10">
                                    @if ($deployment->success === 1)
                                        <input type="text" readonly class="form-control status success" id="status" value="success">
                                    @else
                                        <input type="text" readonly class="form-control status failed" id="status" value="failed">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="notes"  class="col-sm-2 col-form-label text-md-right">notes:</label>
                                <div class="col-sm-10">
                                    <textarea type="text" class="form-control"  readonly class="form-control status success" id="notes">{{$deployment->notes}}</textarea>
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
                                                @if ($output->success  === true)
                                                    <label for="message-{{$key}}" class="col-sm-2 col-form-label mini-status success">success &#9989; :</label>
                                                @else
                                                    <label for="message-{{$key}}" class="col-sm-2 col-form-label mini-status success">failed &#10060; :</label>
                                                @endif
                                                <div class="col-sm-10">
                                                    @if(strpos($output->message,"\n"))
                                                        <textarea type="text" readonly class="form-control status success" id="message-{{$key}}">{{$output->message}}</textarea>
                                                    @else
                                                        <input type="text" readonly class="form-control status success" id="message-{{$key}}" value="{{$output->message}}" />
                                                    @endif
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

