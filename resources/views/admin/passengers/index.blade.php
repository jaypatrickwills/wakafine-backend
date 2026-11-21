<x-admin-layout title="Passengers">
    <div class="bg-white rounded-[2rem] border-2 border-ink/10">
        <div class="px-5 py-4 border-b border-ink/10 flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-ink">All passengers</h2>
            <form method="GET" class="flex items-center gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name or phone"
                       class="rounded-lg border-ink/15 text-sm">
                <select name="status" class="rounded-lg border-ink/15 text-sm">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                </select>
                <button class="rounded-full bg-forest-dark text-white px-4 py-1.5 text-sm wk-label">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink/5 text-ink/60 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-2 text-left">Passenger</th>
                        <th class="px-3 py-2 text-left">Phone</th>
                        <th class="px-3 py-2 text-left">Bookings</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-5 py-2 text-right">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink/10">
                    @forelse ($passengers as $passenger)
                        <tr class="hover:bg-ink/5">
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.passengers.show', $passenger) }}" class="font-medium text-forest hover:underline">{{ $passenger->name }}</a>
                            </td>
                            <td class="px-3 py-3">{{ $passenger->phone }}</td>
                            <td class="px-3 py-3">{{ $passenger->passenger_bookings_count }}</td>
                            <td class="px-3 py-3"><x-status-badge :status="$passenger->status" /></td>
                            <td class="px-5 py-3 text-right text-ink/60 text-xs">{{ $passenger->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-ink/50">No passengers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-ink/10">{{ $passengers->links() }}</div>
    </div>
</x-admin-layout>
