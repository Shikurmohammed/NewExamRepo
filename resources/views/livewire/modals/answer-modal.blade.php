<div x-data="{ isOpen: @entangle('isModalOpen') }">
    <!-- Button to open modal -->
    <button @click="isOpen = true" class="mt-2 bg-slate-400 text-white px-2 py-1 rounded"><i class="fa fa-plus-circle"></i>
        New</button>

    <!-- Modal -->
    <div x-show="isOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 w-full h-full"
        @click.away="isOpen = false" style="display: none;">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="bg-white rounded-lg shadow-lg p-6 ml-10 max-w-4xl w-full" x-show.transition.opacity="isOpen">
            <h2 class="text-xl font-semibold mb-4">Create Answer</h2>
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
                                <option value="{{ $module['id'] }}" class="l1 text-white px-10 bg-red-600">
                                    {{ $module['name'] }}</option>
                            @endforeach
                        </select>
                        @error('module_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 max-lg:2xl" wire:ignore>
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
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 max-lg:2xl" wire:ignore>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="topic_id">
                            Question
                        </label>

                        <select id="question_id" wire:model.live="question_id"
                            class="select2 appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white">
                            <option value="">Select Topic</option>
                            @foreach ($this->questions as $question)
                                <option value="{{ $question['id'] }}"> {{ $question['description'] }}</option>
                            @endforeach
                        </select>
                        @error('question_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
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
                                <option value="">Select Position</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position }}"> {{ $position }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="timer">
                            Description
                        </label>
                        <div class="relative">
                            <input wire:model="description"
                                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="explanation" />
                        </div>
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
                                isRight
                                <input type="checkbox" wire:model="is_right" value="0"
                                    class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-blue-600 checked:border-blue-600" />
                            </label>
                        </div>
                        <div class="items-center">
                            <label class="flex items-center cursor-pointer relative">
                                Enabled
                                <input type="checkbox" wire:model="enabled"
                                    class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border
                                     border-slate-300 checked:bg-blue-600 checked:border-blue-600" />
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
