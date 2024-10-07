@extends('pagina.master')


@section('slider')
	<section id="google-map" class="gmap"></section>
@stop

@section('content')
	<div class="content-wrap">

					<div class="container clearfix">

						<!-- Postcontent
						============================================= -->
						<div class="postcontent nobottommargin">

							<h3>Envíanos un Correo</h3>

							<div class="contact-widget">

								<div class="contact-form-result"></div>

								<form class="nobottommargin" id="template-contactform" name="template-contactform" action="include/sendemail.php" method="post">

									<div class="form-process"></div>

									<div class="col_one_third">
										<label for="template-contactform-name">Nombre <small>*</small></label>
										<input type="text" id="template-contactform-name" name="template-contactform-name" value="" class="sm-form-control required" />
									</div>

									<div class="col_one_third">
										<label for="template-contactform-email">Correo <small>*</small></label>
										<input type="email" id="template-contactform-email" name="template-contactform-email" value="" class="required email sm-form-control" />
									</div>

									<div class="col_one_third col_last">
										<label for="template-contactform-phone">Teléfono</label>
										<input type="text" id="template-contactform-phone" name="template-contactform-phone" value="" class="sm-form-control" />
									</div>

									<div class="clear"></div>

									<div class="col_full">
										<label for="template-contactform-subject">Asunto <small>*</small></label>
										<input type="text" id="template-contactform-subject" name="template-contactform-subject" value="" class="required sm-form-control" />
									</div>


									<div class="clear"></div>

									<div class="col_full">
										<label for="template-contactform-message">Mensaje <small>*</small></label>
										<textarea class="required sm-form-control" id="template-contactform-message" name="template-contactform-message" rows="6" cols="30"></textarea>
									</div>

									<div class="col_full hidden">
										<input type="text" id="template-contactform-botcheck" name="template-contactform-botcheck" value="" class="sm-form-control" />
									</div>

									<div class="col_full">
										<button class="button button-3d nomargin" type="submit" id="template-contactform-submit" name="template-contactform-submit" value="submit">Enviar Mensaje</button>
									</div>

								</form>
							</div>

						</div><!-- .postcontent end -->

						<!-- Sidebar
						============================================= -->
						<div class="sidebar col_last nobottommargin">

							<div class="widget clearfix">

								<h3 class="nobottommargin uppercase">Tuxtla Gutiérrez</h3><br>

								<address>
									<strong>Oficinas:</strong><br>
									Libramiento Sur Oriente<br>
									Col. Rivera Cerro Hueco<br>
								</address>
								<abbr title="Phone Number"><strong>Teléfono:</strong></abbr> (961) 604-1030<br>
								<abbr title="Fax"><strong>Celular:</strong></abbr> (961) 128-9379<br>
								<abbr title="Email Address"><strong>Correo:</strong></abbr> cotizaciones@fletesram.com

							</div>

							<div class="line line-sm"></div>

							<div class="widget notopmargin clearfix">

								<a href="#" class="social-icon si-small si-dark si-facebook">
									<i class="icon-facebook"></i>
									<i class="icon-facebook"></i>
								</a>

								<a href="#" class="social-icon si-small si-dark si-twitter">
									<i class="icon-twitter"></i>
									<i class="icon-twitter"></i>
								</a>

								<a href="#" class="social-icon si-small si-dark si-gplus">
									<i class="icon-gplus"></i>
									<i class="icon-gplus"></i>
								</a>

							</div>

						</div><!-- .sidebar end -->

					</div>
					
				</div>
@stop

@section('scripts')
	{{ HTML::script('https://maps.google.com/maps/api/js?key=AIzaSyDE6AdZTsvjLE2VLKtVMYiC1uGWPGu9Rn0') }}
	{{ HTML::script('packages/html/js/jquery.gmap.js') }}

	<script>

		$('#google-map').gMap({

			address: 'LIBRAMIENTO SUR ORIENTE 3670, COL. RIVERA CERRO HUECO',
			maptype: 'ROADMAP',
			zoom: 16,
			markers: [
				{
					address: "LIBRAMIENTO SUR ORIENTE 3670, COL. RIVERA CERRO HUECO",
					html: '<div style="width: 330px;"><h4 style="margin-bottom: 8px;">Fletera <span>Grupo RAM</span></h4><p class="nobottommargin"><img src="images/logo1.png" class="alignleft" alt="" style="max-width: 60px !important; margin: 5px 10px 1px 0;" />Grupo Ram es una empresa cuyo propósito es brindarte seguridad y formalidad en el transporte de tu mercancía.</p></div>',
					icon: {
						image: "images/marker.png",
						iconsize: [32, 39],
						iconanchor: [13,39]
					}
				}
			],
			doubleclickzoom: false,
			controls: {
				panControl: true,
				zoomControl: true,
				mapTypeControl: true,
				scaleControl: false,
				streetViewControl: false,
				overviewMapControl: false
			}
		});

	</script>
	<script type="text/javascript">
		window.onload=function () {
	    $('#contacto').addClass('current');
	  };
	</script>
@stop
