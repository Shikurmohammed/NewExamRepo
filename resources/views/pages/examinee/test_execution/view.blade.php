@extends('layouts.app')
@section('content_body')
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
    <div class="container-fluid dx-viewport">
        <div class="row">
            <div class="col-lg-12">
                <div class="block">
                    <div class="block-body">
                        @include('pages.shared.test.test_form')
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                @include('pages.shared.test.test_table')
            </div>
        </div>
    </div>

@stop
@extends('shared.footer')
