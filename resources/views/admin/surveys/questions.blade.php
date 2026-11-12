@extends('layouts.app')

@section('title', 'Kelola Pertanyaan - ' . $survey->title)

@section('styles')
<style>
    .builder-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .builder-actions {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    .dimension-panel {
        background: #FFFFFF;
        border: 1px solid var(--color-border);
        border-radius: 14px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .dimension-header {
        background: #F8FAFC;
        border-bottom: 1px solid var(--color-border);
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .modal-box {
        background: #FFFFFF;
        border-radius: 16px;
        width: 95%;
        max-width: 650px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 1.75rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        margin: 1rem auto;
    }

    @media (max-width: 768px) {
        .builder-header {
            flex-direction: column;
            align-items: stretch;
        }

        .builder-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
        }

        .builder-actions button,
        .builder-actions a {
            justify-content: center;
            text-align: center;
        }

        .builder-actions button:last-child {
            grid-column: 1 / -1;
        }

        .modal-box {
            padding: 1.25rem;
            border-radius: 12px;
        }
    }

    .option-input-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .option-badge-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: #475569;
        background: #F1F5F9;
        padding: 0.45rem 0.65rem;
        border-radius: 6px;
        border: 1px solid #E2E8F0;
        min-width: 68px;
        text-align: center;
        flex-shrink: 0;
    }

    .btn-remove-option {
        background: #FEE2E2;
        border: 1px solid #FCA5A5;
        color: #DC2626;
        width: 34px;
        height: 34px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.15s ease;
    }

    .btn-remove-option:hover {
        background: #EF4444;
        color: #FFFFFF;
    }

    .btn-add-option {
        background: #EFF6FF;
        color: #2563EB;
        border: 1.5px dashed #93C5FD;
        padding: 0.45rem 0.85rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.15s ease;
    }

    .btn-add-option:hover {
        background: #DBEAFE;
        border-color: #2563EB;
    }

    .pos-dropdown-btn {
        width: 100%;
        padding: 0.6rem 0.75rem;
        border: 1px solid var(--color-border);
        border-radius: 8px;
        font-size: 0.85rem;
        background: #FFFFFF;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: border-color 0.15s ease;
    }

    .pos-dropdown-btn:hover {
        border-color: #94A3B8;
    }

    .pos-dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: #FFFFFF;
        border: 1px solid var(--color-border);
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        z-index: 10000;
        max-height: 230px;
        overflow-y: auto;
        padding: 0.45rem;
    }
</style>
@endsection

@section('content')
<div style="width: 100%;">
    <div style="margin-bottom: 0.75rem;">
        <a href="{{ route('admin.surveys.index') }}" style="color: #64748B; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kuesioner
        </a>
    </div>
    
    <div class="builder-header">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                <span style="background: #EFF6FF; color: #2563EB; font-weight: 800; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 4px;">
                    {{ $survey->category }}
                </span>
                <span style="color: #64748B; font-size: 0.85rem;">&bull; Total {{ $questions->count() }} Butir Pertanyaan</span>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--color-navy-primary); letter-spacing: -0.02em; line-height: 1.3;">
                {{ $survey->title }}
            </h1>
        </div>

        <!-- Action Buttons -->
        <div class="builder-actions">
            <a href="{{ route('survey.form', $survey->slug) }}" target="_blank" style="background: #FFFFFF; border: 1px solid #CBD5E1; color: #334155; text-decoration: none; padding: 0.6rem 0.9rem; border-radius: 8px; font-weight: 700; font-size: 0.825rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                <i class="bi bi-box-arrow-up-right"></i> Preview
            </a>
            <button onclick="openModal('importBankModal')" style="background: #7C3AED; color: #FFFFFF; border: none; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.825rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 10px rgba(124,58,237,0.2);">
                <i class="bi bi-collection-fill"></i> Import Bank Soal
            </button>
            <button onclick="openModal('createQuestionModal')" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.825rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;">
                <i class="bi bi-plus-circle-fill"></i> Tambah Soal Baru
            </button>
        </div>
    </div>
</div>

<!-- Dimension Groups and Questions -->
@php
    $groupedQuestions = $questions->groupBy(function($item) {
        return $item->dimensionModel ? $item->dimensionModel->name : ($item->dimension ?: 'General');
    });
@endphp

@forelse($groupedQuestions as $dimName => $qList)
@php
    $dimModel = $qList->first()->dimensionModel;
    $dimColor = $dimModel ? $dimModel->color : '#2563EB';
