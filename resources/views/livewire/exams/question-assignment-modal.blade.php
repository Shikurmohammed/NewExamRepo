<div x-data="{ isOpen: @entangle('isModalOpen') }">
    <button @click="isOpen = true" class="mt-2 bg-slate-400 text-white px-2 py-1 rounded"><i class="fa fa-plus-circle"></i>
        Assign Question</button>
    <div x-show="isOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 w-full h-full"
        @click.away="isOpen = false" style="display: none;">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="bg-white rounded-lg shadow-lg p-6 ml-10 max-w-4xl w-full" x-show.transition.opacity="isOpen">
            <h2 class="text-xl font-semibold mb-4">Assign Question to Test</h2>
            <form wire:submit.prevent="assignQuestion" class="w-full max-w-4xl h-full">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-2 max-lg:2xl">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="topic_id">
                            Type
                        </label>
                        <select id="type" wire:model="selectedQuestionType"
                        class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select Type</option>
                            @foreach ($this->question_type as $type)
                                <option value="{{ $type['id'] }}"> {{ $type['name'] }}</option>
                            @endforeach
                        </select>
                        @error('question_type')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Test</label>
                        <select wire:model="test_id" id="test"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                            @foreach ($this->tests as $test)
                                <option value="{{ $test->id }}">{{ $test->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-2 max-lg:2xl">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="topic_id">
                            Diffculty
                        </label>
                        <select id="difficulty_level" wire:model="selectedDifficultyLevel"
                        class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select diffculty</option>
                            @foreach ($this->difficulty_level as $diffculty)
                                <option value="{{ $diffculty['id'] }}"> {{ $diffculty['name'] }}</option>
                            @endforeach
                        </select>
                        @error('difficulty_level')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-2 max-lg:2xl">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="topic_id">
                            Topic
                        </label>
                        <select id="topic_id" wire:model.live="topic_ids"
                        class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select Topic</option>
                            @foreach ($this->topics as $topic)
                                <option value="{{ $topic['id'] }}"> {{ $topic['name'] }}</option>
                            @endforeach
                        </select>

                        @error('topic_ids')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-2 max-lg:2xl">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="question_id">
                            Question
                        </label>
                        <select id="question_id" wire:model="question_ids" multiple
                        class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select questions</option>
                            @foreach ($this->questions as $question)
                                <option value="{{ $question['id'] }}" selected> {{ $question['description'] }}</option>
                            @endforeach
                        </select>
                        @error('question_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">No of answers</label>
                        <input type="number" wire:model="answer_count"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        @error('answer_count')
                            <span class="px-2 w-full text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-end w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                    <div class="items-center">
                        <button type="button" wire:click="closeModal" @click="isOpen = false"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
