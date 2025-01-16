@extends('layouts.app')
@section('content_body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ url('update_user') }}" method="post">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">User Name</label>
                            <input type="text" name="user_name" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/ckeditor.js') }}"></script>
@stop
@extends('shared.footer')
