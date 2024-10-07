@extends('pagina.master')

@section('slider')
	<section id="slider" class="slider-element slider-parallax swiper_wrapper clearfix" style="height: 600px;" data-loop="true">

		<div class="swiper-container swiper-parent">
			<div class="swiper-wrapper">
				<div class="swiper-slide" style="background-image: url('packages/html/images/trailer1.jpg'); background-position: center top;">
					<div class="container clearfix">
						<div class="slider-caption slider-caption-right d-none d-sm-block">
							<h2 style="color: #dd0a13">Grupo RAM</h2>
							<p class="d-none d-sm-block">Nos Aseguramos que tu mercancía llegue en <span class="subtitle" style="color: #dd0a13"><b>Tiempo y Forma</b></span></p>
							<p class="text-center">
								<img src="images/logo1.png" alt="" style="max-width: 90px;">
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>
@stop

@section('content')
	<div class="content-wrap">

		<div class="promo promo-light promo-full uppercase bottommargin-lg header-stick">
			<div class="container clearfix">
				<h3 style="letter-spacing: 2px; color: #dd0a13;">¿Necesitas un flete URGENTE?</h3>
				<span style="color: #dd0a13">Solicita una cotización con nosotros</span>
				<a href="{{ URL::to('contacto') }}" class="button button-large button-border button-rounded" style="color: #dd0a13">¡Contáctanos!</a>
			</div>
		</div>

		<div class="container clearfix">

			<div class="col_one_fourth nobottommargin">
				<div class="feature-box media-box">
					<div class="fbox-media">
						<img style="border-radius: 2px;" src="images/servicios/cuadro1.PNG" alt="">
					</div>
				</div>
			</div>

			<div class="col_one_fourth nobottommargin">
				<div class="feature-box media-box">
					<div class="fbox-media">
						<img style="border-radius: 2px;" src="images/servicios/cuadro2.PNG" alt="">
					</div>
				</div>
			</div>

			<div class="col_one_fourth nobottommargin">
				<div class="feature-box media-box">
					<div class="fbox-media">
						<img style="border-radius: 2px;" src="images/servicios/cuadro3.PNG" alt="">
					</div>
				</div>
			</div>

			<div class="col_one_fourth nobottommargin col_last">
				<div class="feature-box media-box">
					<div class="fbox-media">
						<img style="border-radius: 2px;" src="images/servicios/cuadro4.PNG" alt="">
					</div>
				</div>
			</div>

		</div>



		<div class="section nomargin noborder bgcolor1 dark">
			<div class="container topmargin-lg cleafix">

				<div class="col_two_fifth">
					<div class="heading-block nobottomborder">
						<h2>Estas Empresas</h2>
						<span class="ls1">Ya Confían en Nuestros Servicios.</span>
					</div>

					<ul class="clients-grid grid-3 nobottommargin clearfix">
						<li style="padding: 20px;"><a href="#"><img src="images/clientes/1.PNG" alt="Clients"></a></li>
						<li style="padding: 20px;"><a href="#"><img src="images/clientes/2.PNG" alt="Clients"></a></li>
						<li style="padding: 20px;"><a href="#"><img src="images/clientes/3.PNG" alt="Clients"></a></li>
						<li style="padding: 20px;"><a href="#"><img src="images/clientes/4.PNG" alt="Clients"></a></li>
						<li style="padding: 20px;"><a href="#"><img src="images/clientes/5.PNG" alt="Clients"></a></li>
						<li style="padding: 20px;"><a href="#"><img src="images/clientes/6.PNG" alt="Clients"></a></li>
						<li style="padding: 20px;"><a href="#"><img src="images/clientes/7.PNG" alt="Clients"></a></li>
						<li style="padding: 20px;"><a href="#"><img src="images/clientes/8.PNG" alt="Clients"></a></li>
						<li style="padding: 20px;"><a href="#"><img src="images/clientes/9.PNG" alt="Clients"></a></li>
						<li style="padding: 20px;"><a href="#"><img src="images/clientes/10.PNG" alt="Clients"></a></li>
					</ul>
				</div>

				<div class="col_three_fifth col_last">
					<div class="heading-block nobottomborder">
						<h2>Beneficios</h2>
						<span class="ls1">De Nuestros Servicios.</span>
					</div>

					<ul class="iconlist iconlist-large iconlist-color">
						<li><i class="fas fa-clock fa-3x" data-fa-transform="shrink-9 up-4 left-6" data-fa-mask="fas fa-truck" style="color:#dd0a13"></i> Ten la seguridad de que tu mercancía llegará en tiempo y forma, al contar con más del 80% de camiones nuevos.</li>
						<br>
						<li><i class="fas fa-piggy-bank fa-3x" style="color:#dd0a13"></i> Ahorra dinero al contar con precios accesibles.</li>
						<br>
						<li><i class="fas fa-box-open fa-3x" style="color:#dd0a13"></i> Ten la tranquilidad de que tu mercancía estará libre de humedad al contar con camiones con cajas secas.</li>
					</ul>

				</div>

			</div>
		</div>

		<div class="line topmargin-sm"></div>

		<div class="section nobg notopmargin nopadding footer-stick">
			<div class="container clearfix">

				<div class="heading-block nobottomborder title-center">
					<h2>Conoce Nuestros</h2>
					<span class="ls1">Servicios Adicionales.</span>
				</div>
				<div class="row">
					<div class="col-lg-5">
						<img src="images/servicios/uniforme.jpg" alt="Bottom Trust">
					</div>
					<div class="col-lg-7 topmargin-sm">
						<div class="row">
							<div class="col-lg-4" align="center">
								<img src="images/servicios/1.png"> 
								<div class="heading-block nobottomborder">
									<span class="ls1"><h3>Transportación.</h3></span>
								</div>
							</div>
							<div class="col-lg-4" align="center">
								<img src="images/servicios/2.png"> 
								<div class="heading-block nobottomborder">
									<span class="ls1 text-center"><h3>Maniobras.</h3></span>
								</div>
							</div>
							<div class="col-lg-4" align="center">
								<img src="images/servicios/3.png"> 
								<div class="heading-block nobottomborder">
									<span class="ls1 text-center"><h3>Empaquetamiento.</h3></span>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

	</div>
@stop

@section('scripts')
	<script type="text/javascript">
		window.onload=function () {
	    $('#inicio').addClass('current');
	  };
	</script>
@stop