<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    //Menampilkan nama tabel yang digunakan oleh model ini
    protected $table = 'tbl_teachers';
    protected $fillable = [
        'user_id',
        'subject_id',
        'nip',
        'name',
        'gender',
    ];

    //Menyiapkan hubungan antara model Teacher dan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Menyiapkan hubungan antara model Teacher dan model Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
