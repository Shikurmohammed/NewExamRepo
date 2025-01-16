<div class="title"><strong>question List</strong></div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Topic</th>
                                <th>Question</th>
                                <th>Explanation</th>
                                <th>Type</th>
                                <th>Difficulty-Level</th>
                                <th>Position</th>
                                <th>Timer</th>
                                <th>isFullScreen</th>
                                <th>isAutoNext</th>
                                <th>isInlineAnswer</th>
                                <th>isEnabled</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($question_data as $question)
                            <tr>
                                <th scope="row">{{$question->id}}</th>
                                <td>{{$question->topic_id}}</td>
                                <td>{{$question->description}}</td>
                                <td>{{$question->explanation}}</td>
                                <td>{{$question->type}}</td>
                                <td>{{$question->difficulty}}</td>
                                <td>{{$question->position}}</td>
                                <td>{{$question->timer}}</td>
                                <td>{{$question->fullscreen}}</td>
                                <td>{{$question->auto_next}}</td>
                                <td>{{$question->inline_answers}}</td>
                                <td>{{$question->enabled==1?'Yes':'No'}}</td>
                                <td>
                                    <a href="{{url('edit_question', $question->id)}}"><i class="fas fa-edit" style="color:cyan"></i></a>
                                    <a href="{{url('delete_question', $question->id)}}"
                                        onclick="confirmDelete(event)">
                                        <i class="fas fa-trash" style="color:purple"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
