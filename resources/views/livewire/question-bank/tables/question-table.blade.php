<div class="col-span-full xl:col-span-8 bg-white dark:bg-gray-800 shadow-sm rounded ">
    <header class="flex justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Question List</h2>

        <div class="flex justify-between">
            <!-- include topic-modal -->
            @livewire('modals.question-modal')
            <!-- end-->
            <input type="text" wire:model.live="search" placeholder="search here"
                class="ml-5 mt-1 block w-1/10 border border-gray-300 rounded-md p-2 mr-0">
        </div>
    </header>
    <div class="p-3">
        <!-- Table -->

        <div class="overflow-x-auto ">
            <table class="table-auto w-full dark:text-gray-300">
                <!-- Table header -->
                <thead
                    class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm">
                    <tr>
                        <th class="p-2">
                            <div class="font-semibold text-left">#</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Topic</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Question</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Explanation</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Type</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Difficulty</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Position</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Timer</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">isFullScreen</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">isAutoNext</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">isInlineAnswer</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">isEnabled</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Action</div>
                        </th>
                    </tr>
                </thead>
                <!-- Table body -->
                <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">
                    <!-- Row -->
                    @foreach ($this->questions as $question)
                        <tr wire:key={{ $question->id }}>
                            <td class="p-2">
                                <div class="flex items-center">
                                    <div class="text-gray-800 dark:text-gray-100">{{ $question->id }}</div>
                                </div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->topic_id }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center  text-green-500">{{ $question->description }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->explanation }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->type }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->difficulty }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->position }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->timer }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->fullscreen }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->auto_next }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->inline_answers }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $question->enabled == 1 ? 'Yes' : 'No' }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center text-sky-500">
                                    <a wire:click.prevent ="edit({{ $question->id }})"><i
                                            class="fas fa-edit" style="color:cyan"></i></a>
                                    <a wire:click.prevent ="delete({{ $question->id }})"
                                        style="cursor: pointer;">
                                        <i class="fas fa-trash" style="color:purple"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $this->questions->onEachSide(1)->links('vendor.pagination.tailwind', ['scrollTo' => false]) }}
        </div>
    </div>
</div>
<!-- Question Table End-->
