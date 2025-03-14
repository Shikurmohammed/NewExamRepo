<div id="edit_question_modal" class="relative min-w-full z-1000">

    <div class="p-4 bg-blue-600 rounded-t-lg">
        <h2 class="text-lg font-semibold text-white">Edit Question</h2>
    </div>

    <div class="p-6">
        <form wire:submit.prevent="save">
            <div class="flex flex-wrap mb-6 -mx-3">
                <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0 max-lg:2xl" wire:ignore>
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="edit_topic_id">
                        Topic
                    </label>
                    <select id="edit_topic_id" wire:model.live="topic_id"
                        class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border rounded appearance-none select2 focus:outline-none focus:bg-white">
                        <option value="" disabled>Select Topic</option>
                        @foreach ($this->topics as $topic)
                            <option value="{{ $topic['id'] }}"> {{ $topic['name'] }}</option>
                        @endforeach
                    </select>
                    @error('topic_id')
                        <p class="text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0 max-lg:2xl" wire:ignore>
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="type">
                        Question Type
                    </label>
                    <select wire:model="type" id="edit_question_type"
                        class="block w-full px-4 py-3 pr-8 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500">

                        <option value="" disabled>Select Type</option>
                        <option value="1">Single answer</option>
                        <option value="2">Multiple answers</option>
                        <option value="3">Free answer</option>
                        <option value="4">Ordering answers</option>
                    </select>
                </div>
                <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0 max-lg:2xl" wire:ignore>
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="difficulty">
                        Difficulty
                    </label>
                    <select wire:model="difficulty" id="edit_difficulty"
                        class="block w-full px-4 py-3 pr-8 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                        id="difficulty">
                        <option value="" disabled>Select Difficulty</option>
                        <option value="1">Easy</option>
                        <option value="2">Medium</option>
                        <option value="3">Hard</option>
                    </select>
                </div>
                <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0 ">
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="position">
                        Position
                    </label>
                    <div class="relative">
                        <select wire:model="position"
                            class="block w-full px-4 py-3 pr-8 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                            id="position">
                            <option value="">Select Position</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position }}"> {{ $position }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0 ">
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="timer">
                        Timer
                    </label>
                    <div class="relative">
                        <input wire:model="timer"
                            class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                            id="timer" type="number">
                    </div>
                </div>
                <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0">
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="timer">
                        Question
                    </label>
                    <div class="relative">
                        <textarea wire:model="description"
                            class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                            id="description"></textarea>
                    </div>
                    @error('description')
                        <p class="text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0 ">
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="timer">
                        Explanation
                    </label>
                    <div class="relative">
                        <textarea wire:model="explanation"
                            class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                            id="explanation"></textarea>
                    </div>
                </div>
                <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0 ">
                    <div class="items-center">
                        <label class="relative flex items-center cursor-pointer">
                            isFullScreen
                            <input type="checkbox" checked wire:model="fullscreen" value="1"
                                class="w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer hover:shadow-md border-slate-300 checked:bg-blue-600 checked:border-blue-600" />
                        </label>
                    </div>
                    <div class="items-center">
                        <label class="relative flex items-center cursor-pointer">
                            isInlineAnswer
                            <input type="checkbox" checked wire:model="inline_answers" value="1"
                                class="w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer hover:shadow-md border-slate-300 checked:bg-blue-600 checked:border-blue-600" />
                        </label>
                    </div>
                    <div class="items-center">
                        <label class="relative flex items-center cursor-pointer">
                            isAutoNext
                            <input type="checkbox" wire:model="auto_next" value="1" checked
                                class="w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer hover:shadow-md border-slate-300 checked:bg-blue-600 checked:border-blue-600" />
                        </label>
                    </div>
                    <div class="items-center">
                        <label class="relative flex items-center cursor-pointer">
                            Enabled
                            <input type="checkbox" checked wire:model="enabled" value="1"
                                class="w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer hover:shadow-md border-slate-300 checked:bg-blue-600 checked:border-blue-600" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" wire:click="closeModal"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save Changes
                </button>
            </div>
        </form>
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

    function initializeSelect2(id) {

        $('#edit_topic_id').select2({
            dropdownParent: $('#edit_question_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select module",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('topic_id', data);
        });

        $('#edit_question_type').select2({
            dropdownParent: $('#edit_question_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select type",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('type', data);
        });
        $('#edit_difficulty').select2({
            dropdownParent: $('#edit_question_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select difficulty",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log(data);
            @this.set('difficulty', data);
        });


    }
</script>
@endscript()
