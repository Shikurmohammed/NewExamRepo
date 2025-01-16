@extends('layouts.app')
@section('content_body')
    <style>

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #eee;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        .child-table {
            display: none;
        }

        .child-table table {
            margin-left: 16px;
        }

        .child-table th {
            background-color: #eeefff;
        }

        .toggle-btn {
            cursor: pointer;
            color: green;
            width: inherit;
            /* text-decoration: underline; */
        }
    </style>
    <div class="container-fluid dx-viewport">
        <div class="row">
            <div class="col-lg-12">
                <div class="block-body">
                    @include('shared.assignQuestion.question_assignment_form')
                </div>
            </div>
            <div class="col-lg-12">
                <div class="block">
                    @include('shared.assignQuestion.question_assignment_table')
                </div>
            </div>
        </div>
    </div>
    {{-- <script src="{{ asset('js/ckeditor.js') }}"></script>
    <script>
        var getTopicsUrl = "{{ route('get.topics') }}";
        var getQuestionsUrl = "{{ route('get.questions') }}";
    </script>
    <script src="{{ asset('js/answer.js') }}"></script>
  --}}
  <script>
    var question_assignment_url ="{{url('assign_question')}}";
</script>
  <script src="{{ asset('js/toggle_parentchild.js') }}"></script>
  <script src="{{ asset('js/assignQuestion.js') }}"></script>
  <script src="{{ asset('js/checkbox_selection.js') }}"></script>

@stop
@extends('shared.footer')