@endphp
<div class="dimension-panel">
    <!-- Dimension Header -->
    <div class="dimension-header">
        <div style="display: flex; align-items: center; gap: 0.6rem;">
            <div style="width: 12px; height: 12px; border-radius: 50%; background: {{ $dimColor }}; flex-shrink: 0;"></div>
            <h3 style="font-size: 1rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">
                Dimensi: {{ $dimName }}
            </h3>
            <span style="font-size: 0.75rem; font-weight: 700; background: {{ $dimColor }}15; color: {{ $dimColor }}; padding: 0.15rem 0.5rem; border-radius: 4px;">
                {{ $qList->count() }} Butir
            </span>
        </div>
        @if($dimModel && $dimModel->description)
            <div style="font-size: 0.775rem; color: #64748B; max-width: 450px;">
                {{ $dimModel->description }}
            </div>
        @endif
    </div>

    <!-- Questions in Dimension Table -->
    <div class="table-container">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
            <thead>
                <tr style="background: #FFFFFF; border-bottom: 1px solid #F1F5F9; color: #64748B; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                    <th style="padding: 0.75rem 1rem; width: 60px;">No</th>
                    <th style="padding: 0.75rem 1rem; width: 160px;">Indikator</th>
                    <th style="padding: 0.75rem 1rem;">Teks Pertanyaan</th>
                    <th style="padding: 0.75rem 1rem; width: 150px;">Tipe & Skala</th>
                    <th style="padding: 0.75rem 1rem; width: 170px;">Logic Deskriptif</th>
                    <th style="padding: 0.75rem 1rem; width: 90px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($qList as $q)
                <tr style="border-bottom: 1px solid #F8FAFC;">
                    <td style="padding: 0.75rem 1rem; font-weight: 800; color: var(--color-navy-primary);">
                        <span style="background: #F1F5F9; padding: 0.2rem 0.45rem; border-radius: 4px; font-size: 0.8rem;">
                            {{ $q->section }}{{ $q->question_number }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem 1rem; font-weight: 600; color: #334155; font-size: 0.8rem;">
                        {{ $q->indicator_title ?: '-' }}
                    </td>
                    <td style="padding: 0.75rem 1rem; color: #1E293B; line-height: 1.45;">
                        {{ $q->question_text }}
                        @if($q->applies_to_employment_status || $q->applies_to_positions || $q->applies_to_gender)
                            <div style="margin-top: 0.25rem; font-size: 0.75rem; color: #D97706; font-weight: 600;">
                                <i class="bi bi-person-fill-lock"></i> Khusus: {{ $q->applies_to_employment_status ?? '-' }} / {{ $q->applies_to_positions ?? '-' }} / {{ $q->applies_to_gender ?? 'Semua Gender' }}
                            </div>
                        @endif
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        @if($q->question_type === 'dual_rating' || $q->section === 'B')
                            <span style="background: #EFF6FF; color: #2563EB; font-weight: 700; font-size: 0.725rem; padding: 0.15rem 0.4rem; border-radius: 4px; display: inline-block;">
                                Dual Rating (1-{{ $q->rating_scale ?? 4 }})
                            </span>
                        @elseif($q->question_type === 'single_rating')
                            <span style="background: #F0FDF4; color: #16A34A; font-weight: 700; font-size: 0.725rem; padding: 0.15rem 0.4rem; border-radius: 4px; display: inline-block;">
                                Skala 1-{{ $q->rating_scale ?? 4 }}
                            </span>
                        @elseif($q->question_type === 'essay')
                            <span style="background: #FAF5FF; color: #9333EA; font-weight: 700; font-size: 0.725rem; padding: 0.15rem 0.4rem; border-radius: 4px; display: inline-block;">
                                Uraian
                            </span>
                        @elseif($q->question_type === 'multiple_choice')
                            <span style="background: #FFF7ED; color: #EA580C; font-weight: 700; font-size: 0.725rem; padding: 0.15rem 0.4rem; border-radius: 4px; display: inline-block;">
                                Pilihan Ganda
                            </span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        @if($q->require_reason_on_low_score)
                            <span style="display: inline-flex; align-items: center; gap: 0.3rem; background: #FEF3C7; color: #92400E; font-size: 0.725rem; font-weight: 700; padding: 0.2rem 0.4rem; border-radius: 4px;">
                                <i class="bi bi-check-circle-fill" style="color: #D97706;"></i>
                                @if($q->question_type === 'multiple_choice')
                                    Deskriptif Aktif
                                @else
                                    Wajib jika &le; {{ $q->low_score_threshold ?? 2 }}
                                @endif
                            </span>
                        @else
                            <span style="color: #94A3B8; font-size: 0.75rem;">Nonaktif</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem 1rem; text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 0.35rem;">
                            <button onclick="editSurveyQuestion({{ json_encode($q) }})" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; padding: 0.3rem 0.5rem; border-radius: 6px; font-size: 0.8rem; cursor: pointer;" title="Edit Pertanyaan">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('admin.surveys.questions.destroy', [$survey->id, $q->id]) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan ini dari kuesioner?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #FEE2E2; border: 1px solid #FCA5A5; color: #DC2626; padding: 0.3rem 0.5rem; border-radius: 6px; font-size: 0.8rem; cursor: pointer;" title="Hapus dari Kuesioner">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@empty
<div style="background: #FFFFFF; border: 1px dashed var(--color-border); border-radius: 12px; padding: 3rem 1.5rem; text-align: center; color: #64748B; margin-bottom: 2rem;">
    <i class="bi bi-ui-checks-grid" style="font-size: 2.5rem; color: #94A3B8; display: block; margin-bottom: 0.75rem;"></i>
    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--color-navy-primary); margin-bottom: 0.35rem;">
        Belum ada butir pertanyaan dalam kuesioner ini
    </h3>
    <p style="font-size: 0.875rem; margin-bottom: 1.25rem;">
        Pilih pertanyaan dari <b>Bank Soal</b> atau tambahkan pertanyaan kustom baru.
    </p>
    <div style="display: flex; justify-content: center; gap: 0.75rem; flex-wrap: wrap;">
        <button onclick="openModal('importBankModal')" style="background: #7C3AED; color: #FFFFFF; border: none; padding: 0.65rem 1.1rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;">
            <i class="bi bi-collection-fill"></i> Import Bank Soal
        </button>
        <button onclick="openModal('createQuestionModal')" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.65rem 1.1rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;">
            <i class="bi bi-plus-circle-fill"></i> Tambah Soal Baru
        </button>
    </div>
</div>
@endforelse

