<!-- filepath: /c:/Users/shikurm/Documents/laravel/laravel-tailwindcss/resources/views/livewire/modals/question-modal.blade.php -->
<div x-data="{ isOpen: @entangle('isModalOpen') }">
    <!-- Button to open modal -->
    <button @click="isOpen = true" class="mt-2 bg-slate-400 text-white px-2 py-1 rounded"><i
        class="fa fa-plus-circle"></i> New</button>

    <!-- Modal -->
    <div x-show="isOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 w-full h-full"
        @click.away="isOpen = false" style="display: none;">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="bg-white rounded-lg shadow-lg p-6 ml-10 max-w-4xl w-full" x-show.transition.opacity="isOpen">
            <h2 class="text-xl font-semibold mb-4">Create Question</h2>


            {{-- {{$selectedIds}}{{$this->topics}} --}}

            <form wire:submit.prevent="create" class="w-full max-w-4xl h-full">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 max-lg:2xl">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-module">
                            Module
                        </label>
                        <select id="module_id" wire:model="module_id"
                            class="select2 appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white">
                            <option value="">Select Module</option>
                            @foreach ($this->modules as $module)
                                <option value="{{ $module->id }}" class="l1 text-white px-10 bg-red-600">
                                    {{ $module->name }}</option>
                            @endforeach
                        </select>
                        @error('module_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 max-lg:2xl">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="topic_id">
                            Topic
                        </label>
                        <select id="topic_id" wire:model="topic_id"
                            class="select2 appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white">
                            <option value="">Select Topic</option>
                            @foreach ($this->topics as $topic)
                                <option value="{{ $topic['id'] }}"> {{ $topic['name'] }}</option>
                            @endforeach
                        </select>
                        @error('topic_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="type">
                            Question Type
                        </label>
                        <div class="relative">
                            <select wire:model="type"
                                class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="type">
                                <option value="1">Single answer</option>
                                <option value="2">Multiple answers</option>
                                <option value="3">Free answer</option>
                                <option value="4">Ordering answers</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="difficulty">
                            Difficulty
                        </label>
                        <div class="relative">
                            <select wire:model="difficulty"
                                class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="difficulty">
                                <option value="1">Easy</option>
                                <option value="2">Medium</option>
                                <option value="3">Hard</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="position">
                            Position
                        </label>
                        <div class="relative">
                            <select wire:model="position"
                                class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="position">
                                <option value="0"></option>
                                <option value="1">1</option>
                            </select>
                        </div>

                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="timer">
                            Timer
                        </label>
                        <div class="relative">
                            <input wire:model="timer"
                                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="timer" type="number">
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="timer">
                            Question
                        </label>
                        <div class="relative">
                            <textarea wire:model="description"
                                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="description"></textarea>
                        </div>
                        @error('description')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="timer">
                            Explanation
                        </label>
                        <div class="relative">
                            <textarea wire:model="explanation"
                                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="explanation"></textarea>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                        <div class="items-center">
                            <label class="flex items-center cursor-pointer relative">
                                isFullScreen
                                <input type="checkbox" checked wire:model="fullscreen" value="1"
                                    class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-blue-600 checked:border-blue-600" />
                            </label>
                        </div>
                        <div class="items-center">
                            <label class="flex items-center cursor-pointer relative">
                                isInlineAnswer
                                <input type="checkbox" checked wire:model="inline_answers" value="1"
                                    class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-blue-600 checked:border-blue-600" />
                            </label>
                        </div>
                        <div class="items-center">
                            <label class="flex items-center cursor-pointer relative">
                                isAutoNext
                                <input type="checkbox" wire:model="auto_next" value="1" checked
                                    class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-blue-600 checked:border-blue-600" />
                            </label>
                        </div>
                        <div class="items-center">
                            <label class="flex items-center cursor-pointer relative">
                                Enabled
                                <input type="checkbox" checked wire:model="enabled" value="1"
                                    class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-blue-600 checked:border-blue-600" />
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="closeModal" wire:click="closeModal" @click="isOpen = false"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@script()
    {{-- Ensure the script within will be executed once Livewire is fully initialized --}}
    <script>
        document.addEventListener('livewire:load', function() {
            console.log("Loading.....");

            function initializeSelect2() {
                $('#module_id').select2({
                    width: '100%',
                    dropdownAutoWidth: true,
                    placeholder: 'Select ...',
                    allowClear: true,
                    search: true
                }).on('change', function() {
                    let selectedModuleIds = $(this).val();
                    @this.set('module_id', selectedModuleIds);
                });

                $('#topic_id').select2({
                    width: '100%',
                    dropdownAutoWidth: true,
                    placeholder: 'Select ...',
                    allowClear: true,
                    search: true
                }).on('change', function() {
                    let selectedTopicIds = $(this).val();
                    @this.set('topic_id', selectedTopicIds);
                });
            }

            initializeSelect2();

            Livewire.hook('message.processed', (message, component) => {
                $('#module_id').select2('destroy').select2({
                    width: '100%',
                    dropdownAutoWidth: true,
                    placeholder: 'Select ...',
                    allowClear: true,
                    search: true
                }).on('change', function() {
                    let selectedModuleIds = $(this).val();
                    @this.set('module_id', selectedModuleIds);
                });

                $('#topic_id').select2('destroy').select2({
                    width: '100%',
                    dropdownAutoWidth: true,
                    placeholder: 'Select ...',
                    allowClear: true,
                    search: true
                }).on('change', function() {
                    let selectedTopicIds = $(this).val();
                    @this.set('topic_id', selectedTopicIds);
                });
            });
        });
    </script>
@endscript
