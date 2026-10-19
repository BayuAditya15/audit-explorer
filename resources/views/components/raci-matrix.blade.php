<?php

use App\Models\CobitPractice;
use App\Models\CobitRole;
use App\Models\CobitRaciAssignment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app', ['breadcrumb' => 'RACI Matrix'])] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedDomain = '';
    public string $filterRole = '';
    public string $filterLevel = '';
    public int $perPage = 10;

    public ?int $inspectPracticeId = null;
    public ?int $inspectRoleId = null;
    public ?int $highlightRoleId = null;

    public function mount(): void
    {
        if (request()->has('search')) {
            $this->search = (string) request()->query('search');
        }
        if (request()->has('domain')) {
            $this->selectedDomain = (string) request()->query('domain');
        }
        if (request()->has('filterRole')) {
            $this->filterRole = (string) request()->query('filterRole');
        }
        if (request()->has('filterLevel')) {
            $this->filterLevel = (string) request()->query('filterLevel');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedDomain(): void
    {
        $this->resetPage();
    }

    public function updatedFilterRole(): void
    {
        $this->resetPage();
    }

    public function updatedFilterLevel(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function setDomain(string $domainCode): void
    {
        $this->selectedDomain = $domainCode;
        $this->resetPage();
    }

    public function setFilterRole(string $roleId): void
    {
        $this->filterRole = $roleId;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->selectedDomain = '';
        $this->filterRole = '';
        $this->filterLevel = '';
        $this->highlightRoleId = null;
        $this->resetPage();
    }

    public function toggleHighlightRole(int $roleId): void
    {
        if ($this->highlightRoleId === $roleId) {
            $this->highlightRoleId = null;
        } else {
            $this->highlightRoleId = $roleId;
        }
    }

    public function inspectPractice(int $practiceId): void
    {
        $this->inspectPracticeId = $practiceId;
    }

    public function closePracticeModal(): void
    {
        $this->inspectPracticeId = null;
    }

    public function inspectRole(int $roleId): void
    {
        $this->inspectRoleId = $roleId;
    }

    public function closeRoleModal(): void
    {
        $this->inspectRoleId = null;
    }

    public function getDomainMeta(string $domainOrId): array
    {
        $code = strtoupper(substr($domainOrId, 0, 3));

        return match ($code) {
            'EDM' => [
                'code' => 'EDM',
                'name' => 'Evaluate, Direct and Monitor',
                'area' => 'Governance',
            ],
            'APO' => [
                'code' => 'APO',
                'name' => 'Align, Plan and Organize',
                'area' => 'Management',
            ],
            'BAI' => [
                'code' => 'BAI',
                'name' => 'Build, Acquire and Implement',
                'area' => 'Management',
            ],
            'DSS' => [
                'code' => 'DSS',
                'name' => 'Deliver, Service and Support',
                'area' => 'Management',
            ],
            'MEA' => [
                'code' => 'MEA',
                'name' => 'Monitor, Evaluate and Assess',
                'area' => 'Management',
            ],
            default => [
                'code' => $code,
                'name' => 'General Domain',
                'area' => 'COBIT',
            ],
        };
    }

    public function with(): array
    {
        // 1. Roles query (always ordered by sequence 1..33)
        $roles = CobitRole::query()
            ->orderBy('sequence')
            ->get();

        // 2. Practices query with eager loaded relationships (no N+1)
        $practiceQuery = CobitPractice::query()
            ->with(['objective', 'raciAssignments'])
            ->orderBy('practice_id');

        if ($this->selectedDomain !== '') {
            $practiceQuery->whereHas('objective', function ($q) {
                $q->where('objective_id', 'like', $this->selectedDomain . '%');
            });
        }

        if (trim($this->search) !== '') {
            $term = trim($this->search);
            $practiceQuery->where(function ($q) use ($term) {
                $q->where('practice_id', 'like', '%' . $term . '%')
                  ->orWhere('name', 'like', '%' . $term . '%')
                  ->orWhere('description', 'like', '%' . $term . '%')
                  ->orWhereHas('objective', function ($objQ) use ($term) {
                      $objQ->where('objective_id', 'like', '%' . $term . '%')
                           ->orWhere('name', 'like', '%' . $term . '%');
                  });
            });
        }

        if ($this->filterRole !== '') {
            $roleId = (int) $this->filterRole;
            $practiceQuery->whereHas('raciAssignments', function ($q) use ($roleId) {
                $q->where('cobit_role_id', $roleId);
                if ($this->filterLevel !== '') {
                    $q->where('raci_level', $this->filterLevel);
                }
            });
        } elseif ($this->filterLevel !== '') {
            $practiceQuery->whereHas('raciAssignments', function ($q) {
                $q->where('raci_level', $this->filterLevel);
            });
        }

        // Pagination: if perPage is 0 or 231, display all matching
        $limit = ($this->perPage >= 231 || $this->perPage <= 0) ? 231 : $this->perPage;
        $practices = $practiceQuery->paginate($limit);

        // Overall stats
        $totalRolesCount = CobitRole::count();
        $totalPracticesCount = CobitPractice::count();
        $totalAccountableCount = CobitRaciAssignment::where('raci_level', 'A')->count();
        $totalResponsibleCount = CobitRaciAssignment::where('raci_level', 'R')->count();

        // Domain distribution
        $domainCounts = [
            'EDM' => 16,
            'APO' => 83,
            'BAI' => 72,
            'DSS' => 38,
            'MEA' => 22,
        ];

        // Active inspection modal models
        $inspectedPractice = null;
        if ($this->inspectPracticeId) {
            $inspectedPractice = CobitPractice::with(['objective', 'raciAssignments.role'])->find($this->inspectPracticeId);
        }

        $inspectedRole = null;
        if ($this->inspectRoleId) {
            $inspectedRole = CobitRole::with(['raciAssignments.practice.objective'])->find($this->inspectRoleId);
        }

        return [
            'roles' => $roles,
            'practices' => $practices,
            'totalRolesCount' => $totalRolesCount,
            'totalPracticesCount' => $totalPracticesCount,
            'totalAccountableCount' => $totalAccountableCount,
            'totalResponsibleCount' => $totalResponsibleCount,
            'domainCounts' => $domainCounts,
            'inspectedPractice' => $inspectedPractice,
            'inspectedRole' => $inspectedRole,
        ];
    }
};
?>

<div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6 space-y-5 max-w-[1600px] mx-auto">
    <!-- 1. PAGE HEADER (MATCHING FRAMEWORK EXPLORER) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col min-w-0">
            <div class="flex items-center gap-1.5 text-xs text-outline mb-1 font-medium">
                <span>Governance &amp; Management</span>
                <span class="text-outline-variant">•</span>
                <span class="text-primary font-semibold">COBIT 2019 RACI Matrix</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-on-surface">
                Matriks RACI Tata Kelola &amp; Manajemen
            </h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Pemetaan akuntabilitas (Accountable) dan tanggung jawab eksekusi (Responsible) antara 231 praktik COBIT 2019 dan 33 peran organisasi.
            </p>
        </div>
    </div>

    <!-- 2. METRICS SUMMARY (MATCHING FRAMEWORK EXPLORER EXACT STYLING & SIZING) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Metric 1: Roles -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col min-w-0">
                <span class="text-[11px] sm:text-xs uppercase tracking-wider text-outline font-semibold">Peran Organisasi</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-xl sm:text-2xl font-bold text-on-surface tabular-nums">{{ number_format($totalRolesCount) }}</span>
                    <span class="text-xs font-medium text-on-surface-variant">Roles</span>
                </div>
                <span class="text-[11px] text-outline mt-0.5">Kolom G – AM (Sequence 1–33)</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-primary-fixed/50 flex items-center justify-center text-primary flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">badge</span>
            </div>
        </div>

        <!-- Metric 2: Practices -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col min-w-0">
                <span class="text-[11px] sm:text-xs uppercase tracking-wider text-outline font-semibold">Praktik Tata Kelola</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-xl sm:text-2xl font-bold text-on-surface tabular-nums">{{ number_format($totalPracticesCount) }}</span>
                    <span class="text-xs font-medium text-on-surface-variant">Practices</span>
                </div>
                <span class="text-[11px] text-outline mt-0.5">231 Praktik Tata Kelola &amp; Operasional</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-secondary-fixed/50 flex items-center justify-center text-secondary flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">hub</span>
            </div>
        </div>

        <!-- Metric 3: Accountable (A) -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col min-w-0">
                <span class="text-[11px] sm:text-xs uppercase tracking-wider text-outline font-semibold">Accountable (A)</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-xl sm:text-2xl font-bold text-primary tabular-nums">{{ number_format($totalAccountableCount) }}</span>
                    <span class="text-xs font-medium text-on-surface-variant">Penugasan</span>
                </div>
                <span class="text-[11px] text-outline mt-0.5">Tepat 1 Peran per Praktik (100%)</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-primary text-on-primary flex items-center justify-center font-bold text-sm flex-shrink-0 shadow-xs">
                A
            </div>
        </div>

        <!-- Metric 4: Responsible (R) -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col min-w-0">
                <span class="text-[11px] sm:text-xs uppercase tracking-wider text-outline font-semibold">Responsible (R)</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-xl sm:text-2xl font-bold text-on-surface tabular-nums">{{ number_format($totalResponsibleCount) }}</span>
                    <span class="text-xs font-medium text-on-surface-variant">Penugasan</span>
                </div>
                <span class="text-[11px] text-outline mt-0.5">Rata-rata 5,99 Peran per Praktik</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-surface-container-high text-on-surface border border-outline-variant/30 flex items-center justify-center font-bold text-sm flex-shrink-0">
                R
            </div>
        </div>
    </div>

    <!-- 3. CONTROLS & FILTER TOOLBAR (CLEAN, NO EMOJIS, UNIFORM STYLING) -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-4 shadow-xs space-y-3.5">
        <!-- Row 1: Search & Domain Filter Chips -->
        <div class="flex flex-col lg:flex-row lg:items-center gap-3">
            <!-- Search Input -->
            <div class="relative w-full lg:w-96 flex-shrink-0">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]">search</span>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Search code or practice name..." 
                    class="w-full h-9 pl-9 pr-8 rounded-lg bg-surface-container-low border border-outline-variant/40 text-xs text-on-surface placeholder:text-outline focus:outline-none focus:border-primary focus:bg-surface-container-lowest transition-colors"
                />
                @if($search !== '')
                    <button 
                        wire:click="$set('search', '')" 
                        type="button" 
                        class="absolute right-2.5 top-2 text-outline hover:text-on-surface p-0.5 rounded"
                        title="Clear search"
                    >
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>
                @endif
            </div>

            <!-- Domain Chips (Uniform with cobit-explorer.blade.php) -->
            <div class="flex items-center gap-1.5 overflow-x-auto custom-scrollbar pb-1 lg:pb-0 flex-nowrap min-w-0">
                <span class="text-[11px] font-semibold text-outline uppercase tracking-wider mr-1 flex-shrink-0">Domain:</span>
                <button 
                    wire:click="setDomain('')" 
                    type="button" 
                    class="px-2.5 py-1 rounded text-xs font-medium transition-all flex-shrink-0 {{ $selectedDomain === '' ? 'bg-primary text-on-primary font-semibold shadow-xs' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}"
                >
                    All (231)
                </button>

                @foreach(['EDM', 'APO', 'BAI', 'DSS', 'MEA'] as $dCode)
                    @php
                        $isSelected = $selectedDomain === $dCode;
                    @endphp
                    <button 
                        wire:click="setDomain('{{ $dCode }}')" 
                        type="button" 
                        class="px-2.5 py-1 rounded text-xs font-medium transition-all flex-shrink-0 {{ $isSelected ? 'bg-primary text-on-primary font-semibold shadow-xs ring-1 ring-primary' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}"
                    >
                        {{ $dCode }} ({{ $domainCounts[$dCode] ?? 0 }})
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Row 2: Secondary Dropdowns & RACI Legend -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-outline-variant/15 flex-wrap">
            <!-- Filter Dropdowns -->
            <div class="flex items-center gap-2 flex-wrap flex-1 min-w-[280px]">
                <!-- Role Selector Filter -->
                <div class="min-w-[190px] max-w-[280px] flex-1">
                    <select 
                        wire:model.live="filterRole" 
                        class="w-full h-9 px-3 rounded-lg bg-surface-container-low border border-outline-variant/40 text-xs text-on-surface focus:outline-none focus:border-primary focus:bg-surface-container-lowest transition-colors truncate"
                    >
                        <option value="">All 33 Organizational Roles</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}">
                                #{{ $r->sequence }} • {{ $r->short_name }} — {{ $r->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- RACI Level Selector -->
                <div class="w-40 flex-shrink-0">
                    <select 
                        wire:model.live="filterLevel" 
                        class="w-full h-9 px-3 rounded-lg bg-surface-container-low border border-outline-variant/40 text-xs text-on-surface focus:outline-none focus:border-primary focus:bg-surface-container-lowest transition-colors"
                    >
                        <option value="">All Levels (A &amp; R)</option>
                        <option value="A">Accountable Only (A)</option>
                        <option value="R">Responsible Only (R)</option>
                    </select>
                </div>

                <!-- Per Page Selector -->
                <div class="w-28 flex-shrink-0">
                    <select 
                        wire:model.live="perPage" 
                        class="w-full h-9 px-2.5 rounded-lg bg-surface-container-low border border-outline-variant/40 text-xs text-on-surface focus:outline-none focus:border-primary focus:bg-surface-container-lowest transition-colors"
                        title="Rows per page"
                    >
                        <option value="10">10 Rows</option>
                        <option value="20">20 Rows</option>
                        <option value="30">30 Rows</option>
                        <option value="50">50 Rows</option>
                        <option value="100">100 Rows</option>
                    </select>
                </div>
            </div>

            <!-- RACI Legend Bar -->
            <div class="flex items-center gap-3.5 text-xs text-on-surface-variant flex-wrap flex-shrink-0">
                <span class="text-[11px] font-semibold text-outline uppercase tracking-wider">Legend:</span>
                <div class="flex items-center gap-1.5">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded bg-primary text-on-primary font-bold text-[11px] shadow-xs">A</span>
                    <span class="text-xs font-medium text-on-surface">Accountable</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded bg-surface-container-high text-on-surface-variant font-semibold text-[11px] border border-outline-variant/40">R</span>
                    <span class="text-xs font-medium text-on-surface">Responsible</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-outline-variant font-bold text-xs">—</span>
                    <span class="text-xs text-outline">Unassigned</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. QUICK JUMP BAR & SCROLL HINT (CLEAN MATERIAL ICONS, NO EMOTICONS) -->
    <div 
        x-data="{
            scrollToCol(colSeq) {
                const el = document.getElementById('raci-col-' + colSeq);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
            }
        }"
        class="flex items-center justify-between gap-3 px-1 text-xs text-outline flex-wrap"
    >
        <!-- Jump to Role Group Buttons -->
        <div class="flex items-center gap-1.5 overflow-x-auto custom-scrollbar pb-0.5 flex-nowrap">
            <span class="text-[11px] uppercase font-semibold tracking-wider text-outline flex-shrink-0 mr-1">Jump to Column:</span>
            <button 
                @click="scrollToCol(1)" 
                type="button" 
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-medium bg-surface-container-low hover:bg-surface-container hover:text-on-surface text-on-surface-variant border border-outline-variant/30 transition-colors flex-shrink-0"
            >
                <span class="material-symbols-outlined text-[15px] text-outline">account_balance</span>
                <span>Governance (#1–#6)</span>
            </button>
            <button 
                @click="scrollToCol(7)" 
                type="button" 
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-medium bg-surface-container-low hover:bg-surface-container hover:text-on-surface text-on-surface-variant border border-outline-variant/30 transition-colors flex-shrink-0"
            >
                <span class="material-symbols-outlined text-[15px] text-outline">assignment</span>
                <span>PMO &amp; Risk (#7–#12)</span>
            </button>
            <button 
                @click="scrollToCol(13)" 
                type="button" 
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-medium bg-surface-container-low hover:bg-surface-container hover:text-on-surface text-on-surface-variant border border-outline-variant/30 transition-colors flex-shrink-0"
            >
                <span class="material-symbols-outlined text-[15px] text-outline">gavel</span>
                <span>Audit &amp; HR (#13–#15)</span>
            </button>
            <button 
                @click="scrollToCol(16)" 
                type="button" 
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-medium bg-surface-container-low hover:bg-surface-container hover:text-on-surface text-on-surface-variant border border-outline-variant/30 transition-colors flex-shrink-0"
            >
                <span class="material-symbols-outlined text-[15px] text-outline">dns</span>
                <span>CIO &amp; Tech (#16–#25)</span>
            </button>
            <button 
                @click="scrollToCol(26)" 
                type="button" 
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-medium bg-surface-container-low hover:bg-surface-container hover:text-on-surface text-on-surface-variant border border-outline-variant/30 transition-colors flex-shrink-0"
            >
                <span class="material-symbols-outlined text-[15px] text-outline">apartment</span>
                <span>Exec &amp; Projects (#26–#33)</span>
            </button>
        </div>

        <div class="hidden sm:flex items-center gap-1.5 text-xs text-outline flex-shrink-0 font-medium">
            <span class="material-symbols-outlined text-[16px]">swap_horiz</span>
            <span>Scroll horizontally to view 33 roles</span>
        </div>
    </div>

    <!-- 5. RACI MATRIX TABLE (NATURAL VERTICAL EXPANSION FOR ALL ROWS + STICKY COLUMNS) -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/25 shadow-xs overflow-hidden flex flex-col">
        <!-- Table Scroll Container: only horizontal scroll, no vertical scrollbar so all rows are fully visible -->
        <div class="overflow-x-auto custom-scrollbar relative">
            <table class="w-full border-separate border-spacing-0 text-left">
                <!-- THEAD -->
                <thead class="sticky top-0 z-30 bg-surface-container-high border-b border-outline-variant/30 text-on-surface shadow-xs">
                    <tr class="h-[76px]">
                        <!-- Top-Left Sticky 1: Practice ID Header (Frozen at left: 0px, z-index: 50) -->
                        <th 
                            scope="col" 
                            style="position: sticky; left: 0px; top: 0px; z-index: 50; min-width: 96px; width: 96px; max-width: 96px;"
                            class="bg-surface-container-high px-3.5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface border-r border-b border-outline-variant/30 h-[76px] align-middle"
                        >
                            Code
                        </th>

                        <!-- Top-Left Sticky 2: Practice Name Header (Frozen at left: 96px, z-index: 50, fixed 280px width) -->
                        <th 
                            scope="col" 
                            style="position: sticky; left: 96px; top: 0px; z-index: 50; min-width: 280px; width: 280px; max-width: 280px; box-shadow: 4px 0 8px -2px rgba(0,0,0,0.12);"
                            class="bg-surface-container-high px-4 py-3 text-xs font-bold uppercase tracking-wider text-on-surface border-r border-b border-outline-variant/30 h-[76px] align-middle"
                        >
                            COBIT Practice Name
                        </th>

                        <!-- Columns 1 to 33: Role Headers (80px wide with comfortable 76px height) -->
                        @foreach($roles as $role)
                            @php
                                $isHighlighted = $highlightRoleId === $role->id || ($filterRole !== '' && (int)$filterRole === $role->id);
                            @endphp
                            <th 
                                id="raci-col-{{ $role->sequence }}"
                                scope="col" 
                                wire:key="role-th-{{ $role->id }}"
                                style="min-width: 80px; width: 80px; max-width: 80px;"
                                class="px-2 py-2.5 text-center border-r border-b border-outline-variant/20 h-[76px] align-middle transition-colors cursor-pointer select-none {{ $isHighlighted ? 'bg-primary-container/20 ring-1 ring-primary/40' : 'hover:bg-surface-container-highest' }}"
                                wire:click="toggleHighlightRole({{ $role->id }})"
                                title="#{{ $role->sequence }} • {{ $role->name }} (Klik untuk sorot kolom)"
                            >
                                <div class="flex flex-col items-center justify-center gap-1.5 h-full">
                                    <span class="text-[10px] font-mono font-semibold px-1.5 py-0.5 rounded leading-none {{ $isHighlighted ? 'bg-primary text-on-primary font-bold' : 'bg-surface-container-highest text-outline' }}">
                                        #{{ $role->sequence }}
                                    </span>
                                    <span class="text-[11px] font-bold text-on-surface leading-snug text-center block break-words px-0.5">
                                        {{ $role->short_name }}
                                    </span>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <!-- TBODY -->
                <tbody class="divide-y divide-outline-variant/15 text-xs text-on-surface">
                    @forelse($practices as $practice)
                        @php
                            $domainMeta = $this->getDomainMeta($practice->practice_id);
                            $assignmentsMap = $practice->raciAssignments->keyBy('cobit_role_id');
                        @endphp
                        <tr 
                            wire:key="practice-row-{{ $practice->id }}" 
                            class="hover:bg-surface-container-low/70 transition-colors group"
                        >
                            <!-- Sticky Left Col 1: Practice ID (Frozen at left: 0px, z-index: 20) -->
                            <td 
                                style="position: sticky; left: 0px; z-index: 20; min-width: 96px; width: 96px; max-width: 96px;"
                                class="bg-surface-container-lowest group-hover:bg-surface-container-low px-3.5 py-3 border-r border-outline-variant/25 transition-colors align-middle"
                            >
                                <span class="font-mono text-xs font-bold text-on-surface">
                                    {{ $practice->practice_id }}
                                </span>
                            </td>

                            <!-- Sticky Left Col 2: Practice Name (Frozen at left: 96px, z-index: 20, fixed 280px, text wraps downwards naturally) -->
                            <td 
                                style="position: sticky; left: 96px; z-index: 20; min-width: 280px; width: 280px; max-width: 280px; box-shadow: 4px 0 8px -2px rgba(0,0,0,0.12);"
                                class="bg-surface-container-lowest group-hover:bg-surface-container-low px-4 py-3 border-r border-outline-variant/25 transition-colors align-middle"
                            >
                                <div class="flex items-start justify-between gap-2">
                                    <span 
                                        class="text-xs font-medium text-on-surface whitespace-normal break-words leading-snug cursor-pointer hover:text-primary transition-colors flex-1"
                                        wire:click="inspectPractice({{ $practice->id }})"
                                        title="{{ $practice->name }} — Klik untuk melihat deskripsi lengkap"
                                    >
                                        {{ $practice->name }}
                                    </span>
                                    <button 
                                        wire:click="inspectPractice({{ $practice->id }})"
                                        type="button" 
                                        class="text-outline hover:text-primary p-0.5 rounded opacity-40 group-hover:opacity-100 transition-opacity flex-shrink-0 mt-0.5"
                                        title="Detail {{ $practice->practice_id }}"
                                    >
                                        <span class="material-symbols-outlined text-[15px]">info</span>
                                    </button>
                                </div>
                            </td>

                            <!-- 33 RACI Cells for this Practice (80px wide with centered badges) -->
                            @foreach($roles as $role)
                                @php
                                    $assignment = $assignmentsMap->get($role->id);
                                    $level = $assignment?->raci_level;
                                    $isColHighlighted = $highlightRoleId === $role->id || ($filterRole !== '' && (int)$filterRole === $role->id);
                                @endphp
                                <td 
                                    wire:key="cell-{{ $practice->id }}-{{ $role->id }}" 
                                    class="px-2 py-3 text-center border-r border-outline-variant/15 transition-colors w-[80px] min-w-[80px] max-w-[80px] {{ $isColHighlighted ? 'bg-primary-container/10' : '' }}"
                                >
                                    @if($level === 'A')
                                        <span 
                                            class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-primary text-on-primary font-bold text-xs shadow-xs cursor-default"
                                            title="[A] ACCOUNTABLE&#10;Peran: {{ $role->name }}&#10;Praktik: {{ $practice->practice_id }} — {{ $practice->name }}"
                                        >
                                            A
                                        </span>
                                    @elseif($level === 'R')
                                        <span 
                                            class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-surface-container-high text-on-surface-variant font-semibold text-xs border border-outline-variant/30 cursor-default hover:bg-surface-container-highest transition-colors"
                                            title="[R] RESPONSIBLE&#10;Peran: {{ $role->name }}&#10;Praktik: {{ $practice->practice_id }} — {{ $practice->name }}"
                                        >
                                            R
                                        </span>
                                    @else
                                        <span class="text-outline-variant/35 text-xs select-none" title="Tidak ditugaskan">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 2 + count($roles) }}" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-outline text-[36px]">search_off</span>
                                    <p class="text-sm font-semibold text-on-surface">No practices match the filter criteria.</p>
                                    <p class="text-xs text-outline">Try resetting filters or changing your search keywords.</p>
                                    <button 
                                        wire:click="clearFilters" 
                                        type="button" 
                                        class="mt-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-primary text-on-primary hover:bg-primary-container transition-colors"
                                    >
                                        Reset All Filters
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Custom Clean Pagination (Matching Domain Explorer) -->
        @if($practices->hasPages())
            <div class="px-4 py-3 bg-surface-container-lowest border-t border-outline-variant/20 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-outline font-medium">
                <span class="text-[11px] sm:text-xs">
                    {{ $practices->firstItem() ?? 0 }}–{{ $practices->lastItem() ?? 0 }} of {{ $practices->total() }} COBIT practices
                    @if($selectedDomain !== '')
                        in domain <span class="font-semibold text-primary font-mono">{{ $selectedDomain }}</span>
                    @endif
                </span>

                <div class="flex items-center gap-1">
                    <!-- Prev Page Button -->
                    <button 
                        wire:click="previousPage" 
                        @disabled($practices->onFirstPage())
                        type="button" 
                        class="p-1 rounded bg-surface-container-low hover:bg-surface-container text-on-surface disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                        title="Previous page"
                    >
                        <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                    </button>

                    <!-- Numbered Buttons -->
                    @foreach(range(1, $practices->lastPage()) as $pageNum)
                        @if(
                            $pageNum == 1 || 
                            $pageNum == $practices->lastPage() || 
                            abs($pageNum - $practices->currentPage()) <= 1
                        )
                            <button 
                                wire:click="gotoPage({{ $pageNum }})" 
                                type="button" 
                                class="w-6 h-6 rounded text-[11px] font-semibold transition-colors flex items-center justify-center {{ $pageNum === $practices->currentPage() ? 'bg-primary text-on-primary' : 'bg-surface-container-low text-on-surface hover:bg-surface-container' }}"
                            >
                                {{ $pageNum }}
                            </button>
                        @elseif(
                            abs($pageNum - $practices->currentPage()) == 2
                        )
                            <span class="px-0.5 text-outline text-[10px]">…</span>
                        @endif
                    @endforeach

                    <!-- Next Page Button -->
                    <button 
                        wire:click="nextPage" 
                        @disabled(! $practices->hasMorePages())
                        type="button" 
                        class="p-1 rounded bg-surface-container-low hover:bg-surface-container text-on-surface disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                        title="Next page"
                    >
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </button>
                </div>
            </div>
        @else
            <div class="px-4 py-3 bg-surface-container-lowest border-t border-outline-variant/20 flex items-center justify-between text-xs text-outline font-medium">
                <span class="text-[11px] sm:text-xs">
                    {{ $practices->firstItem() ?? 0 }}–{{ $practices->lastItem() ?? 0 }} of {{ $practices->total() }} COBIT practices
                    @if($selectedDomain !== '')
                        in domain <span class="font-semibold text-primary font-mono">{{ $selectedDomain }}</span>
                    @endif
                </span>
            </div>
        @endif
    </div>

    <!-- 6. PRACTICE INSPECTION MODAL -->
    @if($inspectedPractice)
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
            wire:click.self="closePracticeModal"
        >
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[85vh] animate-in fade-in duration-200">
                <!-- Modal Header -->
                <div class="px-5 py-4 border-b border-outline-variant/20 flex items-start justify-between gap-4 bg-surface-container-low/50">
                    <div class="flex flex-col min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-primary text-on-primary">
                                {{ $inspectedPractice->practice_id }}
                            </span>
                            <span class="text-xs font-semibold text-outline truncate">
                                {{ $inspectedPractice->objective->objective_id }} — {{ $inspectedPractice->objective->name }}
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-on-surface mt-1.5 leading-snug">
                            {{ $inspectedPractice->name }}
                        </h3>
                    </div>

                    <button 
                        wire:click="closePracticeModal" 
                        type="button" 
                        class="text-outline hover:text-on-surface p-1 rounded-lg flex-shrink-0"
                    >
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-5 overflow-y-auto custom-scrollbar space-y-4 text-xs text-on-surface leading-relaxed">
                    <!-- Description -->
                    <div>
                        <h4 class="text-[11px] uppercase tracking-wider font-semibold text-outline mb-1">
                            Deskripsi Praktik
                        </h4>
                        <p class="text-xs text-on-surface-variant bg-surface-container-low/60 p-3 rounded-lg border border-outline-variant/20 leading-relaxed">
                            {{ $inspectedPractice->description }}
                        </p>
                    </div>

                    <!-- Accountable Role (Single Point of Accountability) -->
                    @php
                        $accountableRole = $inspectedPractice->raciAssignments->firstWhere('raci_level', 'A')?->role;
                    @endphp
                    <div>
                        <h4 class="text-[11px] uppercase tracking-wider font-semibold text-outline mb-1.5">
                            Penanggung Jawab Akhir (Accountable)
                        </h4>
                        @if($accountableRole)
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-primary-container/10 border border-primary/20">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary text-on-primary font-bold text-sm shadow-xs flex-shrink-0">
                                    A
                                </span>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-bold text-xs text-on-surface truncate">
                                        {{ $accountableRole->name }}
                                    </span>
                                    <span class="text-[11px] text-outline">
                                        Singkatan: <strong class="text-primary">{{ $accountableRole->short_name }}</strong> • Urutan Kolom: #{{ $accountableRole->sequence }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-error font-medium">Tidak ada penugasan Accountable terdaftar.</p>
                        @endif
                    </div>

                    <!-- Responsible Roles (Operational Execution) -->
                    @php
                        $responsibleRoles = $inspectedPractice->raciAssignments->where('raci_level', 'R')->pluck('role');
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <h4 class="text-[11px] uppercase tracking-wider font-semibold text-outline">
                                Pelaksana Bertanggung Jawab (Responsible)
                            </h4>
                            <span class="text-[11px] font-semibold text-primary">
                                {{ $responsibleRoles->count() }} Peran
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto custom-scrollbar pr-1">
                            @foreach($responsibleRoles as $rRole)
                                <div class="flex items-center gap-2 p-2 rounded-lg bg-surface-container-low border border-outline-variant/20">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-surface-container-high text-on-surface-variant font-bold text-xs border border-outline-variant/30 flex-shrink-0">
                                        R
                                    </span>
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-xs font-semibold text-on-surface truncate">
                                            {{ $rRole->name }}
                                        </span>
                                        <span class="text-[10px] text-outline truncate">
                                            {{ $rRole->short_name }} (#{{ $rRole->sequence }})
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-5 py-3 border-t border-outline-variant/20 bg-surface-container-low/40 flex justify-end">
                    <button 
                        wire:click="closePracticeModal" 
                        type="button" 
                        class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-surface-container-high text-on-surface hover:bg-surface-container-highest transition-colors"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 7. ROLE INSPECTION MODAL -->
    @if($inspectedRole)
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
            wire:click.self="closeRoleModal"
        >
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[85vh] animate-in fade-in duration-200">
                <!-- Modal Header -->
                <div class="px-5 py-4 border-b border-outline-variant/20 flex items-start justify-between gap-4 bg-surface-container-low/50">
                    <div class="flex flex-col min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-primary text-on-primary">
                                Kolom #{{ $inspectedRole->sequence }}
                            </span>
                            <span class="text-xs font-mono text-outline truncate">
                                {{ $inspectedRole->code }}
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-on-surface mt-1.5 leading-snug">
                            {{ $inspectedRole->name }} ({{ $inspectedRole->short_name }})
                        </h3>
                    </div>

                    <button 
                        wire:click="closeRoleModal" 
                        type="button" 
                        class="text-outline hover:text-on-surface p-1 rounded-lg flex-shrink-0"
                    >
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-5 overflow-y-auto custom-scrollbar space-y-4 text-xs text-on-surface leading-relaxed">
                    @php
                        $roleAssignments = $inspectedRole->raciAssignments;
                        $roleAccountable = $roleAssignments->where('raci_level', 'A');
                        $roleResponsible = $roleAssignments->where('raci_level', 'R');
                    @endphp

                    <!-- Stats summary -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 rounded-lg bg-primary-container/10 border border-primary/20 flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary text-on-primary font-bold text-sm shadow-xs flex-shrink-0">
                                A
                            </span>
                            <div>
                                <span class="text-[11px] text-outline font-semibold uppercase">Accountable</span>
                                <p class="text-lg font-bold text-primary">{{ $roleAccountable->count() }} Praktik</p>
                            </div>
                        </div>

                        <div class="p-3 rounded-lg bg-surface-container-low border border-outline-variant/30 flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-surface-container-high text-on-surface font-bold text-sm border border-outline-variant/40 flex-shrink-0">
                                R
                            </span>
                            <div>
                                <span class="text-[11px] text-outline font-semibold uppercase">Responsible</span>
                                <p class="text-lg font-bold text-on-surface">{{ $roleResponsible->count() }} Praktik</p>
                            </div>
                        </div>
                    </div>

                    <!-- List of Accountable Practices -->
                    @if($roleAccountable->isNotEmpty())
                        <div>
                            <h4 class="text-[11px] uppercase tracking-wider font-semibold text-outline mb-1.5">
                                Praktik Dimana Peran Ini Accountable (Pengambil Keputusan Utama)
                            </h4>
                            <div class="space-y-1.5 max-h-44 overflow-y-auto custom-scrollbar pr-1">
                                @foreach($roleAccountable as $a)
                                    <div class="p-2 rounded bg-primary-container/5 border border-primary/15 flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="font-mono text-xs font-bold text-primary flex-shrink-0">
                                                {{ $a->practice->practice_id }}
                                            </span>
                                            <span class="text-xs font-medium text-on-surface truncate">
                                                {{ $a->practice->name }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] text-outline font-mono flex-shrink-0">
                                            {{ $a->practice->objective->objective_id }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- List of Responsible Practices -->
                    @if($roleResponsible->isNotEmpty())
                        <div>
                            <h4 class="text-[11px] uppercase tracking-wider font-semibold text-outline mb-1.5">
                                Praktik Dimana Peran Ini Responsible (Pelaksana)
                            </h4>
                            <div class="space-y-1.5 max-h-44 overflow-y-auto custom-scrollbar pr-1">
                                @foreach($roleResponsible as $r)
                                    <div class="p-2 rounded bg-surface-container-low border border-outline-variant/20 flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="font-mono text-xs font-bold text-on-surface flex-shrink-0">
                                                {{ $r->practice->practice_id }}
                                            </span>
                                            <span class="text-xs font-medium text-on-surface truncate">
                                                {{ $r->practice->name }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] text-outline font-mono flex-shrink-0">
                                            {{ $r->practice->objective->objective_id }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="px-5 py-3 border-t border-outline-variant/20 bg-surface-container-low/40 flex justify-between items-center gap-2">
                    <button 
                        wire:click="setFilterRole('{{ $inspectedRole->id }}'); closeRoleModal()" 
                        type="button" 
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-primary text-on-primary hover:bg-primary-container transition-colors shadow-xs truncate"
                    >
                        Filter Praktik Peran Ini
                    </button>
                    <button 
                        wire:click="closeRoleModal" 
                        type="button" 
                        class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-surface-container-high text-on-surface hover:bg-surface-container-highest transition-colors flex-shrink-0"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
