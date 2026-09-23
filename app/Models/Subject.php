<?php

namespace App\Models;

use App\Traits\HasArchive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory, HasArchive;
    //Menampilkan nama tabel yang digunakan oleh model subject
    protected $table = 'tbl_subjects';
    protected $fillable = [
        'code',
        'name',
        'archived',
    ];

    //Menyiapkan hubungan antara model Subject dan model Teacher
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'subject_id');
    }
}
