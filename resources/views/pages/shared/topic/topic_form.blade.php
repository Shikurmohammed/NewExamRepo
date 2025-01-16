<button type="button" class="btn" data-toggle="modal" data-target="#exampleModalCenter"
    style="outline: none; border:none; ">
    <i class="fas fa-plus-circle" style="color:green"></i> New
</button>
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Topic Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('add_topic') }}" method="post">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Module Name</label>
                            <select name="module_id" id="" class="form-control" required>
                                @foreach ($module_data as $module)
                                    <option value="{{ $module->id }}">{{ $module->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Topic Name</label>
                            <input type="text" name="topic_name" class="form-control" required>
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Description</label>
                            <textarea name="description"  class="form-control ckeditor"></textarea>
                        </div>
                        <div class="col-lg-6">
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
