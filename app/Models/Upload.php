<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'file_path',
        'file_name',        
        'file_hash',
        'status',
        'failure_reason',
        'uploaded_at',
    ];

}

