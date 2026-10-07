<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_view_student_index_and_data_json()
    {
        $studentUser = User::factory()->student()->create();

        // Cek akses halaman index
        $this->actingAs($studentUser)
            ->get(route('students.index'))
            ->assertStatus(200);

        // Cek akses data JSON
        $this->actingAs($studentUser)
            ->get(route('students.data'))
            ->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    /** @test */
    public function non_admin_user_cannot_access_create_student_page()
    {
        $studentUser = User::factory()->student()->create();

        // Non-admin mencoba buka halaman tambah siswa
        $this->actingAs($studentUser)
            ->get(route('students.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_access_create_student_page()
    {
        $admin = User::factory()->admin()->create();

        // Admin berhasil buka halaman tambah siswa
        $this->actingAs($admin)
            ->get(route('students.create'))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_new_student()
    {
        $admin = User::factory()->admin()->create();
        $class = SchoolClass::create(['name' => 'X IPA 1', 'level' => 'X']);
        $linkedUser = User::factory()->student()->create();

        $studentData = [
            'nis' => '123456',
            'name' => 'Budi Santoso',
            'gender' => 'L',
            'class_id' => $class->id,
            'user_id' => $linkedUser->id,
        ];

        // Admin menyimpan data siswa baru
        $response = $this->actingAs($admin)
            ->post(route('students.store'), $studentData);

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('tbl_students', [
            'nis' => '123456',
            'name' => 'Budi Santoso',
        ]);
    }

    /** @test */
    public function admin_can_soft_delete_or_archive_student()
    {
        $admin = User::factory()->admin()->create();
        $student = Student::create([
            'nis' => '99999',
            'name' => 'Siswa Dihapus',
            'gender' => 'P',
        ]);

        // Admin menghapus/mengarsip siswa
        $response = $this->actingAs($admin)
            ->delete(route('students.destroy', $student->id));

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('tbl_students', [
            'id' => $student->id,
            'archived' => 1,
        ]);
    }
}