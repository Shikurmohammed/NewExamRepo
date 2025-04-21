<div class="bg-white shadow-sm col-span-full md:col-span-10 lg:col-span-9 xl:col-span-8 dark:bg-gray-800 rounded-xl">
    <header
        class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Test List</h2>
        <div class="flex flex-wrap items-center gap-2">
            @livewire('exams.test-modal')
            @livewire('exams.question-assignment-modal')
        </div>
    </header>
    <div class="p-3 overflow-x-auto">
        <livewire:test-table />
    </div>
</div>
