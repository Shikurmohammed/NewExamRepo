<div class="title"><strong>Answer List</strong></div>
<div class="table-responsive">
    <table class="table table-striped table-sm" style="overflow: hidden;">
        <thead>
            <tr>
                <th></th>
                <th>#</th>
                <th>Question</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0;?>
            @foreach ($questions as $question)
            <?php $i++;?>
            <tr class="parent-row">
                <td>
                    <span class="toggle-btn " onclick="toggleChildTable(this)">></span>
                </td>
                <td>{{ $i }}</td>
                <td>{{ $question->description }}</td>
                <td></td>
            </tr>
            <tr class="child-table">
                <td colspan="5">
                    <table class="table table-striped table-sm" style="overflow: scroll;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Answer</th>
                                <th>Explanation</th>
                                <th>isCorrect</th>
                                <th>isEnabled</th>
                                <th>Position</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <?php $j=0;?>
                                @foreach ($question->Answers as $answer)
                                <?php $j++;?>
                                <tr>
                                    <th>{{ $j }}</th>
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
                                    <td>
                                        <a href="{{ url('edit_answer', $answer->id) }}"><i class="fas fa-edit" style="color:cyan"></i></a>
                                        <a href="{{ url('delete_answer', $answer->id) }}" onclick="confirmDelete(event)">
                                            <i class="fas fa-trash" style="color:purple"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tr>

                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
        </tbody>


    </table>
</div>
