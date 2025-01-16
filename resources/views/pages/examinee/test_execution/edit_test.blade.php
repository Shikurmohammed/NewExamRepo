{{--
*Docblock
    /**
     * Edit Test Form
     *
     * This form allows users to edit an existing test.
     *
     * Fields:
     * - Test Name: The name of the test.
     * - Start: The start date and time of the test.
     * - End: The end date and time of the test.
     * - Duration: The duration of the test in minutes.
     * - Exam Group: The groups associated with the test.
     * - Description: A detailed description of the test.
     * - Basic points: The points awarded for correct answers.
     * - Pass point: The minimum points required to pass the test.
     * - Point for wrong answer: The points deducted for incorrect answers.
     * - Exam Password: The password required to access the test.
     * - Points for no answer: The points awarded for unanswered questions.
     * - Random questions: Whether the questions should be randomized.
     *
     * @var array $group_data The list of available groups.
     * @var object $test_data The data of the test being edited.
     */
--}}
@extends('layouts.app')
@section('content_body')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <style>
      .select2-container--default .select2-selection--multiple .select2-selection__choice {
            display: inline-block;
            margin: 2px;
            padding: 2px 5px;
            /* background-color: #f4f4f4; */
            border: 1px solid #ddd;

            border-radius: 4px;
        }
        .select2-container--default .select2-results__option {
            padding-left: 20px;
        }
        .select2-container--default .select2-results__option .checkbox {
            margin-right: 10px;
        }
        .select2-results__option {
            display: flex;
            align-items: center;
        }
        .select2-results__option input[type="checkbox"] {
            margin-right: 10px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="block">
                    <h5>Edit Exam here!</h5>
                    <form action="{{ url('update_test', $test_data->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <div class="col-lg-2">
                                <label class="form-control-label">Test Name</label>
                                <input type="text" name="test_name" class="form-control" value="{{ $test_data->name }}">
                            </div>

                            <div class="col-lg-2">
                                <label class="form-control-label">Start</label>
                                <input type="datetime-local" name="start" value="{{$test_data->start}}" class="form-control" required>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-control-label">End</label>
                                <input type="datetime-local" name="end" value="{{$test_data->end}}" class="form-control" required>
                            </div>
                            <div class="col-lg-1">
                                <label class="form-control-label">Duration</label>
                                <input type="number" name="duration" value="{{$test_data->duration}}" class="form-control" required>
                            </div>
                            <div class="col-lg-5">
                                <label class="form-control-label">Exam Group </label>
                                <select name="group_id[]" id="group_id " class="form-control select2" required multiple>
                                    @foreach ($group_data as $group)
                                        <option value="{{ $group->id }}" {{ $group->id == $test_data->group_id ? 'selected': ''}}>{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-8">
                                <label class="form-control-label">Description</label>
                                <textarea name="description" id="" cols="30" rows="10" class="form-control ckeditor">{{$test_data->description}}</textarea>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-control-label">Basic points</label>
                                <input type="text" name="score_right"  value="{{$test_data->score_right}}" class="form-control" >
                            </div>
                            <div class="col-lg-2">
                                <label class="form-control-label">Pass point</label>
                                <input type="text" name="score_threshold" value="{{$test_data->score_threshold}}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label class="form-control-label">Point for wrong answer</label>
                                <input type="text" name="score_wrong" value="{{$test_data->score_wrong}}" class="form-control" required>
                            </div>

                            <div class="col-lg-2">
                                <label class="form-control-label">Exam Password</label>
                                <input type="text" name="exam_password" value="{{$test_data->password}}" class="form-control" >
                            </div>

                            <div class="col-lg-3">
                                <label class="form-control-label">Points for no answer</label>
                                <input type="text" name="score_unanswered" value="{{$test_data->score_unanswered}}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">

                            <div class="col-lg-3">
                                <label class="form-control-label">Random questions</label>
                                <input type="hidden" name="random_questions_select" value="0">
                                <input type="checkbox" name="random_questions_select" value="1" {{$test_data->random_questions_select == 1 ?'checked': ''}}}>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">Random Answers</label>
                                <input type="hidden" name="random_answers_select" value="0">
                                <input type="checkbox" name="random_answers_select" value="1" {{$test_data->random_answers_select == 1 ? 'checked': ''}}>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">Partial score for MCMA</label>
                                <input type="hidden" name="mcma_partial_score" value="0">
                                <input type="checkbox" name="mcma_partial_score" value="1" {{$test_data->mcma_partial_score ==1 ? 'checked': ''}}>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">'no answer' options</label>
                                <input type="hidden" name="noanswer_enabled" value="0">
                                <input type="checkbox" name="noanswer_enabled" value="1" {{$test_data->noanswer_enabled ==1 ? 'checked': ''}}>
                            </div>
                        </div>
                        <div class="form-group row">

                            <div class="col-lg-3">
                                <label class="form-control-label">Exam Comment</label>
                                <input type="hidden" name="comment_enabled" value="0">
                                <input type="checkbox" name="comment_enabled" value="1" {{$test_data->comment_enabled ==1 ? 'checked': ''}}>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">Repeatable</label>
                                <input type="hidden" name="repeatable" value="0">
                                <input type="checkbox" name="repeatable" value="1">
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">results to users</label>
                                <input type="hidden" name="result_to_user" value="0">
                                <input type="checkbox" name="result_to_user" value="1" {{$test_data->result_to_user ==1 ? 'checked': ''}}>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">reports to users</label>
                                <input type="hidden" name="report_to_user" value="0">
                                <input type="checkbox" name="report_to_user" value="1" {{$test_data->report_to_user ==1 ? 'checked': ''}}>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">Logout on time out</label>
                                <input type="hidden" name="logout_on_timeout" value="0">
                                <input type="checkbox" name="logout_on_timeout" value="1" {{$test_data->logout_on_timeout ==1 ? 'checked': ''}}>
                            </div>
                        </div>
                        <div class="form-group row">

                            <div class="col-lg-3">
                                <label class="form-control-label">Questions menu</label>
                                <input type="hidden" name="menu_enabled" value="0">
                                <input type="checkbox" name="menu_enabled" value="1" {{$test_data->menu_enabled ==1 ? 'checked': ''}}>
                            </div>
                        </div>


                        <div class="form-group modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/ckeditor.js') }}"></script>
    <script src="{{ asset('js/test.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script>

    </script>
@stop
@extends('shared.footer')
