<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    public function test_user_can_edit_profile()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'email' => 'user@example.com',
            'phone' => '8 123 456 78 90'
        ]);

        $updatedData = [
            'name' => 'Новое имя',
            'email' => 'new-user@example.com',
            'phone' => '8 999 999 99 99',
        ];

        $response = $this->actingAs($user)->put('/profile', $updatedData);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'name' => 'Новое имя',
            'email' => 'new-user@example.com',
            'phone' => '8 999 999 99 99',
        ]);
        $this->assertEquals(session('status'), 'Email изменен. Пожалуйста, подтвердите новый email.');
    }

    public function test_email_verification_is_reset_when_email_changed()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
        ]);

        $updatedData = [
            'name' => 'Новое имя',
            'email' => 'new-user@example.com',
            'phone' => '8 999 999 99 99',
        ];

        $response = $this->actingAs($user)->put('/profile', $updatedData);

        $response->assertRedirect('/dashboard');
        $this->assertNull($user->fresh()->email_verified_at);
        $this->assertEquals(session('status'), 'Email изменен. Пожалуйста, подтвердите новый email.');
    }

    public function test_admin_cannot_edit_profile()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $data = [
            'name' => 'Новое имя',
            'email' => 'new-admin@example.com',
            'phone' => '8 999 999 99 99',
        ];

        $response = $this->actingAs($admin)->put('/profile', $data);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error', 'Вы не можете редактировать свой профиль');
        $this->assertDatabaseMissing('users', ['name' => 'Новое имя']);
    }

    public function test_manager_cannot_edit_profile()
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $data = [
            'name' => 'Новое имя',
            'email' => 'new-manager@example.com',
            'phone' => '8 999 999 99 99',
        ];

        $response = $this->actingAs($manager)->put('/profile', $data);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error', 'Вы не можете редактировать свой профиль');
        $this->assertDatabaseMissing('users', ['name' => 'Новое имя']);
    }

    public function test_name_is_required()
    {
        $user = User::factory()->create(['role' => 'user']);

        $data = [
            'name' => '',
            'email' => 'new@example.com',
            'phone' => '8 999 999 99 99',
        ];

        $response = $this->actingAs($user)->put('/profile', $data);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('users', ['email' => 'new@example.com']);
    }

    public function test_email_is_required_and_must_be_unique()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'email' => 'user@example.com',
        ]);

        // Не заполнен email
        $response1 = $this->actingAs($user)->put('/profile', [
            'name' => 'Новое имя',
            'email' => '',
            'phone' => '8 999 999 99 99',
        ]);
        $response1->assertSessionHasErrors(['email']);

        // Email уже занят
        $otherUser = User::factory()->create(['email' => 'other@example.com']);

        $response2 = $this->actingAs($user)->put('/profile', [
            'name' => 'Новое имя',
            'email' => 'other@example.com',
            'phone' => '8 999 999 99 99',
        ]);
        $response2->assertSessionHasErrors(['email']);
    }

    public function test_phone_is_required_and_must_be_unique_and_valid_format()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'phone' => '8 123 456 78 90',
        ]);

        // Не указан телефон
        $response1 = $this->actingAs($user)->put('/profile', [
            'name' => 'Новое имя',
            'email' => 'new@example.com',
            'phone' => '',
        ]);
        $response1->assertSessionHasErrors(['phone']);

        // Неверный формат телефона
        $response2 = $this->actingAs($user)->put('/profile', [
            'name' => 'Новое имя',
            'email' => 'new@example.com',
            'phone' => '1234567890',
        ]);
        $response2->assertSessionHasErrors(['phone']);

        // Телефон уже занят
        $otherUser = User::factory()->create(['phone' => '8 999 999 99 99']);

        $response3 = $this->actingAs($user)->put('/profile', [
            'name' => 'Новое имя',
            'email' => 'new@example.com',
            'phone' => '8 999 999 99 99',
        ]);
        $response3->assertSessionHasErrors(['phone']);
    }
}