<?php

namespace App\Repositories;

use App\Http\Requests\ComplaintRequest;
use App\Models\Complaint;

class ComplaintRepository
{

    public function getById($user, $id)
    {

        return Complaint::where('id', $id)->where('government_agencie_id', $user->government_agencie->id)->lockForUpdate()->first();

    }

    public function update($user, $id, $userId, $status)
    {
        $complaint = $this->getById($user, $id);

        $complaint->update([
            'locked_by' => $userId,
            'locked_at' => now(),
            'lock_expires_at' => now()->addMinutes(1),
            'status' => $status,
        ]);

        return $complaint->fresh();
    }

    public function addNote($user, $id, $userId, $note)
    {
        $complaint = $this->getById($user, $id);
        $complaint->update([
            'locked_by' => $userId,
            'locked_at' => now(),
            'lock_expires_at' => now()->addMinutes(1),
            'note' => $note,
        ]);

        return $complaint->fresh();
    }

    public function getAll($user)
    {
        return Complaint::where('government_agencie_id', $user->government_agencie->id)->get();
    }
}
