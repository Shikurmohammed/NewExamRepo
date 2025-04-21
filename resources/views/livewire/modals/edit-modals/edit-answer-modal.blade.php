<div id="edit_answer_modal" class="relative min-w-full z-1000">
    <div class="p-4 bg-blue-600 rounded-t-lg">
        <h2 class="text-lg font-semibold text-white">Edit Module</h2>
    </div>
    <div class="p-6">
        <form wire:submit.prevent="save">
            <div class="flex flex-wrap mb-6 -mx-3">
                <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0 max-lg:2xl">
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="grid-module">
                        Module
                    </label>
                    <div class="relative" wire:ignore>
                        <select id="edit_module_id" wire:model="module_id"
                            class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border border-red-500 rounded appearance-none select2 focus:outline-none focus:bg-white">
                            <option value="">Select Module</option>
                            @foreach ($this->modules as $module)
                                <option value="{{ $module['id'] }}" class="px-10 text-white bg-red-600 l1">
                                    {{ $module['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('module_id')
                        <p class="text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0 max-lg:2xl">
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="topic_id">
                        Topic
                    </label>
                    <div class="relative" wire:ignore>
                        <select id="edit_topic_id" wire:model="topic_id"
                            class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border border-red-500 rounded appearance-none select2 focus:outline-none focus:bg-white">
                            <option value="">Select Topic</option>
                            @foreach ($this->topics as $topic)
                                <option value="{{ $topic['id'] }}"> {{ $topic['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('topic_id')
                        <p class="text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <br>
                <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0 max-lg:2xl">
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="topic_id">
                        Question
                    </label>
                    <div class="relative" wire:ignore>
                        <select id="edit_question_id" wire:model.live="question_id"
                            class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border border-red-500 rounded appearance-none select2 focus:outline-none focus:bg-white">
                            <option value="">Select Topic</option>
                            @foreach ($this->questions as $question)
                                <option value="{{ $question['id'] }}"> {{ $question['description'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('question_id')
                        <p class="text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full px-3 mb-6 md:w-1/4 md:mb-0 ">
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="position">
                        Position
                    </label>
                    <div class="relative" wire:ignore>
                        <select wire:model="position"
                            class="block w-full px-4 py-3 pr-8 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                            id="edit_position">
                            <option value="">Select Position</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position }}"> {{ $position }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('position')
                        <p class="text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full px-3 mb-6 md:w-1/4 md:mb-0 ">
                    <div class="items-center">
                        <label class="relative flex items-center cursor-pointer">
                            isRight
                            <input type="checkbox" wire:model="is_right" value="0"
                                class="w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer hover:shadow-md border-slate-300 checked:bg-blue-600 checked:border-blue-600" />
                        </label>
                    </div>
                    <div class="items-center">
                        <label class="relative flex items-center cursor-pointer">
                            Enabled
                            <input type="checkbox" wire:model="enabled"
                                class="w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer hover:shadow-md border-slate-300 checked:bg-blue-600 checked:border-blue-600" />
                        </label>
                    </div>
                </div>
                <div class="w-full px-3 mb-6 md:w-full md:mb-0 ">
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="timer">
                        Description
                    </label>
                    <div class="relative">
                        <input wire:model="description"
                            class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                            id="explanation" />

                    </div>
                    @error('description')
                        <p class="text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full px-3 mb-6 md:w-full md:mb-0 ">
                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="timer">
                        Explanation
                    </label>
                    <div class="relative">
                        <textarea wire:model="explanation"
                            class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                            id="explanation"></textarea>
                    </div>
                    @error('explanation')
                        <p class="text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
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
        $('#edit_module_id').select2({
            dropdownParent: $('#edit_answer_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select module",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log("module_id", data);
            @this.set('module_id', data);
        });
        $('#edit_topic_id').select2({
            dropdownParent: $('#edit_answer_modal'),
            width: '100%',
            placeholder: "Select topic",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log("topic_id", data);
            @this.set('topic_id', data);
        });

        $('#edit_question_id').select2({
            dropdownParent: $('#edit_answer_modal'),
            width: '100%',
            placeholder: "Select question",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log("question_id", data);
            @this.set('question_id', data);
        });

        $('#edit_position').select2({
            dropdownParent: $('#edit_answer_modal'),
            width: '100%',
            placeholder: "Select position",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log("position", data);
            @this.set('position', data);
        });
    }
</script>
@endscript()
