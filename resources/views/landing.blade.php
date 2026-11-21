<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wakafine · Safe bike rides across Bamenda</title>
    <meta name="description" content="Wakafine connects Bamenda passengers with verified bike riders. Book in seconds, track your trip live, and ride with an SOS button in your pocket.">
    <meta name="theme-color" content="#fd550a">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cream font-sans text-ink antialiased">

{{-- ═════════════ HERO ═════════════ --}}
<header class="relative overflow-hidden bg-mandarin text-white">
    {{-- Cream blobs --}}
    <svg class="pointer-events-none absolute -bottom-24 -left-32 w-[55rem] max-w-none text-cream" viewBox="0 0 600 400" fill="currentColor" aria-hidden="true">
        <path d="M0 240C60 150 140 120 220 170s120 140 210 110 120-120 170-90v210H0z"/>
    </svg>
    <svg class="pointer-events-none absolute -right-40 top-16 w-[42rem] max-w-none text-cream" viewBox="0 0 400 400" fill="currentColor" aria-hidden="true">
        <path d="M120 40c70-40 170-30 230 40s50 180-20 240-190 60-250-10S50 80 120 40z"/>
    </svg>

    {{-- Nav --}}
    <nav class="relative z-30 mx-auto flex max-w-7xl items-center justify-between px-6 py-5">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="grid h-9 w-9 place-items-center rounded-full bg-crest font-display text-lg text-forest">W</span>
            <span class="font-display text-2xl uppercase tracking-wide">Wakafine</span>
        </a>
        <ul class="hidden items-center gap-12 md:flex">
            <li><a href="#how" class="wk-label hover:text-crest">How it works</a></li>
            <li><a href="#safety" class="wk-label hover:text-crest">Safety</a></li>
            <li><a href="#apps" class="wk-label hover:text-crest">The apps</a></li>
            <li><a href="#ride" class="wk-label hover:text-crest">Become a rider</a></li>
        </ul>
        <details class="relative md:hidden">
            <summary class="cursor-pointer list-none" aria-label="Open menu">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </summary>
            <ul class="absolute right-0 mt-3 w-56 space-y-3 rounded-2xl bg-forest p-5 shadow-xl">
                <li><a href="#how" class="wk-label">How it works</a></li>
                <li><a href="#safety" class="wk-label">Safety</a></li>
                <li><a href="#apps" class="wk-label">The apps</a></li>
                <li><a href="#ride" class="wk-label">Become a rider</a></li>
            </ul>
        </details>
        <a href="{{ route('admin.login') }}" class="wk-label hidden rounded-full border-2 border-white/70 px-4 py-2 hover:border-crest hover:text-crest md:inline-flex">Admin</a>
    </nav>

    <div class="relative mx-auto max-w-7xl px-6 pb-16 pt-2 md:pb-24">
        {{-- Giant two-tone headline --}}
        <h1 class="wk-display relative z-10 select-none text-center text-[22vw] md:text-[17rem]">
            <span class="text-crest">Waka</span><span class="text-forest">fine</span>
        </h1>

        {{-- Side labels --}}
        <div class="pointer-events-none absolute inset-x-6 top-[42%] z-20 hidden justify-between md:flex">
            <div class="space-y-20">
                <p class="wk-label leading-relaxed">Verified<br>riders</p>
                <p class="wk-label leading-relaxed">Live trip<br>tracking</p>
            </div>
            <p class="wk-label text-right leading-relaxed text-forest">Everyday<br>Bamenda</p>
        </div>

        {{-- Phone mockup overlapping the headline --}}
        <div class="relative z-20 -mt-[14vw] flex justify-center md:-mt-48">
            <div class="wk-float w-64 rounded-[2.75rem] border-[10px] border-ink bg-cream p-4 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.45)] md:w-72">
                <div class="mx-auto mb-4 h-1.5 w-16 rounded-full bg-ink/20"></div>
                <p class="wk-label text-forest/70">Hi Alice</p>
                <p class="wk-display mt-1 text-4xl text-forest">Where to?</p>

                <div class="mt-4 space-y-2 rounded-2xl bg-white p-3 text-sm">
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-mandarin"></span><span class="text-ink/80">Mile 4, Nkwen</span></div>
                    <div class="ml-1 h-4 border-l-2 border-dashed border-ink/20"></div>
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-sm bg-forest"></span><span class="text-ink/80">Commercial Avenue</span></div>
                </div>

                <div class="mt-3 flex items-center gap-3 rounded-2xl bg-forest p-3 text-white">
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-crest font-display text-forest">J</span>
                    <div class="flex-1 leading-tight">
                        <p class="text-sm font-semibold">James · Red moto</p>
                        <p class="text-xs text-white/70">Arriving in 3 min</p>
                    </div>
                    <span class="rounded-full bg-crest px-2 py-0.5 text-[10px] font-bold text-forest">✓ VERIFIED</span>
                </div>

                <p class="wk-display mt-5 text-center text-3xl text-mandarin">Safe ride.<br>Fine days.</p>

                <div class="mt-4 flex gap-2">
                    <span class="flex-1 rounded-full bg-mandarin py-2.5 text-center text-sm font-bold text-white">Request ride</span>
                    <span class="rounded-full bg-red-600 px-3 py-2.5 text-xs font-bold text-white">SOS</span>
                </div>
            </div>
        </div>

        {{-- Circle CTA --}}
        <a href="#apps" class="wk-cta-circle absolute bottom-10 right-6 z-30 w-32 md:bottom-20 md:right-10 md:w-40 md:text-2xl">Book a<br>ride</a>

        <p class="wk-label relative z-20 mt-10 pr-36 text-forest md:hidden">Verified riders · Live tracking · Everyday Bamenda</p>
    </div>
