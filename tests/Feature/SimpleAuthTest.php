<?php

namespace Tests\Feature;

use App\Models\CobitObjective;
use App\Models\CobitPractice;
use App\Models\CobitActivity;
use App\Models\CobitRole;
use App\Models\CobitRaciAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SimpleAuthTest extends TestCase
{
    use RefreshDatabase;

    private function seedCoreCobitData(): void
    {
        $objective = CobitObjective::create([
            'objective_id' => 'EDM01',
            'domain' => 'EDM',
            'area' => 'Governance',
            'name' => 'Ensured Governance Framework Setting and Maintenance',
            'description' => 'Test objective description',
            'purpose_statement' => 'Test objective purpose',
            'new_in_cobit_2019' => false,
            'new_in_cobit_5' => false,
        ]);

        $practice = CobitPractice::create([
            'cobit_objective_id' => $objective->id,
            'practice_id' => 'EDM01.01',
            'name' => 'Evaluate the governance system',
            'description' => 'Test practice description',
        ]);

        CobitActivity::create([
            'cobit_practice_id' => $practice->id,
            'sequence' => 1,
            'description' => 'Identify and engage with stakeholders',
        ]);

        $role = CobitRole::create([
            'code' => 'BOD',
            'name' => 'Board',
            'short_name' => 'Board',
            'sequence' => 1,
        ]);

        CobitRaciAssignment::create([
            'cobit_practice_id' => $practice->id,
            'cobit_role_id' => $role->id,
            'raci_level' => 'A',
        ]);
    }

    public function test_guest_is_redirected_to_login_page_from_protected_routes(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');

        $responseFramework = $this->get('/cobit-framework');
        $responseFramework->assertRedirect('/login');

        $responseRaci = $this->get('/raci-matrix');
        $responseRaci->assertRedirect('/login');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('COBIT Explorer');
        $response->assertSee('Masuk ke Akun');
        $response->assertSee('Alamat Email');
        $response->assertSee('Kata Sandi');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@cobit.local',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user@cobit.local',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('cobit.overview'));
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'user@cobit.local',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'user@cobit.local',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_has_full_access_to_all_menus_without_roles(): void
    {
        $this->seedCoreCobitData();

        $user = User::factory()->create([
            'name' => 'Regular Member',
            'email' => 'member@cobit.local',
        ]);

        $responseOverview = $this->actingAs($user)->get('/');
        $responseOverview->assertStatus(200);
        $responseOverview->assertSee('Overview');
        $responseOverview->assertSee('Regular Member');

        $responseFramework = $this->actingAs($user)->get('/cobit-framework');
        $responseFramework->assertStatus(200);
        $responseFramework->assertSee('EDM01');
        $responseFramework->assertSee('Ensured Governance Framework Setting and Maintenance');

        $responseRaci = $this->actingAs($user)->get('/raci-matrix');
        $responseRaci->assertStatus(200);
        $responseRaci->assertSee('COBIT 2019 RACI Matrix');
        $responseRaci->assertSee('Board');
    }

    public function test_user_can_logout_and_session_is_cleared(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
