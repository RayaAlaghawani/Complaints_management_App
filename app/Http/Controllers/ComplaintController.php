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
                'message' => 'تم إرسال الشكوى بنجاح.',
                'complaint' => $complaint
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء إرسال الشكوى.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * تعديل شكوى موجودة فقط إذا كانت مرفوضة.
     */
    public function updateRejectedComplaint(ComplaintRequest $request, $id): JsonResponse
    {
        try {
            $userId = auth()->id();

            // جلب الشكوى
            $complaint = $this->complaintService->findComplaintById($id);

            if (!$complaint) {
                return response()->json(['message' => 'الشكوى غير موجودة.'], 404);
            }

            // يجب أن تكون الشكوى مرفوضة
            if ($complaint->status !== 'Rejected') {
                return response()->json(['message' => 'لا يمكن تعديل هذه الشكوى إلا إذا كانت مرفوضة.'], 403);
            }

            // يجب أن يكون المستخدم هو صاحب الشكوى
            if ($complaint->user_id !== $userId) {
                return response()->json(['message' => 'لا يمكنك تعديل شكوى ليست لك.'], 403);
            }

            $data = $request->validated();
            $attachment = $request->file('attachment');

            // هذا السطر كان ناقص
            $updatedComplaint = $this->complaintService->updateComplaint($complaint, $data, $attachment);

            // إضافة رابط الصورة
            if ($updatedComplaint->attachment_path) {
                $updatedComplaint->attachment_url = asset('storage/' . $updatedComplaint->attachment_path);
            } else {
                $updatedComplaint->attachment_url = null;
            }

            return response()->json([
                'message' => 'تم تعديل الشكوى بنجاح.',
                'complaint' => $updatedComplaint
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء تعديل الشكوى.',
                'error' => $e->getMessage()
            ], 500);
        }
    }






    /**
     * عرض جميع الشكاوى الخاصة بالمستخدم المسجل دخوله.
     */
    public function myComplaints(): JsonResponse
    {
        try {
            $userId = auth()->id();

            $complaints = $this->complaintService->getComplaintsByUser($userId);

            return response()->json([
                'message' => 'تم جلب الشكاوى الخاصة بك بنجاح.',
                'complaints' => $complaints
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء جلب الشكاوى.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
