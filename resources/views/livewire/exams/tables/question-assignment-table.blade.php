<div class="p-3">
    <div class="overflow-x-auto">
        <table class="table-auto w-full dark:text-gray-300">
            <thead
                class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-green-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm">
                <tr>
                    <th class="p-2 ">
                        <div class="font-semibold text-left "> <input type="checkbox" id="select-all-topics"
                                class="peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10"><span
                                id="count_topic"></span></div>
                    </th>
                    <th class="p-2 ">
                        <div class="font-semibold text-center">#</div>
                    </th>
                    <th class="p-2 ">
                        <div class="font-semibold text-center">Topic</div>
                    </th>
                    <th class="p-2 ">
                        <div class="font-semibold text-center">isEnabled</div>
                    </th>
                    <th class="p-2 ">
                        <div class="font-semibold text-center">Action</div>
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">
                <?php $i = 0; ?>
                @foreach ($this->topics as $topic)
                    <?php $i++; ?>
                    <tr wire:key={{ $topic->id }} class="parent-row">
                        <td class="p-2 w-1/5">
                            <div class="flex items-center">
                                <div class="text-gray-800 dark:text-gray-100"><span class="toggle-btn "
                                        onclick="toggleChildTable(this)">></span></div>
                            </div>
                        </td>
                        <td class="p-2 w-1/5">
                            <div class="text-center"><input type="checkbox"
                                    class="topic-checkbox parent-checkbox peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10"
                                    name="selected_topics[]" value="{{ $topic->id }}"
                                    data-parent-id="{{ $topic->id }}" />
                            </div>
                        </td>
                        <td class="p-2 w-1/5">
                            <div class="text-center text-green-500">{{ $i }}</div>
                        </td>
                        <td class="p-2 w-1/5">
                            <div class="text-center text-green-500">{{ $topic->name }}</div>
                        </td>
                        <td class="p-2 w-1/5">
                            <div class="text-center">
                                @if ($topic->enabled == 1)
                                    <i class="fas fa-check-circle" style="color:rgb(0, 128, 68)"></i>
                                @else
                                    <i class="fas fa-times-circle" style="color:purple"></i>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr class="child-table">
                        <td colspan="5">
                            <table class="table-auto w-full dark:text-gray-300" style="overflow: scroll;">
                                <thead
                                    class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm">
                                    <tr>
                                        <th class="p-2">#</th>
                                        <th><input type="checkbox"
                                                class="select-all-questions peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10"
                                                data-parent-id="{{ $topic->id }}"><span id="count_question"></span>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-center">Question</div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-center">Explanation</div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-center">isEnabled</div>
                                        </th>
                                        <th class="p-2">
                                            <div class="font-semibold text-center">Action</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">
                                    <?php $j = 0; ?>
                                    @foreach ($this->questions as $question)
                                        @if ($question->topic_id == $topic->id)
                                            <?php $j++; ?>
                                            <tr>
                                                <td class="p-2">{{ $j }}</td>
                                                <td><input type="checkbox"
                                                        class="question-checkbox child-checkbox peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800
                                                     checked:before:bg-slate-400 hover:before:opacity-10
                                                        name="selected_questions[]"
                                                        value="{{ $question->id }}"
                                                        data-parent-id="{{ $topic->id }}"></td>
                                                <td class="p-2">
                                                    <div class="text-center text-green-500">
                                                        {{ $question->description }}</div>
                                                </td>
                                                <td class="p-2">
                                                    <div class="text-center text-green-500">
                                                        {{ $question->explanation }}</div>
                                                </td>
                                                <td class="p-2">
                                                    @if ($question->enabled == 1)
                                                        <i class="fas fa-check-circle"
                                                            style="color:rgb(0, 128, 68)"></i>
                                                    @else
                                                        <i class="fas fa-times-circle" style="color:purple"></i>
                                                    @endif
                                                </td>
                                                <td class="p-2">
                                                    <div class="text-center text-green-500">
                                                        <a href="{{ url('edit_answer', $question->id) }}"><i
                                                                class="fas fa-edit" style="color:cyan"></i></a>
                                                        <a href="{{ url('delete_answer', $question->id) }}"
                                                            onclick="confirmDelete(event)">
                                                            <i class="fas fa-trash" style="color:purple"></i></a>
                                                    </div>
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
            @if (!$this->tests || count($this->tests) == 0)
                <h3 class="flex justify-center font-semibold text-gray-800 dark:text-gray-100">No data
                    is found!
                </h3>
            @endif
        </table>

        <table class="table-auto w-full dark:text-gray-300">
            <thead
                class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm">
                <tr>
                    <th class="p-2">
                        <div class="font-semibold text-left"> <input type="checkbox"
                                class="select-all-questions peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10"
                                data-parent-id="{{ $topic->id }}"><span id="count_question"></span></div>
                    </th>
                    <th class="p-2">
                        <div class="font-semibold text-center">Test</div>
                    </th>
                    <th class="p-2">
                        <div class="font-semibold text-center">Assigned Topics</div>
                    </th>
                    <th class="p-2">
                        <div class="font-semibold text-center">Action</div>
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">
                <?php $j = 0; ?>
                @foreach ($this->questions as $question)
                    @if ($question->topic_id == $topic->id)
                        <?php $j++; ?>
                        <tr wire:key={{ $test->id }}>
                            <td class="p-2">
                                <div class="flex items-center">
                                    <div class="text-gray-800 dark:text-gray-100">{{ $j }}</div>
                                </div>
                            </td>
                            <td class="p-2">
                                <div class="text-center"><input type="checkbox"
                                        class="question-checkbox child-checkbox  peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10"
                                        name="selected_questions[]" value="{{ $question->id }}"
                                        data-parent-id="{{ $topic->id }}" />
                                </div>
                            </td>
                            <td class="p-2">
                                <div class="text-center text-green-500">{{ $test->description }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">
                                    @if ($question->enabled == 1)
                                        <i class="fas fa-check-circle" style="color:rgb(0, 128, 68)"></i>
                                    @else
                                        <i class="fas fa-times-circle" style="color:purple"></i>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
            @if (!$this->tests || count($this->tests) == 0)
                <h3 class="flex justify-center font-semibold text-gray-800 dark:text-gray-100">No data
                    is found!
                </h3>
            @endif
        </table>
        {{ $this->topics->links('vendor.livewire.tailwind') }}
    </div>
</div>
