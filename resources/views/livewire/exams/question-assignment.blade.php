<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <div class="row">
        <div wire:poll.alive class="col-span-full xl:col-span-8 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <form id="topics-questions-form">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Test</label>
                        <select name="test_id" id="test"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                            @foreach ($this->tests as $test)
                                <option value="{{ $test->id }}">{{ $test->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="type" id="type"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                            @foreach ($question_type as $type)
                                <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Difficulty</label>
                        <select name="difficulty" id="difficulty"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                            @foreach ($question_difficulty_level as $difficulty)
                                <option value="{{ $difficulty['id'] }}">{{ $difficulty['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="col-lg-3">
                    <label class="form-control-label">No of questions</label>
                    <input type="text" name="question_count" id="question_count" class="form-control" required>
                </div> --}}
                    <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">No of answers</label>
                        <input type="text" name="answer_count" id="answer_count"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                </div>
                <div class="flex justify-end w-full md:w-1/3 px-3 mb-6 md:mb-0 max-lg:2xl">
                    <div class="items-center">
                        <button type="submit" id="submit-selection"
                            class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                    </div>
                </div>

            </form>
        </div>
        <div wire:poll.alive class="col-span-full xl:col-span-8 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <header class="flex justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Test List</h2>

                <div class="flex justify-between">
                    <!-- end-->
                    <input type="text" wire:model.live="search" placeholder="search here"
                        class="ml-5 mt-1 block w-1/10 border border-gray-300 rounded-md p-2 mr-0">
                </div>
            </header>
            @include('livewire.exams.tables.question-assignment-table')


            <button id="uncheck-specific" style="display: none;">Uncheck Specific</button>
        </div>
    </div>
</div>
