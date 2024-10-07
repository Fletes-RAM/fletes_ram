@extends('dashboard.layouts.dashboard.master')

@section('content')

  @include('notifications')

  <?php

  	if ($anno == 2018) {
  		$url = 'https://www.seektable.com/public/report/bf3f28efc38c4ba6853b706cf0fd0908';
  	}elseif ($anno == 2019) {
  		$url = 'https://www.seektable.com/public/report/05e170dd70494b4bbfd19134107c0de2';
  	}elseif ($anno == 2020) {
  		$url = 'https://www.seektable.com/public/report/bbbde8c8dd5b4702894092fa4aa4f4ba';
  	}elseif ($anno == 2021) {
  		$url = 'https://www.seektable.com/public/report/aa42a332fc2247bfa5db40e37d7498c6';
	}elseif ($anno == 2022) {
		$url = 'https://www.seektable.com/public/report/114a82d056764af0b0d84b074478eb5d';
  	}elseif ($anno == 2023) {
		$url = 'https://www.seektable.com/public/report/9e02f61c51a746ada981dcf6aeba5aac';
  	}elseif ($anno == 2024) {
		$url = 'https://www.seektable.com/public/report/93d266cf208b4d689b5d4a15e70647ed';
  	}

  ?>

  <iframe border="0" frameborder="0" 
    width="100%"
    height="800"
    src="{{ $url }}"></iframe>
    
@stop