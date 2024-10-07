@if (Session::has('errors'))
    <!--
    <div class="callout callout-danger">
    <h4>Errores:</h4>
    @foreach ($errors->all() as $message)
        <i class="fas fa-times"></i> {{ $message }} <br>
    @endforeach
    </div>
    -->
    <!-- Modal -->
    <div class="modal fade" id="myModalErrors" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body text-center">
            <h1 class="modal-title" id="myModalLabel"><b><i class="fas fa-thumbs-down fa-5x"></i></b></h1>
            <br><br>
            @foreach ($errors->all() as $message)
                <h4><i class="fas fa-times"></i> {{ $message }}</h4> <br>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#myModalErrors').modal('show');
            $('#myModalErrors').on('shown', function() {
                $("#txtname").focus();
            })
        });
    </script>
@endif


@if (Session::has('success'))
	<!--<div class="alert alert-success alert-dismissable">
    <i class="fas fa-check"></i>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <b><i class="fas fa-thumbs-up"></i></b> {{ Session::get('success') }}
	</div>-->

    <!-- Modal -->
    <div class="modal fade" id="myModalSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body success-modal-body text-center">
            <h1 class="modal-title" id="myModalLabel"><b><i class="fas fa-thumbs-up fa-5x"></i></b><br> {{ Session::get('success') }}</h1>
          </div>
        </div>
      </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#myModalSuccess').modal('show');
            $('#myModalSuccess').on('shown', function() {
                $("#txtname").focus();
            });
            setTimeout(
              function() {
                $('#myModalSuccess').modal('hide');
              }, 3000);
            });
    </script>
@endif

@if (Session::has('error'))
<!--
	<div class="alert alert-danger alert-dismissable">
    <i class="fas fa-ban"></i>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <b><i class="fas fa-times"></i></b> {{ Session::get('error') }}
	</div>
-->
  <!-- Modal -->
  <div class="modal fade" id="myModalError" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body info-modal-body text-center">
          <h1 class="modal-title" id="myModalLabel"><b><i class="fas fa-exclamation-triangle fa-5x"></i></b><br> {{ Session::get('error') }}</h1>
        </div>
      </div>
    </div>
  </div>
  <script>
  $(document).ready(function() {
    $('#myModalError').modal('show');
    $('#myModalError').on('shown', function() {
        $("#txtname").focus();
    });
    setTimeout(
      function() {
        $('#myModalError').modal('hide');
    }, 3000);
  });
  </script>
@endif

@if (Session::has('info'))
  <!--
    <div class="alert alert-info alert-dismissable">
      <i class="fas fa-check"></i>
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <b><i class="fas fa-info-sign"></i></b> {{ Session::get('info') }}
    </div>
  -->
    <!-- Modal -->
    <div class="modal fade" id="myModalInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body info-modal-body text-center">
            <h1 class="modal-title" id="myModalLabel"><b><i class="fas fa-info-circle fa-5x"></i></b><br> {{ Session::get('info') }}</h1>
          </div>
        </div>
      </div>
    </div>
    <script>
    $(document).ready(function() {
      $('#myModalInfo').modal('show');
      $('#myModalInfo').on('shown', function() {
          $("#txtname").focus();
      });
    });
    </script>
@endif
