<x-admin-layout title="Bookings">
    <div class="bg-white rounded-[2rem] border-2 border-ink/10">
        <div class="px-5 py-4 border-b border-ink/10 flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-ink">All bookings</h2>
            <form method="GET" class="flex items-center gap-2">
                <select name="status" class="rounded-lg border-ink/15 text-sm">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <label class="flex items-center gap-1.5 text-sm text-ink/70">
                    <input type="checkbox" name="sos" value="1" @checked(request('sos') === '1') class="rounded border-ink/15 text-red-600">
                    SOS only
                </label>
                <button class="rounded-full bg-forest-dark text-white px-4 py-1.5 text-sm wk-label">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink/5 text-ink/60 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-2 text-left">ID</th>
                        <th class="px-3 py-2 text-left">Passenger</th>
                        <th class="px-3 py-2 text-left">Rider</th>
                        <th class="px-3 py-2 text-left">Route</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-3 py-2 text-right">Fare</th>
                        <th class="px-5 py-2 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink/10">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-ink/5 {{ $booking->sos_triggered ? 'bg-red-50' : '' }}">
                            <td class="px-5 py-3 font-mono text-xs">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="text-forest hover:underline">#{{ $booking->id }}</a>
                                @if ($booking->sos_triggered)
                                    <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-600 text-white">SOS</span>
                                @endif
                            </td>
                            <td class="px-3 py-3">{{ $booking->passenger?->name ?? '—' }}</td>
                            <td class="px-3 py-3">{{ $booking->rider?->name ?? '—' }}</td>
                            <td class="px-3 py-3 text-xs text-ink/60 max-w-56 truncate">{{ $booking->pickup_address }} → {{ $booking->destination_address }}</td>
                            <td class="px-3 py-3"><x-status-badge :status="$booking->status" /></td>
                            <td class="px-3 py-3 text-right">{{ number_format($booking->fare) }}</td>
                            <td class="px-5 py-3 text-right text-ink/60 text-xs">{{ $booking->created_at->format('d M H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-8 text-center text-ink/50">No bookings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-ink/10">{{ $bookings->links() }}</div>
    </div>
</x-admin-layout>
