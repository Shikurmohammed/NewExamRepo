<div class="title"><strong>Exam List</strong></div>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Exam name</th>
                <th>Description</th>
                <th>Start-time</th>
                <th>End-time</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($test_data as $test)
              <tr >
                    <td>{{$test->id}}</td>
                    <td>{{$test->name}}</td>
                    <td>{{$test->description}}</td>
                    <td>{{$test->start}}</td>
                    <td>{{$test->end}}</td>
                    {{-- <td>{{$test->isLocked ==1 ?'Locked':'Unlocked'}}</td> --}}
                    <td  id="{{$test->id}}">
                        <a href="{{url('edit_test', $test->id)}}"><i class="fas fa-edit" style="color:cyan" title="Edit Test"></i></a>
                        @if($test->isLocked == 0)
                        <a href="{{url('lockTest', $test->id)}}"><i class="fas fa-unlock" style="color:cyan" title="Lock Test"></i></a>
                        @else
                        <a href="{{url('unLockTest', $test->id)}}"><i class="fas fa-lock" style="color:crimson" title="Unlock Test"></i></a>
                        @endif
                        <a
                            onclick="confirmDelete(event)">
                            <i class="fas fa-trash" style="color:purple" title="Delete Test"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div>
    {{$test_data->links()}}
</div>
