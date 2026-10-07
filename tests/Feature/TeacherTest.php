<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_view_teacher_index_and_data_json()
    {
        $user = User::factory()->student()->create();

        $this->actingAs($user)
            ->get(route('teachers.index'))
            ->assertStatus(200);

        $this->actingAs($user)
            ->get(route('teachers.data'))
            ->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    /** @test */
    public function non_admin_cannot_access_create_teacher_page()
    {
        $user = User::factory()->student()->create();

        $this->actingAs($user)
            ->get(route('teachers.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_access_create_teacher_page()
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('teachers.create'))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_new_teacher()
    {
        $admin = User::factory()->admin()->create();
        $subject = Subject::create(['code' => 'MTK-01', 'name' => 'Matematika']);

        $teacherData = [
            'nip' => '198001012020',
            'name' => 'Siti Aminah',
            'gender' => 'P',
            'subject_id' => $subject->id,
        ];

        $response = $this->actingAs($admin)
            ->post(route('teachers.store'), $teacherData);

        $response->assertRedirect(route('teachers.index'));
        $this->assertDatabaseHas('tbl_teachers', [
            'nip' => '198001012020',
            'name' => 'Siti Aminah',
        ]);
    }

    /** @test */
    public function admin_can_soft_delete_or_archive_teacher()
    {
        $admin = User::factory()->admin()->create();
        $teacher = Teacher::create([
            'nip' => '000111222',
            'name' => 'Guru Dihapus',
            'gender' => 'L',
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('teachers.destroy', $teacher->id));

        $response->assertRedirect(route('teachers.index'));
        $this->assertDatabaseHas('tbl_teachers', [
            'id' => $teacher->id,
            'archived' => 1,
        ]);
    }
}