<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberDepartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'title_id',
        'remark',
        'member_id',
        'writer_id',
        // 'is_review'
    ];
}
