<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/AdminLTE.css")}}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bg-black">

        <div class="form-box" id="login-box">
            <div class="header">{{ trans('syntara::all.signin') }}</div>
            <form id="login-form"  method="post">
                <div class="body bg-gray">
                    <div class="form-group">
                        @if($loginAttribute === 'email')
                        <input type="text" class="form-control" placeholder="{{ trans('syntara::all.email') }}" name="email" id="email">
                        @elseif($loginAttribute === 'username')
                        <input type="text" class="form-control" placeholder="{{ trans('syntara::users.username') }}" name="username" id="username">
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="{{ trans('syntara::all.password') }}" name="pass" id="pass">
                    </div>
                    <div class="form-group">
                        <a href="{{ URL::route('recover.index') }}">Olvidé mi contraseña</a>
                    </div>
                </div>
                <div class="footer" id="main-container">
                    <button type="submit" class="btn bg-olive btn-block">{{ trans('syntara::all.signin') }}</button>
                    <div align="center">
                        <img src="{{ asset('images/logo1.png') }}" class="img-responsive" alt="Fletes RAM">
                    </div>
                </div>
            </form>

        </div>


        <!-- jQuery 2.0.2 -->
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('packages/mrjuliuss/syntara/assets/js/dashboard/base.js') }}"></script>

        <script type="text/javascript" src="{{ asset('packages/mrjuliuss/syntara/assets/js/dashboard/login.js') }}"></script>
        <!-- Bootstrap -->
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/bootstrap.min.js")}}" type="text/javascript"></script>

    </body>
</html>
