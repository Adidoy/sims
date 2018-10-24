@extends('layouts.master')

@section('styles-include')
<style type="text/css">
    body {
        background: #22313F;
    }

    .hris-login {
        background-color: #019875;
        color: white;
        text-decoration: none;
    }

    .btn-primary {
        background-color: #013243;
        border: none;
        border-radius: 2px;
    }

    .btn-primary:hover {
        background-color: #336E7B;
    }

    .panel {
        padding: 0px 5px;
    }

    .panel-heading {
        margin: 5px;
    }

    .hris-login:hover {
        background-color: #1BA39C;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid" id="page-body" style="margin-top: 100px;">
    <div class="col-md-offset-4 col-md-4 panel panel-default" id="login-panel">
              
        <div class="panel-heading">
            <a href="{{ url('/') }}" style="margin: 10px;">
                <div style="color: #800000; margin: 0; padding: 0;">
                    <div class="col-md-1">
                        <img src="{{ asset('images/logo.png') }}" style="height: 64px; width: auto;" />
                    </div>
                    <div class="col-md-offset-1 col-md-7" style="font-size: 12px; white-space: nowrap;">
                        <h5 style="margin: 3px;">Polytechnic University Of the Philippines</h5>
                        <p style="margin: 3px;">Sta. Mesa, Manila</p>
                        <p style="margin: 3px; font-size: 15px;"> 
                          <strong> Supplies Inventory Management System</strong> 
                        </p>
                    </div>  
                </div>
                <div class="clearfix"></div>
            </a>
        </div>

        <div class="panel-body">

            @include('errors.alert')

            <form 
                class="form-horizontal" 
                action="{{ url('login') }}" 
                id="login-form" 
                method="post">

                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="form-group">
                    <div class="col-md-12">
                        <label for="username"> Username </label>
                        <input 
                            type="text" 
                            id="username" 
                            class="form-control" 
                            value="{{ old('username') }}" 
                            name="username" 
                            placeholder="Username" 
                            required 
                            autofocus />
                        <p 
                            class="text-muted" 
                            style="margin-top: 5px; font-size: 12px;">
                            Your username may consists of letters and numbers provided by the System Adminstrator. Contact the Administrator for more information regarding your credentials
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <label for="password"> Password </label>
                        <input 
                            type="password" 
                            id="password" 
                            class="form-control" 
                            placeholder="Password" 
                            name="password" 
                            value="{{ old("password") }}" 
                            required 
                            autofocus />
                        <p class="text-muted" style="margin-top: 5px; font-size: 12px;">
                            If you have forgotten your password, you may contact the administrator to reset your password.
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <button 
                            type="submit" 
                            id="login-button" 
                            data-loading-text="Logging in..." 
                            class="btn btn-lg btn-primary btn-block" 
                            autocomplete="off">
                            Login
                        </button>
                    </div>
                </div>


            </form>
        </div>

        <div class="panel-footer">
            <a href="{{ url('faqs') }}">Frequently Asked Questions </a>
            <a 
            class="pull-right"
            href="https://onedrive.live.com/view.aspx?cid=c5fb49234dd366a6&page=view&resid=C5FB49234DD366A6!1351&parId=C5FB49234DD366A6!1330&app=Word">
                <strong>User's Application Form</strong>
            </a>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
    $(document).ready(function() {
        $("#login-button").click(function() {
            var $btn = $(this);
            $btn.button('loading');

            setTimeout(function () {
                $btn.button('reset');
            }, 1000);
        });
    })
</script>
@endsection