<?php

namespace Tests\Feature;

use App\Models\CobitActivity;
use App\Models\CobitObjective;
use App\Models\CobitPractice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CobitExplorerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected CobitObjective $objEDM01;
    protected CobitObjective $objAPO01;
    protected CobitPractice $practiceEDM0101;
    protected CobitPractice $practiceEDM0102;
    protected CobitActivity $activity1;
    protected CobitActivity $activity2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'auditor@example.com',
        ]);

        $this->objEDM01 = CobitObjective::create([
            'objective_id' => 'EDM01',
            'area' => 'Governance',
            'domain' => 'Evaluate, Direct and Monitor',
            'name' => 'Ensured Governance Framework Setting and Maintenance',
            'description' => 'Analyze and articulate the requirements for the governance of enterprise IT.',
            'purpose_statement' => 'Provide a consistent approach integrated and aligned with the enterprise governance approach.',
            'new_in_cobit_2019' => false,
            'new_in_cobit_5' => true,
        ]);

        $this->objAPO01 = CobitObjective::create([
            'objective_id' => 'APO01',
            'area' => 'Management',
            'domain' => 'Align, Plan and Organize',
            'name' => 'Managed I&T Management Framework',
            'description' => 'Design the management system for enterprise I&T.',
            'purpose_statement' => 'Implement a consistent management approach for enterprise governance.',
            'new_in_cobit_2019' => true,
            'new_in_cobit_5' => false,
        ]);

        $this->practiceEDM0101 = CobitPractice::create([
            'cobit_objective_id' => $this->objEDM01->id,
            'practice_id' => 'EDM01.01',
            'name' => 'Evaluate the governance system.',
            'description' => 'Continually identify and engage with the enterprise stakeholders.',
        ]);

        $this->practiceEDM0102 = CobitPractice::create([
            'cobit_objective_id' => $this->objEDM01->id,
            'practice_id' => 'EDM01.02',
            'name' => 'Direct the governance system.',
            'description' => 'Inform leaders on I&T governance principles.',
        ]);

        $this->activity1 = CobitActivity::create([
            'cobit_practice_id' => $this->practiceEDM0101->id,
            'sequence' => 1,
            'description' => 'Analyze and identify the internal and external environment factors.',
        ]);

        $this->activity2 = CobitActivity::create([
            'cobit_practice_id' => $this->practiceEDM0101->id,
            'sequence' => 2,
            'description' => 'Determine the significance of I&T for the business strategy.',
        ]);
    }

    /**
     * 1. Test bahwa halaman framework dapat diakses dan merender header & metrics.
     */
    public function test_cobit_framework_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->user)->get(route('cobit.framework'));
        $response->assertStatus(200);
        $response->assertSee('COBIT Framework Explorer');
        $response->assertSee('EDM01');
    }

    /**
     * 2. Test filter domain memilih domain yang tepat.
     */
    public function test_cobit_explorer_filters_by_domain(): void
    {
        Livewire::actingAs($this->user)
            ->test('cobit-explorer')
            ->call('setDomain', 'APO')
            ->assertSet('selectedDomain', 'APO')
            ->assertSee('APO01')
            ->assertDontSee('EDM01')
            ->call('setDomain', '')
            ->assertSet('selectedDomain', '')
            ->assertSee('EDM01')
            ->assertSee('APO01');
    }

    /**
     * 3. Test pencarian teks pada objective, practice, dan activity.
     */
    public function test_cobit_explorer_searches_objective_practice_and_activity(): void
    {
        // Search by objective name
        Livewire::actingAs($this->user)
            ->test('cobit-explorer')
            ->set('search', 'Governance Framework')
            ->assertSee('EDM01')
            ->assertDontSee('APO01');

        // Search by practice ID
        Livewire::actingAs($this->user)
            ->test('cobit-explorer')
            ->set('search', 'EDM01.02')
            ->assertSee('EDM01')
            ->assertDontSee('APO01');

        // Search by activity keyword
        Livewire::actingAs($this->user)
            ->test('cobit-explorer')
            ->set('search', 'external environment')
            ->assertSee('EDM01')
            ->assertDontSee('APO01');
    }

    /**
     * 4. Test memilih objective menampilkan practice dan activity-nya.
     */
    public function test_cobit_explorer_selecting_objective_renders_practices_and_activities(): void
    {
        Livewire::actingAs($this->user)
            ->test('cobit-explorer')
            ->call('selectObjective', 'EDM01')
            ->assertSet('selectedObjectiveId', 'EDM01')
            ->assertSee('Evaluate the governance system.')
            ->assertSee('Direct the governance system.')
            ->assertSee('Analyze and identify the internal and external environment factors.')
            ->assertSee('Determine the significance of I&T for the business strategy.');
    }

    /**
     * 5. Test toggle expand/collapse all practices.
     */
    public function test_cobit_explorer_toggle_expand_all_practices(): void
    {
        Livewire::actingAs($this->user)
            ->test('cobit-explorer')
            ->assertSet('expandAllPractices', false)
            ->call('toggleExpandAll')
            ->assertSet('expandAllPractices', true)
            ->call('toggleExpandAll')
            ->assertSet('expandAllPractices', false);
    }

    /**
     * 6. Test bahwa tombol modal inspeksi tidak ada dan aktivitas tampil langsung di accordion.
     */
    public function test_cobit_explorer_renders_activities_without_inspection_modal(): void
    {
        Livewire::actingAs($this->user)
            ->test('cobit-explorer')
            ->call('selectObjective', 'EDM01')
            ->assertDontSee('Inspeksi')
            ->assertDontSee('Buka Modal Inspeksi Rinci')
            ->assertSee('2 Activities')
            ->assertSee('Analyze and identify the internal and external environment factors.');
    }

    /**
     * 7. Test link RACI Matrix memuat parameter pencarian yang tepat.
     */
    public function test_cobit_explorer_raci_link_contains_search_param(): void
    {
        Livewire::actingAs($this->user)
            ->test('cobit-explorer')
            ->call('selectObjective', 'EDM01')
            ->assertSee(route('raci.matrix') . '?search=EDM01');
    }

    /**
     * 8. Test badge New in COBIT 2019 ditampilkan jika berlaku.
     */
    public function test_cobit_explorer_renders_new_in_cobit_badge(): void
    {
        Livewire::actingAs($this->user)
            ->test('cobit-explorer')
            ->call('selectObjective', 'APO01')
            ->assertSee('New in COBIT 2019');
    }

    /**
     * 9. Test layer kanan detail tersembunyi pada saat All dan baru muncul saat objective dipilih.
     */
    public function test_cobit_explorer_right_detail_hidden_on_all_and_shown_on_select_objective(): void
    {
        Livewire::actingAs($this->user)
            ->test('cobit-explorer')
            // Default load pada All: layer kanan tidak muncul
            ->assertSet('selectedDomain', '')
            ->assertSet('selectedObjectiveId', null)
            ->assertDontSee('Purpose Statement:')
            // Ketika memilih objective: layer kanan muncul
            ->call('selectObjective', 'EDM01')
            ->assertSet('selectedObjectiveId', 'EDM01')
            ->assertSee('Purpose Statement:')
            // Ketika tombol tutup ditekan: layer kanan tertutup kembali
            ->call('closeDetail')
            ->assertSet('selectedObjectiveId', null)
            ->assertDontSee('Purpose Statement:')
            // Ketika memilih objective lalu menekan All / reset domain: layer kanan tertutup kembali
            ->call('selectObjective', 'EDM01')
            ->assertSet('selectedObjectiveId', 'EDM01')
            ->call('setDomain', '')
            ->assertSet('selectedDomain', '')
            ->assertSet('selectedObjectiveId', null)
            ->assertDontSee('Purpose Statement:');
    }
}
