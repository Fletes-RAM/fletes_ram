<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="SemiColonWeb" />

	<!-- Stylesheets
	============================================= -->
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Raleway:300,400,500,600,700|Crete+Round:400i" rel="stylesheet" type="text/css" />
	{{ HTML::style('canvas/css/bootstrap.css') }}
	{{ HTML::style('canvas/style.css') }}
	{{ HTML::style('canvas/css/dark.css') }}
	{{ HTML::style('canvas/css/font-icons.css') }}
	{{ HTML::style('canvas/css/animate.css') }}
	{{ HTML::style('canvas/css/magnific-popup.css') }}

	{{ HTML::style('canvas/css/responsive.css') }}
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- Document Title
	============================================= -->
	<title>Próximamente | Fletes RAM</title>

</head>

<body class="stretched">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">

		<!-- Header
		============================================= -->
		<header id="header" class="no-sticky transparent-header dark">

			<div id="header-wrap">

				<div class="container clearfix">

					<!-- Logo
					============================================= -->
					<div id="logo">
						<a href="index.html" class="standard-logo" data-dark-logo="images/logo1.png"><img src="images/logo1.png" alt="Fletes RAM"></a>
						<a href="index.html" class="retina-logo" data-dark-logo="images/logo1.png"><img src="images/logo1.png" alt="Fletes RAM"></a>
					</div><!-- #logo end -->


				</div>

			</div>

		</header><!-- #header end -->

		<section id="slider" class="slider-element full-screen dark" style="overflow: hidden;">

			<div class="container-fluid clearfix vertical-middle" style="z-index: 6;">

				<div class="heading-block title-center nobottomborder">
					<h1>¡Estamos en Construcción!</h1>
					<span>Por Favor, Regresa en unos días... Estamos muy cerca de terminar!!!</span>
				</div>

				<div id="countdown-ex1" class="countdown countdown-large coming-soon divcenter bottommargin" style="max-width:700px;"></div>

				<div class="divider divider-center divider-short divider-margin"><i class="icon-time"></i></div>

			</div>

			<div class="video-wrap">
				<video poster='images/videos/explore.jpg' preload muted autoplay loop>
					<source src='images/videos/explore.mp4' type='video/mp4' />
					<source src='images/videos/explore.webm' type='video/webm' />
				</video>
				<div class="video-overlay"></div>
			</div>

		</section>


	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="icon-angle-up"></div>

	<!-- External JavaScripts
	============================================= -->
	{{ HTML::script('canvas/js/jquery.js') }}
	{{ HTML::script('canvas/js/plugins.js') }}

	<!-- Footer Scripts
	============================================= -->
	{{ HTML::script('canvas/js/functions.js') }}

	<script>
		jQuery(document).ready( function($){
			var newDate = new Date(2018, 5, 30);
			$('#countdown-ex1').countdown({until: newDate});
		});
	</script>

</body>
</html>