  <!--Module Table Start -->
  <div wire:poll.alive class="col-span-full xl:col-span-8 bg-white dark:bg-gray-800 shadow-sm rounded-xl">

    <header class="flex justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Module List</h2>

        <div class="flex justify-between">
            <!-- include topic-modal -->
            @livewire('modals.module-modal')
            <!-- end-->
            <input type="text" wire:model.live="search" placeholder="search here"
                class="ml-5 mt-1 block w-1/10 border border-gray-300 rounded-md p-2 mr-0">
        </div>
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
                            <div class="font-semibold text-center">Module Name</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Creatd By</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Status</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Action</div>
                        </th>
                    </tr>
                </thead>
                <!-- Table body -->
                <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">
                    <!-- Row -->
                    @foreach ($modules as $module)
                        <tr wire:key={{ $module->id }}>
                            <td class="p-2">
                                <div class="flex items-center">
                                    <div class="text-gray-800 dark:text-gray-100">{{ $module->id }}</div>
                                </div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $module->name }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center text-green-500">{{ $module->user->name }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $module->enabled }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center text-sky-500">
                                    <a href="{{ url('edit_category', $module->id) }}"><i class="fas fa-edit"
                                            style="color:cyan" title="Edit"></i></a>
                                    <a wire:click="delete({{ $module->id }})" style="cursor: pointer;"
                                        title="Delete">
                                        <i class="fas fa-trash" style="color:purple"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                @if (!$modules || count($modules) == 0)
                    <h3 class="flex justify-center font-semibold text-gray-800 dark:text-gray-100">No data
                        is found!
                    </h3>
                @endif
            </table>
            {{ $modules->links('vendor.livewire.tailwind') }}
        </div>
    </div>
</div>
<!-- Module Table End-->
