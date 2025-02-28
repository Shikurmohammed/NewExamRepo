<div class="bg-white rounded shadow-sm col-span-full xl:col-span-8 dark:bg-gray-800 ">
    <header class="flex justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <div class="flex justify-between">
            <h2 class="w-auto mr-2 font-semibold text-gray-800 dark:text-gray-100">Question List</h2>
            <span>
                @livewire('modals.question-modal')
            </span>
        </div>
    </header>
    <div class="p-3">
        <!-- Table -->
        <livewire:question-table />
        <div class="overflow-x-auto ">

            {{-- <table id="question_table" class="w-full table-auto dark:text-gray-300">

                <thead
                    class="text-xs text-gray-400 uppercase rounded-sm dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50">
                    <tr>

                        <th class="p-2">
                            <div class="font-semibold text-center">Topic</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Description</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Explanation</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Type</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Difficulty</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Position</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Timer</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">isFullScreen</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">isAutoNext</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">isInlineAnswer</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">isEnabled</div>
                        </th>

                    </tr>
                </thead>

                <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">

                    @foreach ($this->questions as $question)
                        <tr>

                            <td class="p-2">
                                <div class="text-center">{{ $question->topic?->name }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center text-green-500">{{ $question->description }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->explanation }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->type }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->difficulty }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->position }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->timer }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->fullscreen }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->auto_next }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->inline_answers }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->enabled == 1 ? 'Yes' : 'No' }}</div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table> --}}
            {{-- {{ $this->questions->onEachSide(1)->links('vendor.pagination.tailwind', ['scrollTo' => false]) }} --}}
        </div>
    </div>
</div>
<!-- Question Table End-->
