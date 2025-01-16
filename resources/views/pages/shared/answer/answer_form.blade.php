<div class="block">
    <div class="title">
        <hr>
    </div>
    <button type="button" class="btn" data-toggle="modal" data-target="#exampleModalCenter"
    style="outline: none; border:none; ">
    <i class="fas fa-plus-circle" style="color:green"></i> New
</button>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Answer Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('add_answer') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label class="form-control-label">Module</label>
                                <select name="module_id" id="module" class="form-control" required>
                                    @foreach ($module_data as $module)
                                        <option value="{{ $module->id }}">{{ $module->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-control-label">Topic Name</label>
                                <select name="topic_id" id="topic" class="form-control" required>
                                    {{-- @foreach ($topic_data as $Topic)
                                <option value="{{$Topic->id}}">{{$Topic->name}}</option>
                                @endforeach --}}
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-control-label">Questions</label>
                                <select name="question_id" id="question" class="form-control" required>
                                    {{-- @foreach ($question_data as $question)
                                <option value="{{$question->id}}">{{$question->description}}</option>
                                @endforeach --}}
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label class="form-control-label">Answer</label>
                                <textarea name="description" id="" class="form-control ckeditor" required></textarea>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-control-label">Explanation</label>
                                <textarea name="explanation" id="" class="form-control ckeditor"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-2">
                                <label class="form-control-label">Position</label>
                                <select name="position" id="position" class="form-control" required>
                                    <option value="0"></option>
                                    <option value="1">1</option>
                                    <option value="{{}}"></option>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="form-control-label">isRight
                                    <input type="hidden" name="is_right" value="0">
                                    <input type="checkbox" name="is_right" value="1"></label>
                                </label>
                            </div>

                            <div class="col-lg-2">
                                <label class="form-control-label">keyboard_key
                                    <input type="hidden" name="keyboard_key" value="0">
                                    <input type="checkbox" name="keyboard_key" value="1" checked></label>
                                </label>
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
