<div class="block"><button type="button" class="btn" data-toggle="modal" data-target="#exampleModalCenter"
        style="outline: none; border:none; ">
        <i class="fas fa-plus-circle" style="color:green"></i> New
    </button>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog  modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">User Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('add_user') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="form-control-label">User Name</label>
                                <input type="text" name="user_name" class="form-control" required>
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
