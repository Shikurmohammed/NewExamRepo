<div class="bg-white shadow-sm col-span-full xl:col-span-8 dark:bg-gray-800 rounded-xl">
    <header class="flex justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Topic List</h2>
        <div class="flex justify-between">
            @livewire('modals.topic-modal')
        </div>
    </header>
    <livewire:topic-table />

    {{-- <div class="p-3">
        <div class="overflow-x-auto">
            <table id="topic_table" class="w-full table-auto datatable dark:text-gray-300">

                <thead
                    class="text-xs text-gray-400 uppercase rounded-sm dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50">
                    <tr>
                        <th class="p-2">
                            <div class="font-semibold text-left">#</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Topic Name</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Description</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Module</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Creatd By</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">isEnabled</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Action</div>
                        </th>
                    </tr>
                </thead>

                <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">

                    @foreach ($topics as $topic)
                        <tr>
                            <td class="p-2">
                                <div class="flex items-center">
                                    <div class="text-gray-800 dark:text-gray-100">{{ $topic->id }}</div>
                                </div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $topic->name }}</div>
                            </td>

                            <td class="p-2">
                                <div class="text-center">{{ $topic->description }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $topic->module->name }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center text-green-500">{{ $topic->user->name }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $topic->enabled }}</div>
                            </td>


                            <td class="p-2">
                                <div class="text-center text-sky-500">
                                    <a wire:click="alert('')"><i class="fas fa-edit" style="color:cyan"></i></a>
                                    <a wire:click="delete({{ $topic->id }})" style="cursor: pointer;">
                                        <i class="fas fa-trash" style="color:purple"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                @if (!$topics || count($topics) == 0)
                    <h3 class="flex justify-center font-semibold text-gray-800 dark:text-gray-100">No data
                        is found!
                    </h3>
                @endif
            </table>
        </div>
    </div> --}}

</div>
