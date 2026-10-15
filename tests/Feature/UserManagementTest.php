<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_admin_cannot_access_user_management()
    {
        $user = User::factory()->student()->create();

        $this->actingAs($user)->get(route('users.index'))->assertStatus(403);
    }

    /** @test */
    public function admin_can_deactivate_and_reactivate_user()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->student()->create();

        $this->actingAs($admin)->post(route('users.toggle-status', $user->id))
            ->assertRedirect(route('users.index'));
        $this->assertSame(0, (int) $user->fresh()->status);

        $this->actingAs($admin)->post(route('users.toggle-status', $user->id));
        $this->assertSame(1, (int) $user->fresh()->status);
    }

    /** @test */
    public function admin_can_reset_password_and_cannot_target_self()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->student()->create();
        $oldHash = $user->password;

        $this->actingAs($admin)->post(route('users.reset-password', $user->id))
            ->assertRedirect(route('users.index'));
        $this->assertNotSame($oldHash, $user->fresh()->password);

        $this->actingAs($admin)->post(route('users.toggle-status', $admin->id))->assertStatus(403);
    }
}