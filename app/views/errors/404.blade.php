<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="eng" lang="eng">
<head>
	<title>Error 404!</title>
	<link href='http://fonts.googleapis.com/css?family=Aldrich' rel='stylesheet' type='text/css'>
	{{ HTML::style('css/404.css') }}
	{{ HTML::script(asset('js/jquery-1.7.2-min.js')) }}
	{{ HTML::script(asset('js/jquery-spritely-0.6.1.js')) }}
</head>
<body>
<div id="container">
	<div id="step" class="step">
		<div id="bg" class="step"></div>
		<div id="404" class="step">
			<div id="title">404!</div>
			<div id="error">La URL Solicitada <br/> No Se Encontró En Este Servidor.</div>
		</div>
			<div id="copyright" ><a href="/"/>IR AL INICIO</a>.</div>
	</div>
</div>
</body>
<head>
	<script type="text/javascript">
		(function($) {
			$(document).ready(function() {			
				$('#404')
					.sprite({fps: 70, no_of_frames: 1})
					.spRandom({top: 30, bottom: 200, left: 30, right: 200})
			});
		})(jQuery);	
	</script>
</head>
</html>
