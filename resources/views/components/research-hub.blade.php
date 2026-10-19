<?php

use App\Models\CobitObjective;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app', ['breadcrumb' => 'Research & Methodology Hub'])] class extends Component
{
    public string $activeTab = 'theory'; // theory, domains, methodology, glossary, literature, figures
    public string $glossarySearch = '';
    public string $selectedDomainFilter = 'ALL';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function setDomainFilter(string $domain): void
    {
        $this->selectedDomainFilter = $domain;
    }

    public function render()
    {
        $objectives = CobitObjective::query()
            ->when($this->selectedDomainFilter !== 'ALL', function ($query) {
                $query->where('objective_id', 'like', $this->selectedDomainFilter . '%');
            })
            ->orderBy('objective_id')
            ->get();

        return view('components.research-hub', [
            'objectives' => $objectives,
        ]);
    }
};
?>

<div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6 space-y-6 max-w-[1600px] mx-auto" x-data="{ copiedNotice: false, copyText(text) { navigator.clipboard.writeText(text); this.copiedNotice = true; setTimeout(() => this.copiedNotice = false, 2500); } }">
    <!-- NOTIFIKASI SALIN SITASI -->
    <div 
        x-show="copiedNotice" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-5 right-5 z-50 flex items-center gap-2 bg-on-surface text-surface-container-lowest px-4 py-2.5 rounded-xl shadow-lg text-xs font-semibold"
        style="display: none;"
    >
        <span class="material-symbols-outlined text-emerald-400 text-[18px]">check_circle</span>
        <span>Keterangan &amp; sitasi gambar berhasil disalin ke clipboard!</span>
    </div>

    <!-- 1. PAGE HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col min-w-0">
            <div class="flex items-center gap-1.5 text-xs text-outline mb-1 font-medium">
                <span>Academic &amp; Research</span>
                <span class="text-outline-variant">•</span>
                <span class="text-primary font-semibold">ISACA COBIT 2019 Official Standard</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-on-surface">
                Research &amp; Methodology Hub
            </h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5 max-w-3xl leading-relaxed">
                Panduan komprehensif berstandar resmi ISACA COBIT 2019.
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap self-start md:self-auto">
            <button 
                onclick="window.print()" 
                type="button" 
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-lowest hover:bg-surface-container-low border border-outline-variant/40 text-xs font-semibold text-on-surface transition-colors shadow-xs cursor-pointer"
            >
                <span class="material-symbols-outlined text-[16px] text-outline">print</span>
                <span>Print / Export Reference</span>
            </button>
        </div>
    </div>

    <!-- 2. NAVIGATION TABS -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-1.5 shadow-xs overflow-x-auto custom-scrollbar">
        <div class="flex items-center gap-1 min-w-max">
            <button 
                wire:click="setTab('theory')" 
                type="button" 
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $activeTab === 'theory' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}"
            >
                <span class="material-symbols-outlined text-[17px]">auto_stories</span>
                <span>Landasan Teori Baku (ISACA)</span>
            </button>

            <button 
                wire:click="setTab('figures')" 
                type="button" 
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $activeTab === 'figures' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}"
            >
                <span class="material-symbols-outlined text-[17px]">schema</span>
                <span>Diagram &amp; Gambar Skripsi (Bab 2 &amp; 3)</span>
            </button>

            <button 
                wire:click="setTab('domains')" 
                type="button" 
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $activeTab === 'domains' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}"
            >
                <span class="material-symbols-outlined text-[17px]">hub</span>
                <span>5 Domain &amp; 40 Subdomain</span>
            </button>

            <button 
                wire:click="setTab('methodology')" 
                type="button" 
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $activeTab === 'methodology' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}"
            >
                <span class="material-symbols-outlined text-[17px]">calculate</span>
                <span>Metodologi &amp; Gap Analysis</span>
            </button>

            <button 
                wire:click="setTab('glossary')" 
                type="button" 
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $activeTab === 'glossary' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}"
            >
                <span class="material-symbols-outlined text-[17px]">spellcheck</span>
                <span>Glosarium Istilah Baku</span>
            </button>

            <button 
                wire:click="setTab('literature')" 
                type="button" 
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $activeTab === 'literature' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}"
            >
                <span class="material-symbols-outlined text-[17px]">library_books</span>
                <span>Penelitian Terdahulu</span>
            </button>
        </div>
    </div>

    <!-- 3. TAB CONTENTS -->
    <!-- TAB 1: LANDASAN TEORI BAKU (ISACA COBIT 2019) -->
    @if($activeTab === 'theory')
        <div class="space-y-6 animate-in fade-in duration-200">
            <!-- 1.1 Definisi & Pengenalan COBIT 2019 -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                    <span class="w-8 h-8 rounded-lg bg-primary-fixed/50 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[19px]">menu_book</span>
                    </span>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">1. Konsep &amp; Definisi Resmi COBIT 2019</h2>
                        <p class="text-xs text-on-surface-variant">ISACA (Information Systems Audit and Control Association) — Framework Introduction and Methodology</p>
                    </div>
                </div>

                <div class="text-xs sm:text-sm text-on-surface-variant leading-relaxed space-y-3">
                    <p>
                        <strong>COBIT (Control Objectives for Information and Related Technology) 2019</strong> merupakan kerangka kerja (*framework*) komprehensif terstandar global yang dipublikasikan oleh <strong>ISACA</strong> untuk tata kelola dan manajemen teknologi informasi enterprise (*Enterprise Governance of Information and Technology / EGIT*). 
                    </p>
                    <p>
                        COBIT 2019 mendefinisikan bahwa tata kelola enterprise terhadap I&amp;T bukan merupakan tanggung jawab departemen TI semata, melainkan merupakan bagian integral dari tata kelola enterprise menyeluruh (*enterprise-wide governance*) yang dipimpin langsung oleh dewan komisaris/direksi bersama jajaran manajemen eksekutif.
                    </p>
                </div>

                <!-- Perbedaan Utama: Governance vs Management (ISACA Section 1.3) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div class="p-4 rounded-xl bg-purple-500/5 border border-purple-500/20 space-y-2">
                        <div class="flex items-center gap-2 text-purple-700 font-bold text-xs uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[18px]">account_balance</span>
                            <span>Tata Kelola (Governance)</span>
                        </div>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Memastikan kebutuhan, kondisi, dan opsi pemangku kepentingan dievaluasi secara berkala (*Evaluate*); menentukan arah strategis enterprise melalui penentuan prioritas dan pengambilan keputusan (*Direct*); serta memantau kinerja dan kepatuhan terhadap arah dan tujuan yang disepakati (*Monitor*).
                        </p>
                        <div class="text-[11px] font-semibold text-purple-900 bg-purple-100 px-2.5 py-1 rounded-md inline-block">
                            Wewenang: Dewan Komisaris / Direksi (Board of Directors) — Domain: EDM (Evaluate, Direct and Monitor)
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-blue-500/5 border border-blue-500/20 space-y-2">
                        <div class="flex items-center gap-2 text-blue-700 font-bold text-xs uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[18px]">engineering</span>
                            <span>Manajemen (Management)</span>
                        </div>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Merencanakan (*Plan*), membangun dan mengakuisisi (*Build*), menjalankan operasional layanan (*Run/Service*), serta memantau aktivitas operasional (*Monitor*) selaras dengan arahan yang telah ditetapkan oleh badan tata kelola untuk mencapai sasaran bisnis enterprise.
                        </p>
                        <div class="text-[11px] font-semibold text-blue-900 bg-blue-100 px-2.5 py-1 rounded-md inline-block">
                            Wewenang: Manajemen Eksekutif (CEO, CIO, CTO, Kepala Divisi) — Domain: APO, BAI, DSS, MEA
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1.2 Prinsip Baku COBIT 2019 (ISACA Figure 3.1 & 3.2) -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-5">
                <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                    <span class="w-8 h-8 rounded-lg bg-primary-fixed/50 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[19px]">verified</span>
                    </span>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">2. Prinsip Resmi COBIT 2019 (ISACA Principles)</h2>
                        <p class="text-xs text-on-surface-variant">ISACA membagi prinsip menjadi 2 kategori: 6 Prinsip Sistem Tata Kelola dan 3 Prinsip Kerangka Kerja Tata Kelola</p>
                    </div>
                </div>

                <!-- Bagian A: 6 Prinsip Sistem Tata Kelola (Figure 3.1) -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-primary-fixed text-primary">ISACA Figure 3.1</span>
                        <h3 class="text-sm font-bold text-on-surface">Enam Prinsip Sistem Tata Kelola (Principles for a Governance System)</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-xs text-on-surface-variant">
                        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                            <strong class="text-on-surface font-semibold flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px] font-bold">1</span>
                                Provide Stakeholder Value
                            </strong>
                            <p class="text-[11px] leading-relaxed">
                                Setiap enterprise membutuhkan sistem tata kelola untuk menciptakan nilai melalui optimalisasi realisasi manfaat bisnis, pengelolaan risiko, dan optimalisasi penggunaan sumber daya I&amp;T.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                            <strong class="text-on-surface font-semibold flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px] font-bold">2</span>
                                Holistic Approach
                            </strong>
                            <p class="text-[11px] leading-relaxed">
                                Sistem tata kelola I&amp;T dibangun dari sekumpulan komponen pendukung terintegrasi dari berbagai tipe (7 komponen tata kelola) yang saling berinteraksi secara efektif.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                            <strong class="text-on-surface font-semibold flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px] font-bold">3</span>
                                Dynamic Governance System
                            </strong>
                            <p class="text-[11px] leading-relaxed">
                                Sistem tata kelola harus bersifat dinamis: setiap perubahan pada strategi enterprise atau faktor desain lainnya harus segera diperbarui dampaknya pada sistem EGIT.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                            <strong class="text-on-surface font-semibold flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px] font-bold">4</span>
                                Governance Distinct From Management
                            </strong>
                            <p class="text-[11px] leading-relaxed">
                                Sistem tata kelola harus memisahkan secara tegas aktivitas, struktur kewenangan, dan tanggung jawab antara Tata Kelola (*Governance*) dan Manajemen (*Management*).
                            </p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                            <strong class="text-on-surface font-semibold flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px] font-bold">5</span>
                                Tailored to Enterprise Needs
                            </strong>
                            <p class="text-[11px] leading-relaxed">
                                Sistem tata kelola harus disesuaikan secara spesifik dengan kebutuhan dan karakteristik unik enterprise menggunakan serangkaian faktor desain (*Design Factors*).
                            </p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                            <strong class="text-on-surface font-semibold flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px] font-bold">6</span>
                                End-to-End Governance System
                            </strong>
                            <p class="text-[11px] leading-relaxed">
                                Sistem tata kelola tidak hanya mencakup fungsi departemen TI, melainkan mencakup seluruh enterprise dari ujung ke ujung serta seluruh alur pemrosesan informasi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bagian B: 3 Prinsip Kerangka Kerja Tata Kelola (Figure 3.2) -->
                <div class="space-y-3 pt-2 border-t border-outline-variant/15">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-secondary-fixed text-secondary">ISACA Figure 3.2</span>
                        <h3 class="text-sm font-bold text-on-surface">Tiga Prinsip Kerangka Kerja Tata Kelola (Principles for a Governance Framework)</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs text-on-surface-variant">
                        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                            <strong class="text-on-surface font-semibold flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-full bg-secondary/15 text-secondary flex items-center justify-center text-[10px] font-bold">1</span>
                                Based on a Conceptual Model
                            </strong>
                            <p class="text-[11px] leading-relaxed">
                                Kerangka kerja tata kelola harus dibangun di atas model konseptual yang jelas, terstruktur, mendefinisikan komponen kunci, serta konsisten dalam implementasi.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                            <strong class="text-on-surface font-semibold flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-full bg-secondary/15 text-secondary flex items-center justify-center text-[10px] font-bold">2</span>
                                Open and Flexible
                            </strong>
                            <p class="text-[11px] leading-relaxed">
                                Kerangka kerja harus terbuka dan fleksibel sehingga dapat mengakomodasi konten baru serta menyelesaikan isu-isu baru tanpa mengurangi integritas model dasar.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                            <strong class="text-on-surface font-semibold flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-full bg-secondary/15 text-secondary flex items-center justify-center text-[10px] font-bold">3</span>
                                Aligned to Major Standards
                            </strong>
                            <p class="text-[11px] leading-relaxed">
                                Kerangka kerja tata kelola harus selaras dengan standar industri, regulasi, dan framework terkemuka dunia lainnya (ITIL, TOGAF, ISO/IEC 27000, PMBOK, CMMI).
                            </p>
                        </div>
                    </div>
                </div>

                <!-- EMBEDDED DIAGRAM 2.1 -->
                <div class="mt-4 p-4 rounded-xl bg-surface-container-high/50 border border-outline-variant/40 space-y-3">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <span class="text-xs font-bold text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">image</span>
                            <span>Ilustrasi Visual Gambar Skripsi</span>
                        </span>
                        <button 
                            @click="copyText('Gambar 2.1: Enam Prinsip Sistem Tata Kelola dan Tiga Prinsip Kerangka Kerja COBIT 2019 (Sumber: ISACA, 2018)')" 
                            type="button" 
                            class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary hover:underline cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[14px]">content_copy</span>
                            <span>Salin Judul &amp; Sumber</span>
                        </button>
                    </div>

                    <!-- Visual Box Diagram 2.1 -->
                    <div class="bg-surface-container-lowest rounded-lg border border-outline-variant/30 p-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Kolom Kiri: 6 Prinsip Sistem Tata Kelola -->
                            <div class="p-3 rounded-lg border border-primary/20 bg-primary/5 space-y-2">
                                <div class="text-center pb-2 border-b border-primary/20">
                                    <span class="text-[11px] font-bold text-primary uppercase tracking-wider block">Principles for a Governance System</span>
                                    <span class="text-[10px] text-outline">ISACA Figure 3.1</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-[10px]">
                                    <div class="p-2 bg-surface-container-lowest rounded border border-outline-variant/20 text-center font-medium">1. Provide Stakeholder Value</div>
                                    <div class="p-2 bg-surface-container-lowest rounded border border-outline-variant/20 text-center font-medium">2. Holistic Approach</div>
                                    <div class="p-2 bg-surface-container-lowest rounded border border-outline-variant/20 text-center font-medium">3. Dynamic Governance</div>
                                    <div class="p-2 bg-surface-container-lowest rounded border border-outline-variant/20 text-center font-medium">4. Governance vs Mgmt</div>
                                    <div class="p-2 bg-surface-container-lowest rounded border border-outline-variant/20 text-center font-medium">5. Tailored to Needs</div>
                                    <div class="p-2 bg-surface-container-lowest rounded border border-outline-variant/20 text-center font-medium">6. End-to-End System</div>
                                </div>
                            </div>

                            <!-- Kolom Kanan: 3 Prinsip Kerangka Kerja -->
                            <div class="p-3 rounded-lg border border-secondary/20 bg-secondary/5 space-y-2 flex flex-col justify-between">
                                <div class="text-center pb-2 border-b border-secondary/20">
                                    <span class="text-[11px] font-bold text-secondary uppercase tracking-wider block">Principles for a Governance Framework</span>
                                    <span class="text-[10px] text-outline">ISACA Figure 3.2</span>
                                </div>
                                <div class="space-y-2 text-[10px]">
                                    <div class="p-2 bg-surface-container-lowest rounded border border-outline-variant/20 text-center font-medium">1. Based on a Conceptual Model</div>
                                    <div class="p-2 bg-surface-container-lowest rounded border border-outline-variant/20 text-center font-medium">2. Open and Flexible</div>
                                    <div class="p-2 bg-surface-container-lowest rounded border border-outline-variant/20 text-center font-medium">3. Aligned to Major Standards</div>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan Gambar Baku -->
                        <div class="pt-2 border-t border-outline-variant/20 text-center space-y-0.5">
                            <p class="text-xs font-bold text-on-surface">Gambar 2.1: Enam Prinsip Sistem Tata Kelola dan Tiga Prinsip Kerangka Kerja COBIT 2019</p>
                            <p class="text-[11px] text-outline italic">Sumber: ISACA (2018), COBIT 2019 Framework: Introduction and Methodology, hlm. 21–22</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1.3 COBIT Goals Cascade Resmi ISACA (Figure 4.16 & 4.17) -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                    <span class="w-8 h-8 rounded-lg bg-tertiary-fixed/50 flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined text-[19px]">waterfall_chart</span>
                    </span>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">3. Alur COBIT Goals Cascade (ISACA Chapter 4)</h2>
                        <p class="text-xs text-on-surface-variant">Mekanisme penerjemahan kebutuhan pemangku kepentingan menjadi tujuan tata kelola operasional yang terukur</p>
                    </div>
                </div>

                <div class="text-xs sm:text-sm text-on-surface-variant leading-relaxed space-y-3">
                    <p>
                        <strong>Goals Cascade</strong> adalah mekanisme utama dalam COBIT 2019 yang menjembatani ekspektasi strategis para pemangku kepentingan menjadi prioritas tujuan tata kelola dan manajemen yang dapat dieksekusi secara nyata oleh enterprise.
                    </p>
                </div>

                <!-- EMBEDDED DIAGRAM 2.2: GOALS CASCADE -->
                <div class="p-4 rounded-xl bg-surface-container-high/50 border border-outline-variant/40 space-y-3">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <span class="text-xs font-bold text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">image</span>
                            <span>Ilustrasi Visual Gambar Skripsi</span>
                        </span>
                        <button 
                            @click="copyText('Gambar 2.2: Alur COBIT Goals Cascade Penerjemahan Kebutuhan Pemangku Kepentingan (Sumber: ISACA, 2018)')" 
                            type="button" 
                            class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary hover:underline cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[14px]">content_copy</span>
                            <span>Salin Judul &amp; Sumber</span>
                        </button>
                    </div>

                    <!-- Flow Diagram Visual Goals Cascade -->
                    <div class="bg-surface-container-lowest rounded-lg border border-outline-variant/30 p-4 space-y-3">
                        <div class="flex flex-col items-center space-y-2 max-w-2xl mx-auto">
                            <!-- Level 1 -->
                            <div class="w-full p-3 rounded-lg bg-purple-50 border border-purple-200 text-center space-y-0.5 shadow-xs">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-purple-200 text-purple-900">TINGKAT 1</span>
                                <h4 class="text-xs font-bold text-purple-950">Stakeholder Drivers &amp; Needs</h4>
                                <p class="text-[11px] text-purple-800">Kebutuhan bisnis, strategi enterprise, tren pasar, dan kepatuhan regulasi eksternal</p>
                            </div>

                            <span class="material-symbols-outlined text-outline text-[20px]">arrow_downward</span>

                            <!-- Level 2 -->
                            <div class="w-full p-3 rounded-lg bg-indigo-50 border border-indigo-200 text-center space-y-0.5 shadow-xs">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-indigo-200 text-indigo-900">TINGKAT 2</span>
                                <h4 class="text-xs font-bold text-indigo-950">13 Enterprise Goals (EG)</h4>
                                <p class="text-[11px] text-indigo-800">4 Dimensi BSC: Financial (EG01-04), Customer (EG05-07), Internal (EG08-11), Learning &amp; Growth (EG12-13)</p>
                            </div>

                            <span class="material-symbols-outlined text-outline text-[20px]">arrow_downward</span>

                            <!-- Level 3 -->
                            <div class="w-full p-3 rounded-lg bg-blue-50 border border-blue-200 text-center space-y-0.5 shadow-xs">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-200 text-blue-900">TINGKAT 3</span>
                                <h4 class="text-xs font-bold text-blue-950">13 Alignment Goals (AG)</h4>
                                <p class="text-[11px] text-blue-800">4 Dimensi BSC Keselarasan I&amp;T: Financial (AG01-04), Customer (AG05-06), Internal (AG07-11), L&amp;G (AG12-13)</p>
                            </div>

                            <span class="material-symbols-outlined text-outline text-[20px]">arrow_downward</span>

                            <!-- Level 4 -->
                            <div class="w-full p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-center space-y-0.5 shadow-xs">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-200 text-emerald-900">TINGKAT 4</span>
                                <h4 class="text-xs font-bold text-emerald-950">40 Governance and Management Objectives</h4>
                                <p class="text-[11px] text-emerald-800">5 Domain: EDM (5 Objectives), APO (14 Objectives), BAI (11 Objectives), DSS (6 Objectives), MEA (4 Objectives)</p>
                            </div>
                        </div>

                        <!-- Keterangan Gambar Baku -->
                        <div class="pt-3 border-t border-outline-variant/20 text-center space-y-0.5">
                            <p class="text-xs font-bold text-on-surface">Gambar 2.2: Alur COBIT Goals Cascade Penerjemahan Kebutuhan Pemangku Kepentingan</p>
                            <p class="text-[11px] text-outline italic">Sumber: ISACA (2018), COBIT 2019 Framework: Introduction and Methodology, hlm. 33</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1.4 7 Komponen Sistem Tata Kelola (ISACA Figure 4.1) -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                    <span class="w-8 h-8 rounded-lg bg-secondary-fixed/50 flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined text-[19px]">category</span>
                    </span>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">4. Tujuh Komponen Sistem Tata Kelola (Governance Components)</h2>
                        <p class="text-xs text-on-surface-variant">ISACA Figure 4.1 — Faktor-faktor interaktif yang membentuk sistem tata kelola EGIT yang holistik</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                    <div class="p-3.5 rounded-lg border border-outline-variant/25 bg-surface-container-low/40 flex flex-col gap-1.5">
                        <span class="font-bold text-xs text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">cycle</span>
                            1. Proses (Processes)
                        </span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Serangkaian aktivitas dan praktik terorganisir yang menghasilkan output spesifik untuk mendukung pencapaian seluruh tujuan I&amp;T enterprise.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg border border-outline-variant/25 bg-surface-container-low/40 flex flex-col gap-1.5">
                        <span class="font-bold text-xs text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">corporate_fare</span>
                            2. Struktur Organisasi
                        </span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Entitas pengambil keputusan kunci di dalam perusahaan (dewan direksi, komite pengarah I&amp;T, peran kepemimpinan C-Level, dan manajer unit).
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg border border-outline-variant/25 bg-surface-container-low/40 flex flex-col gap-1.5">
                        <span class="font-bold text-xs text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">policy</span>
                            3. Prinsip, Kebijakan, Kerangka Kerja
                        </span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Pedoman tertulis dan aturan operasional harian yang menerjemahkan perilaku yang diinginkan ke dalam petunjuk praktis bagi manajemen.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg border border-outline-variant/25 bg-surface-container-low/40 flex flex-col gap-1.5">
                        <span class="font-bold text-xs text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">dataset</span>
                            4. Informasi (Information)
                        </span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Semua data dan informasi yang dihasilkan serta dimanfaatkan oleh enterprise agar sistem tata kelola berfungsi secara akurat dan transparan.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg border border-outline-variant/25 bg-surface-container-low/40 flex flex-col gap-1.5">
                        <span class="font-bold text-xs text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">diversity_3</span>
                            5. Budaya, Etika, &amp; Perilaku
                        </span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Sikap, integritas, dan pola perilaku individu maupun enterprise yang seringkali menjadi faktor penentu keberhasilan aktivitas tata kelola.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg border border-outline-variant/25 bg-surface-container-low/40 flex flex-col gap-1.5">
                        <span class="font-bold text-xs text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">psychology</span>
                            6. SDM, Keahlian, &amp; Kompetensi
                        </span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Keahlian profesional, sertifikasi, kualifikasi, pengetahuan teknis, dan kesiapan personel yang diperlukan untuk mengeksekusi peran operasional.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg border border-outline-variant/25 bg-surface-container-low/40 flex flex-col gap-1.5 sm:col-span-2 lg:col-span-3">
                        <span class="font-bold text-xs text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">cloud</span>
                            7. Layanan, Infrastruktur, &amp; Aplikasi
                        </span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Infrastruktur teknologi, perangkat keras, pusat data, platform komputasi awan, perangkat lunak, dan layanan pendukung pengolahan I&amp;T.
                        </p>
                    </div>
                </div>

                <!-- EMBEDDED DIAGRAM 2.3 -->
                <div class="p-4 rounded-xl bg-surface-container-high/50 border border-outline-variant/40 space-y-3">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <span class="text-xs font-bold text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">image</span>
                            <span>Ilustrasi Visual Gambar Skripsi</span>
                        </span>
                        <button 
                            @click="copyText('Gambar 2.3: Tujuh Komponen Pembentuk Sistem Tata Kelola (Sumber: ISACA, 2018)')" 
                            type="button" 
                            class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary hover:underline cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[14px]">content_copy</span>
                            <span>Salin Judul &amp; Sumber</span>
                        </button>
                    </div>

                    <div class="bg-surface-container-lowest rounded-lg border border-outline-variant/30 p-4 space-y-3">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-[10px]">
                            <div class="p-2.5 rounded bg-primary-fixed/20 border border-primary-fixed/50 font-bold text-primary">1. Processes</div>
                            <div class="p-2.5 rounded bg-primary-fixed/20 border border-primary-fixed/50 font-bold text-primary">2. Org Structures</div>
                            <div class="p-2.5 rounded bg-primary-fixed/20 border border-primary-fixed/50 font-bold text-primary">3. Principles &amp; Policies</div>
                            <div class="p-2.5 rounded bg-primary-fixed/20 border border-primary-fixed/50 font-bold text-primary">4. Information</div>
                            <div class="p-2.5 rounded bg-secondary-fixed/20 border border-secondary-fixed/50 font-bold text-secondary">5. Culture &amp; Behavior</div>
                            <div class="p-2.5 rounded bg-secondary-fixed/20 border border-secondary-fixed/50 font-bold text-secondary">6. People &amp; Skills</div>
                            <div class="p-2.5 rounded bg-secondary-fixed/20 border border-secondary-fixed/50 font-bold text-secondary col-span-2">7. Services, Infrastructure &amp; Apps</div>
                        </div>

                        <!-- Keterangan Gambar Baku -->
                        <div class="pt-2 border-t border-outline-variant/20 text-center space-y-0.5">
                            <p class="text-xs font-bold text-on-surface">Gambar 2.3: Tujuh Komponen Pembentuk Sistem Tata Kelola (Governance Components)</p>
                            <p class="text-[11px] text-outline italic">Sumber: ISACA (2018), COBIT 2019 Framework: Introduction and Methodology, hlm. 24</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1.5 11 Design Factors & 3 Dampak Desain (ISACA Figure 4.4 & 7.1) -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                    <span class="w-8 h-8 rounded-lg bg-tertiary-fixed/50 flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined text-[19px]">tune</span>
                    </span>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">5. Sebelas Faktor Desain &amp; Tiga Dampak Desain (Design Factors)</h2>
                        <p class="text-xs text-on-surface-variant">ISACA Figure 4.4 &amp; 7.1 — Parameter penyesuaian untuk menghasilkan Tailored Governance System</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-xs text-on-surface-variant">
                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20">
                        <strong class="text-on-surface block mb-1">DF 1: Enterprise Strategy</strong>
                        Pilihan strategi bisnis organisasi (Cost Leadership, Differentiation, Growth/Acquisition, Client Service/Stability).
                    </div>
                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20">
                        <strong class="text-on-surface block mb-1">DF 2: Enterprise Goals</strong>
                        Tujuan enterprise yang diterjemahkan dari strategi bisnis ke dalam 13 tujuan terstandar COBIT 2019.
                    </div>
                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20">
                        <strong class="text-on-surface block mb-1">DF 3: Risk Profile</strong>
                        Tingkat selera risiko (*risk appetite*) dan skenario risiko I&amp;T yang dihadapi organisasi.
                    </div>
                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20">
                        <strong class="text-on-surface block mb-1">DF 4: I&amp;T-Related Issues</strong>
                        Masalah aktual terkait I&amp;T yang dialami enterprise (incident downtime, project delays, cyber threats).
                    </div>
                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20">
                        <strong class="text-on-surface block mb-1">DF 5: Threat Landscape</strong>
                        Tingkat ancaman keamanan informasi dan lingkungan eksternal (High Threat vs Normal Threat).
                    </div>
                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20">
                        <strong class="text-on-surface block mb-1">DF 6: Compliance Requirements</strong>
                        Kekuatan mandat kepatuhan hukum, regulasi pemerintah, dan standar industri (OJK, BI, ISO, GDPR, UU PDP).
                    </div>
                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20">
                        <strong class="text-on-surface block mb-1">DF 7: Role of IT</strong>
                        Peran strategis teknologi dalam organisasi (Support, Factory, Turnaround, Strategic).
                    </div>
                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20">
                        <strong class="text-on-surface block mb-1">DF 8: Sourcing Model for IT</strong>
                        Model penyediaan operasional I&amp;T (Insourced, Outsourced, Cloud-based, Hybrid).
                    </div>
                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20">
                        <strong class="text-on-surface block mb-1">DF 9: IT Implementation Methods</strong>
                        Metodologi pengembangan sistem yang diadopsi (Agile, DevOps, Traditional/Waterfall, Hybrid).
                    </div>
                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20">
                        <strong class="text-on-surface block mb-1">DF 10: Technology Adoption Strategy</strong>
                        Sikap organisasi terhadap adopsi teknologi baru (First Mover, Follower, Slow Adopter).
                    </div>
                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20 md:col-span-2 lg:col-span-2">
                        <strong class="text-on-surface block mb-1">DF 11: Enterprise Size</strong>
                        Skala ukuran organisasi (Large Enterprise > 250 pegawai, SME/Small-Medium Enterprise &lt; 250 pegawai).
                    </div>
                </div>

                <!-- EMBEDDED DIAGRAM 2.4 -->
                <div class="p-4 rounded-xl bg-surface-container-high/50 border border-outline-variant/40 space-y-3">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <span class="text-xs font-bold text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">image</span>
                            <span>Ilustrasi Visual Gambar Skripsi</span>
                        </span>
                        <button 
                            @click="copyText('Gambar 2.4: Sebelas Faktor Desain dan Tiga Dampaknya terhadap Sistem Tata Kelola (Sumber: ISACA, 2018)')" 
                            type="button" 
                            class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary hover:underline cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[14px]">content_copy</span>
                            <span>Salin Judul &amp; Sumber</span>
                        </button>
                    </div>

                    <div class="bg-surface-container-lowest rounded-lg border border-outline-variant/30 p-4 space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                            <!-- 11 DF Box -->
                            <div class="md:col-span-7 p-3 rounded-lg bg-surface-container-low border border-outline-variant/25 space-y-1.5">
                                <span class="text-[11px] font-bold text-primary uppercase tracking-wider block">11 Design Factors</span>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 text-[10px]">
                                    <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 text-center">DF1 Strategy</div>
                                    <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 text-center">DF2 Goals</div>
                                    <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 text-center">DF3 Risk</div>
                                    <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 text-center">DF4 Issues</div>
                                    <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 text-center">DF5 Threat</div>
                                    <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 text-center">DF6 Compliance</div>
                                    <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 text-center">DF7 Role IT</div>
                                    <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 text-center">DF8 Sourcing</div>
                                    <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 text-center">DF9 Methods</div>
                                    <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 text-center">DF10 Adoption</div>
                                    <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 text-center col-span-2 sm:col-span-2">DF11 Enterprise Size</div>
                                </div>
                            </div>

                            <!-- Flow Arrow -->
                            <div class="md:col-span-1 flex justify-center text-outline">
                                <span class="material-symbols-outlined text-[24px]">trending_flat</span>
                            </div>

                            <!-- 3 Impacts Box -->
                            <div class="md:col-span-4 p-3 rounded-lg bg-tertiary-fixed/20 border border-tertiary-fixed/40 space-y-1.5 text-[10px]">
                                <span class="text-[11px] font-bold text-tertiary uppercase tracking-wider block">3 Impacts on EGIT</span>
                                <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 font-medium">1. Priority &amp; Target Levels</div>
                                <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 font-medium">2. Component Variations</div>
                                <div class="p-1.5 bg-surface-container-lowest rounded border border-outline-variant/20 font-medium">3. Specific Focus Areas</div>
                            </div>
                        </div>

                        <!-- Keterangan Gambar Baku -->
                        <div class="pt-2 border-t border-outline-variant/20 text-center space-y-0.5">
                            <p class="text-xs font-bold text-on-surface">Gambar 2.4: Sebelas Faktor Desain dan Tiga Dampaknya terhadap Sistem Tata Kelola</p>
                            <p class="text-[11px] text-outline italic">Sumber: ISACA (2018), COBIT 2019 Design Guide, hlm. 17 &amp; 28</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB KHUSUS: DIAGRAM & GAMBAR SKRIPSI (BAB 2 & 3) -->
    @if($activeTab === 'figures')
        <div class="space-y-6 animate-in fade-in duration-200">
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-2">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-primary-fixed/50 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[19px]">schema</span>
                    </span>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">Daftar Diagram &amp; Gambar Skripsi (Bab 2 &amp; Bab 3)</h2>
                        <p class="text-xs text-on-surface-variant">Seluruh gambar terstandar COBIT 2019 dengan nomor gambar, keterangan resmi, dan sumber sitasi yang siap disalin untuk dokumen skripsi Anda</p>
                    </div>
                </div>
            </div>

            <!-- GRID SEMUA GAMBAR SKRIPSI -->
            <div class="space-y-6">
                <!-- GAMBAR 2.1 -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between gap-3 flex-wrap pb-3 border-b border-outline-variant/20">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-primary text-on-primary">Gambar 2.1</span>
                            <h3 class="text-sm font-bold text-on-surface">Prinsip Sistem Tata Kelola &amp; Kerangka Kerja COBIT 2019</h3>
                        </div>
                        <button 
                            @click="copyText('Gambar 2.1: Enam Prinsip Sistem Tata Kelola dan Tiga Prinsip Kerangka Kerja COBIT 2019 (Sumber: ISACA, 2018)')" 
                            type="button" 
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-primary transition-colors cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[15px]">content_copy</span>
                            <span>Salin Judul &amp; Sumber</span>
                        </button>
                    </div>

                    <!-- Visual Panel 2.1 -->
                    <div class="p-4 rounded-xl bg-surface-container-low/40 border border-outline-variant/30 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-3.5 bg-surface-container-lowest rounded-xl border border-primary/20 space-y-2">
                                <span class="text-xs font-bold text-primary block text-center uppercase tracking-wide">6 Governance System Principles (ISACA Figure 3.1)</span>
                                <div class="grid grid-cols-2 gap-2 text-xs text-on-surface-variant">
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 font-medium">1. Provide Stakeholder Value</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 font-medium">2. Holistic Approach</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 font-medium">3. Dynamic Governance</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 font-medium">4. Governance vs Mgmt</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 font-medium">5. Tailored to Enterprise</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 font-medium">6. End-to-End System</div>
                                </div>
                            </div>

                            <div class="p-3.5 bg-surface-container-lowest rounded-xl border border-secondary/20 space-y-2 flex flex-col justify-between">
                                <span class="text-xs font-bold text-secondary block text-center uppercase tracking-wide">3 Governance Framework Principles (ISACA Figure 3.2)</span>
                                <div class="space-y-2 text-xs text-on-surface-variant">
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 font-medium">1. Based on a Conceptual Model</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 font-medium">2. Open and Flexible</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 font-medium">3. Aligned to Major Standards</div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 text-center space-y-0.5">
                            <p class="text-xs font-bold text-on-surface">Gambar 2.1: Enam Prinsip Sistem Tata Kelola dan Tiga Prinsip Kerangka Kerja COBIT 2019</p>
                            <p class="text-[11px] text-outline italic">Sumber: ISACA (2018), COBIT 2019 Framework: Introduction and Methodology, hlm. 21–22</p>
                        </div>
                    </div>
                </div>

                <!-- GAMBAR 2.2 -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between gap-3 flex-wrap pb-3 border-b border-outline-variant/20">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-primary text-on-primary">Gambar 2.2</span>
                            <h3 class="text-sm font-bold text-on-surface">Alur COBIT Goals Cascade</h3>
                        </div>
                        <button 
                            @click="copyText('Gambar 2.2: Alur COBIT Goals Cascade Penerjemahan Kebutuhan Pemangku Kepentingan (Sumber: ISACA, 2018)')" 
                            type="button" 
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-primary transition-colors cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[15px]">content_copy</span>
                            <span>Salin Judul &amp; Sumber</span>
                        </button>
                    </div>

                    <!-- Visual Panel 2.2 -->
                    <div class="p-4 rounded-xl bg-surface-container-low/40 border border-outline-variant/30 space-y-4">
                        <div class="flex flex-col items-center space-y-2 max-w-2xl mx-auto">
                            <div class="w-full p-3 rounded-lg bg-purple-50 border border-purple-200 text-center space-y-0.5 shadow-xs">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-purple-200 text-purple-900">TINGKAT 1</span>
                                <h4 class="text-xs font-bold text-purple-950">Stakeholder Drivers &amp; Needs</h4>
                                <p class="text-[11px] text-purple-800">Kebutuhan bisnis, strategi enterprise, tren pasar, dan kepatuhan regulasi eksternal</p>
                            </div>

                            <span class="material-symbols-outlined text-outline text-[20px]">arrow_downward</span>

                            <div class="w-full p-3 rounded-lg bg-indigo-50 border border-indigo-200 text-center space-y-0.5 shadow-xs">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-indigo-200 text-indigo-900">TINGKAT 2</span>
                                <h4 class="text-xs font-bold text-indigo-950">13 Enterprise Goals (EG)</h4>
                                <p class="text-[11px] text-indigo-800">4 Dimensi BSC: Financial (EG01-04), Customer (EG05-07), Internal (EG08-11), Learning &amp; Growth (EG12-13)</p>
                            </div>

                            <span class="material-symbols-outlined text-outline text-[20px]">arrow_downward</span>

                            <div class="w-full p-3 rounded-lg bg-blue-50 border border-blue-200 text-center space-y-0.5 shadow-xs">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-200 text-blue-900">TINGKAT 3</span>
                                <h4 class="text-xs font-bold text-blue-950">13 Alignment Goals (AG)</h4>
                                <p class="text-[11px] text-blue-800">4 Dimensi BSC Keselarasan I&amp;T: Financial (AG01-04), Customer (AG05-06), Internal (AG07-11), L&amp;G (AG12-13)</p>
                            </div>

                            <span class="material-symbols-outlined text-outline text-[20px]">arrow_downward</span>

                            <div class="w-full p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-center space-y-0.5 shadow-xs">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-200 text-emerald-900">TINGKAT 4</span>
                                <h4 class="text-xs font-bold text-emerald-950">40 Governance and Management Objectives</h4>
                                <p class="text-[11px] text-emerald-800">5 Domain: EDM (5 Objectives), APO (14 Objectives), BAI (11 Objectives), DSS (6 Objectives), MEA (4 Objectives)</p>
                            </div>
                        </div>

                        <div class="pt-2 text-center space-y-0.5">
                            <p class="text-xs font-bold text-on-surface">Gambar 2.2: Alur COBIT Goals Cascade Penerjemahan Kebutuhan Pemangku Kepentingan</p>
                            <p class="text-[11px] text-outline italic">Sumber: ISACA (2018), COBIT 2019 Framework: Introduction and Methodology, hlm. 33</p>
                        </div>
                    </div>
                </div>

                <!-- GAMBAR 2.3 -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between gap-3 flex-wrap pb-3 border-b border-outline-variant/20">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-primary text-on-primary">Gambar 2.3</span>
                            <h3 class="text-sm font-bold text-on-surface">Tujuh Komponen Tata Kelola (Governance Components)</h3>
                        </div>
                        <button 
                            @click="copyText('Gambar 2.3: Tujuh Komponen Pembentuk Sistem Tata Kelola (Sumber: ISACA, 2018)')" 
                            type="button" 
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-primary transition-colors cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[15px]">content_copy</span>
                            <span>Salin Judul &amp; Sumber</span>
                        </button>
                    </div>

                    <!-- Visual Panel 2.3 -->
                    <div class="p-4 rounded-xl bg-surface-container-low/40 border border-outline-variant/30 space-y-4">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-center text-xs">
                            <div class="p-3 rounded-lg bg-primary-fixed/20 border border-primary-fixed/50 font-bold text-primary">1. Processes</div>
                            <div class="p-3 rounded-lg bg-primary-fixed/20 border border-primary-fixed/50 font-bold text-primary">2. Org Structures</div>
                            <div class="p-3 rounded-lg bg-primary-fixed/20 border border-primary-fixed/50 font-bold text-primary">3. Principles &amp; Policies</div>
                            <div class="p-3 rounded-lg bg-primary-fixed/20 border border-primary-fixed/50 font-bold text-primary">4. Information</div>
                            <div class="p-3 rounded-lg bg-secondary-fixed/20 border border-secondary-fixed/50 font-bold text-secondary">5. Culture &amp; Behavior</div>
                            <div class="p-3 rounded-lg bg-secondary-fixed/20 border border-secondary-fixed/50 font-bold text-secondary">6. People &amp; Skills</div>
                            <div class="p-3 rounded-lg bg-secondary-fixed/20 border border-secondary-fixed/50 font-bold text-secondary col-span-2">7. Services, Infrastructure &amp; Apps</div>
                        </div>

                        <div class="pt-2 text-center space-y-0.5">
                            <p class="text-xs font-bold text-on-surface">Gambar 2.3: Tujuh Komponen Pembentuk Sistem Tata Kelola (Governance Components)</p>
                            <p class="text-[11px] text-outline italic">Sumber: ISACA (2018), COBIT 2019 Framework: Introduction and Methodology, hlm. 24</p>
                        </div>
                    </div>
                </div>

                <!-- GAMBAR 2.4 -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between gap-3 flex-wrap pb-3 border-b border-outline-variant/20">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-primary text-on-primary">Gambar 2.4</span>
                            <h3 class="text-sm font-bold text-on-surface">Sebelas Faktor Desain &amp; Tiga Dampaknya terhadap Sistem Tata Kelola</h3>
                        </div>
                        <button 
                            @click="copyText('Gambar 2.4: Sebelas Faktor Desain dan Tiga Dampaknya terhadap Sistem Tata Kelola (Sumber: ISACA, 2018)')" 
                            type="button" 
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-primary transition-colors cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[15px]">content_copy</span>
                            <span>Salin Judul &amp; Sumber</span>
                        </button>
                    </div>

                    <!-- Visual Panel 2.4 -->
                    <div class="p-4 rounded-xl bg-surface-container-low/40 border border-outline-variant/30 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                            <div class="md:col-span-7 p-3.5 rounded-lg bg-surface-container-lowest border border-outline-variant/25 space-y-2">
                                <span class="text-xs font-bold text-primary uppercase tracking-wider block">11 Design Factors (ISACA Figure 4.4)</span>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-[11px]">
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 text-center">DF1 Strategy</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 text-center">DF2 Goals</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 text-center">DF3 Risk</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 text-center">DF4 Issues</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 text-center">DF5 Threat</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 text-center">DF6 Compliance</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 text-center">DF7 Role IT</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 text-center">DF8 Sourcing</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 text-center">DF9 Methods</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 text-center">DF10 Adoption</div>
                                    <div class="p-2 bg-surface-container-low rounded border border-outline-variant/20 text-center col-span-2 sm:col-span-2">DF11 Enterprise Size</div>
                                </div>
                            </div>

                            <div class="md:col-span-1 flex justify-center text-outline">
                                <span class="material-symbols-outlined text-[26px]">trending_flat</span>
                            </div>

                            <div class="md:col-span-4 p-3.5 rounded-lg bg-tertiary-fixed/20 border border-tertiary-fixed/40 space-y-2 text-xs">
                                <span class="text-xs font-bold text-tertiary uppercase tracking-wider block">3 Impacts on EGIT (Figure 7.1)</span>
                                <div class="p-2 bg-surface-container-lowest rounded border border-outline-variant/20 font-medium">1. Priority &amp; Target Levels</div>
                                <div class="p-2 bg-surface-container-lowest rounded border border-outline-variant/20 font-medium">2. Component Variations</div>
                                <div class="p-2 bg-surface-container-lowest rounded border border-outline-variant/20 font-medium">3. Specific Focus Areas</div>
                            </div>
                        </div>

                        <div class="pt-2 text-center space-y-0.5">
                            <p class="text-xs font-bold text-on-surface">Gambar 2.4: Sebelas Faktor Desain dan Tiga Dampaknya terhadap Sistem Tata Kelola</p>
                            <p class="text-[11px] text-outline italic">Sumber: ISACA (2018), COBIT 2019 Design Guide, hlm. 17 &amp; 28</p>
                        </div>
                    </div>
                </div>

                <!-- GAMBAR 3.1 -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between gap-3 flex-wrap pb-3 border-b border-outline-variant/20">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-primary text-on-primary">Gambar 3.1</span>
                            <h3 class="text-sm font-bold text-on-surface">Alur Perancangan Sistem Tata Kelola 4 Tahapan (Governance System Design Workflow)</h3>
                        </div>
                        <button 
                            @click="copyText('Gambar 3.1: Alur Metodologi Perancangan Sistem Tata Kelola COBIT 2019 4 Tahapan (Sumber: ISACA, 2018)')" 
                            type="button" 
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-primary transition-colors cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[15px]">content_copy</span>
                            <span>Salin Judul &amp; Sumber</span>
                        </button>
                    </div>

                    <!-- Visual Panel 3.1 -->
                    <div class="p-4 rounded-xl bg-surface-container-low/40 border border-outline-variant/30 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                            <div class="p-3.5 rounded-lg bg-surface-container-lowest border border-outline-variant/20 space-y-1">
                                <span class="w-6 h-6 rounded-full bg-primary text-on-primary font-bold text-xs flex items-center justify-center">1</span>
                                <strong class="text-xs text-on-surface block">Stage 1: Pahami Konteks</strong>
                                <p class="text-[11px] text-on-surface-variant leading-relaxed">Analisis strategi, enterprise goals, profil risiko, dan permasalahan aktual I&amp;T (DF1–DF4).</p>
                            </div>

                            <div class="p-3.5 rounded-lg bg-surface-container-lowest border border-outline-variant/20 space-y-1">
                                <span class="w-6 h-6 rounded-full bg-primary text-on-primary font-bold text-xs flex items-center justify-center">2</span>
                                <strong class="text-xs text-on-surface block">Stage 2: Cakupan Awal</strong>
                                <p class="text-[11px] text-on-surface-variant leading-relaxed">Terapkan Design Factor Toolkit untuk memetakan prioritas awal 40 objective dan target level.</p>
                            </div>

                            <div class="p-3.5 rounded-lg bg-surface-container-lowest border border-outline-variant/20 space-y-1">
                                <span class="w-6 h-6 rounded-full bg-primary text-on-primary font-bold text-xs flex items-center justify-center">3</span>
                                <strong class="text-xs text-on-surface block">Stage 3: Sempurnakan Cakupan</strong>
                                <p class="text-[11px] text-on-surface-variant leading-relaxed">Penyesuaian target kapabilitas berdasarkan ancaman, kepatuhan, sourcing, dan metode (DF5–DF11).</p>
                            </div>

                            <div class="p-3.5 rounded-lg bg-surface-container-lowest border border-outline-variant/20 space-y-1">
                                <span class="w-6 h-6 rounded-full bg-primary text-on-primary font-bold text-xs flex items-center justify-center">4</span>
                                <strong class="text-xs text-on-surface block">Stage 4: Finalisasi Desain</strong>
                                <p class="text-[11px] text-on-surface-variant leading-relaxed">Penetapan rancangan final, analisis gap, penyusunan roadmap implementasi, dan rekomendasi RACI.</p>
                            </div>
                        </div>

                        <div class="pt-2 text-center space-y-0.5">
                            <p class="text-xs font-bold text-on-surface">Gambar 3.1: Alur Metodologi Perancangan Sistem Tata Kelola COBIT 2019 (4-Stage Workflow)</p>
                            <p class="text-[11px] text-outline italic">Sumber: ISACA (2018), COBIT 2019 Design Guide, hlm. 31</p>
                        </div>
                    </div>
                </div>

                <!-- GAMBAR 3.2 -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between gap-3 flex-wrap pb-3 border-b border-outline-variant/20">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-primary text-on-primary">Gambar 3.2</span>
                            <h3 class="text-sm font-bold text-on-surface">Skala Kapabilitas Proses CMMI (0–5) &amp; Skala Penilaian NPLF</h3>
                        </div>
                        <button 
                            @click="copyText('Gambar 3.2: Skala Tingkat Kapabilitas Proses CMMI (Level 0–5) dan Kriteria Penilaian NPLF (Sumber: ISACA, 2018)')" 
                            type="button" 
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-primary transition-colors cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[15px]">content_copy</span>
                            <span>Salin Judul &amp; Sumber</span>
                        </button>
                    </div>

                    <!-- Visual Panel 3.2 -->
                    <div class="p-4 rounded-xl bg-surface-container-low/40 border border-outline-variant/30 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Tangga Level 0 - 5 -->
                            <div class="p-3.5 bg-surface-container-lowest rounded-xl border border-outline-variant/20 space-y-2">
                                <span class="text-xs font-bold text-primary block text-center uppercase tracking-wide">6 Level Kapabilitas Proses (CMMI Scheme)</span>
                                <div class="space-y-1.5 text-xs">
                                    <div class="p-2 bg-emerald-50 border border-emerald-200 rounded font-semibold text-emerald-900 flex justify-between"><span>Level 5: Optimizing</span> <span class="text-[10px]">Continuous Improvement</span></div>
                                    <div class="p-2 bg-blue-50 border border-blue-200 rounded font-semibold text-blue-900 flex justify-between"><span>Level 4: Quantitative</span> <span class="text-[10px]">Statistically Measured</span></div>
                                    <div class="p-2 bg-indigo-50 border border-indigo-200 rounded font-semibold text-indigo-900 flex justify-between"><span>Level 3: Defined</span> <span class="text-[10px]">Organization Standard SOP</span></div>
                                    <div class="p-2 bg-purple-50 border border-purple-200 rounded font-semibold text-purple-900 flex justify-between"><span>Level 2: Managed</span> <span class="text-[10px]">Planned &amp; Documented</span></div>
                                    <div class="p-2 bg-amber-50 border border-amber-200 rounded font-semibold text-amber-900 flex justify-between"><span>Level 1: Initial</span> <span class="text-[10px]">Ad-hoc / Basic Success</span></div>
                                    <div class="p-2 bg-slate-100 border border-slate-200 rounded font-semibold text-slate-700 flex justify-between"><span>Level 0: Incomplete</span> <span class="text-[10px]">Not Implemented / Fails</span></div>
                                </div>
                            </div>

                            <!-- Skala NPLF & Milestone -->
                            <div class="p-3.5 bg-surface-container-lowest rounded-xl border border-outline-variant/20 space-y-2 flex flex-col justify-between">
                                <span class="text-xs font-bold text-secondary block text-center uppercase tracking-wide">Kriteria Ketercapaian NPLF (ISO/IEC 33020)</span>
                                <div class="space-y-1.5 text-xs">
                                    <div class="p-2 bg-emerald-50 border border-emerald-200 rounded font-semibold text-emerald-900 flex justify-between"><span>F — Fully Achieved</span> <span>&gt;85% – 100%</span></div>
                                    <div class="p-2 bg-blue-50 border border-blue-200 rounded font-semibold text-blue-900 flex justify-between"><span>L — Largely Achieved</span> <span>&gt;50% – 85%</span></div>
                                    <div class="p-2 bg-amber-50 border border-amber-200 rounded font-semibold text-amber-900 flex justify-between"><span>P — Partially Achieved</span> <span>&gt;15% – 50%</span></div>
                                    <div class="p-2 bg-rose-50 border border-rose-200 rounded font-semibold text-rose-900 flex justify-between"><span>N — Not Achieved</span> <span>0% – 15%</span></div>
                                </div>
                                <div class="p-2.5 bg-amber-50 rounded border border-amber-200 text-[11px] text-amber-900 italic">
                                    <strong>Milestone Rule:</strong> Level N hanya tercapai jika SEMUA aktivitas pada level tersebut mencapai rating minimal <strong>L</strong> atau <strong>F</strong>.
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 text-center space-y-0.5">
                            <p class="text-xs font-bold text-on-surface">Gambar 3.2: Skala Tingkat Kapabilitas Proses CMMI (Level 0–5) dan Kriteria Penilaian NPLF</p>
                            <p class="text-[11px] text-outline italic">Sumber: ISACA (2018), COBIT 2019 Framework: Introduction and Methodology, hlm. 39</p>
                        </div>
                    </div>
                </div>

                <!-- GAMBAR 3.3 -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between gap-3 flex-wrap pb-3 border-b border-outline-variant/20">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-primary text-on-primary">Gambar 3.3</span>
                            <h3 class="text-sm font-bold text-on-surface">Model Formula Analisis Kesenjangan (Gap Analysis) &amp; Rekomendasi RACI</h3>
                        </div>
                        <button 
                            @click="copyText('Gambar 3.3: Kerangka Kerja Analisis Kesenjangan (Gap Analysis) dan Pemetaan Rekomendasi RACI (Sumber: Disarikan dari ISACA, 2018)')" 
                            type="button" 
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-primary transition-colors cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[15px]">content_copy</span>
                            <span>Salin Judul &amp; Sumber</span>
                        </button>
                    </div>

                    <!-- Visual Panel 3.3 -->
                    <div class="p-4 rounded-xl bg-surface-container-low/40 border border-outline-variant/30 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-center text-xs">
                            <div class="p-3.5 bg-surface-container-lowest rounded-lg border border-outline-variant/25 space-y-1">
                                <span class="text-[11px] font-bold text-outline uppercase block">Current Condition</span>
                                <strong class="text-sm text-on-surface block font-mono">Current Capability Level</strong>
                                <p class="text-[11px] text-on-surface-variant">Hasil evaluasi bukti audit faktual dan pemenuhan aktivitas proses.</p>
                            </div>

                            <div class="p-3.5 bg-surface-container-lowest rounded-lg border border-primary/30 space-y-1 flex flex-col justify-center">
                                <span class="text-[11px] font-bold text-primary uppercase block">Formula Matematis</span>
                                <div class="text-sm font-mono font-bold text-primary bg-primary-fixed/20 p-2 rounded">
                                    GAP = Target − Current
                                </div>
                                <span class="text-[10px] text-outline mt-1 font-mono">Indeks = (Current / Target) × 100%</span>
                            </div>

                            <div class="p-3.5 bg-surface-container-lowest rounded-lg border border-outline-variant/25 space-y-1">
                                <span class="text-[11px] font-bold text-outline uppercase block">Target Expectation</span>
                                <strong class="text-sm text-on-surface block font-mono">Target Capability Level</strong>
                                <p class="text-[11px] text-on-surface-variant">Target strategis yang ditentukan berdasarkan hasil analisis 11 Design Factors.</p>
                            </div>
                        </div>

                        <!-- Keterangan Gambar Baku -->
                        <div class="pt-2 text-center space-y-0.5">
                            <p class="text-xs font-bold text-on-surface">Gambar 3.3: Kerangka Kerja Analisis Kesenjangan (Gap Analysis) dan Pemetaan Rekomendasi RACI</p>
                            <p class="text-[11px] text-outline italic">Sumber: Disarikan dari ISACA COBIT 2019 &amp; Metodologi Penelitian Sistem Informasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 2: 5 DOMAIN & 40 SUBDOMAIN -->
    @if($activeTab === 'domains')
        <div class="space-y-6 animate-in fade-in duration-200">
            <!-- Penjelasan Ringkas 5 Domain Resmi ISACA -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="p-3.5 rounded-xl border border-purple-200 bg-purple-50/60 flex flex-col gap-1">
                    <span class="font-mono text-xs font-bold text-purple-800">EDM (5 Objectives)</span>
                    <strong class="text-xs text-on-surface">Evaluate, Direct &amp; Monitor</strong>
                    <p class="text-[11px] text-on-surface-variant leading-relaxed">
                        Domain tata kelola (*Governance*). Mengatur evaluasi opsi strategis, penetapan arahan bisnis, dan pengawasan berkala dewan komisaris/direksi.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl border border-indigo-200 bg-indigo-50/60 flex flex-col gap-1">
                    <span class="font-mono text-xs font-bold text-indigo-800">APO (14 Objectives)</span>
                    <strong class="text-xs text-on-surface">Align, Plan &amp; Organize</strong>
                    <p class="text-[11px] text-on-surface-variant leading-relaxed">
                        Manajemen strategi I&amp;T, arsitektur enterprise, inovasi, portofolio investasi, anggaran, SDM, hubungan kemitraan, vendor, kualitas, dan risiko.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl border border-blue-200 bg-blue-50/60 flex flex-col gap-1">
                    <span class="font-mono text-xs font-bold text-blue-800">BAI (11 Objectives)</span>
                    <strong class="text-xs text-on-surface">Build, Acquire &amp; Implement</strong>
                    <p class="text-[11px] text-on-surface-variant leading-relaxed">
                        Manajemen program/proyek, identifikasi kebutuhan solusi, pengadaan sistem, manajemen perubahan (*change management*), dan aset TI.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl border border-emerald-200 bg-emerald-50/60 flex flex-col gap-1">
                    <span class="font-mono text-xs font-bold text-emerald-800">DSS (6 Objectives)</span>
                    <strong class="text-xs text-on-surface">Deliver, Service &amp; Support</strong>
                    <p class="text-[11px] text-on-surface-variant leading-relaxed">
                        Manajemen operasional harian, insiden, permintaan layanan (*service desk*), kesinambungan bisnis (*BCP/DRP*), dan keamanan sistem.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl border border-amber-200 bg-amber-50/60 flex flex-col gap-1">
                    <span class="font-mono text-xs font-bold text-amber-800">MEA (4 Objectives)</span>
                    <strong class="text-xs text-on-surface">Monitor, Evaluate &amp; Assess</strong>
                    <p class="text-[11px] text-on-surface-variant leading-relaxed">
                        Evaluasi kinerja internal, pengendalian internal (*internal control*), audit kepatuhan, serta kesesuaian terhadap regulasi eksternal.
                    </p>
                </div>
            </div>

            <!-- Filter Domain Bar -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-3.5 shadow-xs flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-semibold text-outline uppercase tracking-wider">Filter Domain:</span>
                    @foreach(['ALL' => 'Semua (40)', 'EDM' => 'EDM (5)', 'APO' => 'APO (14)', 'BAI' => 'BAI (11)', 'DSS' => 'DSS (6)', 'MEA' => 'MEA (4)'] as $key => $label)
                        <button 
                            wire:click="setDomainFilter('{{ $key }}')" 
                            type="button" 
                            class="px-2.5 py-1 rounded text-xs font-medium transition-all cursor-pointer {{ $selectedDomainFilter === $key ? 'bg-primary text-on-primary font-semibold shadow-xs' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
                <span class="text-xs text-outline font-medium">Menampilkan {{ $objectives->count() }} Objectives</span>
            </div>

            <!-- List 40 Subdomain / Objectives dengan Purpose Statement -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($objectives as $obj)
                    @php
                        $codePrefix = substr($obj->objective_id, 0, 3);
                        $pillColor = match($codePrefix) {
                            'EDM' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'APO' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                            'BAI' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'DSS' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'MEA' => 'bg-amber-100 text-amber-800 border-amber-200',
                            default => 'bg-slate-100 text-slate-800 border-slate-200',
                        };
                    @endphp
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-4 shadow-xs flex flex-col justify-between space-y-3">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-mono text-xs font-bold px-2 py-0.5 rounded border {{ $pillColor }}">
                                    {{ $obj->objective_id }}
                                </span>
                                <span class="text-[11px] font-semibold text-outline uppercase tracking-wider">
                                    {{ $obj->area }} Domain
                                </span>
                            </div>

                            <h3 class="text-sm font-bold text-on-surface leading-snug">
                                {{ $obj->name }}
                            </h3>

                            <p class="text-xs text-on-surface-variant leading-relaxed">
                                {{ $obj->description }}
                            </p>
                        </div>

                        @if($obj->purpose_statement)
                            <div class="bg-primary-fixed/20 border border-primary-fixed/40 rounded-lg p-2.5 flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[17px] mt-0.5 flex-shrink-0">lightbulb</span>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-primary block">Tujuan Baku (Purpose Statement):</span>
                                    <p class="text-xs text-on-surface leading-relaxed italic">
                                        {{ $obj->purpose_statement }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- TAB 3: METODOLOGI & GAP ANALYSIS (ISACA STANDARDS) -->
    @if($activeTab === 'methodology')
        <div class="space-y-6 animate-in fade-in duration-200">
            <!-- 3.1 4 Tahapan Desain Sistem Tata Kelola ISACA (Figure 7.2) -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                    <span class="w-8 h-8 rounded-lg bg-primary-fixed/50 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[19px]">account_tree</span>
                    </span>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">1. Alur Perancangan Sistem Tata Kelola (ISACA 4-Stage Workflow)</h2>
                        <p class="text-xs text-on-surface-variant">ISACA COBIT 2019 Design Guide (Figure 7.2) — Alur metodologis standar dalam merancang dan mengevaluasi EGIT</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="p-3.5 rounded-lg border border-outline-variant/20 bg-surface-container-low flex flex-col gap-1.5">
                        <span class="w-6 h-6 rounded-full bg-primary text-on-primary font-bold text-xs flex items-center justify-center">1</span>
                        <strong class="text-xs text-on-surface">Stage 1: Pahami Konteks &amp; Strategi</strong>
                        <p class="text-[11px] text-on-surface-variant leading-relaxed">
                            Menganalisis strategi enterprise, enterprise goals, profil risiko saat ini, dan permasalahan aktual I&amp;T yang dihadapi (DF 1 s.d DF 4).
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg border border-outline-variant/20 bg-surface-container-low flex flex-col gap-1.5">
                        <span class="w-6 h-6 rounded-full bg-primary text-on-primary font-bold text-xs flex items-center justify-center">2</span>
                        <strong class="text-xs text-on-surface">Stage 2: Tentukan Cakupan Awal</strong>
                        <p class="text-[11px] text-on-surface-variant leading-relaxed">
                            Menerapkan *Design Factor Toolkit* untuk memetakan prioritas awal 40 objective dan menetapkan target capability level awal.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg border border-outline-variant/20 bg-surface-container-low flex flex-col gap-1.5">
                        <span class="w-6 h-6 rounded-full bg-primary text-on-primary font-bold text-xs flex items-center justify-center">3</span>
                        <strong class="text-xs text-on-surface">Stage 3: Sempurnakan Cakupan</strong>
                        <p class="text-[11px] text-on-surface-variant leading-relaxed">
                            Menyesuaikan rancangan dengan mempertimbangkan lanskap ancaman, mandat kepatuhan, model sourcing, dan peran TI (DF 5 s.d DF 11).
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg border border-outline-variant/20 bg-surface-container-low flex flex-col gap-1.5">
                        <span class="w-6 h-6 rounded-full bg-primary text-on-primary font-bold text-xs flex items-center justify-center">4</span>
                        <strong class="text-xs text-on-surface">Stage 4: Finalisasi Desain &amp; Aksi</strong>
                        <p class="text-[11px] text-on-surface-variant leading-relaxed">
                            Menetapkan desain sistem tata kelola final, menyimpulkan nilai kesenjangan (*GAP*), dan menyusun *Implementation Roadmap* serta rekomendasi RACI.
                        </p>
                    </div>
                </div>

                <!-- EMBEDDED DIAGRAM 3.1 -->
                <div class="p-4 rounded-xl bg-surface-container-high/50 border border-outline-variant/40 space-y-3">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <span class="text-xs font-bold text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">image</span>
                            <span>Ilustrasi Visual Gambar Skripsi</span>
                        </span>
                        <button 
                            @click="copyText('Gambar 3.1: Alur Metodologi Perancangan Sistem Tata Kelola COBIT 2019 4 Tahapan (Sumber: ISACA, 2018)')" 
                            type="button" 
                            class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary hover:underline cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[14px]">content_copy</span>
                            <span>Salin Judul &amp; Sumber</span>
                        </button>
                    </div>

                    <div class="bg-surface-container-lowest rounded-lg border border-outline-variant/30 p-4 space-y-3">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-center text-xs">
                            <div class="p-2.5 rounded bg-primary-fixed/20 border border-primary-fixed/50 font-bold text-primary flex-1">Stage 1: Pahami Konteks</div>
                            <span class="material-symbols-outlined text-outline text-[18px]">arrow_forward</span>
                            <div class="p-2.5 rounded bg-primary-fixed/20 border border-primary-fixed/50 font-bold text-primary flex-1">Stage 2: Cakupan Awal</div>
                            <span class="material-symbols-outlined text-outline text-[18px]">arrow_forward</span>
                            <div class="p-2.5 rounded bg-primary-fixed/20 border border-primary-fixed/50 font-bold text-primary flex-1">Stage 3: Sempurnakan</div>
                            <span class="material-symbols-outlined text-outline text-[18px]">arrow_forward</span>
                            <div class="p-2.5 rounded bg-secondary-fixed/20 border border-secondary-fixed/50 font-bold text-secondary flex-1">Stage 4: Finalisasi</div>
                        </div>

                        <div class="pt-2 border-t border-outline-variant/20 text-center space-y-0.5">
                            <p class="text-xs font-bold text-on-surface">Gambar 3.1: Alur Metodologi Perancangan Sistem Tata Kelola COBIT 2019 (4-Stage Workflow)</p>
                            <p class="text-[11px] text-outline italic">Sumber: ISACA (2018), COBIT 2019 Design Guide, hlm. 31</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3.2 Capability Level (Skala 0 - 5 Berbasis CMMI Scheme) -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                    <span class="w-8 h-8 rounded-lg bg-secondary-fixed/50 flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined text-[19px]">trending_up</span>
                    </span>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">2. Tingkat Kapabilitas Proses (Capability Level 0–5)</h2>
                        <p class="text-xs text-on-surface-variant">Standar COBIT Performance Management (CPM) berbasis skema CMMI (Capability Maturity Model Integration)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
                    <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 flex flex-col gap-1">
                        <span class="font-bold text-xs text-slate-700 font-mono">Level 0: Incomplete</span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Proses tidak terimplementasi atau gagal mencapai tujuannya. Tidak ada bukti terdokumentasi mengenai pencapaian tujuan proses sama sekali.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 flex flex-col gap-1">
                        <span class="font-bold text-xs text-primary font-mono">Level 1: Initial</span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Proses telah berjalan secara intuitif / ad-hoc dan mencapai tujuan dasarnya, namun belum terorganisir dengan dokumentasi kebijakan formal.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 flex flex-col gap-1">
                        <span class="font-bold text-xs text-primary font-mono">Level 2: Managed</span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Proses telah direncanakan, dimonitor, disesuaikan, dan hasil kerjanya didokumentasikan serta dipelihara dengan baik oleh unit pelaksana.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 flex flex-col gap-1">
                        <span class="font-bold text-xs text-primary font-mono">Level 3: Defined</span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Proses telah distandarisasi di seluruh organisasi melalui SOP resmi dan mampu mencapai hasil proses yang diharapkan secara konsisten.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 flex flex-col gap-1">
                        <span class="font-bold text-xs text-primary font-mono">Level 4: Quantitative</span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Proses telah diukur dan dikendalikan secara kuantitatif menggunakan metrik kinerja statistik atau Key Performance Indicators (KPI) terstandar.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/20 flex flex-col gap-1">
                        <span class="font-bold text-xs text-primary font-mono">Level 5: Optimizing</span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Proses terus disempurnakan secara berkelanjutan (*continuous improvement*) berdasarkan evaluasi hasil analisis inovasi dan teknologi mutakhir.
                        </p>
                    </div>
                </div>
            </div>

            <!-- 3.3 Skala Penilaian Ketercapaian (NPLF) & Gap Analysis Formula -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                <!-- Skala NPLF & Aturan Milestone ISACA -->
                <div class="lg:col-span-6 bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 shadow-xs space-y-3">
                    <h3 class="text-sm font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">percent</span>
                        <span>Skala Penilaian Ketercapaian (NPLF Scale - ISO/IEC 33020)</span>
                    </h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Standar resmi ISACA COBIT Performance Management untuk mengukur tingkat kepatuhan aktivitas:
                    </p>

                    <div class="space-y-2 text-xs">
                        <div class="p-2.5 rounded-lg bg-rose-50 border border-rose-200 flex items-center justify-between">
                            <span class="font-bold text-rose-800">N — Not Achieved (0% – 15%)</span>
                            <span class="text-rose-700 text-[11px]">Sedikit / Tidak tercapai sama sekali</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-amber-50 border border-amber-200 flex items-center justify-between">
                            <span class="font-bold text-amber-800">P — Partially Achieved (&gt;15% – 50%)</span>
                            <span class="text-amber-700 text-[11px]">Tercapai sebagian</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-between">
                            <span class="font-bold text-blue-800">L — Largely Achieved (&gt;50% – 85%)</span>
                            <span class="text-blue-700 text-[11px]">Tercapai substansial</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                            <span class="font-bold text-emerald-800">F — Fully Achieved (&gt;85% – 100%)</span>
                            <span class="text-emerald-700 text-[11px]">Tercapai penuh</span>
                        </div>
                    </div>

                    <div class="p-3 rounded-lg bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1">
                        <strong class="font-semibold block flex items-center gap-1.5 text-amber-950">
                            <span class="material-symbols-outlined text-[16px]">priority_high</span>
                            Aturan Emas Milestone ISACA (Milestone Rule):
                        </strong>
                        <p class="text-[11px] leading-relaxed">
                            Suatu level kapabilitas dinyatakan tercapai (*Achieved*) jika dan hanya jika <strong>SELURUH aktivitas pada level tersebut</strong> memperoleh rating minimal <strong>L (Largely)</strong> atau <strong>F (Fully)</strong>. Nilai kuesioner tidak boleh dirata-ratakan secara linier tanpa memenuhi prasyarat milestone level sebelumnya.
                        </p>
                    </div>
                </div>

                <!-- Rumus & Definisi GAP Analysis Resmi -->
                <div class="lg:col-span-6 bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 shadow-xs space-y-3">
                    <h3 class="text-sm font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">functions</span>
                        <span>Rumus &amp; Perhitungan GAP Analysis</span>
                    </h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        <strong>Analisis GAP (*Gap Analysis*)</strong> adalah metode evaluasi matematis untuk mengukur besarnya kesenjangan antara tingkat kapabilitas saat ini (*Current Level*) dengan target kapabilitas yang diharapkan (*Target Level*).
                    </p>

                    <!-- Formula Box -->
                    <div class="p-4 rounded-xl bg-surface-container-high/60 border border-outline-variant/40 text-center space-y-1.5">
                        <span class="text-[11px] font-semibold text-outline uppercase tracking-wider block">Formula Gap Analysis</span>
                        <div class="text-base sm:text-lg font-mono font-bold text-primary">
                            GAP = Target Capability Level − Current Capability Level
                        </div>
                        <div class="text-[11px] font-mono text-on-surface-variant">
                            Indeks Capaian (%) = (Current Capability Level / Target Capability Level) × 100%
                        </div>
                    </div>

                    <div class="text-xs text-on-surface-variant space-y-1.5">
                        <p><strong>Interpretasi Nilai Kesenjangan (GAP):</strong></p>
                        <ul class="list-disc list-inside space-y-1 text-[11px]">
                            <li><strong>GAP = 0 (Compliant):</strong> Tidak terdapat kesenjangan. Kapabilitas proses telah memenuhi target ekspektasi enterprise.</li>
                            <li><strong>GAP &gt; 0 (Improvement Priority):</strong> Terjadi kesenjangan kapabilitas sebesar nilai GAP. Wajib dirumuskan rekomendasi perbaikan berbasis matriks RACI untuk aktivitas yang belum mencapai rating L/F.</li>
                            <li><strong>GAP &lt; 0 (Exceeds Target):</strong> Kapabilitas aktual melebihi target yang ditetapkan, membuka peluang efisiensi alokasi sumber daya.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 3.4 Matriks Penugasan RACI Standar ISACA -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 shadow-xs space-y-3">
                <h3 class="text-sm font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">badge</span>
                    <span>Prinsip Penugasan Matriks RACI Standar ISACA</span>
                </h3>
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    Setiap aktivitas dalam 40 subdomain COBIT 2019 dipetakan ke peran enterprise menggunakan kerangka RACI:
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                        <strong class="text-primary font-bold block">R — Responsible</strong>
                        <p class="text-[11px] text-on-surface-variant leading-relaxed">
                            Pihak yang bertindak sebagai pelaksana operasional untuk menyelesaikan tugas atau aktivitas proses secara teknis.
                        </p>
                    </div>

                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                        <strong class="text-purple-700 font-bold block">A — Accountable</strong>
                        <p class="text-[11px] text-on-surface-variant leading-relaxed">
                            Penanggung jawab tunggal (*Single Point of Accountability*) yang memegang wewenang persetujuan akhir. <strong>Prinsip ISACA: Tepat satu peran A per aktivitas.</strong>
                        </p>
                    </div>

                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                        <strong class="text-blue-700 font-bold block">C — Consulted</strong>
                        <p class="text-[11px] text-on-surface-variant leading-relaxed">
                            Pihak ahli atau pemangku kepentingan yang dimintai pandangan dan masukan secara dua arah sebelum keputusan/tindakan diambil.
                        </p>
                    </div>

                    <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20 space-y-1">
                        <strong class="text-amber-700 font-bold block">I — Informed</strong>
                        <p class="text-[11px] text-on-surface-variant leading-relaxed">
                            Pihak yang menerima pembaruan informasi secara satu arah mengenai kemajuan atau hasil penyelesaian aktivitas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 4: GLOSARIUM ISTILAH BAKU ISACA -->
    @if($activeTab === 'glossary')
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4 animate-in fade-in duration-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-outline-variant/20">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-primary-fixed/50 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[19px]">spellcheck</span>
                    </span>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">Glosarium Istilah Baku COBIT 2019 (ISACA Standard)</h2>
                        <p class="text-xs text-on-surface-variant">Daftar terminologi resmi berstandar ISACA untuk referensi penulisan Bab 2 Landasan Teori</p>
                    </div>
                </div>

                <div class="relative w-full sm:w-72">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]">search</span>
                    <input 
                        wire:model.live.debounce.250ms="glossarySearch" 
                        type="text" 
                        placeholder="Cari istilah..." 
                        class="w-full h-9 pl-9 pr-3 rounded-lg bg-surface-container-low border border-outline-variant/40 text-xs text-on-surface placeholder:text-outline focus:outline-none focus:border-primary focus:bg-surface-container-lowest transition-colors"
                    />
                </div>
            </div>

            @php
                $glossaryItems = [
                    ['term' => 'EGIT (Enterprise Governance of Information and Technology)', 'desc' => 'Sistem tata kelola menyeluruh di tingkat perusahaan yang mengarahkan dan mengendalikan pemanfaatan I&T untuk mencapai nilai bisnis optimal serta memitigasi risiko.'],
                    ['term' => 'Governance Objective', 'desc' => 'Tujuan tata kelola yang berkaitan langsung dengan proses evaluasi opsi strategis, penetapan arahan keputusan, dan pengawasan berkala dewan direksi/komisaris (Domain EDM).'],
                    ['term' => 'Management Objective', 'desc' => 'Tujuan manajemen yang berkaitan dengan perencanaan harian, perancangan, pembangunan solusi, eksekusi layanan, dan pemantauan kinerja operasional I&T (Domain APO, BAI, DSS, MEA).'],
                    ['term' => 'Goals Cascade', 'desc' => 'Alur penerjemahan berjenjang dari kebutuhan pemangku kepentingan (Stakeholder Needs) menjadi 13 Enterprise Goals, 13 Alignment Goals, dan 40 Governance/Management Objectives.'],
                    ['term' => 'Enterprise Goals (EG)', 'desc' => '13 tujuan bisnis perusahaan yang dikelompokkan ke dalam 4 dimensi Balanced Scorecard (Financial, Customer, Internal, Learning & Growth).'],
                    ['term' => 'Alignment Goals (AG)', 'desc' => '13 sasaran keselarasan I&T (dahulu bernama IT-Related Goals pada COBIT 5) yang mendukung pencapaian tujuan bisnis enterprise.'],
                    ['term' => 'Governance Components', 'desc' => '7 faktor interaktif (Processes, Organizational Structures, Principles/Policies, Information, Culture/Behavior, People/Skills, Services/Infrastructure) yang bekerja sama membentuk sistem tata kelola.'],
                    ['term' => 'Design Factors', 'desc' => '11 variabel kontekstual organisasi (seperti strategi bisnis, profil risiko, peran TI, dan ukuran enterprise) yang digunakan untuk menyesuaikan skala prioritas sistem tata kelola.'],
                    ['term' => 'Focus Area', 'desc' => 'Topik atau domain tata kelola spesifik yang membutuhkan panduan mendalam tambahan, seperti Cyber Security, Small and Medium Enterprises (SMEs), dan DevOps.'],
                    ['term' => 'Capability Level', 'desc' => 'Skala kematangan proses bertingkat (Level 0 sampai 5) berbasis CMMI yang mengukur seberapa baik suatu proses tata kelola/manajemen I&T diimplementasikan dalam enterprise.'],
                    ['term' => 'NPLF Scale', 'desc' => 'Skala penilaian ketercapaian aktivitas proses berbasis standar ISO/IEC 33020: Not Achieved (0-15%), Partially Achieved (>15-50%), Largely Achieved (>50-85%), Fully Achieved (>85-100%).'],
                    ['term' => 'Milestone Rule', 'desc' => 'Aturan resmi ISACA di mana suatu level kapabilitas hanya dinyatakan tercapai apabila seluruh aktivitas pada level tersebut memperoleh status minimal Largely (L) atau Fully (F).'],
                    ['term' => 'Gap Analysis', 'desc' => 'Metode analisis matematis yang mengukur selisih antara tingkat kapabilitas target yang diharapkan dengan kapabilitas nyata yang ditemukan saat asesmen (GAP = Target - Current).'],
                    ['term' => 'RACI Matrix', 'desc' => 'Model penetapan tanggung jawab peran: Responsible (pelaksana teknis), Accountable (pemilik akuntabilitas akhir tunggal), Consulted (pemberi masukan ahli), dan Informed (penerima info pembaruan).'],
                    ['term' => 'Audit Evidence', 'desc' => 'Bukti faktual yang diverifikasi oleh auditor (seperti dokumen kebijakan, log sistem, notula rapat, struktur organisasi, dan tiket helpdesk) untuk mengonfirmasi ketercapaian aktivitas.'],
                    ['term' => 'Service Level Agreement (SLA)', 'desc' => 'Perjanjian formal antara penyedia layanan TI dengan pengguna bisnis mengenai standar kuantitatif performa layanan (ketersediaan, waktu respon perbaikan).'],
                ];

                if (trim($glossarySearch) !== '') {
                    $searchQ = strtolower(trim($glossarySearch));
                    $glossaryItems = array_filter($glossaryItems, fn($item) => str_contains(strtolower($item['term']), $searchQ) || str_contains(strtolower($item['desc']), $searchQ));
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 pt-2">
                @forelse($glossaryItems as $item)
                    <div class="p-3.5 rounded-lg border border-outline-variant/25 bg-surface-container-low/40 space-y-1">
                        <h4 class="text-xs font-bold text-primary">{{ $item['term'] }}</h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                @empty
                    <div class="p-8 text-center col-span-2 text-outline text-xs">
                        Tidak ada istilah yang cocok dengan kata kunci pencarian.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- TAB 5: PENELITIAN TERDAHULU -->
    @if($activeTab === 'literature')
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs space-y-4 animate-in fade-in duration-200">
            <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                <span class="w-8 h-8 rounded-lg bg-tertiary-fixed/50 flex items-center justify-center text-tertiary">
                    <span class="material-symbols-outlined text-[19px]">library_books</span>
                </span>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-on-surface">Matriks Penelitian Terdahulu (State of the Art)</h2>
                    <p class="text-xs text-on-surface-variant">Tabel acuan studi literatur komparatif untuk penyusunan Bab 2 Skripsi / Tesis</p>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr class="bg-surface-container-high border-b border-outline-variant/30 text-on-surface font-bold">
                            <th class="p-3">Peneliti &amp; Tahun</th>
                            <th class="p-3">Objek / Institusi</th>
                            <th class="p-3">Domain COBIT yang Diteliti</th>
                            <th class="p-3">Metodologi yang Dipakai</th>
                            <th class="p-3">Hasil Analisis &amp; Nilai GAP</th>
                            <th class="p-3">Relevansi / Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20 text-on-surface-variant">
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="p-3 font-semibold text-on-surface">Pratama et al. (2022)</td>
                            <td class="p-3">Instansi Perbankan BUMN</td>
                            <td class="p-3 font-mono text-primary">EDM03, APO12, DSS05</td>
                            <td class="p-3">COBIT 2019 Design Factors &amp; Capability Level</td>
                            <td class="p-3">Current Level 2.4, Target Level 4.0. Ditemukan GAP 1.6 pada tata kelola manajemen risiko dan keamanan data.</td>
                            <td class="p-3">Menjadi acuan pembobotan Design Factor untuk sektor yang memiliki regulasi kepatuhan ketat.</td>
                        </tr>
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="p-3 font-semibold text-on-surface">Hidayat &amp; Raharjo (2023)</td>
                            <td class="p-3">Dinas Komunikasi &amp; Informatika (Diskominfo)</td>
                            <td class="p-3 font-mono text-primary">APO01, BAI06, DSS01, MEA01</td>
                            <td class="p-3">COBIT 2019 Capability Assessment &amp; RACI Mapping</td>
                            <td class="p-3">Current Level 1.8, Target Level 3.0. GAP 1.2 pada SOP perubahan sistem (BAI06) dan pemantauan performa.</td>
                            <td class="p-3">Menunjukkan pentingnya pemetaan RACI matrix untuk mengatasi kekosongan wewenang (*lack of accountability*).</td>
                        </tr>
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="p-3 font-semibold text-on-surface">Wibowo &amp; Santoso (2023)</td>
                            <td class="p-3">Perguruan Tinggi Swasta</td>
                            <td class="p-3 font-mono text-primary">APO07, DSS02, DSS03</td>
                            <td class="p-3">COBIT 2019 Assessment &amp; Gap Analysis</td>
                            <td class="p-3">Current Level 2.0, Target Level 3.0. GAP 1.0 pada pengelolaan insiden dan SDM TI pendukung e-learning.</td>
                            <td class="p-3">Memberikan acuan formulasi rekomendasi perbaikan berbasis skala prioritas tingkat urgensi gap.</td>
                        </tr>
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="p-3 font-semibold text-on-surface">Kusuma et al. (2024)</td>
                            <td class="p-3">Perusahaan E-Commerce / Startup</td>
                            <td class="p-3 font-mono text-primary">BAI03, BAI10, DSS05</td>
                            <td class="p-3">COBIT 2019 Core Model &amp; Agile Alignment</td>
                            <td class="p-3">Current Level 2.8, Target Level 4.0. GAP 1.2 pada dokumentasi konfigurasi dan kontrol keamanan cloud.</td>
                            <td class="p-3">Acuan penerapan COBIT 2019 pada organisasi yang mengadopsi metode pengembangan sistem lincah (Agile/DevOps).</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/20 text-xs text-on-surface-variant">
                <strong>Tips Penulisan Bab 2:</strong> Sintesiskan tabel ini dengan memaparkan apa yang membedakan penelitian Anda (misalnya perbedaan objek studi, pemilihan domain prioritas, atau kedalaman instrumen audit bukti operasional) dibandingkan penelitian-penelitian di atas.
            </div>
        </div>
    @endif
</div>
