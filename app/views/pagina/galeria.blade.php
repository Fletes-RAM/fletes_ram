@extends('pagina.master')

@section('content')
	
	<!-- Page Title
		============================================= -->
		<section id="page-title" class="nobg">

			<div class="container clearfix">
				<h1>Galería de Imágenes</h1>
				<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ URL::to('/') }}">Inicio</a></li>
				<li class="breadcrumb-item active" aria-current="page">Galería</li>
			</ol>
			</div>

		</section><!-- #page-title end -->
		
		<div class="row">
		<div class="col-lg-3"> .</div>

		<div class="col-lg-6">
			<div class="postcontent nobottommargin">
				<!-- Portfolio Single Gallery Thumbs
				============================================= -->
				<div class="col_full portfolio-single-image masonry-thumbs grid-4" data-big="4" data-lightbox="gallery">
					<a href="{{ asset('images/galeria/1.jpeg') }}" data-lightbox="gallery-item"><img class="image_fade" src="{{ asset('images/galeria/1.jpeg') }}" alt="Gallery Thumb 1"></a>
					<a href="{{ asset('images/galeria/2.jpeg') }}" data-lightbox="gallery-item"><img class="image_fade" src="{{ asset('images/galeria/2.jpeg') }}" alt="Gallery Thumb 2"></a>
					<a href="{{ asset('images/galeria/3.jpeg') }}" data-lightbox="gallery-item"><img class="image_fade" src="{{ asset('images/galeria/3.jpeg') }}" alt="Gallery Thumb 3"></a>
					<a href="{{ asset('images/galeria/4.jpeg') }}" data-lightbox="gallery-item"><img class="image_fade" src="{{ asset('images/galeria/4.jpeg') }}" alt="Gallery Thumb 4"></a>
					<a href="{{ asset('images/galeria/5.jpeg') }}" data-lightbox="gallery-item"><img class="image_fade" src="{{ asset('images/galeria/5.jpeg') }}" alt="Gallery Thumb 5"></a>
					<a href="{{ asset('images/galeria/6.jpeg') }}" data-lightbox="gallery-item"><img class="image_fade" src="{{ asset('images/galeria/6.jpeg') }}" alt="Gallery Thumb 6"></a>
					<a href="{{ asset('images/galeria/7.jpeg') }}" data-lightbox="gallery-item"><img class="image_fade" src="{{ asset('images/galeria/7.jpeg') }}" alt="Gallery Thumb 7"></a>
					<a href="{{ asset('images/galeria/8.jpeg') }}" data-lightbox="gallery-item"><img class="image_fade" src="{{ asset('images/galeria/8.jpeg') }}" alt="Gallery Thumb 8"></a>
					<a href="{{ asset('images/galeria/9.jpeg') }}" data-lightbox="gallery-item"><img class="image_fade" src="{{ asset('images/galeria/9.jpeg') }}" alt="Gallery Thumb 9"></a>
					<a href="{{ asset('images/galeria/10.jpeg') }}" data-lightbox="gallery-item"><img class="image_fade" src="{{ asset('images/galeria/10.jpeg') }}" alt="Gallery Thumb 9"></a>
					<a href="{{ asset('images/galeria/11.jpeg') }}" data-lightbox="gallery-item"><img class="image_fade" src="{{ asset('images/galeria/11.jpeg') }}" alt="Gallery Thumb 9"></a>
				</div><!-- .portfolio-single-image end -->

			</div>
		</div>

		<div class="col-lg-3"> .</div>
		</div>
@stop

@section('scripts')
	
	<script type="text/javascript">
		window.onload=function () {
	    $('#galeria').addClass('current');
	  };
	</script>

@stop