</header>

{{-- ═════════════ MARQUEE ═════════════ --}}
<div class="overflow-hidden border-y-4 border-ink bg-forest py-4 text-crest" aria-hidden="true">
    <div class="wk-marquee flex w-max gap-10 whitespace-nowrap font-display text-3xl uppercase">
        @foreach (range(1, 2) as $_)
            @foreach (['Safe rides', 'Verified riders', 'Live tracking', 'SOS in your pocket', 'Fair fares in XAF', 'Made in Bamenda'] as $word)
                <span>{{ $word }}</span><span class="text-mandarin">✺</span>
            @endforeach
        @endforeach
    </div>
</div>

{{-- ═════════════ HOW IT WORKS ═════════════ --}}
<section id="how" class="mx-auto max-w-7xl px-6 py-24">
    <p class="wk-label text-mandarin">How it works</p>
    <h2 class="wk-display mt-3 text-6xl text-forest md:text-8xl">Three taps.<br><span class="text-mandarin">You're moving.</span></h2>

    <div class="mt-14 grid gap-6 md:grid-cols-3">
        @foreach ([
            ['01', 'Drop your pin', 'Use your GPS or type the pickup and where you are going. You see the fare estimate up front.', 'bg-mandarin text-white', 'text-crest'],
            ['02', 'Meet your rider', 'We match you with the nearest verified rider. See their name, bike and phone before they arrive.', 'bg-forest text-white', 'text-crest'],
            ['03', 'Ride & rate', 'Follow every step live. Pay the fare when you arrive, then rate your trip so the whole city stays safe.', 'bg-crest text-forest', 'text-mandarin'],
        ] as [$num, $title, $body, $card, $numColor])
            <article class="{{ $card }} rounded-[2rem] p-8 transition hover:-translate-y-1">
                <p class="font-display text-7xl {{ $numColor }}">{{ $num }}</p>
                <h3 class="wk-display mt-6 text-4xl">{{ $title }}</h3>
                <p class="mt-3 text-base leading-relaxed opacity-90">{{ $body }}</p>
            </article>
        @endforeach
    </div>
</section>

{{-- ═════════════ SAFETY ═════════════ --}}
<section id="safety" class="relative overflow-hidden bg-forest text-white">
    <svg class="pointer-events-none absolute -right-24 -top-24 w-[34rem] text-forest-dark" viewBox="0 0 400 400" fill="currentColor" aria-hidden="true">
        <path d="M120 40c70-40 170-30 230 40s50 180-20 240-190 60-250-10S50 80 120 40z"/>
    </svg>
    <div class="relative mx-auto grid max-w-7xl gap-14 px-6 py-24 md:grid-cols-2 md:items-center">
        <div>
            <p class="wk-label text-crest">Safety first</p>
            <h2 class="wk-display mt-3 text-6xl text-crest md:text-8xl">Ride safe.<br><span class="text-white">Arrive fine.</span></h2>
            <p class="mt-6 max-w-md text-lg text-white/80">Every Wakafine rider is checked by our admin team before they can take a single trip. And if anything feels wrong, help is one tap away.</p>
        </div>
        <ul class="grid gap-4 sm:grid-cols-2">
            @foreach ([
                ['Verified riders', 'ID and driving licence reviewed by an admin before a rider can go online.'],
                ['SOS button', 'One tap flags your trip and alerts the Wakafine admin team immediately.'],
                ['Live status', 'Know when your rider accepts, arrives, starts and ends the trip.'],
                ['Two-way ratings', 'Passengers and riders rate each other after every ride.'],
            ] as [$title, $body])
                <li class="rounded-3xl border-2 border-white/15 bg-white/5 p-6">
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-crest font-display text-lg text-forest">✓</span>
                    <h3 class="mt-4 font-display text-2xl uppercase text-crest">{{ $title }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-white/75">{{ $body }}</p>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ═════════════ STATS ═════════════ --}}
<section class="bg-crest">
    <div class="mx-auto grid max-w-7xl grid-cols-1 divide-y-2 divide-forest/15 px-6 py-14 text-center sm:grid-cols-3 sm:divide-x-2 sm:divide-y-0">
        @foreach ([
            [$stats['trips'], 'Trips completed'],
            [$stats['riders'], 'Verified riders'],
            [$stats['passengers'], 'Happy passengers'],
        ] as [$value, $label])
            <div class="py-6 sm:py-0">
                <p class="font-display text-7xl text-forest md:text-8xl">{{ number_format($value) }}</p>
                <p class="wk-label mt-2 text-forest/70">{{ $label }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ═════════════ APPS ═════════════ --}}
