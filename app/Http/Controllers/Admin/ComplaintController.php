<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function index(Request $request): View
    {
        $query = Complaint::with(['user', 'booking'])->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $complaints = $query->paginate(15)->withQueryString();

        return view('admin.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint): View
    {
        $complaint->load(['user', 'booking.passenger', 'booking.rider', 'resolver']);

        return view('admin.complaints.show', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,investigating,resolved'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $complaint->update([
            'status' => $data['status'],
            'admin_note' => $data['admin_note'] ?? $complaint->admin_note,
            'resolved_by' => $data['status'] === Complaint::STATUS_RESOLVED
                ? $request->user()->id
                : null,
            'resolved_at' => $data['status'] === Complaint::STATUS_RESOLVED
                ? now()
                : null,
        ]);

        return back()->with('success', 'Complaint updated.');
    }
}
