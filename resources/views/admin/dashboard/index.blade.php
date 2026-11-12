@extends('layouts.app')

@section('title', 'Dashboard Analytics - Adiprima Survey Center')

@section('styles')
<style>
    .dashboard-container {
        width: 100%;
        max-width: 1300px;
        margin: 0 auto;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .category-nav-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .category-nav-card {
        background: #FFFFFF;
        border: 2px solid #E2E8F0;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        position: relative;
        overflow: hidden;
    }

    .category-nav-card:hover {
        border-color: #3B82F6;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -4px rgba(59, 130, 246, 0.12);
    }

    .category-nav-card.active {
        border-color: var(--color-navy-primary);
        background: linear-gradient(145deg, #FFFFFF, #EFF6FF);
        box-shadow: 0 8px 20px -4px rgba(12, 43, 100, 0.15);
    }

    .category-nav-card.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: var(--color-navy-primary);
    }

    .cat-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .category-nav-card.active .cat-icon-box {
        background: var(--color-navy-primary);
        color: #FFFFFF;
    }

    .category-nav-card:not(.active) .cat-icon-box {
        background: #F1F5F9;
        color: #475569;
    }

    .cat-title-text {
        font-weight: 800;
        font-size: 0.95rem;
        color: #0F172A;
        line-height: 1.3;
    }

    .category-nav-card.active .cat-title-text {
        color: var(--color-navy-primary);
    }

    .cat-badge-info {
        font-size: 0.75rem;
        color: #64748B;
        margin-top: 0.2rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .survey-selector-card {
        background: linear-gradient(135deg, #0C2B64 0%, #1E40AF 100%);
        color: #FFFFFF;
        border-radius: 16px;
        padding: 1.5rem 1.75rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
        box-shadow: 0 10px 25px -5px rgba(12, 43, 100, 0.25);
    }

    .nav-tabs-wrapper {
        display: flex;
        gap: 0.5rem;
        border-bottom: 2px solid #E2E8F0;
        margin-bottom: 1.75rem;
        overflow-x: auto;
        padding-bottom: 2px;
        scrollbar-width: thin;
        -webkit-overflow-scrolling: touch;
    }

    .nav-tabs-wrapper::-webkit-scrollbar {
        height: 4px;
    }

    .tab-item {
        padding: 0.75rem 1.25rem;
        font-weight: 700;
        font-size: 0.9rem;
        color: #64748B;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -4px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        flex-shrink: 0;
        border-radius: 6px 6px 0 0;
    }

    .tab-item:hover {
        color: var(--color-navy-primary);
        background: #F8FAFC;
    }

    .tab-item.active {
        color: var(--color-navy-primary);
        border-bottom-color: var(--color-navy-primary);
        background: #EFF6FF;
    }

    .card-panel {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        margin-bottom: 2rem;
        min-width: 0;
        max-width: 100%;
    }

    .table-container {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        -webkit-overflow-scrolling: touch;
        background: #FFFFFF;
    }

    .raw-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
        text-align: left;
    }

    .raw-table th {
        background: #F8FAFC;
        color: #334155;
        font-weight: 700;
        padding: 0.85rem 0.75rem;
        border-bottom: 2px solid #E2E8F0;
        white-space: nowrap;
    }

    .raw-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #F1F5F9;
        color: #1E293B;
        white-space: nowrap;
    }

    .raw-table tr:hover {
        background: #F8FAFC;
    }

    .progress-bar-bg {
        width: 100%;
        height: 10px;
        background: #E2E8F0;
        border-radius: 9999px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #10B981, #059669);
        border-radius: 9999px;
    }

    .analytics-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1.35fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
        width: 100%;
        max-width: 100%;
    }

    .analytics-grid > * {
        min-width: 0;
        max-width: 100%;
    }

    .chart-box {
        position: relative;
        width: 100%;
        max-width: 100%;
        min-width: 0;
        overflow: hidden;
    }

    .chart-box canvas {
        width: 100% !important;
        max-width: 100% !important;
    }

    .ipa-chart-box {
        height: 340px;
    }

    .radar-chart-box {
        height: 300px;
        margin-bottom: 1.5rem;
    }

    .bar-chart-box {
        height: 240px;
    }

    .ipa-legend-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.5rem;
        margin-top: 0.75rem;
        font-size: 0.725rem;
    }

    .stats-stat-row {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .stat-badge-item {
        border-left: 2px solid rgba(255,255,255,0.2);
        padding-left: 1.25rem;
    }

    @media (max-width: 992px) {
        .analytics-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 768px) {
        .category-nav-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .dashboard-header > div:last-child {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
            gap: 0.5rem;
        }

        .dashboard-header > div:last-child a {
            justify-content: center;
        }

        .survey-selector-card {
            padding: 1.25rem;
            flex-direction: column;
            align-items: stretch;
            gap: 1.25rem;
        }

        .stats-stat-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            width: 100%;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 1rem;
        }

        .stat-badge-item {
            border-left: none;
            padding-left: 0;
            text-align: center;
        }

        .card-panel {
            padding: 1.25rem 1rem;
            border-radius: 14px;
        }
    }

    @media (max-width: 640px) {
        .card-panel {
            padding: 1rem;
            border-radius: 14px;
        }

        .ipa-chart-box {
            height: 260px;
        }

        .radar-chart-box {
            height: 240px;
        }

        .bar-chart-box {
            height: 220px;
        }

        .ipa-legend-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 480px) {
        .stats-stat-row {
            grid-template-columns: 1fr 1fr;
        }
        .stats-stat-row > div:last-child {
            grid-column: 1 / -1;
        }
        .tab-item {
            padding: 0.65rem 0.95rem;
            font-size: 0.825rem;
        }
    }

    .feedback-q-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .feedback-q-header {
        background: #F8FAFC;
        border-bottom: 1px solid #E2E8F0;
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .feedback-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .feedback-table th {
        background: #F1F5F9;
        color: #334155;
        font-weight: 700;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #E2E8F0;
        font-size: 0.8rem;
    }

    .feedback-table td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: top;
    }

    .badge-count-total {
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #BFDBFE;
        font-weight: 800;
        font-size: 0.825rem;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        white-space: nowrap;
    }

    .badge-count-total.high-freq {
        background: #FEF3C7;
        color: #92400E;
        border-color: #FDE68A;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    
    <!-- Top Header -->
    <div class="dashboard-header">
        <div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--color-navy-primary); letter-spacing: -0.02em;">
                Dashboard Analytics & Monitoring
            </h1>
            <p style="color: #64748B; font-size: 0.875rem; margin-top: 0.25rem;">
                Analisa data kuesioner, pantau partisipasi responden, dan eksplorasi insight per dimensi.
            </p>
        </div>
    </div>

    <!-- 1. Kategori Survey Selector Nav -->
    <div style="margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 0.8rem; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">
            <i class="bi bi-grid-fill" style="color: var(--color-navy-primary);"></i> Pilih Kategori Survei:
        </span>
        <span style="font-size: 0.75rem; color: #94A3B8;">
            Klik kategori untuk beralih topik kuesioner
        </span>
    </div>

    <div class="category-nav-grid">
        @foreach($allCategories as $catName)
            @php
                $catSurveys = $surveysByCategory->get($catName, collect());
                $catCount = $catSurveys->count();
                $isActiveCat = ($selectedCategory === $catName);
                
                $catIcon = match(trim($catName)) {
                    'Survey Budaya Kerja', 'Budaya Kerja' => 'bi-people-fill',
                    'Employee Engagement Survey', 'Engagement Survey' => 'bi-graph-up-arrow',
                    'Customer Satisfaction Survey', 'Kepuasan Pelanggan' => 'bi-award-fill',
                    'Internal Satisfaction Survey', 'Kepuasan Internal' => 'bi-chat-left-quote-fill',
                    default => 'bi-clipboard-check-fill'
                };
            @endphp
            <a href="{{ route('admin.dashboard', ['category' => $catName, 'tab' => $tab]) }}" 
               class="category-nav-card {{ $isActiveCat ? 'active' : '' }}"
               title="Pilih kategori {{ $catName }}">
                <div class="cat-icon-box">
                    <i class="bi {{ $catIcon }}"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div class="cat-title-text">{{ $catName }}</div>
                    <div class="cat-badge-info">
                        <span><i class="bi bi-collection"></i> {{ $catCount }} Edisi Survei</span>
                        @if($isActiveCat)
                            <span style="background: #DEF7EC; color: #03543F; font-size: 0.65rem; font-weight: 800; padding: 0.1rem 0.4rem; border-radius: 4px;">AKTIF</span>
                        @endif
                    </div>
                </div>
                @if($isActiveCat)
                    <div style="color: var(--color-navy-primary); font-size: 1.1rem;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                @endif
            </a>
        @endforeach
    </div>

    <!-- 2. Active Survey Selector Banner (With Category Dropdown for inner surveys) -->
    <div class="survey-selector-card">
        <div style="flex: 1; min-width: 280px;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.4rem; flex-wrap: wrap;">
                <span style="font-size: 0.725rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #93C5FD; background: rgba(255,255,255,0.12); padding: 0.2rem 0.55rem; border-radius: 6px;">
                    <i class="bi bi-tag-fill"></i> Kategori: {{ $selectedCategory }}
                </span>
                <span style="font-size: 0.75rem; color: #BFDBFE;">
                    &bull; Pilih Edisi Kuesioner:
                </span>
            </div>

            <form action="{{ route('admin.dashboard') }}" method="GET" id="surveySwitcherForm">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="hidden" name="category" value="{{ $selectedCategory }}">

                <div style="position: relative;">
                    <select name="survey_id" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.dispatchEvent(new Event('submit', {bubbles: true, cancelable: true}))" style="background: #FFFFFF; color: #0F172A; font-weight: 700; font-size: 0.975rem; padding: 0.75rem 1rem; border-radius: 10px; border: none; width: 100%; box-shadow: 0 4px 14px rgba(0,0,0,0.18); cursor: pointer; appearance: auto;">
                        @php
                            $currentCatSurveys = $surveysByCategory->get($selectedCategory, collect());
                        @endphp
                        
                        @if($currentCatSurveys->count() > 0)
                            <optgroup label="Daftar Survei dalam Kategori: {{ $selectedCategory }}">
                                @foreach($currentCatSurveys as $s)
                                    <option value="{{ $s->id }}" {{ $activeSurvey->id == $s->id ? 'selected' : '' }}>
                                        {{ $s->title }} {{ $s->is_active ? '● (Aktif)' : '○ (Nonaktif / Arsip)' }} &bull; {{ $s->responses_count }} Responden
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif

                        @if($allSurveys->count() > $currentCatSurveys->count())
                            <optgroup label="── Kategori Lainnya ──">
                                @foreach($allSurveys as $otherS)
                                    @if($otherS->category !== $selectedCategory)
                                        <option value="{{ $otherS->id }}">
                                            [{{ $otherS->category }}] {{ $otherS->title }}
                                        </option>
                                    @endif
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                </div>
            </form>
        </div>

        <div class="stats-stat-row">
            <div class="stat-badge-item">
                <div style="font-size: 0.7rem; color: #BFDBFE;">Masa Aktif:</div>
                <div style="font-size: 0.85rem; font-weight: 700;">
                    @if($activeSurvey->start_date && $activeSurvey->end_date)
                        {{ $activeSurvey->start_date->format('d M') }} - {{ $activeSurvey->end_date->format('d M Y') }}
                    @elseif($activeSurvey->start_date)
                        Mulai {{ $activeSurvey->start_date->format('d M Y') }}
                    @else
                        Terbuka
                    @endif
                </div>
            </div>

            <div class="stat-badge-item">
                <div style="font-size: 0.7rem; color: #BFDBFE;">Total Soal:</div>
                <div style="font-size: 1.1rem; font-weight: 800;">
                    {{ $questions->count() }} Butir
                </div>
            </div>

            <div class="stat-badge-item">
                <div style="font-size: 0.7rem; color: #BFDBFE;">Responden:</div>
                <div style="font-size: 1.1rem; font-weight: 800; color: #6EE7B7;">
                    {{ $totalFilled }} Orang
                </div>
            </div>
        </div>
    </div>

    <!-- Submenu Tabs -->
    <div class="nav-tabs-wrapper">
        <a href="{{ route('admin.dashboard', ['survey_id' => $activeSurvey->id, 'category' => $selectedCategory, 'tab' => 'raw_data']) }}" class="tab-item {{ $tab == 'raw_data' ? 'active' : '' }}">
            <i class="bi bi-table"></i> 1. Raw Data
        </a>
        <a href="{{ route('admin.dashboard', ['survey_id' => $activeSurvey->id, 'category' => $selectedCategory, 'tab' => 'rekap_perolehan']) }}" class="tab-item {{ $tab == 'rekap_perolehan' ? 'active' : '' }}">
            <i class="bi bi-bar-chart-steps"></i> 2. Monitoring
        </a>
        <a href="{{ route('admin.dashboard', ['survey_id' => $activeSurvey->id, 'category' => $selectedCategory, 'tab' => 'analisa']) }}" class="tab-item {{ $tab == 'analisa' ? 'active' : '' }}">
            <i class="bi bi-pie-chart-fill"></i> 3. Analisa Dimensi
        </a>
        <a href="{{ route('admin.dashboard', ['survey_id' => $activeSurvey->id, 'category' => $selectedCategory, 'tab' => 'descriptive_reasons']) }}" class="tab-item {{ $tab == 'descriptive_reasons' ? 'active' : '' }}">
            <i class="bi bi-chat-left-text-fill"></i> 4. Feedback Uraian
        </a>
    </div>

    <!-- SUBMENU 1: DATA SURVEI (RAW DATA) -->
    @if($tab == 'raw_data')
    <div class="card-panel">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin: 0;">
                    1. Data Survei Mentah (Raw Data) &ndash; {{ $activeSurvey->title }}
                </h3>
                <p style="color: #64748B; font-size: 0.825rem; margin: 0.25rem 0 0 0;">
                    Daftar seluruh respon butir pertanyaan beserta waktu pengerjaan kuesioner.
                </p>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('admin.surveys.questions', $activeSurvey->id) }}" style="background: #FFFFFF; border: 1px solid #CBD5E1; color: #334155; text-decoration: none; padding: 0.55rem 0.95rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.15s ease;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#FFFFFF'">
                    <i class="bi bi-ui-checks"></i> Soal
                </a>
                <a href="{{ route('admin.dashboard.export', ['survey_id' => $activeSurvey->id]) }}" style="background: #10B981; color: #FFFFFF; text-decoration: none; padding: 0.55rem 1.1rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.45rem; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25); transition: all 0.15s ease;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10B981'">
                    <i class="bi bi-file-earmark-excel-fill" style="font-size: 1rem;"></i> Unduh Excel (.xlsx)
                </a>
            </div>
        </div>
        
        <!-- Filter & Search Bar -->
        <form action="{{ route('admin.dashboard') }}" method="GET" style="display: flex; gap: 0.6rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
            <input type="hidden" name="survey_id" value="{{ $activeSurvey->id }}">
            <input type="hidden" name="category" value="{{ $selectedCategory }}">
            <input type="hidden" name="tab" value="raw_data">
            
            <div style="flex: 1; min-width: 200px; position: relative;">
                <i class="bi bi-search" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: #94A3B8;"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / NIK / departemen..." style="width: 100%; padding: 0.6rem 0.85rem 0.6rem 2.3rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem;">
            </div>

            <select name="department" style="padding: 0.6rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem; background: #FFF;">
                <option value="">Semua Departemen</option>
                @foreach($allDepartments as $dept)
                    <option value="{{ $dept }}" {{ $departmentFilter == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>

            <select name="position" style="padding: 0.6rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem; background: #FFF;">
                <option value="">Semua Jabatan</option>
                @foreach($allPositions as $pos)
                    <option value="{{ $pos }}" {{ $positionFilter == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                @endforeach
            </select>

            <button type="submit" style="background: var(--color-navy-primary); color: #FFF; border: none; padding: 0.6rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer;">
                Filter
            </button>

            @if($search || $departmentFilter || $positionFilter)
                <a href="{{ route('admin.dashboard', ['survey_id' => $activeSurvey->id, 'category' => $selectedCategory, 'tab' => 'raw_data']) }}" style="background: #F1F5F9; color: #64748B; text-decoration: none; padding: 0.6rem 0.85rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center;">
                    Reset
                </a>
            @endif
        </form>

        <!-- Datatable -->
        <div class="table-container">
            <div class="table-touch-hint">
                <i class="bi bi-arrows-expand"></i> Geser tabel ke samping untuk melihat detail responden & jawaban lengkap
            </div>
            <table class="raw-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>Usia</th>
                        <th>Pendidikan</th>
                        <th>Status</th>
                        <th>Tenure</th>
                        <th>Departemen</th>
                        <th>Jabatan</th>
                        <th style="min-width: 140px;">Mulai Dikerjakan</th>
                        <th style="min-width: 140px;">Selesai Dikerjakan</th>
                        <th style="min-width: 110px;">Lama Pengerjaan</th>
                        @foreach($questions as $q)
                            @if($q->question_type === 'dual_rating' || (empty($q->question_type) && $q->section === 'B'))
                                <th title="{{ $q->question_text }}">Q{{ $q->question_number }} (H)</th>
                                <th title="{{ $q->question_text }}">Q{{ $q->question_number }} (K)</th>
                            @else
                                <th title="{{ $q->question_text }}">Q{{ $q->question_number }}</th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($responses as $index => $resp)
                    @php 
                        $answersMap = $resp->answers->keyBy('question_id');
                        $startedAt = $resp->started_at ?? $resp->created_at;
                        $submittedAt = $resp->submitted_at ?? $resp->updated_at;
                        $durationStr = '-';
                        if ($startedAt && $submittedAt) {
                            $diffSec = $startedAt->diffInSeconds($submittedAt);
                            $minVal = round($diffSec / 60, 1);
                            $durationStr = ($minVal < 1 ? '< 1' : $minVal) . ' Menit';
                        }
                    @endphp
                    <tr>
                        <td>{{ $responses->firstItem() + $index }}</td>
                        <td>{{ $resp->nik ?? '-' }}</td>
                        <td style="font-weight: 700;">{{ $resp->name }}</td>
                        <td>{{ $resp->gender ? substr($resp->gender, 0, 1) : '-' }}</td>
                        <td>{{ $resp->age ?? '-' }}</td>
                        <td>{{ $resp->education ?? '-' }}</td>
                        <td>
                            {{ $resp->employment_status ?? '-' }}
                            @if($resp->outsourcing_company)
                                <span style="font-size: 0.75rem; font-weight: 700; color: #7C3AED; background: #F3E8FF; padding: 0.1rem 0.4rem; border-radius: 4px; margin-left: 0.25rem;">{{ $resp->outsourcing_company }}</span>
                            @endif
                        </td>
                        <td>{{ $resp->tenure ?? '-' }}</td>
                        <td><span style="font-weight: 600; background: #EFF6FF; color: #1D4ED8; padding: 0.15rem 0.4rem; border-radius: 4px;">{{ $resp->department ?? '-' }}</span></td>
                        <td>{{ $resp->position ?? '-' }}</td>
                        <td style="font-size: 0.775rem; color: #475569; white-space: nowrap;">
                            <i class="bi bi-clock-history" style="color: #3B82F6;"></i> {{ $startedAt ? $startedAt->format('d/m/Y ; H:i') : '-' }}
                        </td>
                        <td style="font-size: 0.775rem; color: #475569; white-space: nowrap;">
                            <i class="bi bi-check-circle" style="color: #10B981;"></i> {{ $submittedAt ? $submittedAt->format('d/m/Y ; H:i') : '-' }}
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            <span style="font-size: 0.75rem; font-weight: 700; color: #0369A1; background: #E0F2FE; padding: 0.2rem 0.5rem; border-radius: 6px; display: inline-block;">
                                {{ $durationStr }}
                            </span>
                        </td>

                        @foreach($questions as $q)
                            @php $ans = $answersMap->get($q->id); @endphp
                            @if($q->question_type === 'dual_rating' || (empty($q->question_type) && $q->section === 'B'))
                                <td style="text-align: center; color: #2563EB; font-weight: 700;">
                                    {{ $ans ? $ans->expectation_score : '-' }}
                                </td>
                                <td style="text-align: center; font-weight: 700; color: {{ ($ans && $ans->reality_score <= 2) ? '#DC2626' : '#16A34A' }}; background: {{ ($ans && $ans->reality_score <= 2) ? '#FEE2E2' : 'transparent' }};">
                                    {{ $ans ? $ans->reality_score : '-' }}
                                </td>
                            @elseif($q->question_type === 'multiple_choice')
                                <td style="text-align: center; font-size: 0.825rem; font-weight: 600; color: #1E293B;" title="{{ $ans ? $ans->text_answer : '' }}">
                                    {{ $ans ? ($ans->text_answer ?? '-') : '-' }}
                                </td>
                            @elseif($q->question_type === 'essay')
                                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;" title="{{ $ans ? $ans->text_answer : '' }}">
                                    {{ $ans ? Str::limit($ans->text_answer, 25) : '-' }}
                                </td>
                            @else
                                <td style="text-align: center; font-weight: 700;">
                                    {{ $ans ? ($ans->reality_score ?? $ans->text_answer ?? '-') : '-' }}
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 13 + $questions->count() }}" style="text-align: center; padding: 2rem; color: #64748B;">
                            Belum ada respon data survei yang masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.25rem; display: flex; justify-content: center; align-items: center;">
            {{ $responses->links() }}
        </div>
    </div>
    @endif

    <!-- SUBMENU 2: REKAP PEROLEHAN (MONITORING) -->
    @if($tab == 'rekap_perolehan')
    <div class="card-panel">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #0F172A; margin-bottom: 1.25rem;">
            2. Rekap Perolehan Survei (Department Monitoring)
        </h3>

        <!-- <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.25rem;">
                <div style="font-size: 0.8rem; font-weight: 600; color: #64748B;">Total Responden Mengisi</div>
                <div style="font-size: 1.85rem; font-weight: 800; color: var(--color-navy-primary); margin: 0.25rem 0;">
                    {{ $totalFilled }} <small style="font-size: 0.95rem; color: #64748B; font-weight: 500;">/ {{ $totalTarget }} Target</small>
                </div>
                <div class="progress-bar-bg" style="margin-top: 0.75rem;">
                    <div class="progress-bar-fill" style="width: {{ min(100, $overallProgressPct) }}%;"></div>
                </div>
                <div style="font-size: 0.8rem; font-weight: 700; color: #059669; margin-top: 0.4rem;">
                    {{ $overallProgressPct }}% Tercapai
                </div>
            </div>
        </div> -->

        <div class="table-container">
            <div class="table-touch-hint">
                <i class="bi bi-arrows-expand"></i> Geser tabel ke samping untuk melihat rekap target & progress per unit
            </div>
            <table class="raw-table">
                <thead>
                    <tr>
                        <th>Grup Unit / Departemen</th>
                        <!-- <th style="text-align: center;">Target</th> -->
                        <th style="text-align: center;">Masuk</th>
                        <!-- <th style="width: 35%;">Progress</th>
                        <th style="text-align: center;">Status</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekapData as $rk)
                    <tr>
                        <td style="font-weight: 700; color: #0F172A;">{{ $rk['group'] }}</td>
                        <!-- <td style="text-align: center;">{{ $rk['target'] }}</td> -->
                        <td style="text-align: center; font-weight: 700; color: var(--color-navy-primary);">{{ $rk['filled'] }}</td>
                        <!-- <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div class="progress-bar-bg" style="flex: 1;">
                                    <div class="progress-bar-fill" style="width: {{ min(100, $rk['progress']) }}%;"></div>
                                </div>
                                <span style="font-size: 0.8rem; font-weight: 700; width: 40px;">{{ $rk['progress'] }}%</span>
                            </div>
                        </td> -->
                        <!-- <td style="text-align: center;">
                            @if($rk['progress'] >= 100)
                                <span style="background: #DEF7EC; color: #03543F; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Tuntas</span>
                            @elseif($rk['progress'] >= 50)
                                <span style="background: #FEF08A; color: #854D0E; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Berjalan</span>
                            @else
                                <span style="background: #FEE2E2; color: #991B1B; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Rendah</span>
                            @endif
                        </td> -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- SUBMENU 3: ANALISA DIMENSI (GRAFIK) -->
    @if($tab == 'analisa')
    <div class="analytics-grid">
        <!-- Summary Dimension Cards -->
        <div style="min-width: 0; max-width: 100%;">
            <!-- KARTU 1: SKOR RATA-RATA SELURUH DIMENSI -->
            <div class="card-panel" style="margin-bottom: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <h3 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin: 0 0 0.25rem 0;">
                            Skor Rata-Rata Seluruh Dimensi
                        </h3>
                        <p style="font-size: 0.775rem; color: #64748B; margin: 0;">
                            *Perhitungan rata-rata murni dari data valid (skor &ge; 1), mengabaikan nilai 0 / pertanyaan tidak berlaku.
                        </p>
                    </div>
                    <span style="background: #F1F5F9; color: #475569; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 6px; border: 1px solid #E2E8F0;">
                        Skala 1 &ndash; {{ $surveyScaleMax ?? 4 }}
                    </span>
                </div>

                <!-- 2 Metric Cards: Harapan & Kenyataan -->
                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.85rem; margin-bottom: 1rem;">
                    <!-- Rata-rata Harapan -->
                    <div style="background: #F0FDF4; border: 1.5px solid #BBF7D0; border-radius: 12px; padding: 1rem; position: relative; min-width: 0;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem; flex-wrap: wrap; gap: 0.25rem;">
                            <span style="font-size: 0.78rem; font-weight: 700; color: #166534; text-transform: uppercase; letter-spacing: 0.02em;">
                                <i class="bi bi-star-fill" style="color: #10B981;"></i> Skor Harapan
                            </span>
                            <span style="font-size: 0.7rem; background: #DCFCE7; color: #15803D; font-weight: 700; padding: 0.15rem 0.45rem; border-radius: 4px;">Expectation</span>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 0.35rem; margin-bottom: 0.45rem; flex-wrap: wrap;">
                            <span style="font-size: 1.65rem; font-weight: 800; color: #14532D; line-height: 1;">
                                {{ number_format($overallExpScore ?? 0, 2) }}
                            </span>
                            <span style="font-size: 0.8rem; color: #166534; font-weight: 600;">/ {{ number_format($surveyScaleMax ?? 4, 2) }}</span>
                        </div>
                        <div class="progress-bar-bg" style="background: #DCFCE7; height: 6px;">
                            <div class="progress-bar-fill" style="background: #10B981; width: {{ ($surveyScaleMax ?? 4) > 0 ? min(100, (($overallExpScore ?? 0) / ($surveyScaleMax ?? 4)) * 100) : 0 }}%;"></div>
                        </div>
                    </div>

                    <!-- Rata-rata Kenyataan -->
                    <div style="background: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 12px; padding: 1rem; position: relative; min-width: 0;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem; flex-wrap: wrap; gap: 0.25rem;">
                            <span style="font-size: 0.78rem; font-weight: 700; color: #1E40AF; text-transform: uppercase; letter-spacing: 0.02em;">
                                <i class="bi bi-check2-circle" style="color: #2563EB;"></i> Skor Kenyataan
                            </span>
                            <span style="font-size: 0.7rem; background: #DBEAFE; color: #1D4ED8; font-weight: 700; padding: 0.15rem 0.45rem; border-radius: 4px;">Perception</span>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 0.35rem; margin-bottom: 0.45rem; flex-wrap: wrap;">
                            <span style="font-size: 1.65rem; font-weight: 800; color: #1E3A8A; line-height: 1;">
                                {{ number_format($overallRealScore ?? 0, 2) }}
                            </span>
                            <span style="font-size: 0.8rem; color: #1E40AF; font-weight: 600;">/ {{ number_format($surveyScaleMax ?? 4, 2) }}</span>
                        </div>
                        <div class="progress-bar-bg" style="background: #DBEAFE; height: 6px;">
                            <div class="progress-bar-fill" style="background: #2563EB; width: {{ ($surveyScaleMax ?? 4) > 0 ? min(100, (($overallRealScore ?? 0) / ($surveyScaleMax ?? 4)) * 100) : 0 }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Gap Bar Summary -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.4rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-arrows-expand" style="color: #64748B;"></i>
                        <span style="font-size: 0.825rem; font-weight: 700; color: #334155;">Rata-Rata Gap Seluruh Dimensi:</span>
                    </div>
                    <div>
                        <span style="font-size: 1rem; font-weight: 800; color: {{ ($overallGap ?? 0) < 0 ? '#DC2626' : '#2563EB' }};">
                            {{ (($overallGap ?? 0) > 0 ? '+' : '') . number_format($overallGap ?? 0, 2) }}
                        </span>
                        <small style="font-size: 0.75rem; color: #64748B; margin-left: 0.25rem;">(Kenyataan &minus; Harapan)</small>
                    </div>
                </div>

                <!-- Dimensi Tertinggi & Terendah -->
                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem;">
                    <div style="background: #DEF7EC; border: 1px solid #84E1BC; border-radius: 10px; padding: 0.75rem 0.85rem; min-width: 0;">
                        <div style="font-size: 0.685rem; font-weight: 700; color: #03543F; text-transform: uppercase;">Dimensi Tertinggi</div>
                        <div style="font-size: 0.95rem; font-weight: 800; color: #046C4E; margin: 0.15rem 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $highestDimension }}">{{ $highestDimension }}</div>
                        <div style="font-size: 0.775rem; color: #03543F;">Skor: <b>{{ number_format($highestScore, 2) }}</b></div>
                    </div>

                    <div style="background: #FEF2F2; border: 1px solid #FCA5A5; border-radius: 10px; padding: 0.75rem 0.85rem; min-width: 0;">
                        <div style="font-size: 0.685rem; font-weight: 700; color: #991B1B; text-transform: uppercase;">Dimensi Terendah</div>
                        <div style="font-size: 0.95rem; font-weight: 800; color: #B91C1C; margin: 0.15rem 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $lowestDimension }}">{{ $lowestDimension }}</div>
                        <div style="font-size: 0.775rem; color: #991B1B;">Skor: <b>{{ number_format($lowestScore, 2) }}</b></div>
                    </div>
                </div>
            </div>

            <!-- KARTU 2: TABEL RATA-RATA PER DIMENSI / DETAIL INDIKATOR -->
            <div class="card-panel" id="dimensionCardPanel">
                <!-- VIEW 1: DAFTAR DIMENSI -->
                <div id="dimensionSummaryView">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <h3 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 0.45rem;">
                                <i class="bi bi-table" style="color: var(--color-navy-primary);"></i> Tabel Rata-Rata per Dimensi
                            </h3>
                            <p style="font-size: 0.75rem; color: #64748B; margin: 0.2rem 0 0 0;">
                                Klik baris dimensi untuk melihat detail tiap indikatornya.
                            </p>
                        </div>
                        <span style="font-size: 0.75rem; color: #64748B; font-weight: 600; background: #F1F5F9; padding: 0.2rem 0.6rem; border-radius: 9999px;">
                            {{ count($surveyDimensions) }} Dimensi
                        </span>
                    </div>

                    <div style="overflow-x: auto; border: 1px solid #E2E8F0; border-radius: 10px;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.825rem; text-align: left;">
                            <thead>
                                <tr style="background: #F8FAFC; color: #475569; border-bottom: 1.5px solid #E2E8F0;">
                                    <th style="padding: 0.65rem 0.85rem; font-weight: 700;">Dimensi</th>
                                    <th style="padding: 0.65rem 0.85rem; font-weight: 700; text-align: center; color: #166534; white-space: nowrap;">Skor Harapan</th>
                                    <th style="padding: 0.65rem 0.85rem; font-weight: 700; text-align: center; color: #1E40AF; white-space: nowrap;">Skor Kenyataan</th>
                                    <th style="padding: 0.65rem 0.85rem; font-weight: 700; text-align: center; white-space: nowrap;">Gap</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surveyDimensions as $dim)
                                @php
                                    $exp = $dimensionExpectationScores[$dim] ?? 0;
                                    $real = $dimensionScores[$dim] ?? 0;
                                    $gap = $dimensionGaps[$dim] ?? 0;
                                    $indCount = count($dimensionDetails[$dim]['indicators'] ?? []);
                                @endphp
                                <tr class="dimension-clickable-row"
                                    data-dimension="{{ $dim }}"
                                    style="border-bottom: 1px solid #F1F5F9; cursor: pointer; transition: background 0.15s;"
                                    onmouseover="this.style.background='#EFF6FF'"
                                    onmouseout="this.style.background='transparent'"
                                    title="Klik untuk melihat detail indikator dimensi {{ $dim }}">
                                    <td style="padding: 0.65rem 0.85rem; font-weight: 700; color: #1E293B;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                                            <div>
                                                <span style="color: var(--color-navy-primary);">{{ $dim }}</span>
                                                <small style="display: block; font-size: 0.7rem; font-weight: 500; color: #64748B;">
                                                    {{ $indCount }} indikator
                                                </small>
                                            </div>
                                            <i class="bi bi-chevron-right" style="color: #94A3B8; font-size: 0.8rem;"></i>
                                        </div>
                                    </td>
                                    <td style="padding: 0.65rem 0.85rem; text-align: center;">
                                        <span style="display: inline-block; background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; font-weight: 700; padding: 0.15rem 0.55rem; border-radius: 6px; font-size: 0.8rem;">
                                            {{ $exp >= 1 ? number_format($exp, 2) : '-' }}
                                        </span>
                                    </td>
                                    <td style="padding: 0.65rem 0.85rem; text-align: center;">
                                        <span style="display: inline-block; background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; font-weight: 700; padding: 0.15rem 0.55rem; border-radius: 6px; font-size: 0.8rem;">
                                            {{ $real >= 1 ? number_format($real, 2) : '-' }}
                                        </span>
                                    </td>
                                    <td style="padding: 0.65rem 0.85rem; text-align: center;">
                                        @if($exp >= 1 && $real >= 1)
                                            <span style="display: inline-block; font-weight: 800; font-size: 0.8rem; padding: 0.15rem 0.55rem; border-radius: 6px; background: {{ $gap < 0 ? '#FEF2F2' : '#EFF6FF' }}; color: {{ $gap < 0 ? '#DC2626' : '#2563EB' }}; border: 1px solid {{ $gap < 0 ? '#FECACA' : '#BFDBFE' }};">
                                                {{ ($gap > 0 ? '+' : '') . number_format($gap, 2) }}
                                            </span>
                                        @else
                                            <span style="color: #94A3B8;">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background: #F8FAFC; border-top: 2px solid #CBD5E1; font-weight: 800;">
                                <tr>
                                    <td style="padding: 0.75rem 0.85rem; color: #0F172A; font-size: 0.825rem;">
                                        Rata-Rata Seluruh Dimensi
                                    </td>
                                    <td style="padding: 0.75rem 0.85rem; text-align: center; color: #166534; font-size: 0.85rem;">
                                        {{ ($overallExpScore ?? 0) >= 1 ? number_format($overallExpScore, 2) : '-' }}
                                    </td>
                                    <td style="padding: 0.75rem 0.85rem; text-align: center; color: #1E40AF; font-size: 0.85rem;">
                                        {{ ($overallRealScore ?? 0) >= 1 ? number_format($overallRealScore, 2) : '-' }}
                                    </td>
                                    <td style="padding: 0.75rem 0.85rem; text-align: center; font-size: 0.85rem; color: {{ ($overallGap ?? 0) < 0 ? '#DC2626' : '#2563EB' }}; font-weight: 800;">
                                        {{ (($overallGap ?? 0) > 0 ? '+' : '') . number_format($overallGap ?? 0, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- VIEW 2: DETAIL DIMENSI (INDIKATOR) -->
                <div id="dimensionDetailView" style="display: none;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.85rem; flex-wrap: wrap;">
                        <div>
                            <button type="button" id="btnBackToDimensions" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; padding: 0.35rem 0.7rem; border-radius: 8px; font-size: 0.775rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
                                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Dimensi
                            </button>
                            <h3 id="detailDimensionTitle" style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin: 0;">
                                Detail Dimensi
                            </h3>
                            <p id="detailDimensionMeta" style="font-size: 0.75rem; color: #64748B; margin: 0.2rem 0 0 0;"></p>
                        </div>
                        <span id="detailIndicatorCountBadge" style="font-size: 0.75rem; color: #1D4ED8; font-weight: 700; background: #EFF6FF; border: 1px solid #BFDBFE; padding: 0.25rem 0.65rem; border-radius: 9999px;">
                            0 Indikator
                        </span>
                    </div>

                    <div style="overflow-x: auto; border: 1px solid #E2E8F0; border-radius: 10px;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem; text-align: left;">
                            <thead>
                                <tr style="background: #F8FAFC; color: #475569; border-bottom: 1.5px solid #E2E8F0;">
                                    <th style="padding: 0.6rem 0.65rem; font-weight: 700; width: 48px;">No</th>
                                    <th style="padding: 0.6rem 0.65rem; font-weight: 700; min-width: 180px;">Indikator</th>
                                    <th style="padding: 0.6rem 0.65rem; font-weight: 700; text-align: center; color: #166534; white-space: nowrap;">Harapan</th>
                                    <th style="padding: 0.6rem 0.65rem; font-weight: 700; text-align: center; color: #1E40AF; white-space: nowrap;">Kenyataan</th>
                                    <th style="padding: 0.6rem 0.65rem; font-weight: 700; text-align: center; white-space: nowrap;">Gap</th>
                                    <th style="padding: 0.6rem 0.65rem; font-weight: 700; text-align: center; white-space: nowrap;">Kuadran IPA</th>
                                </tr>
                            </thead>
                            <tbody id="detailIndicatorsBody"></tbody>
                            <tfoot id="detailIndicatorsFoot" style="background: #F8FAFC; border-top: 2px solid #CBD5E1; font-weight: 800;"></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div style="display: flex; flex-direction: column; gap: 1.25rem; min-width: 0; max-width: 100%;">
            <!-- Matriks Kuadran IPA -->
            <div class="card-panel" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.85rem; flex-wrap: wrap;">
                    <div style="min-width: 0;">
                        <h3 id="ipaChartTitle" style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                            <i class="bi bi-grid-1x2-fill" style="color: var(--color-navy-primary);"></i> <span>Matriks Kuadran IPA (Importance Performance Analysis)</span>
                        </h3>
                        <p id="ipaChartSubtitle" style="font-size: 0.75rem; color: #64748B; margin: 0.2rem 0 0 0;">
                            Pemetaan Harapan (Expectation) vs Kenyataan (Performance) seluruh dimensi.
                        </p>
                    </div>
                    <button type="button" id="btnResetChartView" style="display: none; background: #EFF6FF; border: 1px solid #BFDBFE; color: #1D4ED8; padding: 0.3rem 0.65rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                        <i class="bi bi-arrow-counterclockwise"></i> Semua Dimensi
                    </button>
                </div>

                <div class="chart-box ipa-chart-box">
                    <canvas id="ipaQuadrantChart"></canvas>
                </div>

                <div id="ipaThresholdInfo" style="margin-top: 0.75rem; padding: 0.55rem 0.75rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 0.75rem; color: #475569; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;"></div>

                <div class="ipa-legend-grid">
                    <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 0.4rem 0.6rem; border-radius: 8px;">
                        <strong>Need Improvement (Kiri Atas):</strong> Harapan tinggi, kenyataan rendah &rarr; <em>Prioritas utama perbaikan</em>
                    </div>
                    <div style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 0.4rem 0.6rem; border-radius: 8px;">
                        <strong>Maintain Quality (Kanan Atas):</strong> Harapan tinggi, kenyataan tinggi &rarr; <em>Pertahankan kualitas</em>
                    </div>
                    <div style="background: #F8FAFC; border: 1px solid #CBD5E1; color: #475569; padding: 0.4rem 0.6rem; border-radius: 8px;">
                        <strong>Low Priority (Kiri Bawah):</strong> Harapan rendah, kenyataan rendah &rarr; <em>Prioritas rendah</em>
                    </div>
                    <div style="background: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF; padding: 0.4rem 0.6rem; border-radius: 8px;">
                        <strong>Possible Overkill (Kanan Bawah):</strong> Harapan rendah, kenyataan tinggi &rarr; <em>Berlebihan / realokasi</em>
                    </div>
                </div>
            </div>

            <!-- Visualisasi Radar & Bar Chart -->
            <div class="card-panel" style="margin-bottom: 0;">
                <h3 id="radarBarChartTitle" style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin-bottom: 1rem;">
                    Visualisasi Radar & Bar Chart
                </h3>
                
                <div class="chart-box radar-chart-box">
                    <canvas id="dimensionRadarChart"></canvas>
                </div>

                <div class="chart-box bar-chart-box">
                    <canvas id="dimensionBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- SUBMENU 4: FEEDBACK DESKRIPTIF & ALASAN -->
    @if($tab == 'descriptive_reasons')
    <div class="card-panel">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin: 0;">
                    4. Rekapitulasi Feedback & Uraian Per Butir Pertanyaan
                </h3>
                <p style="color: #64748B; font-size: 0.825rem; margin: 0.25rem 0 0 0;">
                    Daftar masukan, alasan skor rendah, dan uraian terbuka responden yang dikelompokkan per butir pertanyaan serta dihitung frekuensi kesamaan keyword/poin masukan.
                </p>
            </div>
            <div style="background: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF; padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="bi bi-chat-square-text-fill"></i> {{ count($feedbackByQuestion) }} Butir Pertanyaan Memiliki Feedback
            </div>
        </div>

        @forelse($feedbackByQuestion as $index => $fb)
        @php
            $q = $fb['question'];
            $indicator = $q->indicator_title ?? $q->dimensionName ?? ('Bagian ' . ($q->section ?? 'B'));
        @endphp
        <div class="feedback-q-card">
            <!-- Question Header Card -->
            <div class="feedback-q-header">
                <div style="flex: 1; min-width: 260px;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem; flex-wrap: wrap;">
                        <span style="background: var(--color-navy-primary); color: #FFFFFF; font-weight: 800; font-size: 0.75rem; padding: 0.2rem 0.55rem; border-radius: 6px;">
                            Q{{ $q->question_number }}
                        </span>
                        <span style="background: #F1F5F9; color: #475569; font-weight: 700; font-size: 0.75rem; padding: 0.2rem 0.55rem; border-radius: 6px; border: 1px solid #E2E8F0;">
                            {{ $indicator }}
                        </span>
                        <span style="font-size: 0.75rem; color: #64748B;">
                            Tipe: <b>{{ ucfirst(str_replace('_', ' ', $q->question_type ?? 'Rating')) }}</b>
                        </span>
                    </div>
                    <div style="font-size: 0.925rem; font-weight: 700; color: #1E293B; line-height: 1.4;">
                        {{ $q->question_text }}
                    </div>
                </div>

                <div style="text-align: right; display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0;">
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 0.45rem 0.75rem; border-radius: 8px; text-align: center;">
                        <span style="display: block; font-size: 0.675rem; color: #64748B; font-weight: 600; text-transform: uppercase;">Total Masukan</span>
                        <span style="font-size: 1.05rem; font-weight: 800; color: var(--color-navy-primary);">{{ $fb['total_feedback'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Top Keywords Summary Bar (if available) -->
            @if(!empty($fb['top_keywords']))
            <div style="background: #FDFEFE; border-bottom: 1px dashed #E2E8F0; padding: 0.6rem 1.25rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <span style="font-size: 0.75rem; font-weight: 700; color: #64748B; display: inline-flex; align-items: center; gap: 0.3rem;">
                    <i class="bi bi-tags-fill" style="color: #3B82F6;"></i> Keyword Terbanyak:
                </span>
                @foreach($fb['top_keywords'] as $kw => $kwCount)
                    <span style="font-size: 0.75rem; background: #EFF6FF; color: #1E40AF; border: 1px solid #DBEAFE; padding: 0.15rem 0.5rem; border-radius: 12px; font-weight: 600;">
                        {{ $kw }} <b style="color: #2563EB;">({{ $kwCount }}x)</b>
                    </span>
                @endforeach
            </div>
            @endif

            <!-- Table of Grouped Feedback with Total Column -->
            <div style="overflow-x: auto;">
                <table class="feedback-table">
                    <thead>
                        <tr>
                            <th style="width: 45px; text-align: center;">No</th>
                            <th>Poin Masukan / Uraian Responden</th>
                            <th style="min-width: 170px;">Departemen Terkait</th>
                            <th style="width: 120px; text-align: center; white-space: nowrap;">Total (Jumlah)</th>
                            <th style="min-width: 190px;">Rincian Responden</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fb['grouped_items'] as $itemIdx => $item)
                        <tr>
                            <td style="text-align: center; font-weight: 700; color: #64748B;">
                                {{ $itemIdx + 1 }}
                            </td>
                            <td>
                                <div style="font-size: 0.875rem; font-weight: 600; color: #1E293B; line-height: 1.45;">
                                    &ldquo;{{ $item['sample_text'] }}&rdquo;
                                </div>
                                @if(!empty($item['scores']))
                                    <div style="margin-top: 0.35rem; font-size: 0.725rem; color: #64748B;">
                                        Skor Kenyataan Terkait: 
                                        @foreach($item['scores'] as $sc)
                                            <span style="background: {{ $sc <= 2 ? '#FEE2E2' : '#DCFCE7' }}; color: {{ $sc <= 2 ? '#B91C1C' : '#15803D' }}; padding: 0.1rem 0.35rem; border-radius: 4px; font-weight: 700; font-size: 0.7rem; margin-right: 2px;">
                                                {{ $sc }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; flex-wrap: wrap; gap: 0.3rem;">
                                    @foreach($item['departments'] as $dept)
                                        <span style="background: #F1F5F9; color: #334155; border: 1px solid #E2E8F0; font-size: 0.75rem; font-weight: 600; padding: 0.15rem 0.45rem; border-radius: 4px;">
                                            {{ $dept }}
                                        </span>
                                    @endforeach
                                    @if(empty($item['departments']))
                                        <span style="color: #94A3B8; font-size: 0.75rem;">-</span>
                                    @endif
                                </div>
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <span class="badge-count-total {{ $item['total'] > 1 ? 'high-freq' : '' }}" title="{{ $item['total'] }} responden menyampaikan poin/keyword serupa">
                                    <i class="bi {{ $item['total'] > 1 ? 'bi-fire' : 'bi-person-fill' }}"></i>
                                    {{ $item['total'] }}x
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 0.775rem; color: #475569; display: flex; flex-direction: column; gap: 0.25rem;">
                                    @foreach(array_slice($item['responses'], 0, 3) as $respDetail)
                                        <div>
                                            <b style="color: #1E293B;">{{ $respDetail['name'] }}</b> 
                                            <span style="color: #64748B;">({{ $respDetail['dept'] }} &bull; {{ $respDetail['pos'] }})</span>
                                        </div>
                                    @endforeach
                                    @if(count($item['responses']) > 3)
                                        <div style="font-size: 0.725rem; color: #2563EB; font-weight: 600;">
                                            +{{ count($item['responses']) - 3 }} responden lainnya
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div style="background: #FFFFFF; border: 1px dashed #CBD5E1; border-radius: 12px; padding: 3rem 1.5rem; text-align: center; color: #64748B;">
            <i class="bi bi-chat-left-dots" style="font-size: 2.5rem; color: #94A3B8; display: block; margin-bottom: 0.75rem;"></i>
            <h4 style="font-size: 1.05rem; font-weight: 700; color: #1E293B; margin-bottom: 0.35rem;">
                Belum Ada Masukan Feedback atau Uraian
            </h4>
            <p style="font-size: 0.85rem; margin: 0;">
                Responden belum mengisi uraian atau catatan alasan nilai pada edisi survei ini.
            </p>
        </div>
        @endforelse
    </div>
    @endif

</div>
@endsection

@section('scripts')
@if($tab == 'analisa')
<script>
    (function() {
        const scaleMax = {{ $surveyScaleMax ?? 4 }};
        const dimLabels = @json(array_keys($dimensionScores));
        const realScores = @json(array_values($dimensionScores));
        const expScores = @json(array_values($dimensionExpectationScores));
        const dimensionDetails = @json($dimensionDetails);
        const overallMeanExp = {{ (float) ($overallExpScore ?? 0) }};
        const overallMeanReal = {{ (float) ($overallRealScore ?? 0) }};

        const quadrantMeta = {
            need_improvement: {
                label: 'Need Improvement',
                sub: 'Prioritas Utama',
                bg: '#FEF2F2',
                color: '#DC2626',
                border: '#FECACA'
            },
            maintain_quality: {
                label: 'Maintain Quality',
                sub: 'Pertahankan',
                bg: '#ECFDF5',
                color: '#059669',
                border: '#A7F3D0'
            },
            low_priority: {
                label: 'Low Priority',
                sub: 'Prioritas Rendah',
                bg: '#F8FAFC',
                color: '#64748B',
                border: '#CBD5E1'
            },
            possible_overkill: {
                label: 'Possible Overkill',
                sub: 'Berlebihan',
                bg: '#EFF6FF',
                color: '#2563EB',
                border: '#BFDBFE'
            }
        };

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text ?? '';
            return div.innerHTML;
        }

        function formatNum(val) {
            return (val !== null && val !== undefined && Number(val) >= 1) ? Number(val).toFixed(2) : '-';
        }

        function formatGap(gap) {
            if (gap === null || gap === undefined || Number.isNaN(Number(gap))) return '-';
            const num = Number(gap);
            return (num > 0 ? '+' : '') + num.toFixed(2);
        }

        function getQuadrantBadgeHtml(quadrantKey) {
            const meta = quadrantMeta[quadrantKey];
            if (!meta) return '<span style="color: #94A3B8;">-</span>';
            return `<span style="display: inline-block; background: ${meta.bg}; color: ${meta.color}; border: 1px solid ${meta.border}; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 6px; font-size: 0.72rem; white-space: nowrap;">${meta.label}</span>`;
        }

        // Custom Chart.js plugin for 4 IPA quadrants + point labels
        const ipaQuadrantPlugin = {
            id: 'ipaQuadrantPlugin',
            beforeDatasetsDraw(chart) {
                const { ctx, chartArea, scales, options } = chart;
                if (!chartArea || !scales.x || !scales.y) return;

                const pluginOpts = options.plugins?.ipaQuadrantPlugin || {};
                const meanX = pluginOpts.meanX;
                const meanY = pluginOpts.meanY;
                const hasThreshold = (meanX >= 1 && meanY >= 1);

                const left = chartArea.left;
                const right = chartArea.right;
                const top = chartArea.top;
                const bottom = chartArea.bottom;

                const midX = hasThreshold
                    ? Math.min(right, Math.max(left, scales.x.getPixelForValue(meanX)))
                    : (left + right) / 2;
                const midY = hasThreshold
                    ? Math.min(bottom, Math.max(top, scales.y.getPixelForValue(meanY)))
                    : (top + bottom) / 2;

                ctx.save();
                // Quadrant background tints
                // Top-Left: Need Improvement
                ctx.fillStyle = 'rgba(239, 68, 68, 0.06)';
                ctx.fillRect(left, top, midX - left, midY - top);

                // Top-Right: Maintain Quality
                ctx.fillStyle = 'rgba(16, 185, 129, 0.06)';
                ctx.fillRect(midX, top, right - midX, midY - top);

                // Bottom-Left: Low Priority
                ctx.fillStyle = 'rgba(100, 116, 139, 0.05)';
                ctx.fillRect(left, midY, midX - left, bottom - midY);

                // Bottom-Right: Possible Overkill
                ctx.fillStyle = 'rgba(37, 99, 235, 0.05)';
                ctx.fillRect(midX, midY, right - midX, bottom - midY);

                // Crosshair threshold lines
                if (hasThreshold) {
                    ctx.strokeStyle = '#DC2626';
                    ctx.lineWidth = 1.75;
                    ctx.setLineDash([5, 4]);

                    // Vertical mean Performance line
                    ctx.beginPath();
                    ctx.moveTo(midX, top);
                    ctx.lineTo(midX, bottom);
                    ctx.stroke();

                    // Horizontal mean Expectation line
                    ctx.beginPath();
                    ctx.moveTo(left, midY);
                    ctx.lineTo(right, midY);
                    ctx.stroke();
                    ctx.setLineDash([]);
                }

                // Quadrant corner labels
                const isCompact = (right - left) < 320;
                ctx.font = `700 ${isCompact ? '9px' : '10.5px'} "Plus Jakarta Sans", sans-serif`;
                ctx.textBaseline = 'top';
                ctx.fillStyle = 'rgba(185, 28, 28, 0.82)';
                ctx.textAlign = 'left';
                ctx.fillText('Need Improvement', left + 6, top + 6);

                ctx.fillStyle = 'rgba(4, 120, 87, 0.85)';
                ctx.textAlign = 'right';
                ctx.fillText('Maintain Quality', right - 6, top + 6);

                ctx.textBaseline = 'bottom';
                ctx.fillStyle = 'rgba(71, 85, 105, 0.82)';
                ctx.textAlign = 'left';
                ctx.fillText('Low Priority', left + 6, bottom - 6);

                ctx.fillStyle = 'rgba(29, 78, 216, 0.82)';
                ctx.textAlign = 'right';
                ctx.fillText('Possible Overkill', right - 6, bottom - 6);

                ctx.restore();
            },
            afterDatasetsDraw(chart) {
                const { ctx, chartArea } = chart;
                const meta = chart.getDatasetMeta(0);
                if (!meta || !meta.data || !chartArea) return;

                const isCompact = chartArea.width < 320;
                const dataset = chart.data.datasets[0]?.data || [];
                ctx.save();
                ctx.beginPath();
                ctx.rect(chartArea.left, chartArea.top, chartArea.width, chartArea.height);
                ctx.clip();

                ctx.font = `700 ${isCompact ? '9px' : '10px'} "Plus Jakarta Sans", sans-serif`;
                ctx.fillStyle = '#0F172A';
                ctx.textBaseline = 'middle';

                meta.data.forEach((point, idx) => {
                    const raw = dataset[idx];
                    if (!raw || !raw.shortLabel) return;

                    const labelText = isCompact && raw.shortLabel.length > 10
                        ? raw.shortLabel.slice(0, 9) + '…'
                        : raw.shortLabel;

                    const nearRight = point.x > (chartArea.right - (isCompact ? 65 : 85));
                    ctx.textAlign = nearRight ? 'right' : 'left';
                    const offsetX = nearRight ? -7 : 7;
                    const clampedY = Math.min(chartArea.bottom - 10, Math.max(chartArea.top + 10, point.y - 2));
                    ctx.fillText(labelText, point.x + offsetX, clampedY);
                });

                ctx.restore();
            }
        };

        let radarChart = null;
        let barChart = null;
        let ipaChart = null;

        // Radar Chart
        const radarCanvas = document.getElementById('dimensionRadarChart');
        if (radarCanvas) {
            radarChart = new Chart(radarCanvas.getContext('2d'), {
                type: 'radar',
                data: {
                    labels: dimLabels,
                    datasets: [
                        {
                            label: 'Tingkat Kenyataan (Perception)',
                            data: realScores,
                            backgroundColor: 'rgba(37, 99, 235, 0.2)',
                            borderColor: '#2563EB',
                            pointBackgroundColor: '#2563EB',
                            borderWidth: 2
                        },
                        {
                            label: 'Tingkat Harapan (Expectation)',
                            data: expScores,
                            backgroundColor: 'rgba(16, 185, 129, 0.2)',
                            borderColor: '#10B981',
                            pointBackgroundColor: '#10B981',
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 300 },
                    scales: {
                        r: {
                            min: 0,
                            max: scaleMax,
                            ticks: { stepSize: scaleMax > 5 ? 2 : 1 }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 11 } }
                        }
                    }
                }
            });
        }

        // Bar Chart
        const barCanvas = document.getElementById('dimensionBarChart');
        if (barCanvas) {
            barChart = new Chart(barCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: dimLabels,
                    datasets: [
                        {
                            label: 'Kenyataan',
                            data: realScores,
                            backgroundColor: '#2563EB'
                        },
                        {
                            label: 'Harapan',
                            data: expScores,
                            backgroundColor: '#10B981'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 300 },
                    scales: {
                        y: {
                            min: 0,
                            max: scaleMax,
                            ticks: { stepSize: scaleMax > 5 ? 2 : 1 }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 11 } }
                        }
                    }
                }
            });
        }

        // IPA Quadrant Scatter Chart
        const ipaCanvas = document.getElementById('ipaQuadrantChart');
        if (ipaCanvas) {
            ipaChart = new Chart(ipaCanvas.getContext('2d'), {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Posisi IPA',
                        data: [],
                        pointRadius: 6.5,
                        pointHoverRadius: 9,
                        pointBorderWidth: 1.5,
                        pointBorderColor: '#FFFFFF',
                        pointBackgroundColor: []
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 300 },
                    layout: {
                        padding: { top: 6, right: 10, bottom: 2, left: 2 }
                    },
                    onClick(event, elements) {
                        if (!elements.length) return;
                        const item = ipaChart.data.datasets[0].data[elements[0].index];
                        if (item && item.dimensionName && dimensionDetails[item.dimensionName]) {
                            selectDimension(item.dimensionName);
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Performance / Kenyataan',
                                font: { weight: '700', size: 11 },
                                color: '#1E40AF'
                            },
                            ticks: { font: { size: 10 } },
                            min: 1,
                            max: scaleMax
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Expectation / Harapan',
                                font: { weight: '700', size: 11 },
                                color: '#166534'
                            },
                            ticks: { font: { size: 10 } },
                            min: 1,
                            max: scaleMax
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        ipaQuadrantPlugin: {
                            meanX: overallMeanReal,
                            meanY: overallMeanExp
                        },
                        tooltip: {
                            callbacks: {
                                label(ctx) {
                                    const pt = ctx.raw;
                                    const qInfo = quadrantMeta[pt.quadrant]?.label || '-';
                                    return [
                                        pt.fullLabel,
                                        `Harapan (Y): ${Number(pt.y).toFixed(2)}`,
                                        `Kenyataan (X): ${Number(pt.x).toFixed(2)}`,
                                        `Gap: ${formatGap(pt.x - pt.y)}`,
                                        `Kuadran: ${qInfo}`
                                    ];
                                }
                            }
                        }
                    }
                },
                plugins: [ipaQuadrantPlugin]
            });
        }

        function classifyQuadrantClient(exp, real, meanExp, meanReal) {
            if (exp < 1 || real < 1 || meanExp < 1 || meanReal < 1) return null;
            if (exp >= meanExp && real < meanReal) return 'need_improvement';
            if (exp >= meanExp && real >= meanReal) return 'maintain_quality';
            if (exp < meanExp && real < meanReal) return 'low_priority';
            return 'possible_overkill';
        }

        function updateIpaChart(points, meanX, meanY, subtitleText, showReset) {
            const thresholdEl = document.getElementById('ipaThresholdInfo');
            const subtitleEl = document.getElementById('ipaChartSubtitle');
            const resetBtn = document.getElementById('btnResetChartView');

            if (subtitleEl) subtitleEl.textContent = subtitleText;
            if (resetBtn) resetBtn.style.display = showReset ? 'inline-flex' : 'none';

            if (thresholdEl) {
                if (meanX >= 1 && meanY >= 1) {
                    thresholdEl.innerHTML = `
                        <span><strong>Garis Pembatas (Rata-rata):</strong></span>
                        <span>Performance (<strong style="color:#1E40AF;">X&#772;</strong>): <strong>${Number(meanX).toFixed(2)}</strong> &bull; Expectation (<strong style="color:#166534;">Y&#772;</strong>): <strong>${Number(meanY).toFixed(2)}</strong></span>
                    `;
                } else {
                    thresholdEl.innerHTML = '<span>Belum ada data skor Harapan &amp; Kenyataan yang cukup untuk pemetaan kuadran IPA.</span>';
                }
            }

            if (!ipaChart) return;

            const colors = points.map(p => quadrantMeta[p.quadrant]?.color || '#64748B');
            ipaChart.data.datasets[0].data = points;
            ipaChart.data.datasets[0].pointBackgroundColor = colors;
            ipaChart.options.plugins.ipaQuadrantPlugin = { meanX, meanY };

            if (points.length > 0) {
                const xs = points.map(p => p.x).concat(meanX >= 1 ? [meanX] : []);
                const ys = points.map(p => p.y).concat(meanY >= 1 ? [meanY] : []);
                const minX = Math.max(0.5, Math.floor((Math.min(...xs) - 0.35) * 10) / 10);
                const maxX = Math.min(scaleMax + 0.25, Math.ceil((Math.max(...xs) + 0.35) * 10) / 10);
                const minY = Math.max(0.5, Math.floor((Math.min(...ys) - 0.35) * 10) / 10);
                const maxY = Math.min(scaleMax + 0.25, Math.ceil((Math.max(...ys) + 0.35) * 10) / 10);

                ipaChart.options.scales.x.min = minX < maxX ? minX : 1;
                ipaChart.options.scales.x.max = minX < maxX ? maxX : scaleMax;
                ipaChart.options.scales.y.min = minY < maxY ? minY : 1;
                ipaChart.options.scales.y.max = minY < maxY ? maxY : scaleMax;
            } else {
                ipaChart.options.scales.x.min = 1;
                ipaChart.options.scales.x.max = scaleMax;
                ipaChart.options.scales.y.min = 1;
                ipaChart.options.scales.y.max = scaleMax;
            }

            ipaChart.update();
        }

        function updateRadarAndBar(labels, reals, exps, titleText) {
            const chartTitleEl = document.getElementById('radarBarChartTitle');
            if (chartTitleEl) chartTitleEl.textContent = titleText;

            if (radarChart) {
                radarChart.data.labels = labels;
                radarChart.data.datasets[0].data = reals;
                radarChart.data.datasets[1].data = exps;
                radarChart.update();
            }

            if (barChart) {
                barChart.data.labels = labels;
                barChart.data.datasets[0].data = reals;
                barChart.data.datasets[1].data = exps;
                barChart.update();
            }
        }

        function showOverviewMode() {
            const summaryView = document.getElementById('dimensionSummaryView');
            const detailView = document.getElementById('dimensionDetailView');
            if (summaryView) summaryView.style.display = 'block';
            if (detailView) detailView.style.display = 'none';

            // Update IPA scatter for all dimensions
            const overviewPoints = dimLabels.map((dim, idx) => {
                const real = Number(realScores[idx] || 0);
                const exp = Number(expScores[idx] || 0);
                if (real < 1 || exp < 1) return null;
                const shortName = dim.length > 16 ? (dim.slice(0, 15) + '…') : dim;
                return {
                    x: real,
                    y: exp,
                    shortLabel: shortName,
                    fullLabel: dim,
                    dimensionName: dim,
                    quadrant: classifyQuadrantClient(exp, real, overallMeanExp, overallMeanReal)
                };
            }).filter(Boolean);

            updateIpaChart(
                overviewPoints,
                overallMeanReal,
                overallMeanExp,
                'Pemetaan Harapan (Expectation) vs Kenyataan (Performance) seluruh dimensi.',
                false
            );

            updateRadarAndBar(dimLabels, realScores, expScores, 'Visualisasi Radar & Bar Chart (Seluruh Dimensi)');
        }

        function selectDimension(dimName) {
            const detail = dimensionDetails[dimName];
            if (!detail) return;

            const summaryView = document.getElementById('dimensionSummaryView');
            const detailView = document.getElementById('dimensionDetailView');
            const titleEl = document.getElementById('detailDimensionTitle');
            const metaEl = document.getElementById('detailDimensionMeta');
            const countBadgeEl = document.getElementById('detailIndicatorCountBadge');
            const tbodyEl = document.getElementById('detailIndicatorsBody');
            const tfootEl = document.getElementById('detailIndicatorsFoot');

            if (summaryView) summaryView.style.display = 'none';
            if (detailView) detailView.style.display = 'block';

            const indicators = detail.indicators || [];
            if (titleEl) titleEl.textContent = 'Detail Dimensi: ' + detail.name;
            if (countBadgeEl) countBadgeEl.textContent = indicators.length + ' Indikator';
            if (metaEl) {
                metaEl.innerHTML = `Harapan: <strong style="color:#166534;">${formatNum(detail.avg_exp)}</strong> &bull; Kenyataan: <strong style="color:#1E40AF;">${formatNum(detail.avg_real)}</strong> &bull; Gap: <strong style="color:${Number(detail.gap) < 0 ? '#DC2626' : '#2563EB'};">${(detail.avg_exp >= 1 && detail.avg_real >= 1) ? formatGap(detail.gap) : '-'}</strong>`;
            }

            if (tbodyEl) {
                if (indicators.length === 0) {
                    tbodyEl.innerHTML = `
                        <tr>
                            <td colspan="6" style="padding: 1.5rem; text-align: center; color: #64748B;">
                                Belum ada indikator pada dimensi ini.
                            </td>
                        </tr>
                    `;
                } else {
                    tbodyEl.innerHTML = indicators.map(ind => {
                        const hasBoth = (ind.avg_exp !== null && ind.avg_real !== null && ind.avg_exp >= 1 && ind.avg_real >= 1);
                        const gapColor = Number(ind.gap) < 0 ? '#DC2626' : '#2563EB';
                        const gapBg = Number(ind.gap) < 0 ? '#FEF2F2' : '#EFF6FF';
                        const gapBorder = Number(ind.gap) < 0 ? '#FECACA' : '#BFDBFE';

                        return `
                            <tr style="border-bottom: 1px solid #F1F5F9;">
                                <td style="padding: 0.6rem 0.65rem; vertical-align: top;">
                                    <span style="display: inline-block; background: var(--color-navy-primary); color: #FFFFFF; font-weight: 800; font-size: 0.72rem; padding: 0.15rem 0.45rem; border-radius: 5px;">
                                        ${escapeHtml(ind.code)}
                                    </span>
                                </td>
                                <td style="padding: 0.6rem 0.65rem; vertical-align: top;">
                                    <div style="font-weight: 700; color: #0F172A; line-height: 1.35;">${escapeHtml(ind.indicator_title)}</div>
                                    <div style="font-size: 0.74rem; color: #64748B; margin-top: 0.15rem; line-height: 1.35;">${escapeHtml(ind.question_text)}</div>
                                </td>
                                <td style="padding: 0.6rem 0.65rem; text-align: center; vertical-align: top;">
                                    <span style="display: inline-block; background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; font-weight: 700; padding: 0.15rem 0.45rem; border-radius: 6px; font-size: 0.78rem;">
                                        ${formatNum(ind.avg_exp)}
                                    </span>
                                </td>
                                <td style="padding: 0.6rem 0.65rem; text-align: center; vertical-align: top;">
                                    <span style="display: inline-block; background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; font-weight: 700; padding: 0.15rem 0.45rem; border-radius: 6px; font-size: 0.78rem;">
                                        ${formatNum(ind.avg_real)}
                                    </span>
                                </td>
                                <td style="padding: 0.6rem 0.65rem; text-align: center; vertical-align: top;">
                                    ${hasBoth
                                        ? `<span style="display: inline-block; font-weight: 800; font-size: 0.78rem; padding: 0.15rem 0.45rem; border-radius: 6px; background: ${gapBg}; color: ${gapColor}; border: 1px solid ${gapBorder};">${formatGap(ind.gap)}</span>`
                                        : '<span style="color: #94A3B8;">-</span>'}
                                </td>
                                <td style="padding: 0.6rem 0.65rem; text-align: center; vertical-align: top;">
                                    ${getQuadrantBadgeHtml(ind.quadrant)}
                                </td>
                            </tr>
                        `;
                    }).join('');
                }
            }

            if (tfootEl) {
                const hasDimBoth = (detail.avg_exp >= 1 && detail.avg_real >= 1);
                tfootEl.innerHTML = `
                    <tr>
                        <td colspan="2" style="padding: 0.7rem 0.65rem; color: #0F172A; font-size: 0.8rem;">
                            Rata-Rata Dimensi ${escapeHtml(detail.name)}
                        </td>
                        <td style="padding: 0.7rem 0.65rem; text-align: center; color: #166534; font-size: 0.82rem;">
                            ${formatNum(detail.avg_exp)}
                        </td>
                        <td style="padding: 0.7rem 0.65rem; text-align: center; color: #1E40AF; font-size: 0.82rem;">
                            ${formatNum(detail.avg_real)}
                        </td>
                        <td style="padding: 0.7rem 0.65rem; text-align: center; color: ${Number(detail.gap) < 0 ? '#DC2626' : '#2563EB'}; font-size: 0.82rem;">
                            ${hasDimBoth ? formatGap(detail.gap) : '-'}
                        </td>
                        <td style="padding: 0.7rem 0.65rem; text-align: center; color: #64748B; font-size: 0.72rem;">
                            X&#772;: ${formatNum(detail.ipa_mean_real)} / Y&#772;: ${formatNum(detail.ipa_mean_exp)}
                        </td>
                    </tr>
                `;
            }

            // Update IPA scatter chart to show indicators inside this dimension
            const indicatorPoints = indicators.map(ind => {
                if (ind.avg_real === null || ind.avg_exp === null || ind.avg_real < 1 || ind.avg_exp < 1) {
                    return null;
                }
                const shortTitle = ind.indicator_title && ind.indicator_title.length > 14
                    ? `${ind.code} (${ind.indicator_title.slice(0, 12)}…)`
                    : `${ind.code}${ind.indicator_title ? ' ' + ind.indicator_title : ''}`;

                return {
                    x: Number(ind.avg_real),
                    y: Number(ind.avg_exp),
                    shortLabel: shortTitle,
                    fullLabel: `${ind.code} - ${ind.indicator_title}`,
                    quadrant: ind.quadrant
                };
            }).filter(Boolean);

            updateIpaChart(
                indicatorPoints,
                Number(detail.ipa_mean_real || 0),
                Number(detail.ipa_mean_exp || 0),
                `Pemetaan indikator pada dimensi "${detail.name}" berdasarkan garis rata-rata dimensi.`,
                true
            );

            // Update Radar & Bar chart to show indicators in this dimension
            const ratingIndicators = indicators.filter(ind => (ind.avg_real !== null && ind.avg_real >= 1) || (ind.avg_exp !== null && ind.avg_exp >= 1));
            const indLabels = ratingIndicators.map(ind => `${ind.code}: ${ind.indicator_title.length > 18 ? ind.indicator_title.slice(0, 17) + '…' : ind.indicator_title}`);
            const indReals = ratingIndicators.map(ind => ind.avg_real ?? 0);
            const indExps = ratingIndicators.map(ind => ind.avg_exp ?? 0);

            updateRadarAndBar(
                indLabels.length > 0 ? indLabels : [detail.name],
                indLabels.length > 0 ? indReals : [detail.avg_real ?? 0],
                indLabels.length > 0 ? indExps : [detail.avg_exp ?? 0],
                `Visualisasi Indikator Dimensi: ${detail.name}`
            );
        }

        // Bind row click listeners
        document.querySelectorAll('.dimension-clickable-row').forEach(row => {
            row.addEventListener('click', function() {
                const dimName = this.getAttribute('data-dimension');
                if (dimName) selectDimension(dimName);
            });
        });

        const btnBack = document.getElementById('btnBackToDimensions');
        if (btnBack) btnBack.addEventListener('click', showOverviewMode);

        const btnResetChart = document.getElementById('btnResetChartView');
        if (btnResetChart) btnResetChart.addEventListener('click', showOverviewMode);

        // Initialize overview charts on load
        showOverviewMode();
    })();
</script>
@endif
@endsection
