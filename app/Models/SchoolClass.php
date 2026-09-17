<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;
    //Menampilkan nama tabel yang digunakan oleh model ini
    protected $table = 'tbl_classes';
    protected $fillable = [
        'name',
        'level',
    ];

    //Menyiapkan hubungan antara model SchoolClass dan model Student
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}
