<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Exam') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    {{-- <script src="{{asset('js/datatable/js/jquery-3.7.0.js')}}"></script> --}}
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])



    <script src="{{ asset('vendor/flasher/flasher.min.js') }}"></script>
    <!-- Styles -->
    @livewireStyles
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <script>
        if (localStorage.getItem('dark-mode') === 'false' || !('dark-mode' in localStorage)) {
            document.querySelector('html').classList.remove('dark');
            document.querySelector('html').style.colorScheme = 'light';
        } else {
            document.querySelector('html').classList.add('dark');
            document.querySelector('html').style.colorScheme = 'dark';
        }
    </script>
</head>

<body class="antialiased text-gray-600 bg-gray-100 font-inter dark:bg-gray-900 dark:text-gray-400"
    :class="{ 'sidebar-expanded': sidebarExpanded }" x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true' }" x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))">

    <script>
        if (localStorage.getItem('sidebar-expanded') == 'true') {
            document.querySelector('body').classList.add('sidebar-expanded');
        } else {
            document.querySelector('body').classList.remove('sidebar-expanded');
        }
    </script>

    <!-- Page wrapper -->
    <div class="flex h-[100dvh] overflow-hidden">

        <x-app.sidebar :variant="$attributes['sidebarVariant']" />

        <!-- Content area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden @if ($attributes['background']) {{ $attributes['background'] }} @endif"
            x-ref="contentarea">

            <x-app.header :variant="$attributes['headerVariant']" />

            <main class="grow">
                {{ $slot }}
            </main>

        </div>

    </div>


    {{-- <script src="https://cdn.jsdelivr.net/npm/@flasher/flasher-livewire"></script> --}}
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>


    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

    @livewireScriptConfig
    @livewireScripts
    {{-- @script() --}}
    <script>
        Livewire.start();

        //Datatable
        function datatable(id) {
            let dataTable = new DataTable(id, {
                paging: true, // Enable pagination
                searching: true, // Enable searching
                ordering: true, // Enable column ordering
                dom: 'Bfrtip', // Use buttons
                buttons: [
                    'copy', 'excel', 'pdf'
                ],
                order: [
                    [0, 'asc']
                ], // Default ordering on the first column
                pageLength: 10, // Number of rows per page
                lengthMenu: [10, 25, 50, 100], // Options for rows per page
                language: { // Custom language options
                    search: "Filter records:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    emptyTable: "No data available in table"
                },

            });
        }
    </script>
    {{-- @endscript() --}}

</body>

</html>