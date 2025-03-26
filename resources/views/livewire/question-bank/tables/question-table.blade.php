<div class="bg-white rounded shadow-sm col-span-full xl:col-span-8 dark:bg-gray-800 ">
    <header class="flex justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <div class="flex justify-between">
            <h2 class="w-auto mr-2 font-semibold text-gray-800 dark:text-gray-100">Question List</h2>
            <span>
                @livewire('modals.question-modal')
            </span>
        </div>
    </header>
    <div class="p-3">
        <livewire:question-table />
        <div class="overflow-x-auto ">

        </div>
    </div>
</div>
