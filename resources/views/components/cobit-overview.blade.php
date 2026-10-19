<?php

use App\Models\CobitObjective;
use App\Models\CobitPractice;
use App\Models\CobitActivity;
use App\Models\CobitRole;
use App\Models\CobitRaciAssignment;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app', ['breadcrumb' => 'Overview'])] class extends Component
{
    public bool $showSpecModal = false;

    public function openSpecModal(): void
    {
        $this->showSpecModal = true;
    }

    public function closeSpecModal(): void
    {
        $this->showSpecModal = false;
    }

    public function render()
    {
        $totalObjectives = CobitObjective::count();
        $totalPractices = CobitPractice::count();
        $totalActivities = CobitActivity::count();
        $totalRoles = CobitRole::count();
        $totalRaciAssignments = CobitRaciAssignment::count();
        $totalAccountable = CobitRaciAssignment::where('raci_level', 'A')->count();
        $totalResponsible = CobitRaciAssignment::where('raci_level', 'R')->count();

        $domains = [
            [
                'code' => 'EDM',
                'name' => 'Evaluate, Direct and Monitor',
                'area' => 'Governance Domain',
                'objectives' => 5,
                'practices' => 16,
                'activities' => 86,
                'badge_class' => 'bg-purple-100 text-purple-800 border-purple-200',
                'text_class' => 'text-purple-700',
                'url' => route('cobit.framework'),
            ],
            [
                'code' => 'APO',
                'name' => 'Align, Plan and Organize',
                'area' => 'Management Domain',
                'objectives' => 14,
                'practices' => 83,
                'activities' => 438,
                'badge_class' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                'text_class' => 'text-indigo-700',
                'url' => route('cobit.framework'),
            ],
            [
                'code' => 'BAI',
                'name' => 'Build, Acquire and Implement',
                'area' => 'Management Domain',
                'objectives' => 11,
                'practices' => 72,
                'activities' => 363,
                'badge_class' => 'bg-blue-100 text-blue-800 border-blue-200',
                'text_class' => 'text-blue-700',
                'url' => route('cobit.framework'),
            ],
            [
                'code' => 'DSS',
                'name' => 'Deliver, Service and Support',
                'area' => 'Management Domain',
                'objectives' => 6,
                'practices' => 38,
                'activities' => 206,
                'badge_class' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'text_class' => 'text-emerald-700',
                'url' => route('cobit.framework'),
            ],
            [
                'code' => 'MEA',
                'name' => 'Monitor, Evaluate and Assess',
                'area' => 'Management Domain',
                'objectives' => 4,
                'practices' => 22,
                'activities' => 109,
                'badge_class' => 'bg-amber-100 text-amber-800 border-amber-200',
                'text_class' => 'text-amber-700',
                'url' => route('cobit.framework'),
            ],
        ];

        return view('components.cobit-overview', [
            'totalObjectives' => $totalObjectives,
            'totalPractices' => $totalPractices,
            'totalActivities' => $totalActivities,
            'totalRoles' => $totalRoles,
            'totalRaciAssignments' => $totalRaciAssignments,
            'totalAccountable' => $totalAccountable,
            'totalResponsible' => $totalResponsible,
            'domains' => $domains,
        ]);
    }
};
?>

