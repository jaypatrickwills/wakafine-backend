<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Models\Complaint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ComplaintController extends Controller
{
    public function store(StoreComplaintRequest $request): JsonResponse
    {
        $data = $request->validated();

        $complaint = Complaint::create([
            'user_id' => $request->user()->id,
            'booking_id' => $data['booking_id'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => Complaint::STATUS_OPEN,
        ]);

        return (new ComplaintResource($complaint->load('user')))
            ->response()
            ->setStatusCode(201);
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $complaints = $request->user()
            ->complaints()
            ->with('user')
            ->latest()
            ->paginate(15);

        return ComplaintResource::collection($complaints);
    }
}
