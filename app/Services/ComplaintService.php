<?php

namespace App\Services;

use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;

class ComplaintService
{
    public function submitComplaint(array $data, $userId, $attachment = null)
    {
        // رفع الملف إذا موجود
        // رفع الملف إذا موجود
        if ($attachment) {
            // يجب تحديد القرص public ليصبح الوصول ممكناً عبر /storage
            $data['attachment_path'] = $attachment->store('complaints', 'public');
        }

        // إزالة المفتاح 'attachment' لأنه ليس عمودًا فعليًا
        unset($data['attachment']);

        // إضافة صاحب الشكوى
        $data['user_id'] = $userId;

        // إنشاء الشكوى
        $complaint = Complaint::create($data);

        // إضافة رابط URL مباشر للملف
        $complaint->attachment_url = $data['attachment_path']
            ? asset("storage/" . $data['attachment_path'])
            : null;

        return $complaint;
    }

//    public function createComplaint($request)
//    {
//        $attachmentPath = null;
//
//        // حفظ الملف في storage/app/public/complaints
//        if ($request->hasFile('attachment')) {
//            $attachmentPath = $request->file('attachment')->store('complaints', 'public');
//        }
//
//        // إنشاء الشكوى
//        $complaint = Complaint::create([
//            'government_agencie_id' => $request->government_agencie_id,
//            'title' => $request->title,
//            'description' => $request->description,
//            'attachment_path' => $attachmentPath,
//            'user_id' => auth()->id(),
//        ]);
//
//        // إضافة رابط URL مباشر للصورة
//        $complaint->attachment_url = $attachmentPath ? asset("storage/" . $attachmentPath) : null;
//
//        return $complaint;
//    }


    public function findComplaintById($id)
    {
        return Complaint::find($id);
    }
//
//    public function updateComplaint($complaint, $data, $attachment = null)
//    {
//        if ($attachment) {
//            // حذف الملف القديم إذا كان موجود
//            if ($complaint->attachment_path) {
//                Storage::delete($complaint->attachment_path);
//            }
//            $data['attachment_path'] = $attachment->store('complaints');
//        }
//
//        $complaint->update($data);
//
//        return $complaint;
//    }
    public function updateComplaint(Complaint $complaint, array $data, $attachment = null)
    {
        if ($attachment) {
            $data['attachment_path'] = $attachment->store('complaints', 'public');
        }

        unset($data['attachment']);
        $complaint->update($data);

        return $complaint;
    }






//
//
//    public function getComplaintsByUser($userId)
//    {
//        return Complaint::where('user_id', $userId)
//            ->orderBy('created_at', 'desc')
//            ->get();
//    }
    public function getComplaintsByUser($userId)
    {
        $complaints = Complaint::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // إضافة روابط الصور لكل شكوى
        foreach ($complaints as $complaint) {
            $complaint->attachment_url = $complaint->attachment_path
                ? asset("storage/" . $complaint->attachment_path)
                : null;
        }

        return $complaints;
    }



}
