<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ (!empty($siteName)) ? $siteName : "Syntara"}} - {{isset($title) ? $title : '' }}</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        <!--Select 2-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

        <!-- bootstrap 3.0.2 -->

        <link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Lato" rel="stylesheet">
        <link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <!--<link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css" />-->
        <!--<script src="https://use.fontawesome.com/388401cc36.js"></script>-->
        <!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">-->
        <script defer src="https://use.fontawesome.com/releases/v5.2.0/js/all.js" integrity="sha384-4oV5EgaV02iISL2ban6c/RmotsABqE4yZxZLcYMAdG7FAPsyHYAPpywE9PJo+Khy" crossorigin="anonymous"></script>

        <!-- Ionicons -->
        <link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/ionicons.min.css") }}" rel="stylesheet" type="text/css" />
        <!-- DATA TABLES -->
        <link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/datatables/dataTables.bootstrap.css") }}" rel="stylesheet" type="text/css" />
        <!-- Morris chart -->
        <link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/morris/morris.css") }}" rel="stylesheet" type="text/css" />
        <!-- jvectormap -->
        <link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/jvectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet" type="text/css" />
        <!-- fullCalendar -->
        <link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/fullcalendar/fullcalendar.css") }}" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <!--<link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/daterangepicker/daterangepicker-bs3.css") }}" rel="stylesheet" type="text/css" />-->
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <!-- iCheck for checkboxes and radio inputs -->
        <link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/iCheck/all.css") }}" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{ asset("packages/jakubsacha/adminlte/AdminLTE/css/AdminLTE.css") }}" rel="stylesheet" type="text/css" />

        <!-- jakubsacha css fix -->
        <link href="{{ asset("packages/jakubsacha/adminlte/css/AdminLTE.css") }}" rel="stylesheet" type="text/css" />

        <!-- cricle button -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- datetimepicker -->
        <link rel="stylesheet" href="{{ asset('packages/datetimepicker/css/bootstrap-datetimepicker.min.css') }}">

        <!-- Autocomplete UI -->
        <link rel="stylesheet" href="{{ asset('packages/jquery-ui/jquery-ui.css') }}">

        <!-- X Editable -->
        <!--<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>-->
        <link href="{{ asset('css/bootstrap-editable.css') }}" rel="stylesheet"/>


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        @if (!empty($favicon))
        <link rel="icon" {{ !empty($faviconType) ? 'type="$faviconType"' : '' }} href="{{ $favicon }}" />
        @endif


        <!-- jQuery 2.0.2 -->
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>-->
        <script src="{{ asset('js/jquery.min.js') }}"></script>

        <script src="{{ asset('packages/mrjuliuss/syntara/assets/js/dashboard/base.js') }}"></script>

        <!-- Select 2 -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    </head>
    <body class="skin-blue fixed">
        @include(Config::get('syntara::views.header'))

        <div class="wrapper row-offcanvas row-offcanvas-left">
            @include(Config::get('adminlte::views.left'))

            @include(Config::get('adminlte::views.content'))

        </div>

        <!-- jQuery UI 1.10.3 -->
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/jquery-ui-1.10.3.min.js") }}" type="text/javascript"></script>
        <!-- Bootstrap -->
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/bootstrap.min.js") }}" type="text/javascript"></script>
        <!-- Morris.js charts -->
        <script src="{{ asset('js/raphael-min.js') }}"></script>
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/plugins/morris/morris.min.js") }}" type="text/javascript"></script>
        <!-- Sparkline -->
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/plugins/sparkline/jquery.sparkline.min.js") }}" type="text/javascript"></script>
        <!-- jvectormap -->
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js") }}" type="text/javascript"></script>
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js") }}" type="text/javascript"></script>
        <!-- fullCalendar -->
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/plugins/fullcalendar/fullcalendar.min.js") }}" type="text/javascript"></script>
        <!-- jQuery Knob Chart -->
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/plugins/jqueryKnob/jquery.knob.js") }}" type="text/javascript"></script>
        <!-- daterangepicker -->
        <!--<script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/plugins/daterangepicker/daterangepicker.js") }}" type="text/javascript"></script>-->
        <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}" type="text/javascript"></script>
        <!-- CKEdit -->
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/plugins/ckeditor/ckeditor.js") }}" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/plugins/iCheck/icheck.min.js") }}" type="text/javascript"></script>

        <!-- DATA TABES SCRIPT -->
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/plugins/datatables/jquery.dataTables.js") }}" type="text/javascript"></script>
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/plugins/datatables/dataTables.bootstrap.js") }}" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset("packages/jakubsacha/adminlte/AdminLTE/js/AdminLTE/app.js") }}" type="text/javascript"></script>
        <script src="{{ asset("packages/jakubsacha/adminlte/js/app.js") }}" type="text/javascript"></script>

        <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.js"></script>

        <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>

        <script src="{{ asset('js/app.js') }}"></script>

        <!-- DateTimePicker -->
        <script src="{{ asset('packages/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
        <script src="{{ asset('packages/datetimepicker/js/locales/bootstrap-datetimepicker.es.js') }}"></script>

        <!-- jquery-ui -->
        <script src="{{ asset('packages/jquery-ui/jquery-ui.js') }}"></script>

        <!-- X Editable -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

        <script>

            jQuery.validator.setDefaults({
              debug: false,
              success: "valid",
              errorElement: "em",
              errorPlacement: function ( error, element ) {
                // Add the `help-block` class to the error element
                error.addClass( "help-block" );
                if ( element.prop( "type" ) === "checkbox" ) {
                  error.insertAfter( element.parent( "label" ) );
                } else {
                  error.insertAfter( element );
                }
              },
              highlight: function ( element, errorClass, validClass ) {
                $( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
              },
              unhighlight: function (element, errorClass, validClass) {
                $( element ).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
                $(".error").remove();
              }
            });

            $.extend( $.validator.messages, {
              required: "Este campo es obligatorio.",
              remote: "Por favor, rellena este campo.",
              email: "Por favor, escribe una dirección de correo válida.",
              url: "Por favor, escribe una URL válida.",
              date: "Por favor, escribe una fecha válida.",
              dateISO: "Por favor, escribe una fecha (ISO) válida.",
              number: "Por favor, escribe un número válido.",
              digits: "Por favor, escribe sólo dígitos.",
              creditcard: "Por favor, escribe un número de tarjeta válido.",
              equalTo: "Por favor, escribe el mismo valor de nuevo.",
              extension: "Por favor, escribe un valor con una extensión aceptada.",
              maxlength: $.validator.format( "Por favor, no escribas más de {0} caracteres." ),
              minlength: $.validator.format( "Por favor, no escribas menos de {0} caracteres." ),
              rangelength: $.validator.format( "Por favor, escribe un valor entre {0} y {1} caracteres." ),
              range: $.validator.format( "Por favor, escribe un valor entre {0} y {1}." ),
              max: $.validator.format( "Por favor, escribe un valor menor o igual a {0}." ),
              min: $.validator.format( "Por favor, escribe un valor mayor o igual a {0}." ),
              nifES: "Por favor, escribe un NIF válido.",
              nieES: "Por favor, escribe un NIE válido.",
              cifES: "Por favor, escribe un CIF válido."
            } );
        </script>
        @yield('scripts')
        <script type="text/javascript">
          var url = window.location;
          // for sidebar menu entirely but not cover treeview
          $('ul.sidebar-menu a').filter(function() {
              return this.href != url;
          }).parent().removeClass('active');

          // for sidebar menu entirely but not cover treeview
          $('ul.sidebar-menu a').filter(function() {
              return this.href == url;
          }).parent().addClass('active');

          if ( $(window).width() > 768) {
            // for treeview
            $('ul.treeview-menu a').filter(function() {
              return this.href == url;
            }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
          }
        </script>
    </body>
</html>
