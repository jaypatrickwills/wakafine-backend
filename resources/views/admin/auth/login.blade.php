<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login · Wakafine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-mandarin min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    {{-- Cream blob --}}
    <svg class="pointer-events-none absolute -left-32 top-10 w-[30rem] text-cream/30" viewBox="0 0 400 400" fill="currentColor" aria-hidden="true">
        <path d="M120 40c70-40 170-30 230 40s50 180-20 240-190 60-250-10S50 80 120 40z"/>
    </svg>

    <div class="relative w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-full bg-crest items-center justify-center font-display text-4xl text-forest mb-4">W</div>
            <h1 class="font-display text-5xl uppercase tracking-wide text-crest">Wakafine</h1>
            <p class="wk-label text-white/80 mt-2">Admin Panel · Bamenda</p>
        </div>

        <div class="bg-cream rounded-[2rem] p-8 shadow-2xl border-4 border-ink/10">
            <p class="font-display text-2xl uppercase text-forest mb-1">Sign in</p>
            <p class="text-sm text-ink/60 mb-6">Manage riders, passengers and trips.</p>

            @if ($errors->any())
                <div class="mb-4 rounded-2xl bg-red-50 border-2 border-red-200 text-red-800 px-4 py-3 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="login" class="block wk-label text-ink/70 mb-1">Phone or Email</label>
                    <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus
                           class="w-full rounded-2xl border-2 border-ink/15 bg-white px-4 py-3 text-sm focus:border-mandarin focus:ring-0">
                </div>
                <div>
                    <label for="password" class="block wk-label text-ink/70 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full rounded-2xl border-2 border-ink/15 bg-white px-4 py-3 text-sm focus:border-mandarin focus:ring-0">
                </div>
                <label class="flex items-center gap-2 text-sm text-ink/70">
                    <input type="checkbox" name="remember" class="rounded border-ink/20 text-mandarin focus:ring-mandarin">
                    Remember me
                </label>
                <button type="submit"
                        class="w-full wk-pill justify-center bg-mandarin text-white hover:bg-mandarin-dark">
                    Enter dashboard
                </button>
            </form>

            <a href="{{ route('home') }}" class="mt-5 block text-center wk-label text-ink/50 hover:text-mandarin">← Back to website</a>
        </div>
    </div>
</body>
</html>
