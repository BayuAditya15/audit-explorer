<?php

namespace Tests\Feature;

use App\Models\CobitObjective;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ResearchHubTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'researcher@example.com',
        ]);

        CobitObjective::create([
            'objective_id' => 'EDM01',
            'area' => 'Governance',
            'domain' => 'Evaluate, Direct and Monitor',
            'name' => 'Ensured Governance Framework Setting and Maintenance',
            'description' => 'Analyze and articulate governance requirements.',
            'purpose_statement' => 'Provide a consistent approach aligned with enterprise approach.',
        ]);

        CobitObjective::create([
            'objective_id' => 'APO01',
            'area' => 'Management',
            'domain' => 'Align, Plan and Organize',
            'name' => 'Managed I&T Management Framework',
            'description' => 'Design the management system.',
            'purpose_statement' => 'Implement a consistent management approach.',
        ]);
    }

    /**
     * 1. Test bahwa halaman Research Hub dapat diakses oleh user terautentikasi.
     */
    public function test_research_hub_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->user)->get(route('research.hub'));
        $response->assertStatus(200);
        $response->assertSee('Research &amp; Methodology Hub', false);
        $response->assertSee('Landasan Teori Baku (ISACA)');
        $response->assertSee('5 Domain &amp; 40 Subdomain', false);
        $response->assertSee('Metodologi &amp; Gap Analysis', false);
    }

    /**
     * 2. Test tab teori menampilkan standar resmi ISACA: Prinsip Tata Kelola, Goals Cascade, 7 Enablers, dan 11 Design Factors.
     */
    public function test_research_hub_renders_theory_tab(): void
    {
        Livewire::actingAs($this->user)
            ->test('research-hub')
            ->assertSet('activeTab', 'theory')
            ->assertSee('Tata Kelola (Governance)')
            ->assertSee('Manajemen (Management)')
            ->assertSee('Enam Prinsip Sistem Tata Kelola')
            ->assertSee('Tiga Prinsip Kerangka Kerja Tata Kelola')
            ->assertSee('Alur COBIT Goals Cascade')
            ->assertSee('13 Enterprise Goals (EG)')
            ->assertSee('13 Alignment Goals (AG)')
            ->assertSee('Tujuh Komponen Sistem Tata Kelola')
            ->assertSee('Sebelas Faktor Desain &amp; Tiga Dampak Desain', false);
    }

    /**
     * 3. Test tab domain menampilkan 5 domain dan dapat difilter.
     */
    public function test_research_hub_renders_domains_and_filters(): void
    {
        Livewire::actingAs($this->user)
            ->test('research-hub')
            ->call('setTab', 'domains')
            ->assertSet('activeTab', 'domains')
            ->assertSee('Evaluate, Direct &amp; Monitor', false)
            ->assertSee('Align, Plan &amp; Organize', false)
            ->assertSee('EDM01')
            ->assertSee('APO01')
            ->call('setDomainFilter', 'EDM')
            ->assertSet('selectedDomainFilter', 'EDM')
            ->assertSee('EDM01')
            ->assertDontSee('APO01');
    }

    /**
     * 4. Test tab metodologi merender 4 tahapan desain sistem, skala kapabilitas 0-5, aturan milestone NPLF, dan rumus GAP Analysis.
     */
    public function test_research_hub_renders_methodology_and_gap_analysis(): void
    {
        Livewire::actingAs($this->user)
            ->test('research-hub')
            ->call('setTab', 'methodology')
            ->assertSet('activeTab', 'methodology')
            ->assertSee('Alur Perancangan Sistem Tata Kelola (ISACA 4-Stage Workflow)')
            ->assertSee('Stage 1: Pahami Konteks &amp; Strategi', false)
            ->assertSee('Stage 4: Finalisasi Desain &amp; Aksi', false)
            ->assertSee('Level 0: Incomplete')
            ->assertSee('Level 5: Optimizing')
            ->assertSee('Skala Penilaian Ketercapaian (NPLF Scale - ISO/IEC 33020)')
            ->assertSee('Aturan Emas Milestone ISACA (Milestone Rule)')
            ->assertSee('GAP = Target Capability Level − Current Capability Level')
            ->assertSee('Prinsip Penugasan Matriks RACI Standar ISACA');
    }

    /**
     * 5. Test tab glosarium dan fitur pencariannya.
     */
    public function test_research_hub_glossary_search(): void
    {
        Livewire::actingAs($this->user)
            ->test('research-hub')
            ->call('setTab', 'glossary')
            ->assertSet('activeTab', 'glossary')
            ->assertSee('EGIT')
            ->assertSee('Goals Cascade')
            ->assertSee('Milestone Rule')
            ->assertSee('RACI Matrix')
            ->set('glossarySearch', 'SLA')
            ->assertSee('Service Level Agreement (SLA)')
            ->assertDontSee('Design Factors');
    }

    /**
     * 6. Test tab penelitian terdahulu menampilkan matriks literatur.
     */
    public function test_research_hub_literature_matrix(): void
    {
        Livewire::actingAs($this->user)
            ->test('research-hub')
            ->call('setTab', 'literature')
            ->assertSet('activeTab', 'literature')
            ->assertSee('Matriks Penelitian Terdahulu (State of the Art)')
            ->assertSee('Pratama et al. (2022)')
            ->assertSee('Hidayat &amp; Raharjo (2023)', false);
    }

    /**
     * 7. Test tab diagram & gambar skripsi menampilkan seluruh gambar berstandar ISACA.
     */
    public function test_research_hub_renders_figures_tab(): void
    {
        Livewire::actingAs($this->user)
            ->test('research-hub')
            ->call('setTab', 'figures')
            ->assertSet('activeTab', 'figures')
            ->assertSee('Daftar Diagram &amp; Gambar Skripsi (Bab 2 &amp; Bab 3)', false)
            ->assertSee('Gambar 2.1')
            ->assertSee('Gambar 2.2')
            ->assertSee('Gambar 2.3')
            ->assertSee('Gambar 2.4')
            ->assertSee('Gambar 3.1')
            ->assertSee('Gambar 3.2')
            ->assertSee('Gambar 3.3');
    }
}
