<x-admin-layout title="Riders">
    <div class="bg-white rounded-[2rem] border-2 border-ink/10">
        <div class="px-5 py-4 border-b border-ink/10 flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-ink">All riders</h2>
            <form method="GET" class="flex items-center gap-2">
                <select name="verification" class="rounded-lg border-ink/15 text-sm">
                    <option value="">All verification statuses</option>
                    @foreach (['pending', 'approved', 'rejected'] as $v)
                        <option value="{{ $v }}" @selected(request('verification') === $v)>{{ ucfirst($v) }}</option>
                    @endforeach
                </select>
                <label class="flex items-center gap-1.5 text-sm text-ink/70">
                    <input type="checkbox" name="online" value="1" @checked(request('online') === '1') class="rounded border-ink/15 text-forest">
                    Online only
                </label>
                <button class="rounded-full bg-forest-dark text-white px-4 py-1.5 text-sm wk-label">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink/5 text-ink/60 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-2 text-left">Rider</th>
                        <th class="px-3 py-2 text-left">Phone</th>
                        <th class="px-3 py-2 text-left">Bike</th>
                        <th class="px-3 py-2 text-left">Verification</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-3 py-2 text-left">Online</th>
                        <th class="px-5 py-2 text-right">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink/10">
                    @forelse ($riders as $profile)
                        <tr class="hover:bg-ink/5">
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.riders.show', $profile) }}" class="font-medium text-forest hover:underline">{{ $profile->user->name }}</a>
                            </td>
                            <td class="px-3 py-3">{{ $profile->user->phone }}</td>
                            <td class="px-3 py-3 text-ink/70">{{ ucfirst($profile->bike_type) }}{{ $profile->bike_plate ? ' · '.$profile->bike_plate : '' }}</td>
                            <td class="px-3 py-3"><x-status-badge :status="$profile->verification_status" /></td>
                            <td class="px-3 py-3"><x-status-badge :status="$profile->user->status" /></td>
                            <td class="px-3 py-3">
                                @if ($profile->is_online)
                                    <span class="inline-flex items-center gap-1 text-forest text-xs font-medium">
                                        <span class="w-2 h-2 rounded-full bg-mandarin"></span> Online
                                    </span>
                                @else
                                    <span class="text-ink/50 text-xs">Offline</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right text-ink/60 text-xs">{{ $profile->user->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-8 text-center text-ink/50">No riders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-ink/10">{{ $riders->links() }}</div>
    </div>
</x-admin-layout>
