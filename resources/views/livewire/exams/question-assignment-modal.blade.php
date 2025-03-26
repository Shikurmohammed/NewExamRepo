<div x-data="{ isOpen: @entangle('isModalOpen') }" id="assign_question_modal" class="relative">
    <button @click="isOpen = true" class="px-2 py-1 mt-2 text-white rounded bg-slate-400">
        <i class="fa fa-plus-circle"></i> Assign Question
    </button>
    <div x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center w-full h-full bg-black bg-opacity-50"
        @click.away="if (!event.target.closest('.select2-container')) { isOpen = false }" isOpen=false"
        style="display: none;">
        <div class="w-full max-w-4xl p-6 mx-4 bg-white rounded-lg shadow-lg modal-content"
            x-show.transition.opacity="isOpen">
            <h2 class="mb-4 text-xl font-semibold">Assign Question to Test</h2>
            <form wire:submit.prevent="assignQuestion" class="w-full">
                <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-3 lg:grid-cols-3">
                    <div wire:ignore>
                        <label for="question_type"
                            class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase">Type</label>
                        <select id="question_type" wire:model="selectedQuestionType"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                            <option value="">Select Type</option>
                            @foreach ($question_type as $type)
                                <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                        @error('selectedQuestionType')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div wire:ignore>
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="difficulty_level">Difficulty</label>
                        <select id="difficulty_level" wire:model="selectedDifficultyLevel"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                            <option value="">Select Difficulty</option>
                            @foreach ($difficulty_level as $difficulty)
                                <option value="{{ $difficulty['id'] }}">{{ $difficulty['name'] }}</option>
                            @endforeach
                        </select>
                        @error('difficulty_level')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No of answers</label>
                        <input type="number" wire:model="answer_count"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md md:w-1/2">
                        @error('answer_count')
                            <span class="text-xs italic text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-2 lg:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Test</label>
                        <div wire:ignore>
                            <select wire:model="test_id" id="test"
                                class="block w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                                @foreach ($this->tests as $test)
                                    <option value="{{ $test->id }}">{{ $test->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('test_id')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div wire:ignore>
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="topic_id">
                            Topic <i>
                                @if ($this->question_count)
                                    ({{ $this->question_count }}) questions's available
                                @endif
                            </i>
                        </label>
                        <select id="topic_id" wire:model.live="topic_ids" multiple
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                            <option value="">Select Topic</option>
                            @foreach ($this->moduleWithTopics as $module)
                                <option value="#{{ $module->id }}" class="font-bold">{{ $module->name }}</option>
                                @foreach ($module->topics as $topic)
                                    <option value="{{ $topic->id }}" class="ml-8">{{ $topic->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('topic_ids')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-1 lg:grid-cols-1">
                    <div wire:ignore>
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="question_id">Question</label>
                        <select id="question_id" wire:model="question_ids" multiple
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                            <option value="">Select questions</option>
                            @foreach ($this->questions as $question)
                                <option value="{{ $question['id'] }}" selected>{{ $question['description'] }}</option>
                            @endforeach
                        </select>
                        @error('question_id')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" wire:click="closeModal" @click="isOpen = false"
                        class="px-4 py-2 text-gray-700 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@script()
<script>
    $(document).ready(function() {
        initializeSelect2();
    });
    document.addEventListener('livewire:initialize', function() {
        initializeSelect2();
    });

    function initializeSelect2() {
        $('#question_type').select2({
            dropdownParent: $('#assign_question_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select question type",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('selectedQuestionType', data);
        });
        $('#test').select2({
            dropdownParent: $('#assign_question_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select test",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('test_id', data);
        });

        $('#difficulty_level').select2({
            dropdownParent: $('#assign_question_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select difficulty",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('selectedDifficultyLevel', data);
        });
        $('#topic_id').select2({
            dropdownParent: $('#assign_question_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select topic",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('topic_ids', data);
        });
        $('#question_id').select2({
            dropdownParent: $('#assign_question_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select question",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('question_ids', data);
        });


    }
</script>
@endscript()
