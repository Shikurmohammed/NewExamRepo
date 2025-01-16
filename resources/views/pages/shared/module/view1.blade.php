<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">

                <!-- Filter button -->
                <x-dropdown-filter align="right" />

                <!-- Datepicker built with flatpickr -->
                <x-datepicker />

                <!-- Add view button -->
                <button
                    class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">New</span>
                </button>

            </div>

        </div>
        <!-- Cards -->
        <div class="row">
            <div class="col-lg-4">
                {{-- @livewire('module-modal') --}}
            </div>
            <div class="col-lg-12">
                {{-- @livewire('module') --}}
        @livewire('module-list')
            </div>
        </div>
    </div>

</x-app-layout>
<script>
    function confirmDelete(e) {
        e.preventDefault();
        id = e.currentTarget.getAttribute('id');

        if (confirm('Are you sure you want to delete this resource?')) {
            $.ajax({
                url: 'delete_module/' + id,
                type: 'POST',
                data: {
                    _method: 'DELETE', // Like in the form method(in which we use method as a post and specify delete method in the form body)
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(result) {
                    alert('Resource deleted successfully!');
                    $('#resource-' + id).remove();
                },
                error: function(xhr) {
                    alert('An error occurred while deleting the resource.');
                }
            });
        }
    }
</script>