<!-- Modal Import Bank Soal -->
<div id="importBankModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 1rem;">
    <div class="modal-box" style="max-width: 750px; max-height: 88vh; display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">
                    Import dari Bank Soal
                </h3>
                <p style="font-size: 0.8rem; color: #64748B; margin: 0.15rem 0 0;">
                    Pilih template pertanyaan dari Bank Soal sesuai kategori <b>{{ $survey->categoryModel?->name ?? $survey->category }}</b>.
                </p>
            </div>
            <button onclick="closeModal('importBankModal')" style="background: none; border: none; font-size: 1.25rem; color: #94A3B8; cursor: pointer;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.surveys.questions.import', $survey->id) }}" method="POST" style="display: flex; flex-direction: column; height: 100%; overflow: hidden;">
            @csrf
            
            <!-- Category Filter and Target Section -->
            <div style="margin-bottom: 0.75rem; display: flex; gap: 0.65rem; align-items: center; flex-wrap: wrap; background: #F8FAFC; padding: 0.75rem; border-radius: 8px; border: 1px solid #E2E8F0;">
                <div style="flex: 1; min-width: 180px;">
                    <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.2rem;">Filter Kategori Bank Soal:</label>
                    <select id="importCategoryFilter" onchange="filterImportBankList()" style="width: 100%; padding: 0.4rem 0.65rem; border: 1px solid var(--color-border); border-radius: 6px; font-size: 0.8rem; font-weight: 600; background: #FFFFFF;">
                        <option value="">-- Semua Kategori Bank Soal --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" data-name="{{ $cat->name }}" data-slug="{{ $cat->slug }}" {{ ($survey->survey_category_id == $cat->id || $survey->category == $cat->name) ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="min-width: 240px;">
                    <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.2rem;">Target Bagian (Section):</label>
                    <select name="section" id="importTargetSection" style="width: 100%; padding: 0.4rem 0.65rem; border: 1px solid var(--color-border); border-radius: 6px; font-size: 0.8rem; font-weight: 600; background: #FFFFFF;">
                        <option value="auto">⚡ Sesuai Template Asal (Otomatis)</option>
                    </select>
                </div>

                <div style="align-self: flex-end;">
                    <button type="button" onclick="toggleSelectAllBank(this)" id="selectAllBankBtn" style="background: #E2E8F0; border: none; padding: 0.45rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 700; cursor: pointer; white-space: nowrap;">
                        Pilih Semua
                    </button>
                </div>
            </div>

            <div id="bankTemplatesContainer" style="flex: 1; overflow-y: auto; border: 1px solid var(--color-border); border-radius: 8px; margin-bottom: 1rem; padding: 0.5rem;">
                <div id="bankEmptyFiltered" style="display: none; text-align: center; padding: 2rem; color: #94A3B8;">
                    Tidak ada butir template yang cocok untuk filter kategori ini di Bank Soal.
                </div>
                @forelse($bankTemplates as $tpl)
                    <label class="bank-template-item" data-category-id="{{ $tpl->dimension?->survey_category_id }}" data-category-name="{{ $tpl->dimension?->category?->name ?? '' }}" data-section="{{ $tpl->section ?: 'B' }}" style="display: flex; align-items: flex-start; gap: 0.6rem; padding: 0.65rem 0.75rem; border-bottom: 1px solid #F1F5F9; cursor: pointer; font-size: 0.85rem; transition: background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                        <input type="checkbox" name="template_ids[]" value="{{ $tpl->id }}" class="bank-checkbox" style="margin-top: 0.25rem;">
                        <div style="flex: 1;">
                            <div style="display: flex; gap: 0.4rem; align-items: center; margin-bottom: 0.2rem; flex-wrap: wrap;">
                                @if($tpl->dimension)
                                    <span style="background: #EFF6FF; color: #2563EB; font-size: 0.7rem; font-weight: 800; padding: 0.1rem 0.45rem; border-radius: 4px; border: 1px solid #DBEAFE;">
                                        {{ $tpl->dimension->category?->name ?? 'Umum' }}
                                    </span>
                                    <span style="background: {{ $tpl->dimension->color ?? '#2563EB' }}15; color: {{ $tpl->dimension->color ?? '#2563EB' }}; font-size: 0.7rem; font-weight: 700; padding: 0.1rem 0.4rem; border-radius: 4px;">
                                        {{ $tpl->dimension->name }}
                                    </span>
                                @endif
                                <span style="background: #FEF3C7; color: #B45309; font-size: 0.7rem; font-weight: 800; padding: 0.1rem 0.45rem; border-radius: 4px; border: 1px solid #FDE68A;">
                                    Target: Bagian {{ $tpl->section ?: 'B' }}
                                </span>
                                @if($tpl->indicator_title)
                                    <span style="color: #64748B; font-size: 0.75rem; font-weight: 600;">
                                        &bull; {{ $tpl->indicator_title }}
                                    </span>
                                @endif
                                <span style="color: #94A3B8; font-size: 0.7rem;">
                                    ({{ $tpl->question_type }})
                                </span>
                            </div>
                            <div style="color: #1E293B; line-height: 1.4;">{{ $tpl->question_text }}</div>
                        </div>
                    </label>
                @empty
                    <div style="text-align: center; padding: 2rem; color: #94A3B8;">
                        Tidak ada template di Bank Soal. Tambahkan template terlebih dahulu.
                    </div>
                @endforelse
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.6rem;">
                <button type="button" onclick="closeModal('importBankModal')" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #64748B; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: #7C3AED; color: #FFFFFF; border: none; padding: 0.6rem 1.15rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Import Terpilih
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Create Question Directly -->
<div id="createQuestionModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 1rem;">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">
                    Tambah Butir Pertanyaan Baru
                </h3>
                <span style="display: inline-block; font-size: 0.725rem; font-weight: 800; color: #2563EB; background: #EFF6FF; padding: 0.15rem 0.5rem; border-radius: 4px; margin-top: 0.25rem;">
                    Kategori: {{ $survey->categoryModel?->name ?? $survey->category }}
                </span>
            </div>
            <button onclick="closeModal('createQuestionModal')" style="background: none; border: none; font-size: 1.25rem; color: #94A3B8; cursor: pointer;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.surveys.questions.store', $survey->id) }}" method="POST">
            @csrf
            @php
                $allSurveyIndicators = collect()
                    ->merge($questions->pluck('indicator_title'))
                    ->merge($bankTemplates->pluck('indicator_title'))
                    ->merge([
                        'Kompensasi & Benefit', 'Fasilitas & Lingkungan Kerja', 'Tanggung Jawab Individu',
                        'Pengakuan & Apresiasi', 'Kerjasama Tim & Komunikasi', 'Kepemimpinan & Supervisi',
                        'Pengembangan Karir & Pelatihan', 'Budaya Kerja & Nilai Perusahaan',
                        'Kepatuhan K3 & SOP', 'Kualitas Produk & Layanan', 'Kecepatan Respon Layanan',
                        'Kepuasan Menyeluruh', 'Saran & Masukan Perbaikan'
                    ])
                    ->filter()
                    ->map(fn($s) => trim($s))
                    ->unique()
                    ->values();
            @endphp
            <div style="display: grid; grid-template-columns: 1fr 1fr 1.5fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                    @php
                        $surveyCatName = $survey->categoryModel?->name ?? $survey->category;
                        $isCustomerSat = str_contains(strtolower($surveyCatName), 'customer satisfaction');
                        $isBudaya = str_contains(strtolower($surveyCatName), 'budaya');
                    @endphp
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Bagian (Section) *</label>
                    <select name="section" id="createQSec" onchange="updateQuestionNumberDropdown('create')" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                        @if($isCustomerSat)
                            <option value="A">Bagian A (Kepuasan Produk & Layanan)</option>
                            <option value="B">Bagian B (Saran & Masukan Pelanggan)</option>
                            <option value="C">Bagian C (Pertanyaan Tambahan)</option>
                        @elseif($isBudaya)
                            <option value="B">Bagian B (Dimensi Budaya Kerja & AKHLAK)</option>
                            <option value="C">Bagian C (Pertanyaan Umum & Evaluasi)</option>
                            <option value="A">Bagian A (Identitas Responden)</option>
                        @else
                            <option value="B">Bagian B (Dimensi Inti)</option>
                            <option value="C">Bagian C (Pertanyaan Umum)</option>
                            <option value="A">Bagian A (Identitas Responden)</option>
                        @endif
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Nomor Soal *</label>
                    <select name="question_number" id="createQNum" required style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 700;">
                        <!-- Dynamically populated with disabled taken numbers -->
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        Dimensi Indikator *
                    </label>
                    <select name="dimension_id" required style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                        <option value="">-- Pilih Dimensi --</option>
                        @foreach($dimensions as $dim)
                            <option value="{{ $dim->id }}">{{ $dim->name }} ({{ $dim->code ?: 'DIM' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Indikator / Judul Pertanyaan</label>
                <select id="createQIndSelect" onchange="onSurveyIndicatorSelectChanged('create')" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                    <option value="">-- Pilih Indikator --</option>
                    @foreach($allSurveyIndicators as $ind)
                        <option value="{{ $ind }}">{{ $ind }}</option>
                    @endforeach
                    <option value="__custom__">✏️ [+] Tulis Indikator Baru...</option>
                </select>
                <input type="text" name="indicator_title" id="createQIndCustom" placeholder="Ketik nama indikator baru..." style="display: none; width: 100%; margin-top: 0.35rem; padding: 0.6rem 0.75rem; border: 1px solid #93C5FD; border-radius: 8px; font-size: 0.85rem; background: #EFF6FF;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Teks Pertanyaan *</label>
                <textarea name="question_text" required rows="3" placeholder="Tuliskan butir pertanyaan secara jelas..." style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; font-family: inherit; resize: vertical;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Tipe Pertanyaan *</label>
                    <select name="question_type" id="createQTypeSurvey" onchange="toggleSurveyTypeFields('create')" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF;">
                        <option value="dual_rating">Dual Rating (Harapan & Kenyataan)</option>
                        <option value="single_rating">Single Rating (Skala Nilai)</option>
                        <option value="multiple_choice">Pilihan Ganda (MCQ)</option>
                        <option value="essay">Uraian / Deskriptif</option>
                    </select>
                </div>
                <div id="createScaleBlockSurvey">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Skala Rating Kuesioner</label>
                    <div style="padding: 0.55rem 0.75rem; background: #F1F5F9; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.825rem; font-weight: 700; color: #1E293B; display: flex; align-items: center; gap: 0.4rem;">
                        <i class="bi bi-star-fill" style="color: #F59E0B;"></i>
                        <span>Skala 1 - {{ $survey->rating_scale ?? 4 }} ({{ $survey->ratingScale?->name ?? 'Standar' }})</span>
                    </div>
                    <input type="hidden" name="rating_scale" value="{{ $survey->rating_scale ?? 4 }}">
                </div>
            </div>

            <!-- Logic Low Score Box (Descriptive / Reason) -->
            <div id="createReasonContainerSurvey" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 0.85rem; margin-bottom: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.825rem; font-weight: 700; color: var(--color-navy-primary); cursor: pointer;">
                    <input type="checkbox" name="require_reason_on_low_score" id="createReasonSurvey" value="1" style="width: 16px; height: 16px;">
                    <span id="createReasonLabelSurvey">Aktifkan Logic Kolom Alasan / Deskriptif</span>
                </label>
                <p id="createReasonHelpSurvey" style="margin: 0.35rem 0 0 1.6rem; font-size: 0.75rem; color: #64748B;">
                    Default: Nonaktif (Opsional). Jika diaktifkan, responden dapat memberikan uraian/alasan deskriptif.
                </p>
            </div>

            <!-- MCQ Separate Option Inputs Container -->
            <div id="createOptionsBlockSurvey" style="display: none; margin-bottom: 1rem; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem; border-radius: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div>
                        <label style="display: block; font-size: 0.825rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">
                            Kolom Pilihan Jawaban (MCQ)
                        </label>
                        <span style="font-size: 0.75rem; color: #64748B;">Isi setiap opsi pada masing-masing kolom terpisah berikut:</span>
                    </div>
                    <button type="button" onclick="addOptionRowSurvey('create')" class="btn-add-option">
                        <i class="bi bi-plus-lg"></i> Tambah Kolom Opsi
                    </button>
                </div>
                
                <div id="createOptionsListSurvey">
                    <!-- Dynamic option rows -->
                </div>
            </div>

            <!-- Target Responden: Status Pegawai & Jenis Kelamin (Baris 1: 2 Kolom) -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.85rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        Khusus Status Pegawai
                    </label>
                    <select name="applies_to_employment_status" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF;">
                        <option value="">Semua Status Pegawai (Umum)</option>
                        <option value="Tetap">Pegawai Tetap</option>
                        <option value="Kontrak">Pegawai Kontrak</option>
                        <option value="Outsourcing">Outsourcing</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        Khusus Jenis Kelamin
                    </label>
                    <select name="applies_to_gender" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF;">
                        <option value="">Semua Gender (Umum)</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            <!-- Multi-select Checkbox Dropdown for Jabatan (Baris 2: Bawahnya) -->
            <div style="position: relative; margin-bottom: 1.25rem;" id="createPosSurveyDropdownWrapper">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                    Khusus Jabatan (Checkbox)
                </label>
                <button type="button" id="createPosSurveyDropdownBtn" onclick="togglePosDropdownSurvey('create', event)" class="pos-dropdown-btn">
                    <span id="createPosSurveyLabel" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Semua Jabatan (Umum)</span>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem; color: #64748B;"></i>
                </button>

                <div id="createPosSurveyDropdownMenu" class="pos-dropdown-menu">
                    <!-- Select All Option -->
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.45rem 0.6rem; border-radius: 6px; background: #F1F5F9; font-size: 0.8rem; font-weight: 800; color: var(--color-navy-primary); cursor: pointer; margin-bottom: 0.25rem;">
                        <input type="checkbox" id="createPosSurveyAll" value="__all__" onchange="toggleSelectAllPosSurvey('create')" checked style="width: 15px; height: 15px; cursor: pointer;">
                        <span>Semua Jabatan (Umum)</span>
                    </label>
                    
                    <div style="border-top: 1px solid #E2E8F0; margin: 0.25rem 0;"></div>
                    
                    <!-- Individual Positions -->
                    @foreach(['Direktur', 'General Manager', 'Manager', 'Ast. Manager', 'Supervisor', 'Karu', 'Karu ke atas', 'Staf', 'Operator', 'Non-Staf / Pelaksana'] as $pos)
                        <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.6rem; border-radius: 6px; font-size: 0.8rem; color: #334155; cursor: pointer; transition: background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <input type="checkbox" name="applies_to_positions[]" value="{{ $pos }}" class="create-pos-survey-cb" onchange="onPosItemChangedSurvey('create')" checked style="width: 14px; height: 14px; cursor: pointer;">
                            <span>{{ $pos }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.6rem;">
                <button type="button" onclick="closeModal('createQuestionModal')" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #64748B; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.6rem 1.15rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Tambahkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Question in Survey -->
<div id="editQuestionModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 1rem;">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">
                Edit Butir Pertanyaan
            </h3>
            <button onclick="closeModal('editQuestionModal')" style="background: none; border: none; font-size: 1.25rem; color: #94A3B8; cursor: pointer;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="editQuestionForm" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr 1.5fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Bagian (Section) *</label>
                    <select name="section" id="editQSec" onchange="updateQuestionNumberDropdown('edit', window.currentEditingQuestionId)" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                        @if($isCustomerSat)
                            <option value="A">Bagian A (Kepuasan Produk & Layanan)</option>
                            <option value="B">Bagian B (Saran & Masukan Pelanggan)</option>
                            <option value="C">Bagian C (Pertanyaan Tambahan)</option>
                        @elseif($isBudaya)
                            <option value="B">Bagian B (Dimensi Budaya Kerja & AKHLAK)</option>
                            <option value="C">Bagian C (Pertanyaan Umum & Evaluasi)</option>
                            <option value="A">Bagian A (Identitas Responden)</option>
                        @else
                            <option value="B">Bagian B (Dimensi Inti)</option>
                            <option value="C">Bagian C (Pertanyaan Umum)</option>
                            <option value="A">Bagian A (Identitas Responden)</option>
                        @endif
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Nomor Soal *</label>
                    <select name="question_number" id="editQNum" required style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 700;">
                        <!-- Dynamically populated with disabled taken numbers -->
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Dimensi Indikator</label>
                    <select name="dimension_id" id="editQDim" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                        <option value="">-- Pilih Dimensi --</option>
                        @foreach($allDimensions as $dim)
                            <option value="{{ $dim->id }}">
                                {{ $dim->category ? '[' . $dim->category->name . '] ' : '' }}{{ $dim->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Indikator / Judul Pertanyaan</label>
                <select id="editQIndSelect" onchange="onSurveyIndicatorSelectChanged('edit')" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                    <option value="">-- Pilih Indikator --</option>
                    @foreach($allSurveyIndicators as $ind)
                        <option value="{{ $ind }}">{{ $ind }}</option>
                    @endforeach
                    <option value="__custom__">✏️ [+] Tulis Indikator Baru...</option>
                </select>
                <input type="text" name="indicator_title" id="editQIndCustom" placeholder="Ketik nama indikator baru..." style="display: none; width: 100%; margin-top: 0.35rem; padding: 0.6rem 0.75rem; border: 1px solid #93C5FD; border-radius: 8px; font-size: 0.85rem; background: #EFF6FF;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Teks Pertanyaan *</label>
                <textarea name="question_text" id="editQTextSurvey" required rows="3" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; font-family: inherit; resize: vertical;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Tipe Pertanyaan *</label>
                    <select name="question_type" id="editQTypeSurvey" onchange="toggleSurveyTypeFields('edit')" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF;">
                        <option value="dual_rating">Dual Rating (Harapan & Kenyataan)</option>
                        <option value="single_rating">Single Rating (Skala Nilai)</option>
                        <option value="multiple_choice">Pilihan Ganda (MCQ)</option>
                        <option value="essay">Uraian / Deskriptif</option>
                    </select>
                </div>
                <div id="editScaleBlockSurvey">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Skala Rating Kuesioner</label>
                    <div style="padding: 0.55rem 0.75rem; background: #F1F5F9; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.825rem; font-weight: 700; color: #1E293B; display: flex; align-items: center; gap: 0.4rem;">
                        <i class="bi bi-star-fill" style="color: #F59E0B;"></i>
                        <span>Skala 1 - {{ $survey->rating_scale ?? 4 }} ({{ $survey->ratingScale?->name ?? 'Standar' }})</span>
                    </div>
                    <input type="hidden" name="rating_scale" value="{{ $survey->rating_scale ?? 4 }}">
                </div>
            </div>

            <div id="editReasonContainerSurvey" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 0.85rem; margin-bottom: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.825rem; font-weight: 700; color: var(--color-navy-primary); cursor: pointer;">
                    <input type="checkbox" name="require_reason_on_low_score" id="editQReasonSurvey" value="1" style="width: 16px; height: 16px;">
                    <span id="editReasonLabelSurvey">Aktifkan Logic Kolom Alasan / Deskriptif</span>
                </label>
                <p id="editReasonHelpSurvey" style="margin: 0.35rem 0 0 1.6rem; font-size: 0.75rem; color: #64748B;">
                    Default: Nonaktif (Opsional). Jika diaktifkan, responden dapat memberikan uraian/alasan deskriptif.
                </p>
            </div>

            <!-- MCQ Separate Option Inputs Container -->
            <div id="editOptionsBlockSurvey" style="display: none; margin-bottom: 1rem; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem; border-radius: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div>
                        <label style="display: block; font-size: 0.825rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">
                            Kolom Pilihan Jawaban (MCQ)
                        </label>
                        <span style="font-size: 0.75rem; color: #64748B;">Isi setiap opsi pada masing-masing kolom terpisah berikut:</span>
                    </div>
                    <button type="button" onclick="addOptionRowSurvey('edit')" class="btn-add-option">
                        <i class="bi bi-plus-lg"></i> Tambah Kolom Opsi
                    </button>
                </div>
                
                <div id="editOptionsListSurvey">
                    <!-- Dynamic option rows -->
                </div>
            </div>

            <!-- Target Responden: Status Pegawai & Jenis Kelamin (Baris 1: 2 Kolom) -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.85rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        Khusus Status Pegawai
                    </label>
                    <select name="applies_to_employment_status" id="editQEmpSurvey" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF;">
                        <option value="">Semua Status Pegawai (Umum)</option>
                        <option value="Tetap">Pegawai Tetap</option>
                        <option value="Kontrak">Pegawai Kontrak</option>
                        <option value="Outsourcing">Outsourcing</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        Khusus Jenis Kelamin
                    </label>
                    <select name="applies_to_gender" id="editQGenderSurvey" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF;">
                        <option value="">Semua Gender (Umum)</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            <!-- Multi-select Checkbox Dropdown for Jabatan in Edit Modal (Baris 2: Bawahnya) -->
            <div style="position: relative; margin-bottom: 1.25rem;" id="editPosSurveyDropdownWrapper">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                    Khusus Jabatan (Checkbox)
                </label>
                <button type="button" id="editPosSurveyDropdownBtn" onclick="togglePosDropdownSurvey('edit', event)" class="pos-dropdown-btn">
                    <span id="editPosSurveyLabel" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Semua Jabatan (Umum)</span>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem; color: #64748B;"></i>
                </button>

                <div id="editPosSurveyDropdownMenu" class="pos-dropdown-menu">
                    <!-- Select All Option -->
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.45rem 0.6rem; border-radius: 6px; background: #F1F5F9; font-size: 0.8rem; font-weight: 800; color: var(--color-navy-primary); cursor: pointer; margin-bottom: 0.25rem;">
                        <input type="checkbox" id="editPosSurveyAll" value="__all__" onchange="toggleSelectAllPosSurvey('edit')" checked style="width: 15px; height: 15px; cursor: pointer;">
                        <span>Semua Jabatan (Umum)</span>
                    </label>
                    
                    <div style="border-top: 1px solid #E2E8F0; margin: 0.25rem 0;"></div>
                    
                    <!-- Individual Positions -->
                    @foreach(['Direktur', 'General Manager', 'Manager', 'Ast. Manager', 'Supervisor', 'Karu', 'Karu ke atas', 'Staf', 'Operator', 'Non-Staf / Pelaksana'] as $pos)
                        <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.6rem; border-radius: 6px; font-size: 0.8rem; color: #334155; cursor: pointer; transition: background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <input type="checkbox" name="applies_to_positions[]" value="{{ $pos }}" class="edit-pos-survey-cb" onchange="onPosItemChangedSurvey('edit')" checked style="width: 14px; height: 14px; cursor: pointer;">
                            <span>{{ $pos }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.6rem;">
                <button type="button" onclick="closeModal('editQuestionModal')" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #64748B; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.6rem 1.15rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    const categorySectionsConfig = {
        'Customer Satisfaction Survey': [
            { value: 'auto', label: '⚡ Sesuai Template Asal (Otomatis: Bagian A & B)' },
            { value: 'A', label: 'Bagian A (Kepuasan Produk & Layanan)' },
            { value: 'B', label: 'Bagian B (Saran & Masukan Pelanggan)' },
            { value: 'C', label: 'Bagian C (Pertanyaan Tambahan)' },
        ],
        'Survey Budaya Kerja': [
            { value: 'auto', label: '⚡ Sesuai Template Asal (Otomatis: Bagian B & C)' },
            { value: 'B', label: 'Bagian B (Dimensi Budaya Kerja & AKHLAK)' },
            { value: 'C', label: 'Bagian C (Pertanyaan Umum & Evaluasi)' },
            { value: 'A', label: 'Bagian A (Identitas Responden)' },
        ],
        'Employee Engagement Survey': [
            { value: 'auto', label: '⚡ Sesuai Template Asal (Otomatis: Bagian B & C)' },
            { value: 'B', label: 'Bagian B (Dimensi Inti)' },
            { value: 'C', label: 'Bagian C (Pertanyaan Umum & Masukan)' },
            { value: 'A', label: 'Bagian A (Identitas Responden)' },
        ],
        'default': [
            { value: 'auto', label: '⚡ Sesuai Template Asal (Otomatis)' },
            { value: 'B', label: 'Bagian B (Dimensi Inti)' },
            { value: 'C', label: 'Bagian C (Pertanyaan Umum)' },
            { value: 'A', label: 'Bagian A' },
        ]
    };

    function updateImportTargetSections(catName) {
        const targetSelect = document.getElementById('importTargetSection');
        if (!targetSelect) return;

        let sections = categorySectionsConfig['default'];
        for (const key in categorySectionsConfig) {
            if (catName && catName.toLowerCase().includes(key.toLowerCase())) {
                sections = categorySectionsConfig[key];
                break;
            }
        }

        const prevVal = targetSelect.value || 'auto';
        targetSelect.innerHTML = '';
        sections.forEach(sec => {
            const opt = document.createElement('option');
            opt.value = sec.value;
            opt.textContent = sec.label;
            if (sec.value === prevVal) {
                opt.selected = true;
            }
            targetSelect.appendChild(opt);
        });

        if (!sections.some(s => s.value === prevVal)) {
            targetSelect.value = 'auto';
        }
    }

    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
        if (modalId === 'importBankModal') {
            filterImportBankList();
        } else if (modalId === 'createQuestionModal') {
            updateQuestionNumberDropdown('create');
            const indSelect = document.getElementById('createQIndSelect');
            const indCustom = document.getElementById('createQIndCustom');
            if (indSelect) indSelect.value = '';
            if (indCustom) {
                indCustom.style.display = 'none';
                indCustom.value = '';
            }
            initDefaultOptionsSurvey('create');
            toggleSurveyTypeFields('create');
        }
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function filterImportBankList() {
        const catSelect = document.getElementById('importCategoryFilter');
        const catFilter = catSelect ? catSelect.value : '';
        const selectedOption = catSelect && catSelect.selectedIndex >= 0 ? catSelect.options[catSelect.selectedIndex] : null;
        
        let catName = '';
        if (selectedOption && catFilter) {
            catName = selectedOption.getAttribute('data-name') || selectedOption.textContent.trim();
        } else {
            catName = @json($survey->categoryModel?->name ?? $survey->category);
        }

        // Dynamically update the Target Bagian options for this category
        updateImportTargetSections(catName);

        const items = document.querySelectorAll('.bank-template-item');
        let visibleCount = 0;

        items.forEach(item => {
            const itemCatId = item.getAttribute('data-category-id');
            if (!catFilter || itemCatId == catFilter) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
                const chk = item.querySelector('.bank-checkbox');
                if (chk) chk.checked = false;
            }
        });

        const emptyNotice = document.getElementById('bankEmptyFiltered');
        if (emptyNotice) {
            emptyNotice.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        const btn = document.getElementById('selectAllBankBtn');
        if (btn) btn.textContent = 'Pilih Semua';
    }

    document.addEventListener('DOMContentLoaded', function() {
        filterImportBankList();
    });

    function toggleSelectAllBank(btn) {
        const visibleCheckboxes = Array.from(document.querySelectorAll('.bank-template-item'))
            .filter(item => item.style.display !== 'none')
            .map(item => item.querySelector('.bank-checkbox'))
            .filter(Boolean);

        const allChecked = visibleCheckboxes.every(cb => cb.checked);
        visibleCheckboxes.forEach(cb => cb.checked = !allChecked);
        btn.textContent = allChecked ? 'Pilih Semua' : 'Batal Pilih Semua';
    }

    // ==========================================
    // Dynamic MCQ Options Helpers (Survey Builder)
    // ==========================================
    function getLetterLabelSurvey(index) {
        const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        return index < letters.length ? `Opsi ${letters[index]}` : `Opsi ${index + 1}`;
    }

    function reindexOptionsSurvey(mode) {
        const container = document.getElementById(mode === 'create' ? 'createOptionsListSurvey' : 'editOptionsListSurvey');
        if (!container) return;
        const rows = container.querySelectorAll('.option-input-row');
        rows.forEach((row, idx) => {
            const label = row.querySelector('.option-badge-label');
            if (label) label.textContent = getLetterLabelSurvey(idx);
        });
    }

    function addOptionRowSurvey(mode, value = '') {
        const container = document.getElementById(mode === 'create' ? 'createOptionsListSurvey' : 'editOptionsListSurvey');
        if (!container) return;
        const index = container.querySelectorAll('.option-input-row').length;

        const row = document.createElement('div');
        row.className = 'option-input-row';
        row.innerHTML = `
            <span class="option-badge-label">${getLetterLabelSurvey(index)}</span>
            <input type="text" name="options[]" value="${escapeHtml(value)}" placeholder="Tuliskan pilihan jawaban..." style="flex: 1; padding: 0.55rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem;">
            <button type="button" class="btn-remove-option" onclick="removeOptionRowSurvey(this, '${mode}')" title="Hapus Opsi">
                <i class="bi bi-trash-fill"></i>
            </button>
        `;
        container.appendChild(row);
    }

    function removeOptionRowSurvey(btn, mode) {
        const container = document.getElementById(mode === 'create' ? 'createOptionsListSurvey' : 'editOptionsListSurvey');
        if (!container) return;
        const rows = container.querySelectorAll('.option-input-row');
        if (rows.length <= 2) {
            alert('Pertanyaan Pilihan Ganda minimal harus memiliki 2 kolom opsi jawaban.');
            return;
        }
        btn.closest('.option-input-row').remove();
        reindexOptionsSurvey(mode);
    }

    function initDefaultOptionsSurvey(mode, existingOptions = null) {
        const container = document.getElementById(mode === 'create' ? 'createOptionsListSurvey' : 'editOptionsListSurvey');
        if (!container) return;
        container.innerHTML = '';

        if (existingOptions && Array.isArray(existingOptions) && existingOptions.length > 0) {
            existingOptions.forEach(optVal => addOptionRowSurvey(mode, optVal));
        } else {
            addOptionRowSurvey(mode, '');
            addOptionRowSurvey(mode, '');
            addOptionRowSurvey(mode, '');
            addOptionRowSurvey(mode, '');
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function toggleSurveyTypeFields(mode) {
        const typeSelect = document.getElementById(mode === 'create' ? 'createQTypeSurvey' : 'editQTypeSurvey');
        const optBlock = document.getElementById(mode === 'create' ? 'createOptionsBlockSurvey' : 'editOptionsBlockSurvey');
        const scaleBlock = document.getElementById(mode === 'create' ? 'createScaleBlockSurvey' : 'editScaleBlockSurvey');
        const reasonContainer = document.getElementById(mode === 'create' ? 'createReasonContainerSurvey' : 'editReasonContainerSurvey');
        const reasonLabel = document.getElementById(mode === 'create' ? 'createReasonLabelSurvey' : 'editReasonLabelSurvey');
        const reasonHelp = document.getElementById(mode === 'create' ? 'createReasonHelpSurvey' : 'editReasonHelpSurvey');

        if (typeSelect.value === 'multiple_choice') {
            optBlock.style.display = 'block';
            if (scaleBlock) scaleBlock.style.display = 'none';
            if (reasonContainer) reasonContainer.style.display = 'block';
            if (reasonLabel) reasonLabel.textContent = 'Aktifkan Kolom Deskriptif / Alasan Tambahan untuk MCQ (Opsional)';
            if (reasonHelp) reasonHelp.textContent = 'Default: Nonaktif. Jika dicentang, responden dapat mengisi catatan/alasan opsional setelah memilih opsi.';
        } else if (typeSelect.value === 'essay') {
            optBlock.style.display = 'none';
            if (scaleBlock) scaleBlock.style.display = 'none';
            if (reasonContainer) reasonContainer.style.display = 'none';
        } else {
            optBlock.style.display = 'none';
            if (scaleBlock) scaleBlock.style.display = 'block';
            if (reasonContainer) reasonContainer.style.display = 'block';
            if (reasonLabel) reasonLabel.textContent = 'Aktifkan Logic Kolom Alasan Wajib Diisi Jika Skor ≤ 2';
            if (reasonHelp) reasonHelp.textContent = 'Default: Nonaktif. Jika dicentang, kolom alasan wajib diisi saat responden memberi skor rendah.';
        }
    }

    const existingQuestionsData = @json($questions);
    window.currentEditingQuestionId = null;

    function onSurveyIndicatorSelectChanged(mode) {
        const select = document.getElementById(mode === 'create' ? 'createQIndSelect' : 'editQIndSelect');
        const custom = document.getElementById(mode === 'create' ? 'createQIndCustom' : 'editQIndCustom');
        
        if (select.value === '__custom__') {
            custom.style.display = 'block';
            custom.required = false;
            custom.value = '';
            custom.focus();
        } else {
            custom.style.display = 'none';
            custom.value = select.value;
        }
    }

    function updateQuestionNumberDropdown(mode, currentQId = null) {
        const secSelect = document.getElementById(mode === 'create' ? 'createQSec' : 'editQSec');
        const numSelect = document.getElementById(mode === 'create' ? 'createQNum' : 'editQNum');
        if (!secSelect || !numSelect) return;

        const selectedSec = secSelect.value;
        
        // Find all taken question numbers in this section (exclude current question if editing)
        const takenNumbers = existingQuestionsData
            .filter(q => q.section === selectedSec && (currentQId === null || q.id != currentQId))
            .map(q => parseInt(q.question_number))
            .filter(n => !isNaN(n));
        
        // Find current question's own number if editing
        let currentOwnNumber = null;
        if (currentQId !== null) {
            const currentQ = existingQuestionsData.find(q => q.id == currentQId);
            if (currentQ && currentQ.section === selectedSec) {
                currentOwnNumber = parseInt(currentQ.question_number);
            }
        }

        const maxTaken = takenNumbers.length > 0 ? Math.max(...takenNumbers) : 0;
        const maxLimit = Math.max(maxTaken + 5, 25);

        numSelect.innerHTML = '';
        
        let firstAvailable = null;
        for (let i = 1; i <= maxLimit; i++) {
            const isTaken = takenNumbers.includes(i);
            const isOwn = (currentOwnNumber === i);
            const opt = document.createElement('option');
            opt.value = i;
            
            if (isOwn) {
                opt.textContent = `No. ${i} (Nomor Saat Ini)`;
                opt.disabled = false;
                opt.style.fontWeight = 'bold';
                opt.style.color = '#2563EB';
            } else if (isTaken) {
                opt.textContent = `No. ${i} (Sudah Terisi)`;
                opt.disabled = true;
                opt.style.color = '#94A3B8';
                opt.style.background = '#F8FAFC';
            } else {
                opt.textContent = `No. ${i} (Tersedia)`;
                if (firstAvailable === null) firstAvailable = i;
            }
            
            numSelect.appendChild(opt);
        }

        // Selection priority: currentOwnNumber -> previous selected if valid -> firstAvailable -> 1
        if (currentOwnNumber !== null) {
            numSelect.value = currentOwnNumber;
        } else if (firstAvailable !== null) {
            numSelect.value = firstAvailable;
        } else {
            numSelect.value = 1;
        }
    }

    function editSurveyQuestion(q) {
        window.currentEditingQuestionId = q.id;
        document.getElementById('editQuestionForm').action = `/admin/surveys/{{ $survey->id }}/questions/${q.id}`;
        document.getElementById('editQSec').value = q.section || 'B';
        document.getElementById('editQDim').value = q.dimension_id || '';
        
        // Update Question Number Dropdown with current number enabled and others disabled
        updateQuestionNumberDropdown('edit', q.id);
        document.getElementById('editQNum').value = q.question_number || 1;

        // Handle Indicator Select & Custom
        const indSelect = document.getElementById('editQIndSelect');
        const indCustom = document.getElementById('editQIndCustom');
        const indTitle = (q.indicator_title || '').trim();
        
        const optionExists = Array.from(indSelect.options).some(opt => opt.value === indTitle);
        if (optionExists && indTitle !== '') {
            indSelect.value = indTitle;
            indCustom.style.display = 'none';
            indCustom.value = indTitle;
        } else if (indTitle !== '') {
            indSelect.value = '__custom__';
            indCustom.style.display = 'block';
            indCustom.value = indTitle;
        } else {
            indSelect.value = '';
            indCustom.style.display = 'none';
            indCustom.value = '';
        }

        document.getElementById('editQTextSurvey').value = q.question_text || '';
        document.getElementById('editQTypeSurvey').value = q.question_type || 'dual_rating';
        let empVal = q.applies_to_employment_status || '';
        if (empVal.toLowerCase().includes('outsourcing')) empVal = 'Outsourcing';
        else if (empVal.toLowerCase().includes('kontrak')) empVal = 'Kontrak';
        else if (empVal.toLowerCase().includes('tetap')) empVal = 'Tetap';
        document.getElementById('editQEmpSurvey').value = empVal;
        document.getElementById('editQGenderSurvey').value = q.applies_to_gender || '';

        // Set Positions Checkboxes for Edit Modal
        const editCheckboxes = document.querySelectorAll('.edit-pos-survey-cb');
        const editAllCheckbox = document.getElementById('editPosSurveyAll');
        
        if (!q.applies_to_positions || q.applies_to_positions.trim() === '' || q.applies_to_positions.toLowerCase().includes('semua jabatan')) {
            if (editAllCheckbox) editAllCheckbox.checked = true;
            editCheckboxes.forEach(cb => cb.checked = true);
        } else {
            const selectedList = q.applies_to_positions.split(',').map(s => s.trim().toLowerCase());
            let matchCount = 0;
            editCheckboxes.forEach(cb => {
                const isMatch = selectedList.includes(cb.value.toLowerCase());
                cb.checked = isMatch;
                if (isMatch) matchCount++;
            });
            if (editAllCheckbox) editAllCheckbox.checked = (matchCount === editCheckboxes.length);
        }
        updatePosLabelSurvey('edit');

        // Populate MCQ Option Rows
        initDefaultOptionsSurvey('edit', q.options_json);

        toggleSurveyTypeFields('edit');
        openModal('editQuestionModal');
    }

    // ==========================================
    // Multi-Select Checkbox Dropdown for Positions (Survey Question Builder)
    // ==========================================
    function togglePosDropdownSurvey(mode, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        const menu = document.getElementById(mode === 'create' ? 'createPosSurveyDropdownMenu' : 'editPosSurveyDropdownMenu');
        const otherMenu = document.getElementById(mode === 'create' ? 'editPosSurveyDropdownMenu' : 'createPosSurveyDropdownMenu');
        if (otherMenu) otherMenu.style.display = 'none';
        if (menu) menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    function toggleSelectAllPosSurvey(mode) {
        const allCheckbox = document.getElementById(mode === 'create' ? 'createPosSurveyAll' : 'editPosSurveyAll');
        const checkboxes = document.querySelectorAll(mode === 'create' ? '.create-pos-survey-cb' : '.edit-pos-survey-cb');
        
        if (allCheckbox) {
            checkboxes.forEach(cb => {
                cb.checked = allCheckbox.checked;
            });
        }
        
        updatePosLabelSurvey(mode);
    }

    function onPosItemChangedSurvey(mode) {
        const allCheckbox = document.getElementById(mode === 'create' ? 'createPosSurveyAll' : 'editPosSurveyAll');
        const checkboxes = Array.from(document.querySelectorAll(mode === 'create' ? '.create-pos-survey-cb' : '.edit-pos-survey-cb'));
        
        const checkedCount = checkboxes.filter(cb => cb.checked).length;
        const totalCount = checkboxes.length;
        
        if (allCheckbox) {
            allCheckbox.checked = (checkedCount === totalCount);
        }
        
        updatePosLabelSurvey(mode);
    }

    function updatePosLabelSurvey(mode) {
        const allCheckbox = document.getElementById(mode === 'create' ? 'createPosSurveyAll' : 'editPosSurveyAll');
        const checkboxes = Array.from(document.querySelectorAll(mode === 'create' ? '.create-pos-survey-cb' : '.edit-pos-survey-cb'));
        const label = document.getElementById(mode === 'create' ? 'createPosSurveyLabel' : 'editPosSurveyLabel');
        if (!label) return;

        const checkedItems = checkboxes.filter(cb => cb.checked).map(cb => cb.value);
        
        if ((allCheckbox && allCheckbox.checked) || checkedItems.length === checkboxes.length) {
            label.textContent = "Semua Jabatan (Umum)";
        } else if (checkedItems.length === 0) {
            label.textContent = "Pilih Jabatan...";
        } else if (checkedItems.length <= 2) {
            label.textContent = checkedItems.join(", ");
        } else {
            label.textContent = `${checkedItems.length} Jabatan Terpilih`;
        }
    }

    // Close pos dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        const createMenu = document.getElementById('createPosSurveyDropdownMenu');
        const createBtn = document.getElementById('createPosSurveyDropdownBtn');
        if (createMenu && createMenu.style.display === 'block') {
            if (!createMenu.contains(e.target) && !createBtn.contains(e.target)) {
                createMenu.style.display = 'none';
            }
        }

        const editMenu = document.getElementById('editPosSurveyDropdownMenu');
        const editBtn = document.getElementById('editPosSurveyDropdownBtn');
        if (editMenu && editMenu.style.display === 'block') {
            if (!editMenu.contains(e.target) && !editBtn.contains(e.target)) {
                editMenu.style.display = 'none';
            }
        }
    });

    window.onclick = function(event) {
        if (event.target.id === 'importBankModal') closeModal('importBankModal');
        if (event.target.id === 'createQuestionModal') closeModal('createQuestionModal');
        if (event.target.id === 'editQuestionModal') closeModal('editQuestionModal');
    }
</script>
@endsection
@endsection
