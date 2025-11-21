<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplaintRequest;
use App\Services\ComplaintService;
use Illuminate\Http\JsonResponse;
use Exception;

class ComplaintController extends Controller
{
    protected $complaintService;

    // حقن خدمة الشكاوى في المتحكم
    public function __construct(ComplaintService $complaintService)
    {
        // يجب أن يتم تعريف ComplaintService في app/Services/ComplaintService.php
        $this->complaintService = $complaintService;
    }

    /**
     * يخزن شكوى جديدة مقدمة من المواطن المُسجل دخوله.
     */
    public function store(ComplaintRequest $request): JsonResponse
    {
        try {
            $userId = auth()->id();
            $data = $request->validated();
            $attachment = $request->file('attachment');

            $complaint = $this->complaintService->submitComplaint($data, $userId, $attachment);

            return response()->json([
                'message' => 'تم إرسال شكواك بنجاح. سيتم مراجعتها قريباً.',
                'complaint' => $complaint
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء إرسال الشكوى.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
