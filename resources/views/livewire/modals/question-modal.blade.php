<!-- filepath: /c:/Users/shikurm/Documents/laravel/laravel-tailwindcss/resources/views/livewire/modals/question-modal.blade.php -->
<div x-data="{ isOpen: @entangle('isModalOpen') }">
    <!-- Button to import questions -->

    <div class="flex items-center justify-between">
        <form wire:submit.prevent="importQuestions" class="flex items-center justify-between ">
            <input type="file" wire:model="file" required class="max-w:10" />
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="bg-gray-500"
                class="px-2 py-1 mt-2 mr-2 text-white rounded bg-slate-400">
                <i class="fa fa-upload"></i>
                Upload<i wire:loading="importQuestions" class="fa fa-spinner fa-spin"></i></button>

            @error('file')
                <span class="text-red-600">{{ $message }}</span>
            @enderror
        </form>
        <!-- Button to open modal -->
        <button @click="isOpen = true" class="px-2 py-1 mt-2 text-white rounded bg-slate-400"><i
                class="fa fa-plus-circle"></i>
            New</button>

    </div>
    <!-- Modal -->
    <div x-show="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center w-full h-full bg-black bg-opacity-50"
        @click.away="isOpen = false" style="display: none;">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="w-full max-w-4xl p-6 ml-10 bg-white rounded-lg shadow-lg" x-show.transition.opacity="isOpen">
            <h2 class="mb-4 text-xl font-semibold">Create Question</h2>
            {{-- {{$selectedIds}}{{$this->topics}} --}}

            <form wire:submit.prevent="create" class="w-full h-full max-w-4xl">
                <div class="flex flex-wrap mb-6 -mx-3">
                    <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0 max-lg:2xl">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="grid-module">
                            Module
                        </label>
                        <select id="module_id" wire:model="module_id"
                            class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border border-red-500 rounded appearance-none select2 focus:outline-none focus:bg-white">
                            <option value="">Select Module</option>
                            @foreach ($this->modules as $module)
                                <option value="{{ $module['id'] }}" class="px-10 text-white bg-red-600 l1">
                                    {{ $module['name'] }}</option>
                            @endforeach
                        </select>
                        @error('module_id')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0 max-lg:2xl" wire:ignore>
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="topic_id">
                            Topic
                        </label>

                        <select id="topic_id" wire:model.live="topic_id"
                            class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border border-red-500 rounded appearance-none select2 focus:outline-none focus:bg-white">
                            <option value="">Select Topic</option>
                            @foreach ($this->topics as $topic)
                                <option value="{{ $topic['id'] }}"> {{ $topic['name'] }}</option>
                            @endforeach
                        </select>
                        @error('topic_id')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0 " wire:ignore>
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="type">
                            Question Type
                        </label>
                        <div class="relative">
                            <select wire:model="type"
                                class="block w-full px-4 py-3 pr-8 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                id="type">
                                <option value="1">Single answer</option>
                                <option value="2">Multiple answers</option>
                                <option value="3">Free answer</option>
                                <option value="4">Ordering answers</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0 ">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="difficulty">
                            Difficulty
                        </label>
                        <div class="relative">
                            <select wire:model="difficulty"
                                class="block w-full px-4 py-3 pr-8 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                id="difficulty">
                                <option value="1">Easy</option>
                                <option value="2">Medium</option>
                                <option value="3">Hard</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0 ">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="position">
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
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="timer">
                            Timer
                        </label>
                        <div class="relative">
                            <input wire:model="timer"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                id="timer" type="number">
                        </div>
                    </div>
                    <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="timer">
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
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="timer">
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
                    <div class="flex justify-end">
                        <button type="button" id="closeModal" wire:click="closeModal" @click="isOpen = false"
                            class="px-4 py-2 mr-2 text-gray-700 bg-gray-300 rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
