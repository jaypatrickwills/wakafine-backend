<x-admin-layout :title="$passenger->name">
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="space-y-6">
            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <h2 class="font-semibold text-ink mb-4">Profile</h2>
                <dl class="text-sm space-y-2">
                    <div class="flex justify-between"><dt class="text-ink/60">Name</dt><dd class="font-medium">{{ $passenger->name }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink/60">Phone</dt><dd>{{ $passenger->phone }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink/60">Email</dt><dd>{{ $passenger->email ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink/60">Status</dt><dd><x-status-badge :status="$passenger->status" /></dd></div>
                    <div class="flex justify-between"><dt class="text-ink/60">Joined</dt><dd>{{ $passenger->created_at->format('d M Y') }}</dd></div>
                </dl>
            </div>

            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <h2 class="font-semibold text-ink mb-3">Account actions</h2>
                @if ($passenger->status === 'suspended')
                    <form method="POST" action="{{ route('admin.passengers.reactivate', $passenger) }}">@csrf
                        <button class="w-full rounded-full bg-mandarin hover:bg-mandarin-dark text-white text-sm font-semibold py-2 px-5 wk-label">Reactivate account</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.passengers.suspend', $passenger) }}" onsubmit="return confirm('Suspend this passenger? They will not be able to log in.')">@csrf
                        <button class="w-full rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2">Suspend account</button>
                    </form>
                @endif
            </div>

            @if ($passenger->complaints->isNotEmpty())
                <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                    <h2 class="font-semibold text-ink mb-3">Complaints filed</h2>
                    <ul class="space-y-2 text-sm">
                        @foreach ($passenger->complaints->take(5) as $c)
                            <li class="flex items-center justify-between">
                                <span class="truncate">{{ $c->subject }}</span>
                                <x-status-badge :status="$c->status" />
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2rem] border-2 border-ink/10">
                <div class="px-5 py-4 border-b border-ink/10">
                    <h2 class="font-semibold text-ink">Booking history</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-ink/5 text-ink/60 text-xs uppercase">
                            <tr>
                                <th class="px-5 py-2 text-left">ID</th>
                                <th class="px-3 py-2 text-left">Route</th>
                                <th class="px-3 py-2 text-left">Rider</th>
                                <th class="px-3 py-2 text-left">Status</th>
                                <th class="px-3 py-2 text-right">Fare</th>
                                <th class="px-5 py-2 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ink/10">
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td class="px-5 py-2.5 font-mono text-xs">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="text-forest hover:underline">#{{ $booking->id }}</a>
                                    </td>
                                    <td class="px-3 py-2.5 text-xs text-ink/60 max-w-52 truncate">{{ $booking->pickup_address }} → {{ $booking->destination_address }}</td>
                                    <td class="px-3 py-2.5">{{ $booking->rider?->name ?? '—' }}</td>
                                    <td class="px-3 py-2.5"><x-status-badge :status="$booking->status" /></td>
                                    <td class="px-3 py-2.5 text-right">{{ number_format($booking->fare) }}</td>
                                    <td class="px-5 py-2.5 text-right text-ink/60 text-xs">{{ $booking->created_at->format('d M H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-6 text-center text-ink/50">No bookings yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
