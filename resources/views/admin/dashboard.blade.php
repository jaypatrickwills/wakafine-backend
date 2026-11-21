<x-admin-layout title="Dashboard">
    {{-- SOS alerts --}}
    @php
        $sosBookings = \App\Models\Booking::with(['passenger', 'rider'])
            ->where('sos_triggered', true)
            ->where('status', '!=', \App\Models\Booking::STATUS_COMPLETED)
            ->latest('sos_at')
            ->limit(5)
            ->get();
    @endphp
    @if ($sosBookings->isNotEmpty())
        <div class="rounded-[2rem] border-2 border-red-300 bg-red-50 p-5">
            <div class="flex items-center gap-2 text-red-700 font-display text-xl uppercase tracking-wide mb-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                Active SOS alerts
            </div>
            <ul class="space-y-1 text-sm text-red-800">
                @foreach ($sosBookings as $b)
                    <li>
                        Booking #{{ $b->id }} — {{ $b->passenger?->name }}
                        @if ($b->rider) with {{ $b->rider->name }} @endif
                        · triggered {{ $b->sos_at?->diffForHumans() }}
                        <a href="{{ route('admin.bookings.show', $b) }}" class="underline font-medium">View</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $cards = [
                ['label' => 'Passengers', 'value' => $stats['passengers'], 'card' => 'bg-mandarin', 'color' => 'text-crest'],
                ['label' => 'Riders', 'value' => $stats['riders'], 'sub' => $stats['verified_riders'].' verified', 'card' => 'bg-forest', 'color' => 'text-crest'],
                ['label' => 'Pending verifications', 'value' => $stats['pending_verifications'], 'card' => 'bg-crest', 'color' => 'text-forest'],
                ['label' => 'Bookings today', 'value' => $stats['bookings_today'], 'card' => 'bg-white', 'color' => 'text-mandarin'],
                ['label' => 'Active trips', 'value' => $stats['active_trips'], 'card' => 'bg-crest', 'color' => 'text-forest'],
                ['label' => 'Completed trips', 'value' => $stats['completed_trips'], 'card' => 'bg-forest', 'color' => 'text-crest'],
                ['label' => 'Open complaints', 'value' => $stats['open_complaints'], 'card' => 'bg-white', 'color' => 'text-mandarin'],
                ['label' => 'SOS alerts', 'value' => $stats['sos_alerts'], 'card' => 'bg-red-600', 'color' => 'text-white'],
            ];
        @endphp
        @foreach ($cards as $card)
            <div class="{{ $card['card'] }} {{ $card['card'] === 'bg-white' ? 'border-2 border-ink/10' : '' }} rounded-[2rem] p-5">
                <div class="wk-label {{ $card['card'] === 'bg-crest' ? 'text-forest/70' : $card['card'] === 'bg-white' ? 'text-ink/60' : 'text-white/70' }}">{{ $card['label'] }}</div>
                <div class="mt-1 font-display text-5xl {{ $card['color'] }}">{{ $card['value'] }}</div>
                @if (isset($card['sub']))
                    <div class="text-xs mt-1 {{ $card['card'] === 'bg-forest' ? 'text-white/60' : 'text-ink/50' }}">{{ $card['sub'] }}</div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Pending rider verifications --}}
        <div class="bg-white rounded-[2rem] border-2 border-ink/10 lg:col-span-1">
            <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
                <h2 class="font-display text-ink text-xl uppercase tracking-tight">Pending verifications</h2>
                <a href="{{ route('admin.riders.index', ['verification' => 'pending']) }}" class="text-sm text-forest hover:underline">View all</a>
            </div>
            <ul class="divide-y divide-ink/10">
                @forelse ($pendingRiders as $profile)
                    <li class="px-5 py-3 flex items-center justify-between">
                        <div>
                            <div class="font-medium text-sm text-ink">{{ $profile->user->name }}</div>
                            <div class="text-xs text-ink/60">{{ $profile->user->phone }} · {{ ucfirst($profile->bike_type) }}</div>
                        </div>
                        <a href="{{ route('admin.riders.show', $profile) }}" class="text-sm text-forest hover:underline">Review</a>
                    </li>
                @empty
                    <li class="px-5 py-6 text-sm text-ink/50 text-center">No pending verifications.</li>
                @endforelse
            </ul>
        </div>

        {{-- Recent bookings --}}
        <div class="bg-white rounded-[2rem] border-2 border-ink/10 lg:col-span-2">
            <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
                <h2 class="font-display text-ink text-xl uppercase tracking-tight">Recent bookings</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-sm text-forest hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-ink/5 text-ink/60 text-xs uppercase">
                        <tr>
                            <th class="px-5 py-2 text-left">ID</th>
                            <th class="px-3 py-2 text-left">Passenger</th>
                            <th class="px-3 py-2 text-left">Rider</th>
                            <th class="px-3 py-2 text-left">Status</th>
                            <th class="px-3 py-2 text-right">Fare</th>
                            <th class="px-5 py-2 text-right">When</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink/10">
                        @forelse ($recentBookings as $booking)
                            <tr class="hover:bg-ink/5">
                                <td class="px-5 py-2.5 font-mono text-xs">#{{ $booking->id }}</td>
                                <td class="px-3 py-2.5">{{ $booking->passenger?->name ?? '—' }}</td>
                                <td class="px-3 py-2.5">{{ $booking->rider?->name ?? '—' }}</td>
                                <td class="px-3 py-2.5"><x-status-badge :status="$booking->status" /></td>
                                <td class="px-3 py-2.5 text-right">{{ number_format($booking->fare) }} XAF</td>
                                <td class="px-5 py-2.5 text-right text-ink/60 text-xs">{{ $booking->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-6 text-center text-ink/50">No bookings yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Recent complaints --}}
        <div class="bg-white rounded-[2rem] border-2 border-ink/10">
            <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
                <h2 class="font-display text-ink text-xl uppercase tracking-tight">Recent complaints</h2>
                <a href="{{ route('admin.complaints.index') }}" class="text-sm text-forest hover:underline">View all</a>
            </div>
            <ul class="divide-y divide-ink/10">
                @forelse ($recentComplaints as $complaint)
                    <li class="px-5 py-3">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-sm text-ink">{{ $complaint->subject }}</span>
                            <x-status-badge :status="$complaint->status" />
                        </div>
                        <div class="text-xs text-ink/60 mt-0.5">{{ $complaint->user?->name }} · {{ $complaint->created_at->diffForHumans() }}</div>
                    </li>
                @empty
                    <li class="px-5 py-6 text-sm text-ink/50 text-center">No complaints.</li>
                @endforelse
            </ul>
        </div>

        {{-- Bookings last 7 days --}}
        <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
            <h2 class="font-display text-ink text-xl uppercase tracking-tight mb-4">Bookings — last 7 days</h2>
            @php
                $max = max(1, $bookingsThisWeek->max() ?? 1);
                $days = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->toDateString());
            @endphp
            <div class="flex items-end gap-3 h-40">
                @foreach ($days as $day)
                    @php $total = $bookingsThisWeek[$day] ?? 0; @endphp
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="text-xs font-semibold text-ink/70">{{ $total }}</div>
                        <div class="w-full rounded-t bg-mandarin" style="height: {{ max(4, ($total / $max) * 120) }}px"></div>
                        <div class="text-[10px] text-ink/50">{{ \Carbon\Carbon::parse($day)->format('D') }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>
