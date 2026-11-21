<x-admin-layout :title="$rider->user->name">
    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Profile card --}}
        <div class="space-y-6">
            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <h2 class="font-semibold text-ink mb-4">Profile</h2>
                <dl class="text-sm space-y-2">
                    <div class="flex justify-between"><dt class="text-ink/60">Name</dt><dd class="font-medium">{{ $rider->user->name }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink/60">Phone</dt><dd>{{ $rider->user->phone }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink/60">Email</dt><dd>{{ $rider->user->email ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink/60">Account</dt><dd><x-status-badge :status="$rider->user->status" /></dd></div>
                    <div class="flex justify-between"><dt class="text-ink/60">Verification</dt><dd><x-status-badge :status="$rider->verification_status" /></dd></div>
                    <div class="flex justify-between"><dt class="text-ink/60">Online</dt><dd>{{ $rider->is_online ? 'Yes' : 'No' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <h2 class="font-semibold text-ink mb-4">Bike details</h2>
                <dl class="text-sm space-y-2">
                    <div class="flex justify-between"><dt class="text-ink/60">Type</dt><dd>{{ ucfirst($rider->bike_type) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink/60">Plate</dt><dd>{{ $rider->bike_plate ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink/60">Color</dt><dd>{{ $rider->bike_color ?? '—' }}</dd></div>
                </dl>
            </div>

            @if ($earnings)
                <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                    <h2 class="font-semibold text-ink mb-3">Earnings</h2>
                    <div class="text-2xl font-bold text-forest">{{ number_format($earnings->total ?? 0) }} <span class="text-sm font-normal text-ink/60">XAF</span></div>
                    <div class="text-xs text-ink/60 mt-1">{{ $earnings->trips ?? 0 }} completed trips</div>
                </div>
            @endif
        </div>

        {{-- Verification & actions --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <h2 class="font-semibold text-ink mb-4">Verification documents</h2>
                <div class="grid sm:grid-cols-2 gap-4 mb-5">
                    <div class="border border-ink/10 rounded-lg p-4">
                        <div class="text-xs text-ink/60 mb-1">ID document</div>
                        @if ($rider->id_document_path)
                            <a href="{{ Storage::url($rider->id_document_path) }}" target="_blank" class="text-sm text-forest hover:underline">View document</a>
                        @else
                            <span class="text-sm text-ink/50">Not uploaded</span>
                        @endif
                    </div>
                    <div class="border border-ink/10 rounded-lg p-4">
                        <div class="text-xs text-ink/60 mb-1">License</div>
                        @if ($rider->license_path)
                            <a href="{{ Storage::url($rider->license_path) }}" target="_blank" class="text-sm text-forest hover:underline">View document</a>
                        @else
                            <span class="text-sm text-ink/50">Not uploaded</span>
                        @endif
                    </div>
                </div>

                @if ($rider->verification_notes)
                    <div class="rounded-lg bg-ink/5 border border-ink/10 px-4 py-3 text-sm text-ink mb-5">
                        <span class="font-medium">Admin notes:</span> {{ $rider->verification_notes }}
                    </div>
                @endif

                <div class="grid sm:grid-cols-2 gap-4">
                    <form method="POST" action="{{ route('admin.riders.approve', $rider) }}" class="space-y-2">
                        @csrf
                        <textarea name="verification_notes" rows="2" placeholder="Approval note (optional)"
                                  class="w-full rounded-lg border-ink/15 text-sm"></textarea>
                        <button class="w-full rounded-full bg-mandarin hover:bg-mandarin-dark text-white text-sm font-semibold py-2 px-5 wk-label">Approve rider</button>
                    </form>
                    <form method="POST" action="{{ route('admin.riders.reject', $rider) }}" class="space-y-2">
                        @csrf
                        <textarea name="verification_notes" rows="2" placeholder="Reason for rejection (required)" required
                                  class="w-full rounded-lg border-ink/15 text-sm"></textarea>
                        <button class="w-full rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2">Reject rider</button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border-2 border-ink/10 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-ink">Account actions</h2>
                    @if ($rider->user->status === 'suspended')
                        <form method="POST" action="{{ route('admin.riders.reactivate', $rider) }}">@csrf
                            <button class="rounded-full bg-mandarin text-white px-4 py-1.5 text-sm font-semibold">Reactivate account</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.riders.suspend', $rider) }}" onsubmit="return confirm('Suspend this rider? They will be logged out and cannot go online.')">@csrf
                            <button class="rounded-lg bg-red-600 text-white px-4 py-1.5 text-sm font-medium">Suspend account</button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Recent trips --}}
            <div class="bg-white rounded-[2rem] border-2 border-ink/10">
                <div class="px-5 py-4 border-b border-ink/10">
                    <h2 class="font-semibold text-ink">Recent trips</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-ink/5 text-ink/60 text-xs uppercase">
                            <tr>
                                <th class="px-5 py-2 text-left">ID</th>
                                <th class="px-3 py-2 text-left">Passenger</th>
                                <th class="px-3 py-2 text-left">Route</th>
                                <th class="px-3 py-2 text-left">Status</th>
                                <th class="px-3 py-2 text-right">Fare</th>
                                <th class="px-5 py-2 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ink/10">
                            @forelse ($recentTrips as $trip)
                                <tr>
                                    <td class="px-5 py-2.5 font-mono text-xs">#{{ $trip->id }}</td>
                                    <td class="px-3 py-2.5">{{ $trip->passenger?->name }}</td>
                                    <td class="px-3 py-2.5 text-xs text-ink/60 max-w-48 truncate">{{ $trip->pickup_address }} → {{ $trip->destination_address }}</td>
                                    <td class="px-3 py-2.5"><x-status-badge :status="$trip->status" /></td>
                                    <td class="px-3 py-2.5 text-right">{{ number_format($trip->fare) }}</td>
                                    <td class="px-5 py-2.5 text-right text-ink/60 text-xs">{{ $trip->created_at->format('d M H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-6 text-center text-ink/50">No trips yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
