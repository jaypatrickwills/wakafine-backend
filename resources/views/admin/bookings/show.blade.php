<x-admin-layout :title="'Booking #'.$booking->id">
    @if ($booking->sos_triggered)
        <div class="rounded-[2rem] border-2 border-red-300 bg-red-50 p-5 text-red-800">
            <div class="font-semibold">SOS triggered {{ $booking->sos_at?->format('d M Y H:i') }}</div>
            <div class="text-sm mt-1">Passenger: {{ $booking->passenger?->phone }} · Rider: {{ $booking->rider?->phone ?? 'unassigned' }}</div>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <h2 class="font-semibold text-ink mb-4">Trip details</h2>
                <dl class="text-sm space-y-3">
                    <div class="flex justify-between border-b border-ink/10 pb-2">
                        <dt class="text-ink/60">Status</dt>
                        <dd><x-status-badge :status="$booking->status" /></dd>
                    </div>
                    <div class="flex justify-between border-b border-ink/10 pb-2">
                        <dt class="text-ink/60">Fare</dt>
                        <dd class="font-semibold">{{ number_format($booking->fare) }} XAF</dd>
                    </div>
                    <div class="border-b border-ink/10 pb-2">
                        <dt class="text-ink/60 mb-1">Pickup</dt>
                        <dd>{{ $booking->pickup_address }}
                            @if ($booking->pickup_lat) <span class="text-xs text-ink/50">({{ $booking->pickup_lat }}, {{ $booking->pickup_lng }})</span> @endif
                        </dd>
                    </div>
                    <div class="border-b border-ink/10 pb-2">
                        <dt class="text-ink/60 mb-1">Destination</dt>
                        <dd>{{ $booking->destination_address }}
                            @if ($booking->destination_lat) <span class="text-xs text-ink/50">({{ $booking->destination_lat }}, {{ $booking->destination_lng }})</span> @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <h2 class="font-semibold text-ink mb-4">Timeline</h2>
                <ol class="relative border-l border-ink/10 ml-2 space-y-4">
                    @foreach ([
                        'Requested' => $booking->requested_at,
                        'Accepted' => $booking->accepted_at,
                        'Arrived' => $booking->arrived_at,
                        'Trip started' => $booking->started_at,
                        'Completed' => $booking->completed_at,
                        'Cancelled' => $booking->cancelled_at,
                    ] as $label => $time)
                        <li class="ml-4">
                            <span class="absolute -left-1.5 mt-1.5 w-3 h-3 rounded-full {{ $time ? 'bg-mandarin' : 'bg-ink/20' }}"></span>
                            <div class="text-sm font-medium {{ $time ? 'text-ink' : 'text-ink/50' }}">{{ $label }}</div>
                            @if ($time)
                                <div class="text-xs text-ink/60">{{ $time->format('d M Y H:i') }}</div>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>

            @if ($booking->sos_triggered)
                <div class="bg-white rounded-[2rem] border-2 border-red-300 p-5">
                    <h2 class="font-semibold text-red-700 mb-2">Emergency info</h2>
                    <p class="text-sm text-ink/70">SOS was triggered at {{ $booking->sos_at?->format('H:i') }}.
                        Contact the passenger at {{ $booking->passenger?->phone ?? 'unknown' }}.
                        @if ($booking->rider) Rider: {{ $booking->rider->phone }} @endif
                    </p>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <h2 class="font-semibold text-ink mb-3">Passenger</h2>
                @if ($booking->passenger)
                    <a href="{{ route('admin.passengers.show', $booking->passenger) }}" class="text-sm font-medium text-forest hover:underline">{{ $booking->passenger->name }}</a>
                    <div class="text-sm text-ink/60">{{ $booking->passenger->phone }}</div>
                    <div class="mt-2"><x-status-badge :status="$booking->passenger->status" /></div>
                @else
                    <span class="text-sm text-ink/50">Deleted account</span>
                @endif
            </div>

            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <h2 class="font-semibold text-ink mb-3">Rider</h2>
                @if ($booking->rider)
                    @if ($booking->rider->riderProfile)
                        <a href="{{ route('admin.riders.show', $booking->rider->riderProfile) }}" class="text-sm font-medium text-forest hover:underline">{{ $booking->rider->name }}</a>
                    @else
                        <span class="text-sm font-medium">{{ $booking->rider->name }}</span>
                    @endif
                    <div class="text-sm text-ink/60">{{ $booking->rider->phone }}</div>
                    <div class="mt-2"><x-status-badge :status="$booking->rider->status" /></div>
                @else
                    <span class="text-sm text-ink/50">{{ $booking->status === 'searching' ? 'Searching for rider…' : 'Unassigned' }}</span>
                @endif
            </div>

            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <h2 class="font-semibold text-ink mb-3">Ratings</h2>
                <div class="text-sm space-y-1">
                    <div class="flex justify-between"><span class="text-ink/60">Passenger rated rider</span><span>{{ $booking->passenger_rating ? $booking->passenger_rating.'/5' : '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-ink/60">Rider rated passenger</span><span>{{ $booking->rider_rating ? $booking->rider_rating.'/5' : '—' }}</span></div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
