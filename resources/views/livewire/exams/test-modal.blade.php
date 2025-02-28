<div x-data="{ isOpen: @entangle('isModalOpen') }">
    <!-- Button to open modal -->

    <button @click="isOpen = true" class="mt-2 bg-slate-400 text-white px-2 py-1 rounded"><i class="fa fa-plus-circle"></i>
        New</button>

    <!-- Modal -->
    <div x-show="isOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 w-full h-full"
        @click.away="isOpen = false" style="display: none;">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="bg-white rounded-lg shadow-lg p-6 ml-10 max-w-4xl w-full" x-show.transition.opacity="isOpen">
            <h2 class="text-xl font-semibold mb-4">Create Test</h2>
            <form wire:submit.prevent="create" class="w-full max-w-4xl h-full">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Test Name</label>
                        <input type="text" wire:model="test_name"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        @error('test_name')
                            <span class="px-2 w-full text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Start</label>
                        <input type="datetime-local" wire:model.live="start"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">

                        @error('test_name')
                            <span class="px-2 w-full text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">End</label>
                        <input type="datetime-local" wire:model.live="end"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        @error('test_name')
                            <span class="px-2 w-full text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Duration(min)</label>
                        <input type="number" wire:model="duration"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        @error('test_name')
                            <span class="px-2 w-full text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Exam Group</label>
                        <select  wire:model="group_id"
                            class="select2 appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white">
                            <option value="">Select Group</option>
                            @foreach ($this->groups as $group)
                                <option value="{{ $group->id }}" class="l1 text-white px-10" >
                                    {{ $group->name }}</option>
                            @endforeach
                        </select>
                        @error('group_id')
                            <span class="px-2 w-full text-red-700">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0 ">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="timer">
                            Description
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

                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Basic Points</label>
                        <input type="number" wire:model="score_right"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        @error('score_right')
                            <span class="px-2 w-full text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Points for wrong</label>
                        <input type="number" wire:model="score_wrong"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        @error('score_wrong')
                            <span class="px-2 w-full text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">

                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Points to pass</label>
                        <input type="number" wire:model="score_threshold"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        @error('score_threshold')
                            <span class="px-2 w-full text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Exam password</label>
                        <input type="password" wire:model="exam_password" autocomplete
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        @error('exam_password')
                            <span class="px-2 w-full text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Points for no Answer</label>
                        <input type="text" wire:model="score_unanswered"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        @error('score_unanswered')
                            <span class="px-2 w-full text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Order Mode</label>
                        <select wire:model="questions_order_mode"
                            class="select2 appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white">
                            <option value="">Select Module</option>
                            @foreach ($this->qordmode as $ok)
                                <option value="{{ $ok['id'] }}" class="l1 text-white px-10"
                                    @if ($this->questions_order_mode == $ok) selected @endif>
                                    {{ $ok['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('questions_order_mode')
                            <span class="px-2 w-full text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">

                    <div class="inline-flex items-center">
                        <label class="relative flex cursor-pointer items-center rounded-full p-3" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="random_questions_select" value="0">
                            <input type="checkbox" wire:model="random_questions_select" value="1" checked
                                class="peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="cursor-pointer text-slate-600 text-sm" for="ripple-on">
                            Random Questions
                        </label>
                    </div>

                    <div class="inline-flex items-center">
                        <label class="relative flex cursor-pointer items-center rounded-full p-3" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="random_answers_select" value="0">
                            <input type="checkbox" wire:model="random_answers_select" value="1" checked
                                class="peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="cursor-pointer text-slate-600 text-sm" for="ripple-on">
                            Random Answer
                        </label>
                    </div>
                    <div class="inline-flex items-center">
                        <label class="relative flex cursor-pointer items-center rounded-full p-3" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="mcma_partial_score" value="0">
                            <input type="checkbox" wire:model="mcma_partial_score" value="1" checked
                                class="peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="cursor-pointer text-slate-600 text-sm" for="ripple-on">
                            Partial Score for MCMA
                        </label>
                    </div>

                    <div class="inline-flex items-center">
                        <label class="relative flex cursor-pointer items-center rounded-full p-3" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="noanswer_enabled" value="0">
                            <input type="checkbox" wire:model="noanswer_enabled" value="1" checked
                                class="peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="cursor-pointer text-slate-600 text-sm" for="ripple-on">
                            No Answer Option
                        </label>
                    </div>


                    <div class="inline-flex items-center">
                        <label class="relative flex cursor-pointer items-center rounded-full p-3" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="comment_enabled" value="0">
                            <input type="checkbox" wire:model="comment_enabled" value="1" checked
                                class="peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="cursor-pointer text-slate-600 text-sm" for="ripple-on">
                            Exam Comment
                        </label>
                    </div>

                    <div class="inline-flex items-center">
                        <label class="relative flex cursor-pointer items-center rounded-full p-3" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="repeatable" value="0">
                            <input type="checkbox" wire:model="repeatable" value="1" checked
                                class="peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="cursor-pointer text-slate-600 text-sm" for="ripple-on">
                            Repeatable
                        </label>
                    </div>
                    <div class="inline-flex items-center">
                        <label class="relative flex cursor-pointer items-center rounded-full p-3" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="result_to_user" value="0">
                            <input type="checkbox" wire:model="result_to_user" value="1" checked
                                class="peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="cursor-pointer text-slate-600 text-sm" for="ripple-on">
                            Results to users
                        </label>
                    </div>

                    <div class="inline-flex items-center">
                        <label class="relative flex cursor-pointer items-center rounded-full p-3" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="logout_on_timeout" value="0">
                            <input type="checkbox" wire:model="logout_on_timeout" value="1" checked
                                class="peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="cursor-pointer text-slate-600 text-sm" for="ripple-on">
                            Logout on time out
                        </label>
                    </div>

                    <div class="inline-flex items-center">
                        <label class="relative flex cursor-pointer items-center rounded-full p-3" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" name="menu_enabled" value="0">
                            <input id="menu_enabled" type="checkbox"wire:model="menu_enabled" value="1" checked
                                class="peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="cursor-pointer text-slate-600 text-sm" for="ripple-on">
                            Questions menu
                        </label>
                    </div>

                </div>
                <div class="flex justify-end w-full md:w-1/3 px-3 mb-6 md:mb-0 max-lg:2xl">
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
