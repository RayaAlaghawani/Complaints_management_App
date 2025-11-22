<?php

namespace App\Services;

use App\Http\Requests\ComplaintRequest;
use App\Http\Requests\UpdateComplaint;
use App\Models\Complaint;
use App\Repositories\ComplaintRepository;
use App\Repositories\userRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;
use function PHPUnit\Framework\isEmpty;

class ComplaintService
{
    public function __construct(ComplaintRepository $repository)
    {
        $this->repository = $repository;
    }

    public function submitComplaint(array $data, int $userId, ?UploadedFile $attachment): Complaint
    {
        DB::beginTransaction();
        $attachmentPath = null;

        try {

            if ($attachment) {
                // حفظ الملف في مجلد 'complaints/attachments' في قرص 'public'
                $attachmentPath = $attachment->store('complaints/attachments', 'public');
            }

            $complaint = Complaint::create([
                'user_id' => $userId,
                'government_agencie_id' => $data['government_agencie_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'attachment_path' => $attachmentPath,
                'status' => 'Pending',
            ]);

            DB::commit();
            return $complaint;

        } catch (Exception $e) {
            DB::rollBack();
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }
            throw new Exception('فشل في إرسال الشكوى. يرجى المحاولة لاحقاً: ' . $e->getMessage());
        }
    }
    //تعديل حالة الشكوى
    public function updateStatus( UpdateComplaint $request)
    {
        $user = Auth::user();
        $userId=$user->id;
        $status = $request->status;
        $id     = $request->id;
        $complaint = null;
        if (optional(!Auth::user())->hasRole('employee')) {
            throw new \Exception('You do not have permission to edit the complaint.', 401);
        }
            DB::transaction(function () use ($user,$id, $status, $userId, &$complaint) {
            $complaint =$this->repository->getById($user,$id );
            if (!$complaint) {
                throw new \Exception('the complaint is not found.');
            }
            if ($complaint->locked_by &&
                $complaint->locked_by != $userId && $complaint->lock_expires_at > now()) {
                throw new \Exception('the complaint is reserved by another user.', 429);
            }
            $complaint=  $this->repository->update($user,$id,$userId,$status);
        });

        return [
            'data'    => $complaint,
            'message' => 'the complaint has been successfully modified.',
        ];
            }

//اضافة ملاجظة للشكوى
    public function AddNote( UpdateComplaint$request)
    {
        $user = Auth::user();
        $userId=$user->id;
        $note = $request->note;
        $id     = $request->id;
        $complaint = null;
        if (optional(!Auth::user())->hasRole('employee')) {
            throw new \Exception('You do not have permission to edit the complaint.', 401);
        }
        DB::transaction(function () use ($user,$id, $note, $userId, &$complaint) {
            $complaint =$this->repository->getById($user,$id );
            if (!$complaint) {
                throw new \Exception('the complaint is not found.');
            }
            if ($complaint->locked_by &&
                $complaint->locked_by != $userId && $complaint->lock_expires_at > now()) {
                throw new \Exception('the complaint is reserved by another user.', 429);
            }
            $complaint=  $this->repository->addNote($user,$id,$userId,$note);
        });

        return [
            'data'    => $complaint,
            'message' => 'the complaint has been successfully modified.',
        ];
    }


public  function getAll(){
    $user = Auth::user();
 //   $userId=$user->id;
    if (optional(!Auth::user())->hasRole('employee')) {
        throw new \Exception('You do not have permission to edit the complaint.', 401);
    }

    $complaint=$this->repository->getAll($user);
    return [
        'data'    => $complaint,
        'message' => 'success.',
    ];

}











    }
