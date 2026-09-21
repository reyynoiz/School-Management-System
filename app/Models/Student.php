<?php

namespace App\Models;

use App\Traits\HasArchive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory, HasArchive;

    protected $table = 'tbl_students';

    protected $fillable = [
        'user_id',
        'class_id',
        'nis',
        'name',
        'gender',
        'archived',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}