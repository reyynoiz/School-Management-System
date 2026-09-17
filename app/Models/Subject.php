<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    //Menampilkan nama tabel yang digunakan oleh model ini
    protected $table = 'tbl_subjects';
    protected $fillable = [
        'code',
        'name',
    ];

    //Menyiapkan hubungan antara model Subject dan model Teacher
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'subject_id');
    }
}
