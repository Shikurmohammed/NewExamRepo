<div class="w-full px-4 py-8 mx-auto sm:px-6 lg:px-8 max-w-9xl">
    <div class="row">
        <div class="col-lg-12">
            <div class="bg-white rounded shadow-sm col-span-full xl:col-span-8 dark:bg-gray-800 ">
                <header class="flex justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">User List</h2>
                    <div class="flex justify-between">
                        @livewire('modals.user-modal')
                    </div>
                </header>
                <div class="p-3 ">
                    <livewire:user-table />
                </div>
            </div>
        </div>
    </div>
</div>
