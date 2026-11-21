@props(['title' => 'Dashboard'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} · Wakafine Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cream text-ink antialiased">
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside class="w-64 shrink-0 bg-forest-dark text-white flex flex-col">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <div class="w-10 h-10 rounded-full bg-crest flex items-center justify-center font-display text-2xl text-forest">W</div>
            <div>
                <div class="font-display text-2xl uppercase leading-none tracking-wide text-white">Wakafine</div>
                <div class="wk-label text-crest/70 mt-1">Admin Panel</div>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1">
            @php
                $nav = [
                    ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10'],
                    ['route' => 'admin.riders.index', 'pattern' => 'admin.riders.*', 'label' => 'Riders', 'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 6a2 2 0 11-4 0 2 2 0 014 0zM17.5 8.5l2.5-1M6.5 8.5L4 7.5'],
                    ['route' => 'admin.passengers.index', 'pattern' => 'admin.passengers.*', 'label' => 'Passengers', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['route' => 'admin.bookings.index', 'pattern' => 'admin.bookings.*', 'label' => 'Bookings', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['route' => 'admin.complaints.index', 'pattern' => 'admin.complaints.*', 'label' => 'Complaints', 'icon' => 'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z'],
                ];
            @endphp
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="wk-label flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs($item['pattern']) ? 'bg-mandarin text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="px-3 py-4 border-t border-white/10">
            <div class="px-4 pb-2 text-xs text-white/50 truncate">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="wk-label w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-white/70 hover:bg-white/10 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Log out
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <main class="flex-1 min-w-0 flex flex-col">
        <header class="bg-white/80 backdrop-blur border-b-2 border-ink/10 px-6 py-4 flex items-center justify-between">
            <h1 class="font-display text-3xl uppercase tracking-tight text-ink">{{ $title }}</h1>
            <div class="wk-label text-ink/50">{{ now()->format('l, j F Y') }}</div>
        </header>

        <div class="flex-1 p-6 space-y-6">
            @if (session('success'))
                <div class="rounded-2xl bg-crest border-2 border-forest/20 text-forest px-4 py-3 text-sm font-medium">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-2xl bg-red-50 border-2 border-red-200 text-red-800 px-4 py-3 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>
