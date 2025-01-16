@extends('layouts.app')
@section('content_body')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <form action="{{ url('update_group', $group->id) }}" method="post">
                @csrf
               @method('PUT')
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="form-control-label">Group Name</label>
                        <input type="text" name="group_name" value="{{$group->name}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Clear</button>
                    <input type="submit" value="UPDATE" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@extends('shared.footer')
