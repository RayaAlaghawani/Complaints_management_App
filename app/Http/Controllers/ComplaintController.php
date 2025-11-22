<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ComplaintRequest;
use App\Http\Requests\UpdateComplaint;
use App\Models\Complaint;
use App\Services\ComplaintService;
use Google\Rpc\Context\AttributeContext\Response;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
//عرض الشكاوي للموظف
    public function showALL( ){
        $data=[];
        try{
            $data = $this->complaintService->getAll();
            return ResponseHelper::Success($data['data'], $data['message']);
        }catch(\Throwable $th)
        {
            $code = $th->getCode();
            if ($code === 0) {
                $code = 500;
            }
            return ResponseHelper::Error([], $th->getMessage(), $code);
        }}

    //تعديل حالة الشكوى
    public function updateStatus(UpdateComplaint $request)
    {
        try {

            $data = $this->complaintService->updateStatus($request);

            return ResponseHelper::Success($data['data'], $data['message']);

        } catch (\Throwable $th) {

            $code = $th->getCode();

            if ($code === 0) {
                $code = 500;
            }

            return ResponseHelper::Error([], $th->getMessage(), $code);
        }
    }

     //اضافة ملاحظة للشكوى
    //نفسها طلب ملاحظات اضافية من المواطن بهذه الملاحظة
    public function AddNote(UpdateComplaint $request){
        $data=[];
        try{
            $data = $this->complaintService->AddNote($request);
            return ResponseHelper::Success($data['data'], $data['message']);
        }catch(\Throwable $th)
        {
            $code = $th->getCode();
            if ($code === 0) {
                $code = 500;
            }
            return ResponseHelper::Error([], $th->getMessage(), $code);
        }
}
}
