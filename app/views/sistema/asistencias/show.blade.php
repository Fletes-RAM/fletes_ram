@extends('dashboard.layouts.dashboard.master')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.css" integrity="sha512-BDStKWno6Ga+5cOFT9BUnl9erQFzfj+Qmr5MDnuGqTQ/QYDO1LPdonnF6V6lBO6JI13wg29/XmPsufxmCJ8TvQ==" crossorigin="anonymous" />

    <div class="box box-primary">
        <div class="box-header" data-toggle="tooltip" title="Header tooltip">
            <h3 class="box-title">Reporte de Asistencia</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary btn-xs" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div id="output" style="margin: 10px;"></div>
        </div><!-- /.box-body -->
        <div class="box-footer">

        </div><!-- /.box-footer-->
    </div><!-- /.box -->


@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/6.7.0/d3.min.js" integrity="sha512-cd6CHE+XWDQ33ElJqsi0MdNte3S+bQY819f7p3NUHgwQQLXSKjE4cPZTeGNI+vaxZynk1wVU3hoHmow3m089wA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js" integrity="sha512-+IpCthlNahOuERYUSnKFjzjdKXIbJ/7Dd6xvUp+7bEw0Jp2dg6tluyxLs+zq9BMzZgrLv8886T4cBSqnKiVgUw==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/plotly.js/1.58.4/plotly.min.js" integrity="sha512-odxyOOOwpEgYQnS+TzF/P33O+DfGNGqyh89pJ/u2addhMw9ZIef3M8aw/otYSgsPxLdZi3HQhlI9IiX3H5SxpA==" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.js" integrity="sha512-XgJh9jgd6gAHu9PcRBBAp0Hda8Tg87zi09Q2639t0tQpFFQhGpeCgaiEFji36Ozijjx9agZxB0w53edOFGCQ0g==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.es.min.js" integrity="sha512-ezEFV7GUvz7daEgoER1HmT4xTwdU45cvKyzfN8Vjmfgvypz0fF16x7wYyHsWCr0XFe2LbWZbD1j6iejnRpiZ9w==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/c3_renderers.min.js" integrity="sha512-wSdS9YnP8QnEEIfeVciZszSmTzVK6YnF0T3r5HA/nATycWh4j2R0qKc1+KcithoAI5YBHuA5A4+wg3kVI9zKGw==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/d3_renderers.min.js" integrity="sha512-qxm3as02fhBV1Z8J8VjE5jQDm/xqF4kuQZRYgK2XeolnGiZFLAXX3XCUp+VdiPv7cX6sv83p6Mht0vXrHMEX+w==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/export_renderers.min.js" integrity="sha512-p5LbrvUKLNYfB4NnF9AUhdzcr2VaLfWxZ65rU8/P1VM06XvwEGNfU9gaXPiJGQh1NCHzzbhpcjIRLiFE8GSnCA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/gchart_renderers.min.js" integrity="sha512-vaxYskjGjzYDOchWwEL61xmKVPTn87M1r5LFZAcdUOz6jbliLLa5fDX0M8g0YrGNJ7NEdFDbJ4ebT5Ou/4SBIw==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/plotly_renderers.min.js" integrity="sha512-nGY6wbyP3gWxAjsZwsjWahe9nKLCTTyTLn1dpwuNHb9CKLjogHAMVIbbr4wNYL0dKOsWTCrlpx9RDY+bB1MFrQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/tips_data.min.js" integrity="sha512-W9Vz3oLAFsZyrLQP8P11bk8dFJRnBC7HTl+j8HLCvpixAej2zsb8/DqHwJh1cmK4MKzS3fm93qc1tPLSX+vREA==" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.js"></script>

    <script type="text/javascript">
        $(function(){
            google.load("visualization", "1", {packages:["corechart", "charteditor"]});
            $("#output").pivotUI(
                [
                        @foreach ($asistencias as $asistencia)
                    {
                        "Empleado": "{{ $asistencia->user->last_name }} {{ $asistencia->user->first_name }}",
                        "Fecha": "{{ $asistencia->asistencia }}",
                        "Tipo": "{{ $asistencia->tipo }}",
                    },
                    @endforeach
                ],
                {
                    renderers: $.extend(
                        $.pivotUtilities.renderers,
                        $.pivotUtilities.d3_renderers,
                        $.pivotUtilities.c3_renderers,
                        $.pivotUtilities.gchart_renderers,
                        $.pivotUtilities.export_renderers,
                        $.pivotUtilities.plotly_renderers,
                        $.pivotUtilities.d3_renderers,
                    ),
                    rows: ["Empleado","Tipo"],
                    cols: ["Fecha"],
                },false,'es'
            );
        });
    </script>

@endsection