<div class="max-w-[1600px] mx-auto w-full px-4 sm:px-6 lg:px-8 py-5 sm:py-6 space-y-5">
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-outline-variant/15 pb-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-semibold bg-primary-fixed text-on-primary-fixed border border-primary/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    SYS.v2019-R2
                </span>
                <span class="text-xs text-outline font-medium">ISACA Reference Baseline</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-on-surface">Overview</h1>
            <p class="text-xs sm:text-sm text-outline">
                Ringkasan framework tata kelola COBIT 2019, sasaran tata kelola dan manajemen, serta pemetaan matriks RACI.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <button 
                type="button"
                wire:click="openSpecModal"
                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface transition-colors border border-outline-variant/20 shadow-xs cursor-pointer"
            >
                <span class="material-symbols-outlined text-[17px] text-primary">verified</span>
                <span>Spesifikasi Standar</span>
            </button>
            <a 
                href="{{ route('cobit.framework') }}" 
                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg bg-primary-container hover:bg-primary text-on-primary shadow-xs transition-colors"
            >
                <span class="material-symbols-outlined text-[17px]">grid_view</span>
                <span>Buka Explorer</span>
            </a>
        </div>
    </div>

    <!-- METRICS GRID (4 CARDS) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Card 1: Objectives -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-4 shadow-xs flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] sm:text-xs uppercase tracking-wider text-outline font-semibold">Governance &amp; Mgt</span>
                    <div class="text-xl sm:text-2xl font-bold tabular-nums text-on-surface mt-1 leading-none">{{ $totalObjectives }}</div>
                </div>
                <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-[20px]">verified_user</span>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-outline-variant/10">
                <div class="text-xs font-semibold text-on-surface">Objectives</div>
                <div class="text-[11px] text-outline">5 Governance &amp; 35 Management</div>
            </div>
        </div>

        <!-- Card 2: Practices -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-4 shadow-xs flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] sm:text-xs uppercase tracking-wider text-outline font-semibold">Control Core</span>
                    <div class="text-xl sm:text-2xl font-bold tabular-nums text-on-surface mt-1 leading-none">{{ $totalPractices }}</div>
                </div>
                <div class="w-9 h-9 rounded-lg bg-indigo-500/10 text-indigo-700 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-[20px]">account_tree</span>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-outline-variant/10">
                <div class="text-xs font-semibold text-on-surface">Practices</div>
                <div class="text-[11px] text-outline">Governance &amp; Management practices</div>
            </div>
        </div>

        <!-- Card 3: Activities -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-4 shadow-xs flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] sm:text-xs uppercase tracking-wider text-outline font-semibold">Guidance Scope</span>
                    <div class="text-xl sm:text-2xl font-bold tabular-nums text-on-surface mt-1 leading-none">{{ number_format($totalActivities) }}</div>
                </div>
                <div class="w-9 h-9 rounded-lg bg-blue-500/10 text-blue-700 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-[20px]">checklist</span>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-outline-variant/10">
                <div class="text-xs font-semibold text-on-surface">Activities</div>
                <div class="text-[11px] text-outline">Action items &amp; guidance detail</div>
            </div>
        </div>

        <!-- Card 4: RACI Assignments -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-4 shadow-xs flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[11px] sm:text-xs uppercase tracking-wider text-outline font-semibold">RACI Matrix</span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                            33 Roles
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-bold tabular-nums text-on-surface mt-1 leading-none">{{ number_format($totalRaciAssignments) }}</div>
                </div>
                <div class="w-9 h-9 rounded-lg bg-emerald-500/10 text-emerald-700 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-[20px]">table_chart</span>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-outline-variant/10">
                <div class="text-xs font-semibold text-on-surface">RACI Assignments</div>
                <div class="text-[11px] text-outline truncate">{{ $totalAccountable }} Accountable &bull; {{ $totalResponsible }} Responsible</div>
            </div>
        </div>
    </div>

    <!-- TWO-COLUMN SECTION: COBIT DOMAINS & RACI STRUCTURE -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- LEFT: COBIT DOMAINS (7 COLS) -->
        <div class="lg:col-span-7 bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-5 shadow-xs flex flex-col justify-between space-y-4">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-3 border-b border-outline-variant/15 gap-2">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">COBIT Domains</h2>
                        <p class="text-xs text-outline">5 Domain standar tata kelola dan manajemen teknologi informasi</p>
                    </div>
                    <span class="self-start sm:self-auto px-2.5 py-1 rounded-full bg-surface-container text-on-surface text-[11px] font-semibold border border-outline-variant/20">
                        5 Domain Standar
                    </span>
                </div>

                <!-- Domain Rows List -->
                <div class="divide-y divide-outline-variant/10 mt-1">
                    @foreach($domains as $dom)
                        <a 
                            href="{{ $dom['url'] }}" 
                            class="group py-3 flex items-center justify-between hover:bg-surface-container-low/60 px-2 rounded-lg transition-colors"
                        >
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-12 text-center py-1 rounded font-mono text-xs font-bold border {{ $dom['badge_class'] }}">
                                    {{ $dom['code'] }}
                                </span>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[11px] font-semibold uppercase tracking-wider {{ $dom['text_class'] }}">
                                        {{ $dom['area'] }}
                                    </span>
                                    <span class="text-xs sm:text-sm font-semibold text-on-surface truncate group-hover:text-primary transition-colors">
                                        {{ $dom['name'] }}
                                    </span>
                                    <span class="text-[11px] text-outline">
                                        {{ $dom['objectives'] }} Objectives &bull; {{ $dom['practices'] }} Practices &bull; {{ $dom['activities'] }} Activities
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 text-outline group-hover:text-primary text-xs font-medium transition-colors shrink-0 pl-2">
                                <span class="hidden sm:inline">Telusuri</span>
                                <span class="material-symbols-outlined text-[16px] group-hover:translate-x-0.5 transition-transform">chevron_right</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 border-t border-outline-variant/10 flex items-center justify-between text-xs text-outline">
                <span>Total 40 Objectives COBIT 2019</span>
                <a href="{{ route('cobit.framework') }}" class="font-medium text-primary hover:underline flex items-center gap-1">
                    <span>Buka Framework Explorer</span>
                    <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- RIGHT: RACI MATRIX & GOVERNANCE STRUCTURE (5 COLS) -->
        <div class="lg:col-span-5 bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-5 shadow-xs flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-outline-variant/15">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">Struktur Tata Kelola &amp; RACI</h2>
                        <p class="text-xs text-outline">Distribusi akuntabilitas &amp; tanggung jawab</p>
                    </div>
                    <span class="px-2 py-0.5 rounded bg-surface-container font-mono text-[11px] text-outline font-medium border border-outline-variant/20">
                        ISACA RACI
                    </span>
                </div>

                <!-- RACI Summary Card -->
                <div class="mt-4 p-4 rounded-xl bg-surface-container-low/70 border border-outline-variant/20 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                                33 Peran Organisasi
                            </span>
                            <h3 class="text-sm font-bold text-on-surface mt-1.5 leading-snug">
                                Pemetaan Peran Komprehensif
                            </h3>
                            <p class="text-xs text-outline mt-0.5">
                                Seluruh 231 praktik tata kelola dan manajemen telah dipetakan akuntabilitasnya secara definitif.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-outline-variant/15 text-xs">
                        <div class="p-2.5 rounded-lg bg-surface-container-lowest border border-outline-variant/15">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                <span class="text-[11px] font-semibold text-outline">Accountable (A)</span>
                            </div>
                            <span class="text-lg font-bold text-on-surface">{{ $totalAccountable }}</span>
                            <span class="text-[10px] text-outline block">1 per praktik</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-surface-container-lowest border border-outline-variant/15">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <span class="text-[11px] font-semibold text-outline">Responsible (R)</span>
                            </div>
                            <span class="text-lg font-bold text-on-surface">{{ number_format($totalResponsible) }}</span>
                            <span class="text-[10px] text-outline block">Pelaksana tugas</span>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2">
                        <a 
                            href="{{ route('raci.matrix') }}" 
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-primary-container text-on-primary hover:bg-primary transition-colors shadow-xs"
                        >
                            <span>Buka RACI Matrix</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-outline-variant/10 text-center">
                <p class="text-[11px] text-outline">
                    Data tersinkronisasi 100% dengan master ISACA COBIT 2019 reference release.
                </p>
            </div>
        </div>
    </div>

    <!-- QUICK ACCESS (3 CARDS) -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-base sm:text-lg font-bold text-on-surface">Quick Access</h2>
            <span class="text-xs text-outline">Navigasi Langsung Modul Inti</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
            <!-- Quick Card 1: Framework -->
            <a 
                href="{{ route('cobit.framework') }}" 
                class="group bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/20 shadow-xs hover:border-primary/40 hover:shadow-sm transition-all flex flex-col justify-between"
            >
                <div>
                    <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-[20px]">menu_book</span>
                    </div>
                    <h3 class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors mb-1">
                        COBIT Framework
                    </h3>
                    <p class="text-xs text-outline leading-relaxed">
                        Telusuri 40 governance &amp; management objectives beserta 231 practices dan 1.202 activities.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-outline-variant/10 flex items-center justify-between text-xs font-semibold text-primary">
                    <span>Buka Framework</span>
                    <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </div>
            </a>

            <!-- Quick Card 2: RACI Matrix -->
            <a 
                href="{{ route('raci.matrix') }}" 
                class="group bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/20 shadow-xs hover:border-primary/40 hover:shadow-sm transition-all flex flex-col justify-between"
            >
                <div>
                    <div class="w-9 h-9 rounded-lg bg-indigo-500/10 text-indigo-700 flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-[20px]">table_chart</span>
                    </div>
                    <h3 class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors mb-1">
                        RACI Matrix
                    </h3>
                    <p class="text-xs text-outline leading-relaxed">
                        Tinjau 1.614 penetapan peran RACI (33 peran) untuk seluruh aktivitas praktik tata kelola.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-outline-variant/10 flex items-center justify-between text-xs font-semibold text-primary">
                    <span>Lihat Matriks RACI</span>
                    <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </div>
            </a>

            <!-- Quick Card 3: Spesifikasi Standar -->
            <button 
                type="button"
                wire:click="openSpecModal"
                class="group bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/20 shadow-xs hover:border-primary/40 hover:shadow-sm transition-all flex flex-col justify-between text-left cursor-pointer"
            >
                <div>
                    <div class="w-9 h-9 rounded-lg bg-emerald-500/10 text-emerald-700 flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-[20px]">verified</span>
                    </div>
                    <h3 class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors mb-1">
                        Spesifikasi Standar ISACA
                    </h3>
                    <p class="text-xs text-outline leading-relaxed">
                        Parameter baseline rilis ISACA COBIT 2019, domain hierarki, dan model kapabilitas proses.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-outline-variant/10 flex items-center justify-between text-xs font-semibold text-primary">
                    <span>Buka Spesifikasi</span>
                    <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </div>
            </button>
        </div>
    </div>

    <!-- SPECIFICATION SHEET MODAL -->
    @if($showSpecModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
            <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-xl max-w-xl w-full p-5 sm:p-6 shadow-xl space-y-4">
                <div class="flex items-start justify-between border-b border-outline-variant/15 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">verified</span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-on-surface">Spesifikasi Standar COBIT 2019</h3>
                            <p class="text-xs text-outline">ISACA Governance &amp; Management Framework Reference</p>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        wire:click="closeSpecModal"
                        class="p-1 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container transition-colors cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <div class="space-y-3 text-xs text-on-surface/90">
                    <div class="p-3 rounded-lg bg-surface-container-low/70 border border-outline-variant/20 space-y-1.5">
                        <div class="font-bold text-on-surface text-sm">COBIT 2019 Framework: Governance &amp; Management Objectives</div>
                        <p class="text-outline text-[11px] leading-relaxed">
                            COBIT 2019 mendefinisikan 40 sasaran tata kelola dan manajemen yang terbagi dalam 5 domain operasional.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="p-2.5 rounded-lg bg-surface-container-low/50 border border-outline-variant/15">
                            <span class="text-outline text-[11px] block">Rilis Standar</span>
                            <span class="font-bold text-on-surface">COBIT 2019 R2 (ISACA)</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-surface-container-low/50 border border-outline-variant/15">
                            <span class="text-outline text-[11px] block">Total Objectives</span>
                            <span class="font-bold text-on-surface">40 Objectives (5 EDM, 35 Mgt)</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-surface-container-low/50 border border-outline-variant/15">
                            <span class="text-outline text-[11px] block">Total Practices</span>
                            <span class="font-bold text-on-surface">231 Control Practices</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-surface-container-low/50 border border-outline-variant/15">
                            <span class="text-outline text-[11px] block">Total Activities</span>
                            <span class="font-bold text-on-surface">1,202 Action Items</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-surface-container-low/50 border border-outline-variant/15">
                            <span class="text-outline text-[11px] block">Peran RACI</span>
                            <span class="font-bold text-on-surface">33 Roles (ISACA Alignment)</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-surface-container-low/50 border border-outline-variant/15">
                            <span class="text-outline text-[11px] block">Tingkat Kapabilitas</span>
                            <span class="font-bold text-on-surface">Level 0 hingga Level 5</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-outline-variant/15 flex items-center justify-end">
                    <button 
                        type="button" 
                        wire:click="closeSpecModal"
                        class="px-4 py-2 text-xs font-semibold rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface transition-colors cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
