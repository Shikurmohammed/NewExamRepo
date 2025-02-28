<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Exam') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />
    <script src="{{ asset('js/datatable/js/jquery-3.7.0.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('vendor/flasher/flasher.min.js') }}"></script>
    @livewireStyles
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
    <div class="flex h-[100dvh] overflow-hidden">
        <x-app.sidebar :variant="$attributes['sidebarVariant']" />
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden @if ($attributes['background']) {{ $attributes['background'] }} @endif"
            x-ref="contentarea">
            <x-app.header :variant="$attributes['headerVariant']" />
            <main class="grow">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScriptConfig
    @livewireScripts
    <script src="{{ asset('vendor/powergird/js/powergrid.js') }}"></script>
    <script>
        Livewire.start();
    </script>
</body>

</html>
