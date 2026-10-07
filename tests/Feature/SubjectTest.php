<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_view_subject_index_and_data_json()
    {
        $user = User::factory()->student()->create();

        $this->actingAs($user)
            ->get(route('subjects.index'))
            ->assertStatus(200);

        $this->actingAs($user)
            ->get(route('subjects.data'))
            ->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    /** @test */
    public function non_admin_cannot_access_create_subject_page()
    {
        $user = User::factory()->student()->create();

        $this->actingAs($user)
            ->get(route('subjects.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_store_new_subject()
    {
        $admin = User::factory()->admin()->create();

        $subjectData = [
            'code' => 'MTK-01',
            'name' => 'Matematika',
        ];

        $response = $this->actingAs($admin)
            ->post(route('subjects.store'), $subjectData);

        $response->assertRedirect(route('subjects.index'));
        $this->assertDatabaseHas('tbl_subjects', [
            'code' => 'MTK-01',
        ]);
    }
}