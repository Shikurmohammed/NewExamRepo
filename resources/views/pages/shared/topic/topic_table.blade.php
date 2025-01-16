<div class="col-span-full xl:col-span-8 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Topic List</h2>
    </header>
    <div class="p-3">

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full dark:text-gray-300">
                <!-- Table header -->
                <thead
                    class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm">
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
                <!-- Table body -->
                <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">
                    <!-- Row -->
                    @foreach ($topic_data as $topic)
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
                                    <a href="{{ url('edit_category', $topic->id) }}"><i class="fas fa-edit"
                                            style="color:cyan">Edit</i></a>
                                    <a id="{{ $topic->id }}" onclick="confirmDelete(event)" style="cursor: pointer;">
                                        <i class="fas fa-trash" style="color:purple">Delete</i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>

        </div>
    </div>
</div>