<section id="apps" class="mx-auto max-w-7xl px-6 py-24">
    <p class="wk-label text-mandarin">Two apps, one city</p>
    <h2 class="wk-display mt-3 text-6xl text-forest md:text-8xl">Pick your <span class="text-mandarin">side.</span></h2>

    <div class="mt-14 grid gap-6 md:grid-cols-2">
        <article class="relative overflow-hidden rounded-[2.5rem] bg-mandarin p-10 text-white">
            <svg class="pointer-events-none absolute -bottom-20 -right-20 w-80 text-cream/25" viewBox="0 0 400 400" fill="currentColor" aria-hidden="true"><path d="M120 40c70-40 170-30 230 40s50 180-20 240-190 60-250-10S50 80 120 40z"/></svg>
            <p class="wk-label text-crest">For passengers</p>
            <h3 class="wk-display mt-3 text-6xl">Wakafine<br><span class="text-crest">Passenger</span></h3>
            <ul class="mt-6 space-y-2 text-white/90">
                <li>→ Book a bike in seconds</li>
                <li>→ See your rider before they arrive</li>
                <li>→ Track every step, rate every trip</li>
            </ul>
            <a href="#" class="wk-pill relative mt-8 bg-crest text-forest hover:bg-white">Get the app</a>
        </article>

        <article id="ride" class="relative overflow-hidden rounded-[2.5rem] bg-forest p-10 text-white">
            <svg class="pointer-events-none absolute -bottom-20 -right-20 w-80 text-crest/10" viewBox="0 0 400 400" fill="currentColor" aria-hidden="true"><path d="M120 40c70-40 170-30 230 40s50 180-20 240-190 60-250-10S50 80 120 40z"/></svg>
            <p class="wk-label text-crest">For riders</p>
            <h3 class="wk-display mt-3 text-6xl">Wakafine<br><span class="text-crest">Rider</span></h3>
            <ul class="mt-6 space-y-2 text-white/90">
                <li>→ Go online whenever you want</li>
                <li>→ Get requests from passengers nearby</li>
                <li>→ Track your earnings in XAF</li>
            </ul>
            <a href="#" class="wk-pill relative mt-8 bg-mandarin text-white hover:bg-mandarin-dark">Start riding</a>
        </article>
    </div>
</section>

{{-- ═════════════ BIG CTA ═════════════ --}}
<section class="relative overflow-hidden bg-mandarin px-6 py-24 text-center">
    <svg class="pointer-events-none absolute -left-24 bottom-0 w-[30rem] text-cream" viewBox="0 0 600 400" fill="currentColor" aria-hidden="true"><path d="M0 240C60 150 140 120 220 170s120 140 210 110 120-120 170-90v210H0z"/></svg>
    <h2 class="wk-display relative text-[16vw] text-crest md:text-[11rem]">Let's <span class="text-forest">waka.</span></h2>
    <p class="relative mx-auto mt-4 max-w-xl text-lg font-semibold text-white">From Mile 4 to Commercial Avenue — your next ride is a few taps away.</p>
    <a href="#apps" class="wk-cta-circle relative mt-10 w-40 text-2xl">Book<br>now</a>
</section>

{{-- ═════════════ FOOTER ═════════════ --}}
<footer class="bg-ink text-white/70">
    <div class="mx-auto flex max-w-7xl flex-col gap-6 px-6 py-10 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-2">
            <span class="grid h-8 w-8 place-items-center rounded-full bg-crest font-display text-forest">W</span>
            <span class="font-display text-xl uppercase tracking-wide text-white">Wakafine</span>
            <span class="ml-3 text-sm">Bike rides across Bamenda</span>
        </div>
        <div class="flex items-center gap-8">
            <a href="#how" class="wk-label hover:text-crest">How it works</a>
            <a href="#safety" class="wk-label hover:text-crest">Safety</a>
            <a href="{{ route('admin.login') }}" class="wk-label hover:text-crest">Admin</a>
        </div>
        <p class="text-xs">© {{ now()->year }} Wakafine · GDG Team Project</p>
    </div>
</footer>
</body>
</html>
