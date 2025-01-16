<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Cards -->
    <div class="row">
        <div class="col-lg-12">
            <!--topic Table Start -->
            <div class="col-span-full xl:col-span-8 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                <header class="flex justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Answer List</h2>

                    <div class="flex justify-between">
                        <!-- include topic-modal -->
                        @livewire('modals.answer-modal')
                        <!-- end-->
                        <input type="text" wire:model.live="search" placeholder="search here"
                            class="ml-5 mt-1 block w-1/10 border border-gray-300 rounded-md p-2 mr-0">
                    </div>
                </header>
                <div class="p-3">
                    @include('livewire.question-bank.tables.answer-table')
                </div>
            </div>
        </div>
    </div>
</div>
