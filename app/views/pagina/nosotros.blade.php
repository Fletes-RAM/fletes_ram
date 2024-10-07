@extends('pagina.master')

@section('slider')
	<!-- Page Title
	============================================= -->
	<section id="page-title" class="nobg">

		<div class="container clearfix">
			<h1>Acerca de Nosotros</h1>
			<span>Fletes RAM</span>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ URL::to('/') }}">Inicio</a></li>
				<li class="breadcrumb-item active" aria-current="page">Acerca de Nosotros</li>
			</ol>
		</div>

	</section><!-- #page-title end -->
@stop

@section('content')
	<div class="content-wrap">

					<div class="container clearfix">

						<div class="row clearfix">
							<div class="col-lg-6">
								<div class="heading-block nobottomborder bottommargin-sm">
									<h3>Acerca de Fletera Grupo RAM</h3>
								</div>
								<p class="text-justify">
									Somos una empresa dedicada al <span style="color: #dd0a13"><b>autotransporte de carga general</b></span>, a nivel nacional, en camionetas y camiones adecuados. Nuestra experiencia nos respalda, además de nuestra <span style="color: #dd0a13"><b>calidad y eficacia</b></span>. Le garantizamos <span style="color: #dd0a13"><b>puntualidad, seguridad y atención</b></span> constante a sus bienes.

									Como profesionales de la <span style="color: #dd0a13"><b>transportación, servicios de carga y fletes</b></span>, le otorgamos la certeza que sus <span style="color: #dd0a13"><b>mercancías</b></span> llegarán a tiempo y sin problemas, a su destino.

									El prestigio que nos precede ha sido ganado con <span style="color: #dd0a13"><b>trabajo constante y resultados</b></span> que puede comprobar cuando usted lo requiera. Además, contamos con <span style="color: #dd0a13"><b>unidades</b></span> de primera y <span style="color: #dd0a13"><b>personal</b></span> altamente <span style="color: #dd0a13"><b>calificado</b></span> para atender cualquier <span style="color: #dd0a13"><b>duda o inquietud</b></span>. 

									Sabemos que somos la mejor opción en <span style="color: #dd0a13"><b>transportación, fletes y logística</b></span> y se lo demostraremos en cualquier servicio que contrate. Llámenos, somos <span style="color: #dd0a13"><b>profesionales, eficientes y puntuales</b></span> sin importar el tamaño de la carga o el destino. Contáctenos, con gusto le atenderemos..
								</p>
							</div>

							<div class="col-lg-6">
								<img src="images/servicios/quienes.png" alt="">
							</div>
						</div>
					</div>

				</div>
@stop

@section('scripts')
	<script type="text/javascript">
		window.onload=function () {
	    $('#nosotros').addClass('current');
	  };
	</script>
@stop
