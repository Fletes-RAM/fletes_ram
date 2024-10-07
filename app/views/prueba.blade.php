@extends('dashboard.layouts.dashboard.master')


@section('content')
  @include('notifications')
  <a class="btn btn-success btn-lg" href="{{ URL::route('indexDashboard') }}"> <i class="fas fa-home"></i> Regresar a página principal</a>
@stop
