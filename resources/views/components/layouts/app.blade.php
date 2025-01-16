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
    {{-- <link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}"> --}}
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    {{-- <script src="{{asset('vendor/select2/js/select2.min.js')}}"></script> --}}

    <link rel="stylesheet" href="{{ asset('vendor/flasher/flasher.min.css') }}">
    <script src="{{ asset('vendor/flasher/flasher.min.js') }}"></script>
    <!-- Scripts -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/toggle_parentchild.js') }}"></script>
    <script src="{{ asset('js/assignQuestion.js') }}"></script>
    <script src="{{ asset('js/checkbox_selection.js') }}"></script>
    <script>
        //Refer theme.toggle.blade.php file for more, to percieve the code below.
        if (localStorage.getItem('dark-mode') === 'false' || !('dark-mode' in localStorage)) {
            document.querySelector('html').classList.remove('dark');
            document.querySelector('html').style.colorScheme = 'light';
        } else {
            document.querySelector('html').classList.add('dark');
            document.querySelector('html').style.colorScheme = 'dark';
        }
    </script>
    @livewireStyles
</head>

<body class="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400"
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
                {{-- @yield('content') --}}
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScriptConfig <!-- Include Livewire configuration -->
    {{-- <!-- Include Livewire scripts --> --}}
    {{-- @livewireScripts Dont use it with @livewireScriptConfig at same time --}}
</body>

</html>
