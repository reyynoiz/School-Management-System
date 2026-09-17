<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    //Menampilkan nama tabel yang digunakan oleh model ini
    protected $table = 'tbl_students';
    protected $fillable = [
        'user_id',
        'class_id',
        'nis',
        'name',
        'gender',
    ];

    //Menyiapkan hubungan antara model Student dan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Menyiapkan hubungan antara model Student dan model SchoolClass
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
