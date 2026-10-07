<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_view_class_index_and_data_json()
    {
        $user = User::factory()->student()->create();

        $this->actingAs($user)
            ->get(route('classes.index'))
            ->assertStatus(200);

        $this->actingAs($user)
            ->get(route('classes.data'))
            ->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    /** @test */
    public function non_admin_cannot_access_create_class_page()
    {
        $user = User::factory()->student()->create();

        $this->actingAs($user)
            ->get(route('classes.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_store_new_class()
    {
        $admin = User::factory()->admin()->create();

        $classData = [
            'name' => 'XII IPA 1',
            'level' => 'XII',
        ];

        $response = $this->actingAs($admin)
            ->post(route('classes.store'), $classData);

        $response->assertRedirect(route('classes.index'));
        $this->assertDatabaseHas('tbl_classes', [
            'name' => 'XII IPA 1',
        ]);
    }
}