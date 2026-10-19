<?php

use App\Models\CobitObjective;
use App\Models\CobitPractice;
use App\Models\CobitActivity;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app', ['breadcrumb' => 'COBIT Framework'])] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedDomain = '';
    public ?string $selectedObjectiveId = null;
    public bool $expandAllPractices = false;

    public function mount(): void
    {
        $domain = request()->query('domain');
        $objective = request()->query('objective');

        if ($domain && in_array(strtoupper($domain), ['EDM', 'APO', 'BAI', 'DSS', 'MEA'], true)) {
            $this->selectedDomain = strtoupper($domain);
        }

        if ($objective && CobitObjective::where('objective_id', $objective)->exists()) {
            $this->selectedObjectiveId = $objective;
        } else {
            $this->selectedObjectiveId = null;
        }
    }

    public function closeDetail(): void
    {
        $this->selectedObjectiveId = null;
    }

    public function toggleExpandAll(): void
    {
        $this->expandAllPractices = ! $this->expandAllPractices;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function setDomain(string $domainCode): void
    {
        $this->selectedDomain = $domainCode;
        $this->selectedObjectiveId = null;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->selectedDomain = '';
        $this->selectedObjectiveId = null;
        $this->resetPage();
    }

    public function selectObjective(string $objectiveId): void
    {
        $exists = CobitObjective::query()
            ->where('objective_id', $objectiveId)
            ->exists();

        if ($exists) {
            $this->selectedObjectiveId = $objectiveId;
        }
    }

    public function getDomainMeta(string $domainOrId): array
    {
        $code = strtoupper(substr($domainOrId, 0, 3));

        return match ($code) {
            'EDM' => [
                'code' => 'EDM',
                'name' => 'Evaluate, Direct and Monitor',
                'area' => 'Governance',
                'pill' => 'bg-purple-100 text-purple-800',
            ],
            'APO' => [
                'code' => 'APO',
                'name' => 'Align, Plan and Organize',
                'area' => 'Management',
                'pill' => 'bg-indigo-100 text-indigo-800',
            ],
            'BAI' => [
                'code' => 'BAI',
                'name' => 'Build, Acquire and Implement',
                'area' => 'Management',
                'pill' => 'bg-blue-100 text-blue-800',
            ],
            'DSS' => [
                'code' => 'DSS',
                'name' => 'Deliver, Service and Support',
                'area' => 'Management',
                'pill' => 'bg-emerald-100 text-emerald-800',
            ],
            'MEA' => [
                'code' => 'MEA',
                'name' => 'Monitor, Evaluate and Assess',
                'area' => 'Management',
                'pill' => 'bg-amber-100 text-amber-800',
            ],
            default => [
                'code' => $code,
                'name' => $domainOrId,
                'area' => 'COBIT',
                'pill' => 'bg-slate-200 text-slate-800',
            ],
        };
    }

    public function render()
    {
        $totalObjectives = CobitObjective::count();
        $totalPractices = CobitPractice::count();
        $totalActivities = CobitActivity::count();

        $domainCounts = [
            'EDM' => CobitObjective::where('objective_id', 'like', 'EDM%')->count(),
            'APO' => CobitObjective::where('objective_id', 'like', 'APO%')->count(),
            'BAI' => CobitObjective::where('objective_id', 'like', 'BAI%')->count(),
            'DSS' => CobitObjective::where('objective_id', 'like', 'DSS%')->count(),
            'MEA' => CobitObjective::where('objective_id', 'like', 'MEA%')->count(),
        ];

        $objectives = CobitObjective::query()
            ->withCount(['practices', 'activities'])
            ->when($this->selectedDomain !== '', function ($query) {
                $query->where('objective_id', 'like', $this->selectedDomain . '%');
            })
            ->when(trim($this->search) !== '', function ($query) {
                $search = '%' . trim($this->search) . '%';
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('objective_id', 'like', $search)
                        ->orWhere('name', 'like', $search)
                        ->orWhere('description', 'like', $search)
                        ->orWhereHas('practices', function ($practiceQuery) use ($search) {
                            $practiceQuery->where('practice_id', 'like', $search)
                                ->orWhere('name', 'like', $search)
                                ->orWhere('description', 'like', $search);
                        })
                        ->orWhereHas('activities', function ($activityQuery) use ($search) {
                            $activityQuery->where('cobit_activities.description', 'like', $search);
                        });
                });
            })
            ->orderBy('objective_id')
            ->paginate(8);

        $selectedObjective = $this->selectedObjectiveId
            ? CobitObjective::query()
                ->withCount(['practices', 'activities'])
                ->with([
                    'practices' => fn ($query) => $query->orderBy('practice_id'),
                    'practices.activities' => fn ($query) => $query->orderBy('sequence'),
                ])
                ->where('objective_id', $this->selectedObjectiveId)
                ->first()
            : null;

        return view('components.cobit-explorer', [
            'objectives' => $objectives,
            'selectedObjective' => $selectedObjective,
            'expandAllPractices' => $this->expandAllPractices,
            'totalObjectives' => $totalObjectives,
            'totalPractices' => $totalPractices,
            'totalActivities' => $totalActivities,
            'domainCounts' => $domainCounts,
        ]);
    }
};
?>

