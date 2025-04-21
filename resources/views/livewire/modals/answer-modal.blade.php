<div x-data="{ isOpen: @entangle('isModalOpen') }" id="create_answer_modal" class="h-auto">
    <button @click="isOpen = true" class="px-2 py-1 mt-2 text-white rounded bg-slate-400"><i class="fa fa-plus-circle"></i>
        New</button>
    <div x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center w-full h-full bg-black bg-opacity-50"
        @click.away="isOpen = false" style="display: none;">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="w-full max-w-4xl p-6 ml-10 bg-white rounded-lg shadow-lg" x-show.transition.opacity="isOpen">
            <h2 class="mb-4 text-xl font-semibold">Create Answer</h2>
            <form wire:submit.prevent="create" class="w-full h-full max-w-4xl">
                <div class="flex flex-wrap mb-6 -mx-3">
                    <div class="w-full px-3 mb-6 md:w-1/2 md:mb-0 max-lg:2xl">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="grid-module">
                            Module
                        </label>
                        <div class="relative" wire:ignore>
                            <select id="module_id" wire:model="module_id"
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
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="topic_id">
                            Topic
                        </label>
                        <div class="relative" wire:ignore>
                            <select id="topic_id" wire:model="topic_id"
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
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="topic_id">
                            Question
                        </label>
                        <div class="relative" wire:ignore>
                            <select id="question_id" wire:model.live="question_id"
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
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="position">
                            Position
                        </label>
                        <div class="relative" wire:ignore>
                            <select wire:model="position"
                                class="block w-full px-4 py-3 pr-8 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                id="position">
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
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="timer">
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
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="timer">
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
                    <button type="button" id="closeModal" wire:click="closeModal" @click="isOpen = false"
                        class="px-4 py-2 mr-2 text-gray-700 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded">Submit</button>
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

    function initializeSelect2(id) {
        $('#module_id').select2({
            dropdownParent: $('#create_answer_modal'), // Ensure dropdown is attached to the modal
            width: '100%',
            placeholder: "Select module",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log("module_id", data);
            @this.set('module_id', data);
        });
        $('#topic_id').select2({
            dropdownParent: $('#create_answer_modal'),
            width: '100%',
            placeholder: "Select topic",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log("topic_id", data);
            @this.set('topic_id', data);
        });

        $('#question_id').select2({
            dropdownParent: $('#create_answer_modal'),
            width: '100%',
            placeholder: "Select question",
            allowClear: true
        }).on('change', function(e) {
            var data = $(this).val();
            console.log("question_id", data);
            @this.set('question_id', data);
        });

        $('#position').select2({
            dropdownParent: $('#create_answer_modal'),
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
