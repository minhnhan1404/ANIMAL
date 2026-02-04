<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    // Cho phép lưu các thông tin này vào Database
    protected $fillable = [
        'name',
        'status',      // Lưu Tình trạng (Vd: Tuyệt chủng, Nguy cấp)
        'behavior',    // Lưu Tập tính
        'description'  // Mô tả chi tiết
    ];
}
