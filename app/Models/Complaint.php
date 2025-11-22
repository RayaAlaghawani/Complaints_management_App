<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'note',
        'title',
        'locked_by' ,
        'locked_at' ,
        'lock_expires_at' ,

        'description',
        'attachment_path',
        'status',
        'resolved_at',
        'government_agencie_id'
    ];

    /**
     * العلاقة: الشكوى تنتمي إلى مستخدم واحد (المواطن).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
