@extends('layouts.app')
@section('content_body')
    <div class="container-fluid dx-viewport">
        <div class="row">
            <div class="col-lg-12">
                <div class="block">
                    <div class="block-body">
                        @include('shared.question.question_form')
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                    @include('shared.question.question_table')
                    @include('shared.question.question_details')
            </div>
        </div>
    </div>
    <script src="{{asset('js/ckeditor.js')}}">  </script>
    <script src="{{asset('js/question.js')}}">  </script>
@stop
@extends('shared.footer')
