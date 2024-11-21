@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="ratio ratio-4x3">
                    <iframe src="{{ route('laporan.penyerahan.tersendiri', ['route' => $route, 'id' => $id]) }}">
                        This browser does not support PDFs. Please download the PDF to view it: <a href="{{ asset('folder/file_name.pdf') }}">Download PDF</a>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js-content')
    <script type="text/javascript">
        window.addEventListener('DOMContentLoaded', event => {
            // Simple-DataTables
            // https://github.com/fiduswriter/Simple-DataTables/wiki
        });
    </script>
@endsection