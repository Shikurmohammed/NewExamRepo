<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <div class="row">
        <style>
            .select2-container {
                z-index: 1050;
                /* Higher than the modal backdrop */
            }
        </style>
        <div class="col-lg-12">
            @include('livewire.exams.tables.test-table')
        </div>
    </div>
</div>
@script()
<script>
    document.addEventListener('livewire:initialized', () => {
        datatable('#test_table');
    });
</script>
@endscript()
