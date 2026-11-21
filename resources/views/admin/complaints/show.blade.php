<x-admin-layout :title="$complaint->subject">
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-ink">Complaint</h2>
                    <x-status-badge :status="$complaint->status" />
                </div>
                <dl class="text-sm space-y-3">
                    <div class="flex justify-between border-b border-ink/10 pb-2">
                        <dt class="text-ink/60">Filed by</dt>
                        <dd>{{ $complaint->user?->name }} ({{ $complaint->user?->phone }})</dd>
                    </div>
                    <div class="flex justify-between border-b border-ink/10 pb-2">
                        <dt class="text-ink/60">Related booking</dt>
                        <dd>
                            @if ($complaint->booking)
                                <a href="{{ route('admin.bookings.show', $complaint->booking) }}" class="text-forest hover:underline">#{{ $complaint->booking->id }}</a>
                            @else
                                None
                            @endif
                        </dd>
                    </div>
                    <div class="border-b border-ink/10 pb-2">
                        <dt class="text-ink/60 mb-1">Message</dt>
                        <dd class="text-ink whitespace-pre-line">{{ $complaint->message }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink/60">Filed</dt>
                        <dd>{{ $complaint->created_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            @if ($complaint->booking)
                <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                    <h2 class="font-semibold text-ink mb-3">Related trip</h2>
                    <div class="text-sm space-y-1">
                        <div class="flex justify-between"><span class="text-ink/60">Passenger</span><span>{{ $complaint->booking->passenger?->name }}</span></div>
                        <div class="flex justify-between"><span class="text-ink/60">Rider</span><span>{{ $complaint->booking->rider?->name ?? '—' }}</span></div>
                        <div class="flex justify-between"><span class="text-ink/60">Route</span><span class="text-right max-w-64 truncate">{{ $complaint->booking->pickup_address }} → {{ $complaint->booking->destination_address }}</span></div>
                        <div class="flex justify-between"><span class="text-ink/60">Status</span><x-status-badge :status="$complaint->booking->status" /></div>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <h2 class="font-semibold text-ink mb-4">Update complaint</h2>
                <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}" class="space-y-3">
                    @csrf @method('PATCH')
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-ink/15 text-sm">
                            @foreach (['open', 'investigating', 'resolved'] as $s)
                                <option value="{{ $s }}" @selected($complaint->status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1">Admin note</label>
                        <textarea name="admin_note" rows="4" class="w-full rounded-lg border-ink/15 text-sm">{{ old('admin_note', $complaint->admin_note) }}</textarea>
                    </div>
                    <button class="w-full rounded-full bg-forest-dark hover:bg-mandarin text-white text-sm font-semibold py-2 px-5 wk-label">Save</button>
                </form>
            </div>

            @if ($complaint->resolved_at)
                <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5 text-sm">
                    <div class="text-ink/60">Resolved by</div>
                    <div class="font-medium">{{ $complaint->resolver?->name ?? '—' }}</div>
                    <div class="text-ink/60 mt-2">Resolved at</div>
                    <div>{{ $complaint->resolved_at->format('d M Y H:i') }}</div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
