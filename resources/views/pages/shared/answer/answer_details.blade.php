<div class="title"><strong>Answer List</strong></div>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Answer</th>
                <th>Explanation</th>
                <th>isCorrect</th>
                <th>isEnabled</th>
                <th>Position</th>
                <th>hasKeyboardKey</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($answer_data as $answer)
                <tr>
                    <th scope="row">{{ $answer->id }}</th>
                    <td>{{ $answer->question->description }}</td>
                    <td>{{ $answer->description }}</td>
                    <td>{{ $answer->explanation }}</td>
                    <td>
                        @if($answer->is_right==1)
                        <i class="fas fa-check-circle" style="color:rgb(0, 128, 68)"></i>
                        @else
                        <i class="fas fa-times-circle" style="color:purple"></i>
                        @endif
                    </td>
                    <td>
                        @if ($answer->enabled == 1)
                            <i class="fas fa-check-circle" style="color:rgb(0, 128, 68)"></i>
                        @else
                            <i class="fas fa-times-circle" style="color:purple"></i>
                        @endif
                    </td>
                    <td>{{ $answer->position }}</td>
                    <td>{{ $answer->keyboard_key }}</td>
                    <td>
                        <a href="{{ url('edit_answer', $answer->id) }}"><i class="fas fa-edit" style="color:cyan"></i></a>
                        <a href="{{ url('delete_answern', $answer->id) }}" onclick="confirmDelete(event)">
                            <i class="fas fa-trash" style="color:purple"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
