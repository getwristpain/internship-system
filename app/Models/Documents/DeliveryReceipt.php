<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id',
        'file_path',
        'file_upload',
        'type',
        'verified_status',
        'verified_by_teacher',
    ];
}
