@extends('layouts.app')

@section('title', 'Bank Template Soal - Adiprima Survey Center')

@section('styles')
<style>
    .bank-header {
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .filter-form-wrapper {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
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

    .table-responsive-box {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
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
        padding: 0.5rem 0.95rem;
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

    @media (max-width: 768px) {
        .bank-header {
            flex-direction: column;
            align-items: stretch;
        }

        .bank-header button {
            justify-content: center;
            width: 100%;
        }

        .filter-form-wrapper {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-form-wrapper > div,
        .filter-form-wrapper > button,
        .filter-form-wrapper > a {
            width: 100%;
        }

        .modal-box {
            padding: 1.25rem;
            border-radius: 12px;
        }
    }
</style>
@endsection

@section('content')
<div class="bank-header">
    <div>
        <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--color-navy-primary); letter-spacing: -0.02em;">
            Bank Template Soal
        </h1>
        <p style="color: #64748B; font-size: 0.9rem; margin-top: 0.25rem;">
            Daftar master pertanyaan yang dikelompokkan berdasarkan <b>Kategori Survei</b>, <b>Dimensi</b>, dan <b>Indikator</b>.
        </p>
    </div>
    <button onclick="openCreateTemplateModal()" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.75rem 1.25rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: background 0.2s ease; box-shadow: 0 4px 10px rgba(12, 43, 100, 0.2);">
        <i class="bi bi-plus-circle-fill"></i> Tambah Template Pertanyaan
    </button>
</div>

<!-- Filters Bar -->
<div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <form method="GET" action="{{ route('admin.question-bank.index') }}" class="filter-form-wrapper">
        <div style="flex: 1; min-width: 200px;">
            <div style="position: relative;">
                <i class="bi bi-search" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: #94A3B8;"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari teks pertanyaan atau indikator..." style="width: 100%; padding: 0.6rem 0.85rem 0.6rem 2.4rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem;">
            </div>
        </div>

        <!-- Category Filter -->
        <div style="min-width: 180px;">
            <select name="category_id" id="filterCategoryId" onchange="updateFilterDimensions()" style="width: 100%; padding: 0.6rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                <option value="">Semua Kategori Survei</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryFilter == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Dependent Dimension Filter -->
        <div style="min-width: 180px;">
            <select name="dimension_id" id="filterDimensionId" style="width: 100%; padding: 0.6rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF;">
                <option value="">Semua Dimensi</option>
                @foreach($dimensions as $dim)
                    <option value="{{ $dim->id }}" data-category-id="{{ $dim->survey_category_id }}" {{ $dimensionFilter == $dim->id ? 'selected' : '' }}>
                        {{ $dim->category ? '[' . $dim->category->name . '] ' : '' }}{{ $dim->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="min-width: 150px;">
            <select name="question_type" style="width: 100%; padding: 0.6rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF;">
                <option value="">Semua Tipe Soal</option>
                <option value="dual_rating" {{ $typeFilter == 'dual_rating' ? 'selected' : '' }}>Dual Rating</option>
                <option value="single_rating" {{ $typeFilter == 'single_rating' ? 'selected' : '' }}>Single Rating</option>
                <option value="essay" {{ $typeFilter == 'essay' ? 'selected' : '' }}>Uraian / Deskriptif</option>
                <option value="multiple_choice" {{ $typeFilter == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
            </select>
        </div>

        <button type="submit" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
            <i class="bi bi-funnel-fill"></i> Filter
        </button>

        @if($search || $categoryFilter || $dimensionFilter || $typeFilter)
            <a href="{{ route('admin.question-bank.index') }}" style="color: #DC2626; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
                <i class="bi bi-x-circle"></i> Reset
            </a>
        @endif
    </form>
</div>

<!-- Question Templates Table -->
<div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div class="table-responsive-box" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table style="width: 100%; min-width: 1080px; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1.5px solid var(--color-border); color: #475569; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">
                    <th style="padding: 0.95rem 1rem; width: 50px; text-align: center;">No</th>
                    <th style="padding: 0.95rem 1.15rem; width: 210px; min-width: 200px;">Kategori & Dimensi</th>
                    <th style="padding: 0.95rem 1.15rem; min-width: 340px;">Indikator & Teks Pertanyaan</th>
                    <th style="padding: 0.95rem 1.15rem; width: 170px; min-width: 160px;">Tipe & Opsi</th>
                    <th style="padding: 0.95rem 1.15rem; width: 230px; min-width: 220px;">Target Responden</th>
                    <th style="padding: 0.95rem 1rem; width: 100px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $index => $tpl)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;">
                    <td style="padding: 1rem 0.75rem; color: #64748B; font-weight: 600; text-align: center;">
                        {{ $templates->firstItem() + $index }}
                    </td>
                    <td style="padding: 1rem 1.15rem;">
                        @if($tpl->dimension)
                            <div style="display: flex; flex-direction: column; gap: 0.35rem; align-items: flex-start;">
                                <div style="display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap;">
                                    <span style="font-size: 0.7rem; font-weight: 800; color: #2563EB; background: #EFF6FF; padding: 0.15rem 0.5rem; border-radius: 6px; border: 1px solid #DBEAFE;">
                                        <i class="bi {{ $tpl->dimension->category?->icon ?: 'bi-tag-fill' }}"></i> {{ $tpl->dimension->category?->name ?? 'Umum' }}
                                    </span>
                                    <span style="font-size: 0.7rem; font-weight: 800; color: #B45309; background: #FEF3C7; padding: 0.15rem 0.5rem; border-radius: 6px; border: 1px solid #FDE68A;">
                                        Bagian {{ $tpl->section ?: 'B' }}
                                    </span>
                                </div>
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.775rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 6px; background: {{ $tpl->dimension->color ?? '#2563EB' }}15; color: {{ $tpl->dimension->color ?? '#2563EB' }};">
                                    <span style="width: 7px; height: 7px; border-radius: 50%; background: {{ $tpl->dimension->color ?? '#2563EB' }};"></span>
                                    {{ $tpl->dimension->name }}
                                </span>
                            </div>
                        @else
                            <span style="color: #94A3B8; font-size: 0.775rem;">(Tanpa Dimensi)</span>
                        @endif
                    </td>
                    <td style="padding: 1rem 1.15rem;">
                        @if($tpl->indicator_title)
                            <div style="display: inline-block; font-weight: 800; color: var(--color-navy-primary); font-size: 0.775rem; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 0.2rem 0.55rem; border-radius: 6px; margin-bottom: 0.45rem;">
                                <i class="bi bi-bookmark-fill" style="color: #2563EB; font-size: 0.725rem;"></i> {{ $tpl->indicator_title }}
                            </div>
                        @endif
                        <div style="color: #1E293B; line-height: 1.5; font-size: 0.85rem;">
                            {{ $tpl->question_text }}
                        </div>
                    </td>
                    <td style="padding: 1rem 1.15rem;">
                        @if($tpl->question_type === 'dual_rating')
                            <span style="background: #EFF6FF; color: #2563EB; font-weight: 700; font-size: 0.725rem; padding: 0.2rem 0.5rem; border-radius: 6px; display: inline-block; margin-bottom: 0.25rem;">
                                Dual Rating (1-{{ $tpl->rating_scale }})
                            </span>
                        @elseif($tpl->question_type === 'single_rating')
                            <span style="background: #F0FDF4; color: #16A34A; font-weight: 700; font-size: 0.725rem; padding: 0.2rem 0.5rem; border-radius: 6px; display: inline-block;">
                                Skala 1-{{ $tpl->rating_scale }}
                            </span>
                        @elseif($tpl->question_type === 'essay')
                            <span style="background: #FEF3C7; color: #D97706; font-weight: 700; font-size: 0.725rem; padding: 0.2rem 0.5rem; border-radius: 6px; display: inline-block;">
                                Uraian / Teks
                            </span>
                        @elseif($tpl->question_type === 'multiple_choice')
                            <div>
                                <span style="background: #F3E8FF; color: #7C3AED; font-weight: 700; font-size: 0.725rem; padding: 0.2rem 0.5rem; border-radius: 6px; display: inline-block; margin-bottom: 0.25rem;">
                                    Pilihan Ganda
                                </span>
                                @if(!empty($tpl->options_json) && is_array($tpl->options_json))
                                    <div style="font-size: 0.725rem; color: #64748B; font-weight: 600;">
                                        {{ count($tpl->options_json) }} Kolom Pilihan
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($tpl->require_reason_on_low_score)
                            <div style="margin-top: 0.35rem;">
                                <span style="color: #059669; font-size: 0.7rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem; background: #ECFDF5; padding: 0.15rem 0.4rem; border-radius: 4px;">
                                    <i class="bi bi-check-circle-fill"></i> Logic Alasan
                                </span>
                            </div>
                        @endif
                    </td>
                    <td style="padding: 1rem 1.15rem;">
                        <div style="display: flex; flex-direction: column; gap: 0.35rem; font-size: 0.75rem;">
                            <!-- Status -->
                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #1E293B; background: #F1F5F9; padding: 0.15rem 0.45rem; border-radius: 4px; font-weight: 600; width: fit-content;">
                                <i class="bi bi-person-badge" style="color: #64748B;"></i> {{ $tpl->applies_to_employment_status ?: 'Semua Status' }}
                            </span>

                            <!-- Gender -->
                            @if($tpl->applies_to_gender === 'Laki-laki')
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #1D4ED8; background: #EFF6FF; padding: 0.15rem 0.45rem; border-radius: 4px; font-weight: 700; width: fit-content;">
                                    <i class="bi bi-gender-male"></i> Laki-laki
                                </span>
                            @elseif($tpl->applies_to_gender === 'Perempuan')
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #BE185D; background: #FDF2F8; padding: 0.15rem 0.45rem; border-radius: 4px; font-weight: 700; width: fit-content;">
                                    <i class="bi bi-gender-female"></i> Perempuan
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #64748B; background: #F8FAFC; padding: 0.15rem 0.45rem; border-radius: 4px; font-weight: 500; width: fit-content;">
                                    <i class="bi bi-gender-ambiguous"></i> Semua Gender
                                </span>
                            @endif

                            <!-- Jabatan -->
                            <span style="display: inline-flex; align-items: flex-start; gap: 0.35rem; color: #475569; font-size: 0.725rem; line-height: 1.35; margin-top: 0.1rem;">
                                <i class="bi bi-briefcase" style="color: #94A3B8; margin-top: 0.15rem; flex-shrink: 0;"></i>
                                <span>{{ $tpl->applies_to_positions ?: 'Semua Jabatan' }}</span>
                            </span>
                        </div>
                    </td>
                    <td style="padding: 1rem 0.75rem; text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button type="button" onclick="editTemplateById({{ $tpl->id }})" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #334155; padding: 0.4rem 0.6rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.15s ease;" title="Edit Soal" onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='#F1F5F9'">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('admin.question-bank.destroy', $tpl->id) }}" method="POST" onsubmit="return confirm('Hapus template soal ini dari Bank Soal?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #FEE2E2; border: 1px solid #FCA5A5; color: #DC2626; padding: 0.4rem 0.6rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.15s ease;" title="Hapus Soal" onmouseover="this.style.background='#FECACA'" onmouseout="this.style.background='#FEE2E2'">
                                <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 3rem 1rem; text-align: center; color: #64748B;">
                        <i class="bi bi-collection" style="font-size: 2rem; color: #94A3B8; margin-bottom: 0.5rem; display: block;"></i>
                        <p style="font-weight: 600; margin: 0;">Belum ada butir template pertanyaan yang sesuai filter.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
    @if($templates->hasPages())
    <div style="padding: 1rem 1.25rem; border-top: 1px solid var(--color-border); background: #F8FAFC;">
        {{ $templates->links() }}
    </div>
    @endif
</div>

<!-- Modal Create Template -->
<div id="createTemplateModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 1rem;">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">
                Tambah Template Bank Soal
            </h3>
            <button onclick="closeModal('createTemplateModal')" style="background: none; border: none; font-size: 1.25rem; color: #94A3B8; cursor: pointer;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.question-bank.store') }}" method="POST">
            @csrf
            
            <!-- Step 1: Select Survey Category -->
            <div style="margin-bottom: 1rem; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 0.85rem; border-radius: 8px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: var(--color-navy-primary); margin-bottom: 0.35rem;">
                    1. Pilih Kategori Survei *
                </label>
                <select id="createCategoryId" onchange="onCategoryChanged('create')" required style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                    <option value="">-- Pilih Kategori Survei Dulu --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" {{ $categoryFilter == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <small style="color: #64748B; font-size: 0.75rem; margin-top: 0.25rem; display: block;">
                    Dimensi dan daftar indikator di bawah akan otomatis disesuaikan dengan kategori survei ini.
                </small>
            </div>

            <!-- Step 2: Dimension, Indicator & Section -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        2. Dimensi Indikator *
                    </label>
                    <select name="dimension_id" id="createDimensionId" required style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                        <option value="">-- Pilih Kategori Di Atas Dulu --</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        3. Indikator / Judul *
                    </label>
                    <select id="createIndicatorSelect" onchange="onIndicatorSelectChanged('create')" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                        <option value="">-- Pilih Indikator --</option>
                    </select>
                    <!-- Fallback Custom Indicator Input -->
                    <input type="text" name="indicator_title" id="createIndicatorCustom" placeholder="Ketik nama indikator..." style="display: none; width: 100%; margin-top: 0.35rem; padding: 0.55rem 0.75rem; border: 1px solid #93C5FD; border-radius: 8px; font-size: 0.85rem; background: #EFF6FF;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        4. Target Bagian (Section) *
                    </label>
                    <select name="section" id="createSection" required style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                        <option value="B">Bagian B (Dimensi Inti / Nilai)</option>
                        <option value="C">Bagian C (Pertanyaan Umum & Saran)</option>
                        <option value="A">Bagian A (Kepuasan / Responden)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Teks Pertanyaan *</label>
                <textarea name="question_text" required rows="3" placeholder="Tuliskan butir pertanyaan secara jelas..." style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; font-family: inherit; resize: vertical;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Tipe Pertanyaan *</label>
                    <select name="question_type" id="createQType" onchange="toggleTypeFields('create')" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                        <option value="dual_rating">Dual Rating (Harapan & Kenyataan)</option>
                        <option value="single_rating">Single Rating (Skala Nilai)</option>
                        <option value="multiple_choice">Pilihan Ganda (MCQ)</option>
                        <option value="essay">Uraian / Deskriptif</option>
                    </select>
                </div>
                <div id="createScaleBlock">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Skala Rating</label>
                    <select name="rating_scale" id="createScale" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF;">
                        <option value="4">Skala 1 - 4</option>
                        <option value="5">Skala 1 - 5</option>
                        <option value="10">Skala 1 - 10</option>
                    </select>
                </div>
            </div>

            <!-- MCQ Separate Option Inputs Container -->
            <div id="createOptionsBlock" style="display: none; margin-bottom: 1rem; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem; border-radius: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div>
                        <label style="display: block; font-size: 0.825rem; font-weight: 800; color: var(--color-navy-primary);">
                            Kolom Pilihan Jawaban (MCQ)
                        </label>
                        <span style="font-size: 0.75rem; color: #64748B;">Isi setiap opsi pada masing-masing kolom terpisah berikut:</span>
                    </div>
                    <button type="button" onclick="addOptionRow('create')" class="btn-add-option">
                        <i class="bi bi-plus-lg"></i> Tambah Kolom Opsi
                    </button>
                </div>
                
                <div id="createOptionsList">
                    <!-- Dynamic option rows will be placed here -->
                </div>
            </div>

            <!-- Conditional Logic Box -->
            <div id="createReasonContainer" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 0.85rem; margin-bottom: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.825rem; font-weight: 700; color: var(--color-navy-primary); cursor: pointer;">
                    <input type="checkbox" name="require_reason_on_low_score" id="createRequireReason" value="1" style="width: 16px; height: 16px;">
                    <span id="createReasonLabel">Aktifkan Logic Kolom Alasan / Deskriptif</span>
                </label>
                <p id="createReasonHelp" style="margin: 0.35rem 0 0 1.6rem; font-size: 0.75rem; color: #64748B;">
                    Default: Nonaktif (Opsional). Jika diaktifkan, responden dapat memberikan uraian/alasan deskriptif.
                </p>
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
                        <option value="">Semua Jenis Kelamin (Umum)</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>
            
            <!-- Multi-select Checkbox Dropdown for Jabatan (Baris 2: Bawahnya) -->
            <div style="position: relative; margin-bottom: 1.25rem;" id="createPosDropdownWrapper">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                    Khusus Jabatan (Checkbox)
                </label>
                <button type="button" id="createPosDropdownBtn" onclick="togglePosDropdown('create', event)" class="pos-dropdown-btn">
                    <span id="createPosLabel" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Semua Jabatan (Umum)</span>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem; color: #64748B;"></i>
                </button>

                <div id="createPosDropdownMenu" class="pos-dropdown-menu">
                    <!-- Select All Option -->
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.45rem 0.6rem; border-radius: 6px; background: #F1F5F9; font-size: 0.8rem; font-weight: 800; color: var(--color-navy-primary); cursor: pointer; margin-bottom: 0.25rem;">
                        <input type="checkbox" id="createPosAll" value="__all__" onchange="toggleSelectAllPos('create')" checked style="width: 15px; height: 15px; cursor: pointer;">
                        <span>Semua Jabatan (Umum)</span>
                    </label>
                    
                    <div style="border-top: 1px solid #E2E8F0; margin: 0.25rem 0;"></div>
                    
                    <!-- Individual Positions -->
                    @foreach(['Direktur', 'General Manager', 'Manager', 'Ast. Manager', 'Supervisor', 'Karu', 'Karu ke atas', 'Staf', 'Operator', 'Non-Staf / Pelaksana'] as $pos)
                        <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.6rem; border-radius: 6px; font-size: 0.8rem; color: #334155; cursor: pointer; transition: background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <input type="checkbox" name="applies_to_positions[]" value="{{ $pos }}" class="create-pos-cb" onchange="onPosItemChanged('create')" checked style="width: 14px; height: 14px; cursor: pointer;">
                            <span>{{ $pos }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.6rem;">
                <button type="button" onclick="closeModal('createTemplateModal')" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #64748B; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.6rem 1.15rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Simpan ke Bank
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Template -->
<div id="editTemplateModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 1rem;">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">
                Edit Template Bank Soal
            </h3>
            <button onclick="closeModal('editTemplateModal')" style="background: none; border: none; font-size: 1.25rem; color: #94A3B8; cursor: pointer;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="editTemplateForm" method="POST">
            @csrf
            @method('PUT')

            <!-- Step 1: Select Survey Category -->
            <div style="margin-bottom: 1rem; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 0.85rem; border-radius: 8px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: var(--color-navy-primary); margin-bottom: 0.35rem;">
                    1. Pilih Kategori Survei *
                </label>
                <select id="editCategoryId" onchange="onCategoryChanged('edit')" required style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                    <option value="">-- Pilih Kategori Survei --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Step 2: Dimension, Indicator & Section -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        2. Dimensi Indikator *
                    </label>
                    <select name="dimension_id" id="editDimId" required style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                        <option value="">-- Pilih Dimensi --</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        3. Indikator / Judul *
                    </label>
                    <select id="editIndicatorSelect" onchange="onIndicatorSelectChanged('edit')" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                        <option value="">-- Pilih Indikator --</option>
                    </select>
                    <!-- Fallback Custom Indicator Input -->
                    <input type="text" name="indicator_title" id="editIndicatorCustom" placeholder="Ketik nama indikator..." style="display: none; width: 100%; margin-top: 0.35rem; padding: 0.55rem 0.75rem; border: 1px solid #93C5FD; border-radius: 8px; font-size: 0.85rem; background: #EFF6FF;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        4. Target Bagian (Section) *
                    </label>
                    <select name="section" id="editSection" required style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                        <option value="B">Bagian B (Dimensi Inti / Nilai)</option>
                        <option value="C">Bagian C (Pertanyaan Umum & Saran)</option>
                        <option value="A">Bagian A (Kepuasan / Responden)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Teks Pertanyaan *</label>
                <textarea name="question_text" id="editQText" required rows="3" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; font-family: inherit; resize: vertical;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Tipe Pertanyaan *</label>
                    <select name="question_type" id="editQType" onchange="toggleTypeFields('edit')" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF; font-weight: 600;">
                        <option value="dual_rating">Dual Rating (Harapan & Kenyataan)</option>
                        <option value="single_rating">Single Rating (Skala Nilai)</option>
                        <option value="multiple_choice">Pilihan Ganda (MCQ)</option>
                        <option value="essay">Uraian / Deskriptif</option>
                    </select>
                </div>
                <div id="editScaleBlock">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Skala Rating</label>
                    <select name="rating_scale" id="editScale" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF;">
                        <option value="4">Skala 1 - 4</option>
                        <option value="5">Skala 1 - 5</option>
                        <option value="10">Skala 1 - 10</option>
                    </select>
                </div>
            </div>

            <!-- MCQ Separate Option Inputs Container for Edit Modal -->
            <div id="editOptionsBlock" style="display: none; margin-bottom: 1rem; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem; border-radius: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div>
                        <label style="display: block; font-size: 0.825rem; font-weight: 800; color: var(--color-navy-primary);">
                            Kolom Pilihan Jawaban (MCQ)
                        </label>
                        <span style="font-size: 0.75rem; color: #64748B;">Edit opsi pada masing-masing kolom terpisah:</span>
                    </div>
                    <button type="button" onclick="addOptionRow('edit')" class="btn-add-option">
                        <i class="bi bi-plus-lg"></i> Tambah Kolom Opsi
                    </button>
                </div>
                
                <div id="editOptionsList">
                    <!-- Dynamic option rows for edit will be placed here -->
                </div>
            </div>

            <!-- Conditional Logic Box -->
            <div id="editReasonContainer" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 0.85rem; margin-bottom: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.825rem; font-weight: 700; color: var(--color-navy-primary); cursor: pointer;">
                    <input type="checkbox" name="require_reason_on_low_score" id="editRequireReason" value="1" style="width: 16px; height: 16px;">
                    <span id="editReasonLabel">Aktifkan Logic Kolom Alasan / Deskriptif</span>
                </label>
                <p id="editReasonHelp" style="margin: 0.35rem 0 0 1.6rem; font-size: 0.75rem; color: #64748B;">
                    Default: Nonaktif (Opsional). Jika diaktifkan, responden dapat memberikan uraian/alasan deskriptif.
                </p>
            </div>

            <!-- Target Responden: Status Pegawai & Jenis Kelamin (Baris 1: 2 Kolom) -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.85rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        Khusus Status Pegawai
                    </label>
                    <select name="applies_to_employment_status" id="editEmpStatus" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF;">
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
                    <select name="applies_to_gender" id="editGender" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; background: #FFFFFF;">
                        <option value="">Semua Jenis Kelamin (Umum)</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>
            
            <!-- Multi-select Checkbox Dropdown for Jabatan in Edit Modal (Baris 2: Bawahnya) -->
            <div style="position: relative; margin-bottom: 1.25rem;" id="editPosDropdownWrapper">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                    Khusus Jabatan (Checkbox)
                </label>
                <button type="button" id="editPosDropdownBtn" onclick="togglePosDropdown('edit', event)" class="pos-dropdown-btn">
                    <span id="editPosLabel" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Semua Jabatan (Umum)</span>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem; color: #64748B;"></i>
                </button>

                <div id="editPosDropdownMenu" class="pos-dropdown-menu">
                    <!-- Select All Option -->
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.45rem 0.6rem; border-radius: 6px; background: #F1F5F9; font-size: 0.8rem; font-weight: 800; color: var(--color-navy-primary); cursor: pointer; margin-bottom: 0.25rem;">
                        <input type="checkbox" id="editPosAll" value="__all__" onchange="toggleSelectAllPos('edit')" checked style="width: 15px; height: 15px; cursor: pointer;">
                        <span>Semua Jabatan (Umum)</span>
                    </label>
                    
                    <div style="border-top: 1px solid #E2E8F0; margin: 0.25rem 0;"></div>
                    
                    <!-- Individual Positions -->
                    @foreach(['Direktur', 'General Manager', 'Manager', 'Ast. Manager', 'Supervisor', 'Karu', 'Karu ke atas', 'Staf', 'Operator', 'Non-Staf / Pelaksana'] as $pos)
                        <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.6rem; border-radius: 6px; font-size: 0.8rem; color: #334155; cursor: pointer; transition: background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <input type="checkbox" name="applies_to_positions[]" value="{{ $pos }}" class="edit-pos-cb" onchange="onPosItemChanged('edit')" checked style="width: 14px; height: 14px; cursor: pointer;">
                            <span>{{ $pos }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.6rem;">
                <button type="button" onclick="closeModal('editTemplateModal')" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #64748B; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.6rem 1.15rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // All Categories with Dimensions
    const categoriesData = @json($categories);
    const allDimensions = @json($dimensions);
    const templatesMap = @json($templates->getCollection()->keyBy('id'));

    // Standard Indicators per Category
    const standardIndicatorsMap = {
        'employee-engagement-survey': [
            'Gaji & Kompensasi Finansial',
            'Fasilitas & Lingkungan Kerja',
            'Rasa Aman & Kenyamanan Bekerja',
            'Pengakuan & Apresiasi Prestasi',
            'Tanggung Jawab & Otonomi Individu',
            'Peluang Karir & Pelatihan Pegawai',
            'Kerjasama Tim & Solidaritas',
            'Komunikasi Internal Antar Unit',
            'Supervisi & Kepemimpinan Atasan',
            'Kebanggaan & Loyalitas Organisasi',
            'Retensi Pegawai (Keinginan Bertahan)',
            'Kepuasan Menyeluruh (Overall Satisfaction)'
        ],
        'survey-budaya-kerja': [
            'Amanah (Integritas, Jujur & Tanggung Jawab)',
            'Kompeten (Belajar & Mengembangkan Kapabilitas)',
            'Harmonis (Saling Peduli & Menghargai Perbedaan)',
            'Loyal (Berdedikasi & Mengutamakan Kepentingan Perusahaan)',
            'Adaptif (Inovasi & Antusias Menghadapi Perubahan)',
            'Kolaboratif (Membangun Kerjasama Sinergis)',
            'Keselamatan & Kesehatan Kerja (K3 / 5R)',
            'Kepatuhan SOP & Standar Mutu',
            'Etika Kerja & Profesionalisme'
        ],
        'customer-satisfaction-survey': [
            'Kualitas & Konsistensi Produk Kertas',
            'Ketepatan Waktu Pengiriman (On-Time Delivery)',
            'Respon & Keramahan Pelayanan Sales/CS',
            'Kecepatan Penanganan Komplain/Klaim',
            'Dukungan Teknis (Technical Support / After Sales)',
            'Kesesuaian Harga & Nilai Produk',
            'Kemudahan Administrasi & Faktur'
        ],
        'default': [
            'Kualitas Kerja',
            'Komunikasi & Koordinasi',
            'Kedisiplinan & Integritas',
            'Efisiensi & Produktivitas',
            'Kepuasan Umum'
        ]
    };

    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        const createMenu = document.getElementById('createPosDropdownMenu');
        const editMenu = document.getElementById('editPosDropdownMenu');
        if (createMenu) createMenu.style.display = 'none';
        if (editMenu) editMenu.style.display = 'none';
    }

    function openCreateTemplateModal() {
        const catSelect = document.getElementById('createCategoryId');
        if (!catSelect.value && catSelect.options.length > 1) {
            catSelect.selectedIndex = 1;
        }
        onCategoryChanged('create');
        
        // Reset positions to all checked
        const allPos = document.getElementById('createPosAll');
        if (allPos) {
            allPos.checked = true;
            toggleSelectAllPos('create');
        }

        // Reset QType & Scale & Reason
        const qTypeSelect = document.getElementById('createQType');
        if (qTypeSelect) qTypeSelect.value = 'dual_rating';
        const scaleSelect = document.getElementById('createScale');
        if (scaleSelect) scaleSelect.value = '4';
        const reasonCb = document.getElementById('createRequireReason');
        if (reasonCb) reasonCb.checked = false;

        initDefaultOptions('create');
        toggleTypeFields('create');
        openModal('createTemplateModal');
    }

    function onCategoryChanged(mode) {
        const catSelect = document.getElementById(mode === 'create' ? 'createCategoryId' : 'editCategoryId');
        const dimSelect = document.getElementById(mode === 'create' ? 'createDimensionId' : 'editDimId');
        const indSelect = document.getElementById(mode === 'create' ? 'createIndicatorSelect' : 'editIndicatorSelect');
        const indCustom = document.getElementById(mode === 'create' ? 'createIndicatorCustom' : 'editIndicatorCustom');
        const categoryId = catSelect.value;

        indCustom.style.display = 'none';
        indCustom.value = '';

        dimSelect.innerHTML = '';
        indSelect.innerHTML = '';

        if (!categoryId) {
            dimSelect.innerHTML = '<option value="">-- Pilih Kategori Di Atas Dulu --</option>';
            indSelect.innerHTML = '<option value="">-- Pilih Kategori Di Atas Dulu --</option>';
            return;
        }

        const category = categoriesData.find(c => c.id == categoryId);
        const slug = category ? category.slug : '';

        // 1. Populate Dimensions
        const dimensions = category && category.dimensions ? category.dimensions : allDimensions.filter(d => d.survey_category_id == categoryId);
        if (dimensions && dimensions.length > 0) {
            dimSelect.innerHTML = '<option value="">-- Pilih Dimensi --</option>';
            dimensions.forEach(dim => {
                const opt = document.createElement('option');
                opt.value = dim.id;
                opt.textContent = `${dim.name} (${dim.code || 'DIM'})`;
                dimSelect.appendChild(opt);
            });
        } else {
            dimSelect.innerHTML = '<option value="">(Belum ada dimensi untuk kategori ini)</option>';
        }

        // 2. Populate Indicators based on category
        const indicatorList = standardIndicatorsMap[slug] || standardIndicatorsMap['default'];
        indSelect.innerHTML = '<option value="">-- Pilih Indikator Standar --</option>';
        indicatorList.forEach(ind => {
            const opt = document.createElement('option');
            opt.value = ind;
            opt.textContent = ind;
            indSelect.appendChild(opt);
        });

        // Add custom indicator option
        const customOpt = document.createElement('option');
        customOpt.value = '__custom__';
        customOpt.textContent = '✏️ [+] Tulis Indikator Kustom Lainnya...';
        indSelect.appendChild(customOpt);
    }

    function onIndicatorSelectChanged(mode) {
        const indSelect = document.getElementById(mode === 'create' ? 'createIndicatorSelect' : 'editIndicatorSelect');
        const indCustom = document.getElementById(mode === 'create' ? 'createIndicatorCustom' : 'editIndicatorCustom');

        if (indSelect.value === '__custom__') {
            indCustom.style.display = 'block';
            indCustom.required = true;
            indCustom.focus();
        } else {
            indCustom.style.display = 'none';
            indCustom.required = false;
            indCustom.value = indSelect.value;
        }
    }

    function updateFilterDimensions() {
        const catId = document.getElementById('filterCategoryId').value;
        const dimSelect = document.getElementById('filterDimensionId');
        const options = dimSelect.querySelectorAll('option');

        options.forEach(opt => {
            if (!opt.value) {
                opt.style.display = 'block';
                return;
            }
            const optCatId = opt.getAttribute('data-category-id');
            if (!catId || optCatId == catId) {
                opt.style.display = 'block';
            } else {
                opt.style.display = 'none';
            }
        });

        const selectedOpt = dimSelect.options[dimSelect.selectedIndex];
        if (selectedOpt && selectedOpt.style.display === 'none') {
            dimSelect.value = '';
        }
    }

    // ==========================================
    // Multi-Select Checkbox Dropdown for Positions
    // ==========================================
    function togglePosDropdown(mode, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        const menu = document.getElementById(mode === 'create' ? 'createPosDropdownMenu' : 'editPosDropdownMenu');
        const otherMenu = document.getElementById(mode === 'create' ? 'editPosDropdownMenu' : 'createPosDropdownMenu');
        if (otherMenu) otherMenu.style.display = 'none';
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    function toggleSelectAllPos(mode) {
        const allCheckbox = document.getElementById(mode === 'create' ? 'createPosAll' : 'editPosAll');
        const checkboxes = document.querySelectorAll(mode === 'create' ? '.create-pos-cb' : '.edit-pos-cb');
        
        checkboxes.forEach(cb => {
            cb.checked = allCheckbox.checked;
        });
        
        updatePosLabel(mode);
    }

    function onPosItemChanged(mode) {
        const allCheckbox = document.getElementById(mode === 'create' ? 'createPosAll' : 'editPosAll');
        const checkboxes = Array.from(document.querySelectorAll(mode === 'create' ? '.create-pos-cb' : '.edit-pos-cb'));
        
        const checkedCount = checkboxes.filter(cb => cb.checked).length;
        const totalCount = checkboxes.length;
        
        if (checkedCount === totalCount) {
            allCheckbox.checked = true;
        } else {
            allCheckbox.checked = false;
        }
        
        updatePosLabel(mode);
    }

    function updatePosLabel(mode) {
        const allCheckbox = document.getElementById(mode === 'create' ? 'createPosAll' : 'editPosAll');
        const checkboxes = Array.from(document.querySelectorAll(mode === 'create' ? '.create-pos-cb' : '.edit-pos-cb'));
        const label = document.getElementById(mode === 'create' ? 'createPosLabel' : 'editPosLabel');
        
        const checkedItems = checkboxes.filter(cb => cb.checked).map(cb => cb.value);
        
        if (allCheckbox.checked || checkedItems.length === checkboxes.length) {
            label.textContent = "Semua Jabatan (Umum)";
        } else if (checkedItems.length === 0) {
            label.textContent = "Pilih Jabatan...";
        } else if (checkedItems.length <= 2) {
            label.textContent = checkedItems.join(", ");
        } else {
            label.textContent = `${checkedItems.length} Jabatan Terpilih`;
        }
    }

    // ==========================================
    // MCQ Option Rows Builder
    // ==========================================
    function getLetterLabel(index) {
        const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        return index < letters.length ? `Opsi ${letters[index]}` : `Opsi ${index + 1}`;
    }

    function reindexOptions(mode) {
        const container = document.getElementById(mode === 'create' ? 'createOptionsList' : 'editOptionsList');
        const rows = container.querySelectorAll('.option-input-row');
        rows.forEach((row, idx) => {
            const label = row.querySelector('.option-badge-label');
            if (label) label.textContent = getLetterLabel(idx);
        });
    }

    function addOptionRow(mode, value = '') {
        const container = document.getElementById(mode === 'create' ? 'createOptionsList' : 'editOptionsList');
        const index = container.querySelectorAll('.option-input-row').length;

        const row = document.createElement('div');
        row.className = 'option-input-row';
        row.innerHTML = `
            <span class="option-badge-label">${getLetterLabel(index)}</span>
            <input type="text" name="options[]" value="${escapeHtml(value)}" placeholder="Tuliskan pilihan jawaban..." style="flex: 1; padding: 0.55rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem;">
            <button type="button" class="btn-remove-option" onclick="removeOptionRow(this, '${mode}')" title="Hapus Opsi">
                <i class="bi bi-trash-fill"></i>
            </button>
        `;
        container.appendChild(row);
    }

    function removeOptionRow(btn, mode) {
        const container = document.getElementById(mode === 'create' ? 'createOptionsList' : 'editOptionsList');
        const rows = container.querySelectorAll('.option-input-row');
        if (rows.length <= 2) {
            alert('Pertanyaan Pilihan Ganda minimal harus memiliki 2 kolom opsi jawaban.');
            return;
        }
        btn.closest('.option-input-row').remove();
        reindexOptions(mode);
    }

    function initDefaultOptions(mode, existingOptions = null) {
        const container = document.getElementById(mode === 'create' ? 'createOptionsList' : 'editOptionsList');
        container.innerHTML = '';

        if (existingOptions && Array.isArray(existingOptions) && existingOptions.length > 0) {
            existingOptions.forEach(optVal => addOptionRow(mode, optVal));
        } else {
            addOptionRow(mode, '');
            addOptionRow(mode, '');
            addOptionRow(mode, '');
            addOptionRow(mode, '');
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function toggleTypeFields(mode) {
        const typeSelect = document.getElementById(mode === 'create' ? 'createQType' : 'editQType');
        const optBlock = document.getElementById(mode === 'create' ? 'createOptionsBlock' : 'editOptionsBlock');
        const optContainer = document.getElementById(mode === 'create' ? 'createOptionsList' : 'editOptionsList');
        const optInputs = optContainer ? optContainer.querySelectorAll('input[name="options[]"]') : [];
        const scaleBlock = document.getElementById(mode === 'create' ? 'createScaleBlock' : 'editScaleBlock');
        const reasonContainer = document.getElementById(mode === 'create' ? 'createReasonContainer' : 'editReasonContainer');
        const reasonLabel = document.getElementById(mode === 'create' ? 'createReasonLabel' : 'editReasonLabel');
        const reasonHelp = document.getElementById(mode === 'create' ? 'createReasonHelp' : 'editReasonHelp');

        if (typeSelect.value === 'multiple_choice') {
            if (optBlock) optBlock.style.display = 'block';
            optInputs.forEach(input => {
                input.disabled = false;
            });
            if (scaleBlock) scaleBlock.style.display = 'none';
            if (reasonContainer) reasonContainer.style.display = 'block';
            if (reasonLabel) reasonLabel.textContent = 'Aktifkan Kolom Deskriptif / Alasan Tambahan untuk MCQ (Opsional)';
            if (reasonHelp) reasonHelp.textContent = 'Default: Nonaktif. Jika dicentang, responden dapat mengisi catatan/alasan opsional setelah memilih opsi.';
        } else if (typeSelect.value === 'essay') {
            if (optBlock) optBlock.style.display = 'none';
            optInputs.forEach(input => {
                input.disabled = true;
            });
            if (scaleBlock) scaleBlock.style.display = 'none';
            if (reasonContainer) reasonContainer.style.display = 'none';
        } else {
            if (optBlock) optBlock.style.display = 'none';
            optInputs.forEach(input => {
                input.disabled = true;
            });
            if (scaleBlock) scaleBlock.style.display = 'block';
            if (reasonContainer) reasonContainer.style.display = 'block';
            if (reasonLabel) reasonLabel.textContent = 'Aktifkan Logic Kolom Alasan Wajib Diisi Jika Skor ≤ 2';
            if (reasonHelp) reasonHelp.textContent = 'Default: Nonaktif. Jika dicentang, kolom alasan wajib diisi saat responden memberi skor rendah.';
        }
    }

    function editTemplateById(id) {
        const tpl = templatesMap[id];
        if (!tpl) {
            console.error('Template not found for id:', id);
            return;
        }
        editTemplate(tpl);
    }

    function editTemplate(tpl) {
        document.getElementById('editTemplateForm').action = `/admin/question-bank/${tpl.id}`;
        
        // Resolve category id
        let categoryId = null;
        if (tpl.dimension && tpl.dimension.survey_category_id) {
            categoryId = tpl.dimension.survey_category_id;
        } else if (tpl.dimension_id) {
            const foundDim = allDimensions.find(d => d.id == tpl.dimension_id);
            if (foundDim) categoryId = foundDim.survey_category_id;
        }

        document.getElementById('editCategoryId').value = categoryId || '';
        onCategoryChanged('edit');

        // Select Dimension
        if (tpl.dimension_id) {
            document.getElementById('editDimId').value = tpl.dimension_id;
        }

        // Set Indicator Title
        const indSelect = document.getElementById('editIndicatorSelect');
        const indCustom = document.getElementById('editIndicatorCustom');
        const indicatorValue = tpl.indicator_title || '';

        let matched = false;
        for (let i = 0; i < indSelect.options.length; i++) {
            if (indSelect.options[i].value === indicatorValue && indicatorValue !== '') {
                indSelect.selectedIndex = i;
                matched = true;
                break;
            }
        }

        if (matched) {
            indCustom.style.display = 'none';
            indCustom.value = indicatorValue;
        } else if (indicatorValue) {
            indSelect.value = '__custom__';
            indCustom.style.display = 'block';
            indCustom.value = indicatorValue;
        } else {
            indSelect.value = '';
            indCustom.style.display = 'none';
            indCustom.value = '';
        }

        document.getElementById('editSection').value = tpl.section || 'B';
        document.getElementById('editQText').value = tpl.question_text || '';
        document.getElementById('editQType').value = tpl.question_type || 'dual_rating';
        document.getElementById('editScale').value = tpl.rating_scale || 4;
        document.getElementById('editRequireReason').checked = !!tpl.require_reason_on_low_score;
        let empVal = tpl.applies_to_employment_status || '';
        if (empVal.toLowerCase().includes('outsourcing')) empVal = 'Outsourcing';
        else if (empVal.toLowerCase().includes('kontrak')) empVal = 'Kontrak';
        else if (empVal.toLowerCase().includes('tetap')) empVal = 'Tetap';
        document.getElementById('editEmpStatus').value = empVal;
        document.getElementById('editGender').value = tpl.applies_to_gender || '';
        
        // Set Positions Checkboxes for Edit Modal
        const editCheckboxes = document.querySelectorAll('.edit-pos-cb');
        const editAllCheckbox = document.getElementById('editPosAll');
        
        if (!tpl.applies_to_positions || tpl.applies_to_positions.trim() === '' || tpl.applies_to_positions.toLowerCase().includes('semua jabatan')) {
            editAllCheckbox.checked = true;
            editCheckboxes.forEach(cb => cb.checked = true);
        } else {
            const selectedList = tpl.applies_to_positions.split(',').map(s => s.trim().toLowerCase());
            let matchCount = 0;
            editCheckboxes.forEach(cb => {
                const isMatch = selectedList.includes(cb.value.toLowerCase());
                cb.checked = isMatch;
                if (isMatch) matchCount++;
            });
            editAllCheckbox.checked = (matchCount === editCheckboxes.length);
        }
        updatePosLabel('edit');

        // Populate MCQ Options
        if (tpl.options_json && Array.isArray(tpl.options_json)) {
            initDefaultOptions('edit', tpl.options_json);
        } else {
            initDefaultOptions('edit', null);
        }

        toggleTypeFields('edit');
        openModal('editTemplateModal');
    }

    // Close dropdown menu when clicking outside
    window.addEventListener('click', function(e) {
        const createWrapper = document.getElementById('createPosDropdownWrapper');
        const editWrapper = document.getElementById('editPosDropdownWrapper');
        
        if (createWrapper && !createWrapper.contains(e.target)) {
            const menu = document.getElementById('createPosDropdownMenu');
            if (menu) menu.style.display = 'none';
        }
        if (editWrapper && !editWrapper.contains(e.target)) {
            const menu = document.getElementById('editPosDropdownMenu');
            if (menu) menu.style.display = 'none';
        }
    });

    // Form submit handlers
    document.addEventListener('DOMContentLoaded', function() {
        ['createTemplateModal', 'editTemplateModal'].forEach(modalId => {
            const form = document.querySelector(`#${modalId} form`);
            if (form) {
                form.addEventListener('submit', function(e) {
                    const mode = modalId === 'createTemplateModal' ? 'create' : 'edit';
                    const typeSelect = document.getElementById(mode === 'create' ? 'createQType' : 'editQType');
                    const indSelect = document.getElementById(mode === 'create' ? 'createIndicatorSelect' : 'editIndicatorSelect');
                    const indCustom = document.getElementById(mode === 'create' ? 'createIndicatorCustom' : 'editIndicatorCustom');
                    
                    if (indSelect.value !== '__custom__') {
                        indCustom.value = indSelect.value || '';
                    }

                    // For MCQ, validate that at least 2 non-empty options exist
                    if (typeSelect && typeSelect.value === 'multiple_choice') {
                        const optContainer = document.getElementById(mode === 'create' ? 'createOptionsList' : 'editOptionsList');
                        const optInputs = optContainer ? Array.from(optContainer.querySelectorAll('input[name="options[]"]')) : [];
                        const filled = optInputs.filter(inp => inp.value.trim() !== '');
                        if (filled.length < 2) {
                            e.preventDefault();
                            alert('Pertanyaan Pilihan Ganda (MCQ) wajib memiliki minimal 2 pilihan jawaban yang terisi.');
                            return false;
                        }
                    }
                });
            }
        });

        updateFilterDimensions();
    });

    window.onclick = function(event) {
        if (event.target.id === 'createTemplateModal') closeModal('createTemplateModal');
        if (event.target.id === 'editTemplateModal') closeModal('editTemplateModal');
    }
</script>
@endsection
