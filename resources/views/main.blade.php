@extends('layouts.master')
@section('title',"judul")
@section('content')
	<!-- ======= Hero Section ======= -->
  	<section id="hero">
    	<div class="hero-container">
        <div style="height: 45vh; width: 2px;"></div>
    		<!--<h1 class="d-none d-md-inline-block" style="text-shadow: 2px 4px 8px #000;">Sistem Informasi Retribusi Sampah<br>Kota Tanjungpinang Tahun 2024</h1>-->
    	</div>
  	</section><!-- End Hero -->

  	@include('partials.menu')
@endsection
@section('js-content')
  <script type="text/javascript">
      window.addEventListener('DOMContentLoaded', event => {
        var selIdLomba = 0;
        // Simple-DataTables
        // https://github.com/fiduswriter/Simple-DataTables/wiki
        

        //document.getElementById("id_lomba").addEventListener('change', function(){
        //  alert(this.value)
        //})
    });
  </script>
@endsection