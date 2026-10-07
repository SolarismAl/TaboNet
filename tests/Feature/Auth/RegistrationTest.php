<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_new_farmer_can_register_with_tabonet_fields(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Mang Pedro Cantilan',
            'email' => 'pedro.farmer@example.com',
            'role' => 'farmer',
            'phone_number' => '09171234567',
            'barangay' => 'Linotan',
            'rsbsa_number' => '16-68-04-001-000123',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'pedro.farmer@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals('farmer', $user->role);
        $this->assertEquals('Linotan', $user->barangay);
        $this->assertEquals('09171234567', $user->phone_number);
        $this->assertEquals('16-68-04-001-000123', $user->rsbsa_number);
        $this->assertEquals('pending_verification', $user->status);
        $this->assertTrue($user->isFarmer());

        $response->assertSessionHasNoErrors();
    }
}
