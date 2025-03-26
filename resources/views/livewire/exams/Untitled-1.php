<div x-data="{ isOpen: @entangle('isModalOpen') }" id="create_test_modal" class="relative z-60">
    <button @click="isOpen = true" class="px-2 py-1 mt-2 text-white rounded bg-slate-400"><i class="fa fa-plus-circle"></i>
        New</button>
    <div x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center w-full h-full bg-black bg-opacity-50"
        @click.away="isOpen = false" style="display: none;">
        <div class="modal-overlay" style="background: rgba(0, 0, 0, 0.5);"></div>
        <div class="w-full max-w-4xl p-6 ml-10 bg-white rounded-lg shadow-lg" x-show.transition.opacity="isOpen">
            <h2 class="mb-4 text-xl font-semibold">Create Test</h2>
            <form wire:submit.prevent="create" class="w-full h-full max-w-4xl">
                <div class="flex flex-wrap mb-6 -mx-3">
                    <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Test Name</label>
                        <input type="text" wire:model="test_name"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                        @error('test_name')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full px-3 mb-6 md:w-1/4 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Start</label>
                        <input type="datetime-local" wire:model.live="start"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">

                        @error('test_name')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full px-3 mb-6 md:w-1/4 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">End</label>
                        <input type="datetime-local" wire:model.live="end"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                        @error('test_name')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full px-3 mb-6 md:w-1/6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Duration(min)</label>
                        <input type="number" wire:model="duration"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                        @error('test_name')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap mb-6 -mx-3">
                    <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Exam Group</label>
                        <div wire:ignore class="relative">
                            <select wire:model="group_id" id="group_id"
                                class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border rounded appearance-none select2 focus:outline-none focus:bg-white">
                                <option value="">Select Group</option>
                                @foreach ($this->groups as $group)
                                <option value="{{ $group->id }}" class="px-10 text-white l1">
                                    {{ $group->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('group_id')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="w-full px-3 mb-6 md:w-1/4 md:mb-0 ">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                            for="timer">
                            Description
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

                    <div class="w-full px-3 mb-6 md:w-1/4 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Basic Points</label>
                        <input type="number" wire:model="score_right"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                        @error('score_right')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full px-3 mb-6 md:w-1/6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Points for wrong</label>
                        <input type="number" wire:model="score_wrong"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                        @error('score_wrong')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap mb-6 -mx-3">

                    <div class="w-full px-3 mb-6 md:w-1/4 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Points to pass</label>
                        <input type="number" wire:model="score_threshold"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                        @error('score_threshold')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full px-3 mb-6 md:w-1/6 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Exam password</label>
                        <input type="password" wire:model="exam_password" autocomplete
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                        @error('exam_password')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full px-3 mb-6 md:w-1/4 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Points for no Answer</label>
                        <input type="text" wire:model="score_unanswered"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md">
                        @error('score_unanswered')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full px-3 mb-6 md:w-1/3 md:mb-0 ">
                        <label class="block text-sm font-medium text-gray-700">Order Mode</label>
                        <div wire:ignore class="relative">
                            <select wire:model="questions_order_mode" id="questions_order_mode"
                                class="block w-full px-4 py-3 mb-3 leading-tight text-gray-700 bg-gray-200 border rounded appearance-none select2 focus:outline-none focus:bg-white">
                                <option value="">Select Module</option>
                                @foreach ($this->qordmode as $ok)
                                <option value="{{ $ok['id'] }}" class="px-10 text-white"
                                    @if ($this->questions_order_mode == $ok) selected @endif>
                                    {{ $ok['name'] }}
                                </option>
                                @endforeach
                            </select>

                        </div>
                        @error('questions_order_mode')
                        <span class="w-full px-2 text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap mb-6 -mx-3">

                    <div class="inline-flex items-center">
                        <label class="relative flex items-center p-3 rounded-full cursor-pointer" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="random_questions_select" value="0">
                            <input type="checkbox" wire:model="random_questions_select" value="1" checked
                                class="relative w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer border-slate-300 hover:shadow-md before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="text-sm cursor-pointer text-slate-600" for="ripple-on">
                            Random Questions
                        </label>
                    </div>

                    <div class="inline-flex items-center">
                        <label class="relative flex items-center p-3 rounded-full cursor-pointer" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="random_answers_select" value="0">
                            <input type="checkbox" wire:model="random_answers_select" value="1" checked
                                class="relative w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer border-slate-300 hover:shadow-md before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="text-sm cursor-pointer text-slate-600" for="ripple-on">
                            Random Answer
                        </label>
                    </div>
                    <div class="inline-flex items-center">
                        <label class="relative flex items-center p-3 rounded-full cursor-pointer" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="mcma_partial_score" value="0">
                            <input type="checkbox" wire:model="mcma_partial_score" value="1" checked
                                class="relative w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer border-slate-300 hover:shadow-md before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="text-sm cursor-pointer text-slate-600" for="ripple-on">
                            Partial Score for MCMA
                        </label>
                    </div>

                    <div class="inline-flex items-center">
                        <label class="relative flex items-center p-3 rounded-full cursor-pointer" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="noanswer_enabled" value="0">
                            <input type="checkbox" wire:model="noanswer_enabled" value="1" checked
                                class="relative w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer border-slate-300 hover:shadow-md before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="text-sm cursor-pointer text-slate-600" for="ripple-on">
                            No Answer Option
                        </label>
                    </div>


                    <div class="inline-flex items-center">
                        <label class="relative flex items-center p-3 rounded-full cursor-pointer" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="comment_enabled" value="0">
                            <input type="checkbox" wire:model="comment_enabled" value="1" checked
                                class="relative w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer border-slate-300 hover:shadow-md before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="text-sm cursor-pointer text-slate-600" for="ripple-on">
                            Exam Comment
                        </label>
                    </div>

                    <div class="inline-flex items-center">
                        <label class="relative flex items-center p-3 rounded-full cursor-pointer" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="repeatable" value="0">
                            <input type="checkbox" wire:model="repeatable" value="1" checked
                                class="relative w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer border-slate-300 hover:shadow-md before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="text-sm cursor-pointer text-slate-600" for="ripple-on">
                            Repeatable
                        </label>
                    </div>
                    <div class="inline-flex items-center">
                        <label class="relative flex items-center p-3 rounded-full cursor-pointer" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="result_to_user" value="0">
                            <input type="checkbox" wire:model="result_to_user" value="1" checked
                                class="relative w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer border-slate-300 hover:shadow-md before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="text-sm cursor-pointer text-slate-600" for="ripple-on">
                            Results to users
                        </label>
                    </div>

                    <div class="inline-flex items-center">
                        <label class="relative flex items-center p-3 rounded-full cursor-pointer" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" wire:model="logout_on_timeout" value="0">
                            <input type="checkbox" wire:model="logout_on_timeout" value="1" checked
                                class="relative w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer border-slate-300 hover:shadow-md before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="text-sm cursor-pointer text-slate-600" for="ripple-on">
                            Logout on time out
                        </label>
                    </div>

                    <div class="inline-flex items-center">
                        <label class="relative flex items-center p-3 rounded-full cursor-pointer" for="ripple-on"
                            data-ripple-dark="true">
                            <input type="hidden" name="menu_enabled" value="0">
                            <input id="menu_enabled" type="checkbox" wire:model="menu_enabled" value="1" checked
                                class="relative w-5 h-5 transition-all border rounded shadow appearance-none cursor-pointer peer border-slate-300 hover:shadow-md before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:border-slate-800 checked:bg-slate-800 checked:before:bg-slate-400 hover:before:opacity-10" />
                        </label>
                        <label class="text-sm cursor-pointer text-slate-600" for="ripple-on">
                            Questions menu
                        </label>
                    </div>

                </div>
                <div class="flex justify-end w-full px-3 mb-6 md:w-1/3 md:mb-0 max-lg:2xl">
                    <div class="items-center">
                        <button type="button" wire:click="closeModal" @click="isOpen = false"
                            class="px-4 py-2 mr-2 text-gray-700 bg-gray-300 rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>