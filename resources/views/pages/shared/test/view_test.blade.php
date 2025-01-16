@extends('layouts.app')
@section('content_body')
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
    <div class="container-fluid dx-viewport">
        <div class="row">
            <div class="col-lg-12">
                <div class="block">
                    <div class="block-body">
                        @include('shared.test.test_form')
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                @include('shared.test.test_table')
                {{-- @include('shared.test.test_details') --}}
            </div>
        </div>
    </div>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/ckeditor.js') }}"></script>
    <script src="{{ asset('js/test.js') }}"></script>
    {{-- <script src="{{ asset('js/select2.js') }}"></script> --}}
    <script>
        $('.group_id').select2();
    </script>
@stop
@extends('shared.footer')
