<?php

namespace Tests\Feature;

use App\Models\CobitObjective;
use App\Models\CobitPractice;
use App\Models\CobitRaciAssignment;
use App\Models\CobitRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RaciMatrixTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected CobitRole $roleBoard;
    protected CobitRole $roleCIO;
    protected CobitObjective $objEDM01;
    protected CobitObjective $objAPO01;
    protected CobitPractice $practiceEDM0101;
    protected CobitPractice $practiceAPO0101;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'auditor@example.com',
        ]);

        $this->roleBoard = CobitRole::create([
            'code' => 'board',
            'sequence' => 1,
            'name' => 'Board',
            'short_name' => 'Board',
        ]);

        $this->roleCIO = CobitRole::create([
            'code' => 'cio',
            'sequence' => 7,
            'name' => 'Chief Information Officer',
            'short_name' => 'CIO',
        ]);

        $this->objEDM01 = CobitObjective::create([
            'objective_id' => 'EDM01',
            'area' => 'Governance',
            'domain' => 'Evaluate, Direct and Monitor',
            'name' => 'Ensured Governance Framework Setting and Maintenance',
            'description' => 'Analyze and articulate governance requirements.',
        ]);

        $this->objAPO01 = CobitObjective::create([
            'objective_id' => 'APO01',
            'area' => 'Management',
            'domain' => 'Align, Plan and Organize',
            'name' => 'Managed I&T Management Framework',
            'description' => 'Design the management system.',
        ]);

        $this->practiceEDM0101 = CobitPractice::create([
            'cobit_objective_id' => $this->objEDM01->id,
            'practice_id' => 'EDM01.01',
            'name' => 'Evaluate the governance system.',
            'description' => 'Continually identify and engage with stakeholders.',
        ]);

        $this->practiceAPO0101 = CobitPractice::create([
            'cobit_objective_id' => $this->objAPO01->id,
            'practice_id' => 'APO01.01',
            'name' => 'Design the I&T management system.',
            'description' => 'Design the management system.',
        ]);

        // Assignments:
        // EDM01.01: Board is A, CIO is R
        CobitRaciAssignment::create([
            'cobit_practice_id' => $this->practiceEDM0101->id,
            'cobit_role_id' => $this->roleBoard->id,
            'raci_level' => 'A',
        ]);

        CobitRaciAssignment::create([
            'cobit_practice_id' => $this->practiceEDM0101->id,
            'cobit_role_id' => $this->roleCIO->id,
            'raci_level' => 'R',
        ]);

        // APO01.01: CIO is A
        CobitRaciAssignment::create([
            'cobit_practice_id' => $this->practiceAPO0101->id,
            'cobit_role_id' => $this->roleCIO->id,
            'raci_level' => 'A',
        ]);
    }

    /**
     * 1. Test bahwa halaman RACI Matrix dapat diakses dan merender header & metrics.
     */
    public function test_raci_matrix_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->user)->get(route('raci.matrix'));
        $response->assertStatus(200);
        $response->assertSee('Matriks RACI Tata Kelola &amp; Manajemen', false);
        $response->assertSee('EDM01.01');
        $response->assertSee('Board');
        $response->assertSee('CIO');
    }

    /**
     * 2. Test filter domain memilih domain yang tepat.
     */
    public function test_raci_matrix_filters_by_domain(): void
    {
        Livewire::actingAs($this->user)
            ->test('raci-matrix')
            ->call('setDomain', 'EDM')
            ->assertSet('selectedDomain', 'EDM')
            ->assertSee('EDM01.01')
            ->assertDontSee('APO01.01')
            ->call('setDomain', 'APO')
            ->assertSet('selectedDomain', 'APO')
            ->assertSee('APO01.01')
            ->assertDontSee('EDM01.01');
    }

    /**
     * 3. Test filter role memilih praktik yang memiliki penugasan role tersebut.
     */
    public function test_raci_matrix_filters_by_role(): void
    {
        Livewire::actingAs($this->user)
            ->test('raci-matrix')
            ->set('filterRole', (string) $this->roleBoard->id)
            ->assertSee('EDM01.01')
            ->assertDontSee('APO01.01');
    }

    /**
     * 4. Test filter level A (Accountable) dan R (Responsible).
     */
    public function test_raci_matrix_filters_by_raci_level(): void
    {
        // When filtering Board + Level R -> Board has no R, so EDM01.01 shouldn't match
        Livewire::actingAs($this->user)
            ->test('raci-matrix')
            ->set('filterRole', (string) $this->roleBoard->id)
            ->set('filterLevel', 'R')
            ->assertDontSee('EDM01.01');

        // When filtering Board + Level A -> Board is Accountable for EDM01.01
        Livewire::actingAs($this->user)
            ->test('raci-matrix')
            ->set('filterRole', (string) $this->roleBoard->id)
            ->set('filterLevel', 'A')
            ->assertSee('EDM01.01');
    }

    /**
     * 5. Test pencarian kode praktik dan kode objective.
     */
    public function test_raci_matrix_searches_by_practice_and_objective(): void
    {
        Livewire::actingAs($this->user)
            ->test('raci-matrix')
            ->set('search', 'EDM01')
            ->assertSee('EDM01.01')
            ->assertDontSee('APO01.01');
    }

    /**
     * 6. Test toggle highlight role.
     */
    public function test_raci_matrix_toggle_highlight_role(): void
    {
        Livewire::actingAs($this->user)
            ->test('raci-matrix')
            ->assertSet('highlightRoleId', null)
            ->call('toggleHighlightRole', $this->roleCIO->id)
            ->assertSet('highlightRoleId', $this->roleCIO->id)
            ->call('toggleHighlightRole', $this->roleCIO->id)
            ->assertSet('highlightRoleId', null);
    }

    /**
     * 7. Test modal inspeksi praktik.
     */
    public function test_raci_matrix_inspect_practice_modal(): void
    {
        Livewire::actingAs($this->user)
            ->test('raci-matrix')
            ->assertSet('inspectPracticeId', null)
            ->call('inspectPractice', $this->practiceEDM0101->id)
            ->assertSet('inspectPracticeId', $this->practiceEDM0101->id)
            ->assertSee('EDM01.01')
            ->assertSee('Evaluate the governance system.')
            ->call('closePracticeModal')
            ->assertSet('inspectPracticeId', null);
    }

    /**
     * 8. Test modal inspeksi role.
     */
    public function test_raci_matrix_inspect_role_modal(): void
    {
        Livewire::actingAs($this->user)
            ->test('raci-matrix')
            ->assertSet('inspectRoleId', null)
            ->call('inspectRole', $this->roleBoard->id)
            ->assertSet('inspectRoleId', $this->roleBoard->id)
            ->assertSee('Board')
            ->assertSee('Accountable (Pengambil Keputusan Utama)')
            ->call('closeRoleModal')
            ->assertSet('inspectRoleId', null);
    }

    /**
     * 9. Test mount membaca query string search otomatis.
     */
    public function test_raci_matrix_reads_query_string_on_mount(): void
    {
        $this->actingAs($this->user);

        // Simulate GET /raci-matrix?search=EDM01
        $this->get(route('raci.matrix', ['search' => 'EDM01']))
            ->assertStatus(200)
            ->assertSee('EDM01.01');
    }
}
