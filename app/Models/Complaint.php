<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'department',
        'title',
        'description',
        'attachment_path',
        'status',
        'resolved_at',
    ];

    /**
     * العلاقة: الشكوى تنتمي إلى مستخدم واحد (المواطن).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