<div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6 space-y-5 max-w-[1600px] mx-auto">
    <!-- 1. PAGE HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col min-w-0">
            <div class="flex items-center gap-1.5 text-xs text-outline mb-1 font-medium">
                <span>Governance &amp; Management</span>
                <span class="text-outline-variant">•</span>
                <span class="text-primary font-semibold">COBIT 2019 Core</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-on-surface">
                COBIT Framework Explorer
            </h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Explore 40 governance and management objectives, 231 practices, and 1,202 operational activities per ISACA standard.
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap self-start md:self-auto">
            <button 
                onclick="window.print()" 
                type="button" 
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-lowest hover:bg-surface-container-low border border-outline-variant/40 text-xs font-semibold text-on-surface transition-colors shadow-xs ml-auto"
            >
                <span class="material-symbols-outlined text-[16px] text-outline">print</span>
                Print / Export
            </button>
        </div>
    </div>

    <!-- 2. METRICS SUMMARY -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <!-- Metric 1: Objectives -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col min-w-0">
                <span class="text-[11px] sm:text-xs uppercase tracking-wider text-outline font-semibold">Governance &amp; Management</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-xl sm:text-2xl font-bold text-on-surface tabular-nums">{{ number_format($totalObjectives) }}</span>
                    <span class="text-xs font-medium text-on-surface-variant">Objectives</span>
                </div>
                <span class="text-[11px] text-outline mt-0.5">5 Governance (EDM) • 35 Management</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-primary-fixed/50 flex items-center justify-center text-primary flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">policy</span>
            </div>
        </div>

        <!-- Metric 2: Practices -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col min-w-0">
                <span class="text-[11px] sm:text-xs uppercase tracking-wider text-outline font-semibold">Structure &amp; Alignment</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-xl sm:text-2xl font-bold text-on-surface tabular-nums">{{ number_format($totalPractices) }}</span>
                    <span class="text-xs font-medium text-on-surface-variant">Practices</span>
                </div>
                <span class="text-[11px] text-outline mt-0.5">Governance &amp; Operational Practices</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-secondary-fixed/50 flex items-center justify-center text-secondary flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">hub</span>
            </div>
        </div>

        <!-- Metric 3: Activities -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col min-w-0">
                <span class="text-[11px] sm:text-xs uppercase tracking-wider text-outline font-semibold">Implementation Guidance</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-xl sm:text-2xl font-bold text-on-surface tabular-nums">{{ number_format($totalActivities) }}</span>
                    <span class="text-xs font-medium text-on-surface-variant">Activities</span>
                </div>
                <span class="text-[11px] text-outline mt-0.5">Detailed Implementation &amp; Audit Guidance</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-tertiary-fixed/50 flex items-center justify-center text-tertiary flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">checklist</span>
            </div>
        </div>
    </div>

    <!-- 3. MAIN DUAL-PANEL LAYOUT (ALWAYS SIDE-BY-SIDE ON DESKTOP) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
        <!-- ============================================================== -->
        <!-- LEFT / MAIN PANEL: OBJECTIVE LIST (ALWAYS ON THE SIDE)         -->
        <!-- ============================================================== -->
        <section class="lg:col-span-5 bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-4 sm:p-5 shadow-xs flex flex-col lg:h-[calc(100vh-250px)] lg:min-h-[660px]">
            <!-- Top Controls (Sticky in left panel) -->
            <div class="flex-shrink-0 space-y-3 pb-3">
                <!-- Search Bar -->
                <div class="relative w-full">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline text-[18px]">search</span>
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        type="text" 
                        placeholder="Search code or objective name..." 
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

                <!-- Domain Filter Chips -->
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-[11px] text-outline font-semibold uppercase tracking-wider">Framework Domain</span>
                        @if($selectedDomain !== '')
                            <button 
                                wire:click="setDomain('')" 
                                type="button" 
                                class="text-[11px] text-primary hover:underline font-medium cursor-pointer"
                            >
                                Reset Domain
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-1.5 flex-wrap">
                        <button 
                            wire:click="setDomain('')" 
                            type="button" 
                            class="px-2.5 py-1 rounded text-xs font-medium transition-all cursor-pointer {{ $selectedDomain === '' ? 'bg-primary text-on-primary font-semibold shadow-xs' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}"
                        >
                            All ({{ $totalObjectives }})
                        </button>

                        @foreach(['EDM', 'APO', 'BAI', 'DSS', 'MEA'] as $dCode)
                            @php
                                $isSelected = $selectedDomain === $dCode;
                            @endphp
                            <button 
                                wire:click="setDomain('{{ $dCode }}')" 
                                type="button" 
                                class="px-2.5 py-1 rounded text-xs font-medium transition-all cursor-pointer {{ $isSelected ? 'bg-primary text-on-primary font-semibold shadow-xs ring-1 ring-primary' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}"
                            >
                                {{ $dCode }} ({{ $domainCounts[$dCode] ?? 0 }})
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Objectives List (Always vertical scrollable list on the side) -->
            <div class="flex-1 overflow-y-auto custom-scrollbar p-1.5 pr-3 space-y-2.5">
                @forelse($objectives as $obj)
                    @php
                        $isSelected = $selectedObjectiveId === $obj->objective_id;
                        $domainMeta = $this->getDomainMeta($obj->objective_id);
                    @endphp

                    <div 
                        wire:key="obj-card-{{ $obj->id }}"
                        wire:click="selectObjective('{{ $obj->objective_id }}')" 
                        class="p-3.5 rounded-lg border transition-all cursor-pointer flex flex-col gap-1.5 relative {{ $isSelected ? 'bg-primary-fixed/20 border-primary ring-1 ring-inset ring-primary shadow-xs' : 'bg-surface-container-lowest border-outline-variant/30 hover:bg-surface-container-low/60 hover:border-outline-variant/60' }}"
                    >
                        <!-- Top Code & Domain -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold px-2 py-0.5 rounded {{ $isSelected ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface' }}">
                                    {{ $obj->objective_id }}
                                </span>
                                <span class="text-[11px] font-semibold {{ $isSelected ? 'text-primary' : 'text-outline' }}">
                                    {{ $domainMeta['code'] }} • {{ $obj->area }}
                                </span>
                            </div>
                            @if($isSelected)
                                <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span>
                            @else
                                <span class="material-symbols-outlined text-outline-variant text-[18px]">chevron_right</span>
                            @endif
                        </div>

                        <!-- Objective Name -->
                        <h3 class="text-xs sm:text-sm font-semibold text-on-surface leading-snug">
                            {{ $obj->name }}
                        </h3>

                        <!-- Description Snippet -->
                        <p class="text-xs text-on-surface-variant line-clamp-2 leading-relaxed">
                            {{ $obj->description }}
                        </p>

                        <!-- Counts footer -->
                        <div class="flex items-center gap-3 mt-1 text-[11px] text-outline font-medium">
                            <span class="inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">hub</span>
                                {{ $obj->practices_count }} Practices
                            </span>
                            <span>•</span>
                            <span class="inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">format_list_bulleted</span>
                                {{ $obj->activities_count }} Activities
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center bg-surface-container-low rounded-lg border border-dashed border-outline-variant/40 flex flex-col items-center justify-center my-4">
                        <span class="material-symbols-outlined text-outline text-[32px] mb-2">search_off</span>
                        <p class="text-xs sm:text-sm font-semibold text-on-surface">No objectives found</p>
                        <p class="text-xs text-outline mt-1">Try another search keyword or clear the domain filter.</p>
                        <button 
                            wire:click="clearFilters" 
                            type="button" 
                            class="mt-3 px-3 py-1.5 rounded-lg bg-surface-container-lowest border border-outline-variant/50 text-xs font-medium text-primary hover:bg-surface-container"
                        >
                            Reset Filters &amp; Search
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Custom Clean Pagination Footer -->
            @if($objectives->hasPages())
                <div class="flex-shrink-0 pt-3 border-t border-outline-variant/20 flex items-center justify-between gap-2 text-xs text-outline font-medium">
                    <span class="text-[11px] sm:text-xs">
                        {{ $objectives->firstItem() ?? 0 }}–{{ $objectives->lastItem() ?? 0 }} of {{ $objectives->total() }}
                    </span>

                    <div class="flex items-center gap-1">
                        <!-- Prev Page Button -->
                        <button 
                            wire:click="previousPage" 
                            @disabled($objectives->onFirstPage())
                            type="button" 
                            class="p-1 rounded bg-surface-container-low hover:bg-surface-container text-on-surface disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                            title="Previous page"
                        >
                            <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                        </button>

                        <!-- Numbered Buttons -->
                        @foreach(range(1, $objectives->lastPage()) as $pageNum)
                            @if(
                                $pageNum == 1 || 
                                $pageNum == $objectives->lastPage() || 
                                abs($pageNum - $objectives->currentPage()) <= 1
                            )
                                <button 
                                    wire:click="gotoPage({{ $pageNum }})" 
                                    type="button" 
                                    class="w-6 h-6 rounded text-[11px] font-semibold transition-colors flex items-center justify-center {{ $pageNum === $objectives->currentPage() ? 'bg-primary text-on-primary' : 'bg-surface-container-low text-on-surface hover:bg-surface-container' }}"
                                >
                                    {{ $pageNum }}
                                </button>
                            @elseif(
                                abs($pageNum - $objectives->currentPage()) == 2
                            )
                                <span class="px-0.5 text-outline text-[10px]">…</span>
                            @endif
                        @endforeach

                        <!-- Next Page Button -->
                        <button 
                            wire:click="nextPage" 
                            @disabled(! $objectives->hasMorePages())
                            type="button" 
                            class="p-1 rounded bg-surface-container-low hover:bg-surface-container text-on-surface disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                            title="Next page"
                        >
                            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </button>
                    </div>
                </div>
            @endif
        </section>

        <!-- ============================================================== -->
        <!-- RIGHT PANEL: OBJECTIVE DETAILS / PLACEHOLDER (ALWAYS ON SIDE)   -->
        <!-- ============================================================== -->
        <section 
            id="cobit-detail-panel" 
            x-data 
            x-init="$watch('$wire.selectedObjectiveId', () => $el.scrollTo({ top: 0, behavior: 'smooth' }))"
            class="lg:col-span-7 bg-surface-container-lowest rounded-xl border border-outline-variant/25 p-5 sm:p-6 shadow-xs flex flex-col lg:h-[calc(100vh-250px)] lg:min-h-[660px] overflow-y-auto custom-scrollbar"
        >
            @if($selectedObjective)
                @php
                    $selDomainMeta = $this->getDomainMeta($selectedObjective->objective_id);
                @endphp
                <!-- 1. Header Meta Bar -->
                <div class="flex flex-col gap-3 pb-5 border-b border-outline-variant/20 flex-shrink-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-mono text-xs font-bold px-3 py-1 rounded {{ $selDomainMeta['pill'] }}">
                                {{ $selectedObjective->objective_id }}
                            </span>
                            <span class="text-xs font-semibold tracking-wider uppercase text-outline">
                                {{ $selectedObjective->area }} Domain — {{ $selectedObjective->domain }}
                            </span>
                        </div>
                        <a 
                            href="{{ route('raci.matrix') }}?search={{ $selectedObjective->objective_id }}" 
                            target="_blank"
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-surface-container-low hover:bg-surface-container text-xs font-medium text-primary border border-outline-variant/30 transition-colors self-start sm:self-auto"
                            title="View RACI Matrix for {{ $selectedObjective->objective_id }}"
                        >
                            <span class="material-symbols-outlined text-[15px]">grid_view</span>
                            <span>RACI Matrix</span>
                            <span class="material-symbols-outlined text-[13px] text-outline">open_in_new</span>
                        </a>
                    </div>

                    <!-- Title -->
                    <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-on-surface leading-snug">
                        {{ $selectedObjective->name }}
                    </h2>

                    <!-- Description -->
                    <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                        {{ $selectedObjective->description }}
                    </p>

                    <!-- Purpose Statement -->
                    @if($selectedObjective->purpose_statement)
                        <div class="bg-primary-fixed/20 border border-primary-fixed rounded-lg p-3.5 flex items-start gap-3 mt-1">
                            <span class="material-symbols-outlined text-primary text-[22px] mt-0.5 flex-shrink-0">lightbulb</span>
                            <div class="flex flex-col gap-1 min-w-0">
                                <span class="text-xs font-bold uppercase tracking-wider text-primary">Purpose Statement:</span>
                                <p class="text-xs sm:text-sm text-on-surface leading-relaxed">
                                    {{ $selectedObjective->purpose_statement }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Compact Metadata Strip -->
                    <div class="flex items-center gap-3 flex-wrap text-xs text-outline py-2 px-3 bg-surface-container-low rounded-lg mt-1 font-medium">
                        <span class="text-on-surface font-semibold">{{ $selectedObjective->practices_count }} Practices</span>
                        <span class="text-outline-variant">•</span>
                        <span class="text-on-surface font-semibold">{{ $selectedObjective->activities_count }} Activities</span>
                        <span class="text-outline-variant">•</span>
                        <span>Focus: <strong class="text-on-surface font-semibold">{{ $selectedObjective->area }}</strong></span>
                        <span class="text-outline-variant">•</span>
                        <span>Standard: <strong class="text-on-surface font-semibold">COBIT 2019 Core</strong></span>
                        @if($selectedObjective->new_in_cobit_2019)
                            <span class="text-outline-variant">•</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                New in COBIT 2019
                            </span>
                        @endif
                    </div>
                </div>

                <!-- 2. Practices & Activities Accordion -->
                <div class="flex flex-col gap-4 pt-4">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <div>
                            <h3 class="text-base font-bold text-on-surface">
                                Practices &amp; Activities ({{ $selectedObjective->practices->count() }} Practices)
                            </h3>
                            <p class="text-xs text-on-surface-variant mt-0.5">
                                Click on any practice to view detailed operational activity guidance.
                            </p>
                        </div>
                        <button 
                            wire:click="toggleExpandAll"
                            type="button" 
                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-surface-container-low hover:bg-surface-container border border-outline-variant/40 text-xs font-semibold text-on-surface transition-colors cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[16px] text-outline">
                                {{ $expandAllPractices ? 'unfold_less' : 'unfold_more' }}
                            </span>
                            <span>{{ $expandAllPractices ? 'Collapse All' : 'Expand All' }}</span>
                        </button>
                    </div>

                    <div 
                        wire:key="practices-list-{{ $selectedObjective->objective_id }}"
                        x-data="{
                            activePractice: null,
                            togglePractice(id) {
                                if ($wire.expandAllPractices) {
                                    $wire.toggleExpandAll();
                                }
                                const isOpening = (this.activePractice !== id);
                                this.activePractice = isOpening ? id : null;

                                if (isOpening) {
                                    this.$nextTick(() => {
                                        requestAnimationFrame(() => {
                                            const card = document.getElementById('practice-card-' + id);
                                            const container = document.getElementById('cobit-detail-panel');
                                            if (card && container) {
                                                const cardRect = card.getBoundingClientRect();
                                                const containerRect = container.getBoundingClientRect();
                                                const offset = cardRect.top - containerRect.top;
                                                container.scrollBy({
                                                    top: offset - 14,
                                                    behavior: 'smooth'
                                                });
                                            }
                                        });
                                    });
                                }
                            },
                            isOpen(id) {
                                return $wire.expandAllPractices || this.activePractice === id;
                            }
                        }"
                        class="flex flex-col gap-3"
                    >
                        @foreach($selectedObjective->practices as $practice)
                            <div 
                                id="practice-card-{{ $practice->id }}"
                                class="rounded-lg border border-outline-variant/30 overflow-hidden bg-surface-container-lowest shadow-xs flex flex-col transition-all scroll-mt-3"
                            >
                                <!-- Practice Header / Trigger -->
                                <div 
                                    @click="togglePractice({{ $practice->id }})" 
                                    class="w-full px-4 py-3 bg-surface-container-low hover:bg-surface-container/80 flex items-center justify-between text-left transition-colors gap-3 cursor-pointer select-none"
                                >
                                    <div class="flex items-center gap-2.5 min-w-0 flex-1 text-left">
                                        <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-primary text-on-primary flex-shrink-0">
                                            {{ $practice->practice_id }}
                                        </span>
                                        <span class="text-xs sm:text-sm font-semibold text-on-surface truncate">
                                            {{ $practice->name }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span class="text-[11px] font-medium px-2 py-0.5 rounded bg-surface-container-high text-on-surface-variant">
                                            {{ $practice->activities->count() }} Activities
                                        </span>
                                        <div class="p-0.5 text-outline">
                                            <span 
                                                class="material-symbols-outlined text-[20px] transition-transform duration-200 block"
                                                :class="isOpen({{ $practice->id }}) ? 'rotate-180 text-primary' : ''"
                                            >
                                                expand_more
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Practice Body & Activities List -->
                                <div 
                                    x-show="isOpen({{ $practice->id }})" 
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="p-4 bg-surface-container-low/30 border-t border-outline-variant/20 flex flex-col gap-3"
                                >
                                    @if($practice->description)
                                        <p class="text-xs text-on-surface-variant leading-relaxed italic bg-surface-container-lowest p-2.5 rounded border border-outline-variant/20">
                                            {{ $practice->description }}
                                        </p>
                                    @endif

                                    <!-- Activities Sub-list -->
                                    <div class="flex flex-col gap-2">
                                        @forelse($practice->activities as $activity)
                                             <div class="p-2.5 bg-surface-container-lowest rounded-lg border border-outline-variant/20 shadow-2xs flex items-start gap-2.5">
                                                <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-secondary-fixed text-on-secondary-fixed mt-0.5 flex-shrink-0">
                                                    {{ $activity->sequence }}
                                                </span>
                                                <p class="text-xs text-on-surface leading-relaxed flex-1">
                                                    {{ $activity->description }}
                                                </p>
                                            </div>
                                        @empty
                                            <p class="text-xs text-outline italic">No activities recorded for this practice.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- EMPTY / UNSELECTED STATE (Side placeholder) -->
                <div class="h-full flex-1 flex flex-col items-center justify-center text-center p-6 sm:p-10 my-auto">
                    <div class="w-16 h-16 rounded-2xl bg-surface-container-low flex items-center justify-center text-outline mb-4 border border-outline-variant/30">
                        <span class="material-symbols-outlined text-[36px] text-primary/70">touch_app</span>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-on-surface">Select a COBIT 2019 Objective</h3>
                    <p class="text-xs sm:text-sm text-on-surface-variant max-w-md mt-1.5 leading-relaxed">
                        Select an Objective from the list on the left to view governance details, practices, and ISACA standard operational activities.
                    </p>
                    <div class="mt-6 flex items-center gap-2 text-xs text-outline font-medium flex-wrap justify-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-low border border-outline-variant/30">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            5 Governance (EDM)
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-low border border-outline-variant/30">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            35 Management (APO, BAI, DSS, MEA)
                        </span>
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
