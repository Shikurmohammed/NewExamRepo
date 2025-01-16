<div class="block">
    <button type="button" class="btn" data-toggle="modal" data-target="#exampleModalCenter"
        style="outline: none; border:none; ">
        <i class="fas fa-plus-circle" style="color:green"></i> New
    </button>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Test Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('add_test') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-2">
                                <label class="form-control-label">Test Name</label>
                                <input type="text" name="test_name" class="form-control" required>
                            </div>

                            <div class="col-lg-3">
                                <label class="form-control-label">Start</label>
                                <input type="datetime-local" name="start" class="form-control" required>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">End</label>
                                <input type="datetime-local" name="end" class="form-control" required>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-control-label">Duration</label>
                                <input type="number" name="duration" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-5">
                                <label class="form-control-label">From </label>
                                <select name="group_id[]" id="group_id " class="form-control group_id" required multiple>
                                    @foreach ($group_data as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-7">
                                <label class="form-control-label">Description</label>
                                <textarea name="description" id="" cols="30" rows="10" class="form-control ckeditor"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-2">
                                <label class="form-control-label">Basic points</label>
                                <input type="text" name="score_right" class="form-control" required>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">Point for wrong answer</label>
                                <input type="text" name="score_wrong" class="form-control" required>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-control-label">Pass point</label>
                                <input type="text" name="score_threshold" class="form-control" required>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-control-label">Exam Password</label>
                                <input type="text" name="exam_password" class="form-control" required>
                            </div>

                            {{-- <div class="col-lg-2">
                                <label class="form-control-label">Authorized IP's</label>
                                <input type="text" name="ip_range" class="form-control" required>
                            </div> --}}
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label class="form-control-label">Points for no answer</label>
                                <input type="text" name="score_unanswered" class="form-control" required>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">Random questions</label>
                                <input type="hidden" name="random_questions_select" value="0">
                                <input type="checkbox" name="random_questions_select" value="1" checked>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">Random Answers</label>
                                <input type="hidden" name="random_answers_select" value="0">
                                <input type="checkbox" name="random_answers_select" value="1" checked>
                            </div>
                            {{-- <div class="col-lg-3">
                                <label class="form-control-label">Radio button for MCMA</label>
                                <input type="checkbox" name="random_question">
                            </div> --}}

                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label class="form-control-label">Partial score for MCMA</label>
                                <input type="hidden" name="mcma_partial_score" value="0">
                                <input type="checkbox" name="mcma_partial_score" value="1" checked>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">'no answer' options</label>
                                <input type="hidden" name="noanswer_enabled" value="0">
                                <input type="checkbox" name="noanswer_enabled" value="1" checked>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">Exam Comment</label>
                                <input type="hidden" name="comment_enabled" value="0">
                                <input type="checkbox" name="comment_enabled" value="1" checked>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">Repeatable</label>
                                <input type="hidden" name="repeatable" value="0">
                                <input type="checkbox" name="repeatable" value="1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label class="form-control-label">results to users</label>
                                <input type="hidden" name="result_to_user" value="0">
                                <input type="checkbox" name="result_to_user" value="1">
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">reports to users</label>
                                <input type="hidden" name="report_to_user" value="0">
                                <input type="checkbox" name="report_to_user" value="1">
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">Logout on time out</label>
                                <input type="hidden" name="logout_on_timeout" value="0">
                                <input type="checkbox" name="logout_on_timeout" value="1">
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">Questions menu</label>
                                <input type="hidden" name="menu_enabled" value="0">
                                <input type="checkbox" name="menu_enabled" value="1">
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
</div>
