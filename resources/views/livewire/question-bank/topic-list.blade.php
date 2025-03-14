<div class="w-full px-4 py-8 mx-auto sm:px-6 lg:px-8 max-w-9xl">
    <!-- Cards -->
    <div class="row">
        <div class="col-lg-12">
            @include('livewire.question-bank.tables.topic-table')
        </div>
    </div>
    <style>
        /* Optional: Adjust z-index if necessary */
        .select2-container--default .select2-selection--single {
            z-index: 1000;
            /* Adjust as needed */
        }
    </style>
</div>
