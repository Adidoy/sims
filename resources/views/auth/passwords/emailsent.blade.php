@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">
                    <h1>{{$message}}</h1>
                    <a href="{{ route('login') }}"> Return to SIMS </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
