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
                <h5 class="modal-title" id="exampleModalLongTitle">Question Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{url('add_question')}}" method="post">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="form-control-label">Module</label>
                            <select name="module_id" id="module" class="form-control" required>
                                @foreach($module_data as $module)
                                <option value="{{$module->id}}">{{$module->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-control-label">Topic Name</label>
                            <select name="topic_id" id="topic" class="form-control" required>
                                {{-- @foreach($topic_data as $Topic)
                                <option value="{{$Topic->id}}">{{$Topic->name}}</option>
                                @endforeach --}}
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label class="form-control-label">Type</label>
                            <select name="type" id="type" class="form-control" required>
                                <!-- <option value="" disabled>Select Question type</option> -->
                                <option value="1">Single answer</option>
                                <option value="2">Multiple answers</option>
                                <option value="3">Free answer</option>
                                <option value="4">Ordering answers</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-control-label">Difficulty</label>
                            <select name="difficulty_level" id="difficulty_level" class="form-control" required>
                                <option value="1">Easy</option>
                                <option value="2">Medium</option>
                                <option value="3">Hard</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">

                        <div class="col-lg-2">
                            <label class="form-control-label">Position</label>
                            <select name="position" id="position" class="form-control" required>
                                <option value="0"></option>
                                <option value="1">1</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-control-label">Timer</label>
                            <input type="number" name="timer" class="form-control" />
                        </div>
                        <div class="col-lg-2">
                            <label class="form-control-label">isFullScreen
                                <input type="hidden" name="isFullScreen" value="0">
                                <input type="checkbox" name="isFullScreen" value="1" checked></label>
                            </label>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-control-label">isInlineAnswer
                                <input type="hidden" name="isInlineAnswer" value="0">
                                <input type="checkbox" name="isInlineAnswer" value="1" checked></label>
                            </label>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-control-label">isAutoNext
                                <input type="hidden" name="isAutoNext" value="0">
                                <input type="checkbox" name="isAutoNext" value="1" checked></label>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Question Name</label>
                            <textarea name="question_name " id="" class="form-control ckeditor" ></textarea>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Explanation</label>
                            <textarea name="explanation " id="" class="form-control ckeditor"></textarea>
                        </div>

                        <div class="col-lg-2">
                            <label class="form-control-label">Enabled
                                <input type="hidden" name="enabled" value="0">
                                <input type="checkbox" name="enabled" value="1" checked></label>
                            </label>
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
