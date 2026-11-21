<x-admin-layout title="Complaints">
    <div class="bg-white rounded-[2rem] border-2 border-ink/10">
        <div class="px-5 py-4 border-b border-ink/10 flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-ink">Complaints</h2>
            <form method="GET" class="flex items-center gap-2">
                <select name="status" class="rounded-lg border-ink/15 text-sm">
                    <option value="">All statuses</option>
                    @foreach (['open', 'investigating', 'resolved'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button class="rounded-full bg-forest-dark text-white px-4 py-1.5 text-sm wk-label">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink/5 text-ink/60 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-2 text-left">Subject</th>
                        <th class="px-3 py-2 text-left">Filed by</th>
                        <th class="px-3 py-2 text-left">Booking</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-5 py-2 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink/10">
                    @forelse ($complaints as $complaint)
                        <tr class="hover:bg-ink/5">
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.complaints.show', $complaint) }}" class="font-medium text-forest hover:underline">{{ $complaint->subject }}</a>
                            </td>
                            <td class="px-3 py-3">{{ $complaint->user?->name ?? '—' }}</td>
                            <td class="px-3 py-3 font-mono text-xs">{{ $complaint->booking_id ? '#'.$complaint->booking_id : '—' }}</td>
                            <td class="px-3 py-3"><x-status-badge :status="$complaint->status" /></td>
                            <td class="px-5 py-3 text-right text-ink/60 text-xs">{{ $complaint->created_at->format('d M H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-ink/50">No complaints found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-ink/10">{{ $complaints->links() }}</div>
    </div>
</x-admin-layout>
