<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ trans('syntara::all.signin') }}</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="../login/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login/vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login/css/util.css">
	<link rel="stylesheet" type="text/css" href="../login/css/main.css">
<!--===============================================================================================-->

</head>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form id="login-form" class="login100-form validate-form" method="post">
					<span class="login100-form-title p-b-34">
						{{ trans('syntara::all.signin') }} - 149
					</span>

					<div class="wrap-input100 rs1-wrap-input100 validate-input m-b-20 form-group" data-validate="Escribe tu nombre de usuario">
						@if($loginAttribute === 'email')

							<input id="first-name" type="text" class="form-control input100" placeholder="{{ trans('syntara::all.email') }}" name="email" id="email">

						@elseif($loginAttribute === 'username')

							<input id="first-name" type="text" class="form-control input100" placeholder="{{ trans('syntara::users.username') }}" name="username" id="username">

						@endif

						<span class="focus-input100"></span>
					</div>
					<div class="wrap-input100 rs2-wrap-input100 validate-input m-b-20 form-group" data-validate="Escribe tu contraseña">
						<input class="input100" type="password" name="pass" placeholder="{{ trans('syntara::all.password') }}">
						<span class="focus-input100"></span>
					</div>
					<br>
					<div class="footer" id="main-container" style="text-align: center;"></div>
					<br>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn btn" data-loading-text="Ingresando...">
							Login
						</button>
					</div>


					<div class="w-full text-center p-t-27 p-b-239">
						<span class="txt1">
							Olvidaste tu
						</span>

						<a href="{{ URL::route('recover.index') }}" class="txt2">
							contraseña?
						</a>
					</div>

				</form>

				<div class="login100-more" style="background-image: url('../images/logo1.png');"></div>
			</div>
		</div>
	</div>



	<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
	<script src="../login/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="../login/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="../login/vendor/bootstrap/js/popper.js"></script>
	<script src="../login/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="../login/vendor/select2/select2.min.js"></script>
	<script>
		$(".selection-2").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect1')
		});
	</script>
<!--===============================================================================================-->
	<script src="../login/vendor/daterangepicker/moment.min.js"></script>
	<script src="../login/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="../login/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="../login/js/main.js"></script>

	<!-- jQuery 2.0.2 -->
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="{{ asset('packages/mrjuliuss/syntara/assets/js/dashboard/base.js') }}"></script>

	<script type="text/javascript" src="{{ asset('packages/mrjuliuss/syntara/assets/js/dashboard/login.js') }}"></script>

</body>
</html>
