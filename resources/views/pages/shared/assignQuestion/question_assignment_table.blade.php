<div class="title"><strong>Topics with Questions</strong></div>
<div class="table-responsive">
    <form id="topics-questions-form">
        @csrf
        <div class="form-group row">
            <div class="col-lg-3">
                <label class="form-control-label">Test</label>
                <select name="test_id" id="test" class="form-control" required>
                    @foreach ($test_data as $test)
                        <option value="{{ $test->id }}">{{ $test->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label class="form-control-label">Type</label>
                <select name="type" id="type" class="form-control" required>
                    @foreach ($question_type as $type)
                        <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label class="form-control-label">Difficulty</label>
                <select name="difficulty" id="difficulty" class="form-control" required>
                    @foreach ($question_difficulty_level as $difficulty)
                        <option value="{{ $difficulty['id'] }}">{{ $difficulty['name'] }}</option>
                    @endforeach
                </select>
            </div>
            {{-- <div class="col-lg-3">
                <label class="form-control-label">No of questions</label>
                <input type="text" name="question_count" id="question_count" class="form-control" required>
            </div> --}}
            <div class="col-lg-3">
                <label class="form-control-label">No of answers</label>
                <input type="text" name="answer_count" id="answer_count" class="form-control" required>
            </div>
        </div>
        <table class="table table-striped table-sm" style="overflow: hidden;">
            <thead>
                <tr>
                    <th></th>
                    <th><input type="checkbox" id="select-all-topics"><span id="count_topic"></span></th>
                    <th>#</th>
                    <th>Topic</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                @foreach ($topic_data as $topic)
                    <?php $i++; ?>
                    <tr class="parent-row">
                        <td>
                            <span class="toggle-btn " onclick="toggleChildTable(this)">></span>
                        </td>
                        <td><input type="checkbox" class="topic-checkbox parent-checkbox" name="selected_topics[]"
                                value="{{ $topic->id }}" data-parent-id="{{ $topic->id }}"></td>
                        <td>{{ $i }}</td>
                        <td>{{ $topic->name }}</td>
                        <td></td>
                    </tr>
                    <tr class="child-table">
                        <td colspan="5">
                            <table class="table table-striped table-sm" style="overflow: scroll;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><input type="checkbox" class="select-all-questions"
                                                data-parent-id="{{ $topic->id }}"><span id="count_question"></span>
                                        </th>
                                        <th>Question</th>
                                        <th>Explanation</th>
                                        <th>isEnabled</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $j = 0; ?>
                                    @foreach ($question_data as $question)
                                        @if ($question->topic_id == $topic->id)
                                            <?php $j++; ?>
                                            <tr>
                                                <th>{{ $j }}</th>
                                                <td><input type="checkbox" class="question-checkbox child-checkbox"
                                                        name="selected_questions[]" value="{{ $question->id }}"
                                                        data-parent-id="{{ $topic->id }}"></td>
                                                <td>{{ $question->description }}</td>
                                                <td>{{ $question->explanation }}</td>
                                                <td>
                                                    @if ($question->enabled == 1)
                                                        <i class="fas fa-check-circle"
                                                            style="color:rgb(0, 128, 68)"></i>
                                                    @else
                                                        <i class="fas fa-times-circle" style="color:purple"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('edit_answer', $question->id) }}"><i
                                                            class="fas fa-edit" style="color:cyan"></i></a>
                                                    <a href="{{ url('delete_answer', $question->id) }}"
                                                        onclick="confirmDelete(event)">
                                                        <i class="fas fa-trash" style="color:purple"></i></a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="button" id="submit-selection" class="btn btn-primary">Submit</button>
    </form>
</div>
<button id="uncheck-specific" style="display: none;">Uncheck Specific</button>


<table class="table table-striped table-sm" style="overflow: scroll;">
    <thead>
        <tr>
            <th>#</th>
            <th><input type="checkbox" class="select-all-questions"
                    data-parent-id="{{ $topic->id }}"><span id="count_question"></span>
            </th>
            <th>Test</th>
            <th>Assigned Topics</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $j = 0; ?>
        @foreach ($question_data as $question)
            @if ($question->topic_id == $topic->id)
                <?php $j++; ?>
                <tr>
                    <th>{{ $j }}</th>
                    <td><input type="checkbox" class="question-checkbox child-checkbox"
                            name="selected_questions[]" value="{{ $question->id }}"
                            data-parent-id="{{ $topic->id }}"></td>
                    <td>
                        @if ($question->enabled == 1)
                            <i class="fas fa-check-circle"
                                style="color:rgb(0, 128, 68)"></i>
                        @else
                            <i class="fas fa-times-circle" style="color:purple"></i>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('delete_answer', $question->id) }}"
                            onclick="confirmDelete(event)">
                            <i class="fas fa-trash" style="color:purple"></i></a>
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
