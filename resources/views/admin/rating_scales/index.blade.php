@extends('layouts.app')

@section('title', 'Master Skala Rating - Adiprima Survey Center')

@section('styles')
<style>
    .scale-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .scale-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.35rem;
        margin-bottom: 2rem;
    }

    .scale-card {
        background: #FFFFFF;
        border: 1px solid var(--color-border);
        border-radius: 14px;
        padding: 1.35rem 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .scale-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 8px 18px -2px rgba(12, 43, 100, 0.08);
        transform: translateY(-2px);
    }

    .scale-top-stripe {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--color-navy-primary);
    }

    .scale-top-stripe.is-default {
        background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);
    }

    .point-item {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.45rem 0.75rem;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        font-size: 0.825rem;
        transition: background 0.15s ease;
    }

    .point-item:hover {
        background: #F1F5F9;
    }

    .point-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--color-navy-primary);
        color: #FFFFFF;
        font-weight: 800;
        font-size: 0.75rem;
        flex-shrink: 0;
    }

    .point-label {
        font-weight: 700;
        color: #1E293B;
    }

    /* Modal Backdrop */
    .modal-backdrop-custom {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-box-custom {
        background: #FFFFFF;
        width: 95%;
        max-width: 600px;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        max-height: 90vh;
        overflow-y: auto;
    }

    .preset-chip {
        cursor: pointer;
        border: 1.5px solid #CBD5E1;
        background: #FFFFFF;
        padding: 0.45rem 0.85rem;
        border-radius: 8px;
        font-size: 0.825rem;
        font-weight: 700;
        color: #334155;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-family: inherit;
    }

    .preset-chip:hover {
        border-color: var(--color-navy-primary);
        background: #F8FAFC;
        color: var(--color-navy-primary);
    }

    .preset-chip.active {
        border-color: var(--color-navy-primary);
        background: var(--color-navy-primary);
        color: #FFFFFF;
    }

    .label-row-input {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin-bottom: 0.5rem;
    }

    .label-row-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--color-navy-primary);
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .template-quick-btn {
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        color: #1E40AF;
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        font-size: 0.775rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: all 0.15s ease;
        font-family: inherit;
    }

    .template-quick-btn:hover {
        background: #DBEAFE;
        border-color: #93C5FD;
        color: #1D4ED8;
    }

    @media (max-width: 768px) {
        .scale-header {
            flex-direction: column;
            align-items: stretch;
        }

        .scale-header button {
            justify-content: center;
            width: 100%;
        }

        .scale-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .modal-box-custom {
            padding: 1.25rem;
            border-radius: 12px;
        }
    }
</style>
@endsection

@section('content')
<div>
    <!-- Header -->
    <div class="scale-header">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                <a href="{{ route('admin.surveys.index') }}" style="color: #64748B; text-decoration: none; font-size: 0.85rem; font-weight: 600;">
                    <i class="bi bi-arrow-left"></i> Kelola Survei
                </a>
                <span style="color: #CBD5E1;">/</span>
                <span style="color: var(--color-navy-primary); font-size: 0.85rem; font-weight: 700;">Master Skala</span>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--color-navy-primary); letter-spacing: -0.02em; margin: 0 0 0.25rem 0;">
                Master Skala Rating
            </h1>
            <p style="color: #64748B; font-size: 0.9rem; margin: 0; max-width: 760px; line-height: 1.45;">
                Konfigurasi rentang nilai skala (1-4, 1-5, atau 1-10) beserta label deskripsi tiap poin. Skala rating ini dipilih per kuesioner dan berlaku tetap untuk seluruh butir pertanyaan rating di kuesioner tersebut.
            </p>
        </div>
        <div>
            <button onclick="openCreateScaleModal()" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.75rem 1.25rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; box-shadow: 0 4px 10px rgba(12, 43, 100, 0.2); transition: background 0.2s ease;">
                <i class="bi bi-plus-circle-fill"></i> Tambah Master Skala
            </button>
        </div>
    </div>

    <!-- Scale Cards Grid -->
    <div class="scale-grid">
        @forelse($ratingScales as $scale)
        <div class="scale-card">
            <div class="scale-top-stripe {{ $scale->is_default ? 'is-default' : '' }}"></div>

            <div>
                <!-- Top Header & Badges -->
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <div>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.4rem; align-items: center; margin-bottom: 0.45rem;">
                            <span style="font-size: 0.725rem; font-weight: 800; padding: 0.15rem 0.55rem; border-radius: 6px; background: #EFF6FF; color: var(--color-navy-primary); border: 1px solid #BFDBFE;">
                                <i class="bi bi-sliders"></i> Skala {{ $scale->min_score }} s/d {{ $scale->max_score }} ({{ $scale->max_score - $scale->min_score + 1 }} Poin)
                            </span>
                            @if($scale->is_default)
                                <span style="font-size: 0.725rem; font-weight: 800; padding: 0.15rem 0.55rem; border-radius: 6px; background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A;">
                                    <i class="bi bi-star-fill" style="color: #D97706;"></i> Default Sistem
                                </span>
                            @endif
                        </div>
                        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-navy-primary); margin: 0; line-height: 1.3;">
                            {{ $scale->name }}
                        </h3>
                    </div>
                </div>

                @if($scale->description)
                    <p style="font-size: 0.85rem; color: #64748B; margin-bottom: 1rem; line-height: 1.45;">
                        {{ $scale->description }}
                    </p>
                @endif

                <!-- Option Points List -->
                <div style="margin-top: 0.75rem; margin-bottom: 1.25rem;">
                    <div style="font-size: 0.725rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; color: #64748B; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                        <i class="bi bi-list-check" style="color: var(--color-navy-primary);"></i> Rincian Tiap Nilai Skala:
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                        @if(is_array($scale->options_json))
                            @foreach($scale->options_json as $opt)
                                <div class="point-item">
                                    <span class="point-number" style="background: {{ $opt['score'] == 1 ? '#DC2626' : ($opt['score'] == $scale->max_score ? '#0D9444' : 'var(--color-navy-primary)') }};">
                                        {{ $opt['score'] }}
                                    </span>
                                    <span class="point-label">{{ $opt['label'] ?? '-' }}</span>
                                </div>
                            @endforeach
                        @else
                            <span style="font-size: 0.825rem; color: #94A3B8;">Belum ada label</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer Meta & Actions -->
            <div style="border-top: 1px solid #F1F5F9; padding-top: 0.85rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <span style="font-size: 0.775rem; color: #64748B; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="bi bi-clipboard-check" style="color: var(--color-navy-primary);"></i> Digunakan: <strong style="color: #1E293B;">{{ $scale->surveys_count }} Kuesioner</strong>
                </span>

                <div style="display: flex; gap: 0.35rem;">
                    <button type="button" 
                            onclick='openEditScaleModal(@json($scale))'
                            style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #334155; padding: 0.35rem 0.65rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.3rem;"
                            title="Edit Master Skala">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                    @if($scale->surveys_count == 0)
                        <button type="button" 
                                onclick="openDeleteScaleModal('{{ $scale->id }}', '{{ addslashes($scale->name) }}')"
                                style="background: #FEE2E2; border: 1px solid #FCA5A5; color: #DC2626; padding: 0.35rem 0.65rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer;"
                                title="Hapus Master Skala">
                            <i class="bi bi-trash"></i>
                        </button>
                    @else
                        <button type="button" 
                                disabled
                                title="Skala ini sedang digunakan oleh kuesioner aktif dan tidak dapat dihapus."
                                style="background: #F8FAFC; border: 1px solid #E2E8F0; color: #CBD5E1; padding: 0.35rem 0.65rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: not-allowed;">
                            <i class="bi bi-trash"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; background: #FFFFFF; border: 2px dashed #CBD5E1; border-radius: 16px; padding: 3rem 1.5rem; text-align: center;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: #EFF6FF; color: var(--color-navy-primary); display: inline-flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1rem;">
                <i class="bi bi-star-half"></i>
            </div>
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-navy-primary); margin-bottom: 0.5rem;">Belum Ada Master Skala</h3>
            <p style="color: #64748B; font-size: 0.875rem; margin-bottom: 1.5rem;">Silakan tambahkan master skala rating baru untuk digunakan pada kuesioner.</p>
            <button onclick="openCreateScaleModal()" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.75rem 1.25rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 10px rgba(12, 43, 100, 0.2);">
                <i class="bi bi-plus-circle-fill"></i> Tambah Master Skala Pertama
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah Master Skala -->
<div id="createScaleModal" class="modal-backdrop-custom">
    <div class="modal-box-custom">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.85rem;">
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">Tambah Master Skala Rating</h3>
                <p style="color: #64748B; font-size: 0.8rem; margin: 0.2rem 0 0 0;">Tentukan rentang skala dan label deskripsi masing-masing nilainya.</p>
            </div>
            <button type="button" onclick="closeCreateScaleModal()" style="background: none; border: none; font-size: 1.25rem; color: #94A3B8; cursor: pointer;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.rating-scales.store') }}" method="POST" id="createScaleForm">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 1.15rem;">
                <!-- Nama Skala -->
                <div>
                    <label style="display: block; font-size: 0.825rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        Nama Skala Rating <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="text" name="name" id="create_scale_name" required 
                           placeholder="Contoh: Skala Kepuasan 1-4, Skala Likert 1-5" 
                           style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem; padding: 0.65rem 0.85rem; font-family: inherit;">
                </div>

                <!-- Pilihan Cepat Rentang Skala -->
                <div>
                    <label style="display: block; font-size: 0.825rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">
                        Pilih Rentang Skala Nilai <span style="color: #EF4444;">*</span>
                    </label>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.6rem;">
                        <button type="button" class="preset-chip active" id="chip_create_4" onclick="setPresetScale('create', 1, 4, 'Skala Kepuasan 1-4')">
                            <i class="bi bi-check-circle"></i> Skala 1 s/d 4 (4 Poin)
                        </button>
                        <button type="button" class="preset-chip" id="chip_create_5" onclick="setPresetScale('create', 1, 5, 'Skala Kepuasan 1-5')">
                            <i class="bi bi-circle"></i> Skala 1 s/d 5 (5 Poin)
                        </button>
                        <button type="button" class="preset-chip" id="chip_create_10" onclick="setPresetScale('create', 1, 10, 'Skala Penilaian 1-10')">
                            <i class="bi bi-circle"></i> Skala 1 s/d 10 (10 Poin)
                        </button>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <div>
                            <label style="font-size: 0.775rem; color: #64748B; font-weight: 600; display: block; margin-bottom: 0.25rem;">Skala Minimal</label>
                            <input type="number" name="min_score" id="create_min_score" value="1" min="1" max="1" readonly
                                   style="background: #F8FAFC; width: 100%; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; padding: 0.55rem 0.75rem; font-family: inherit; color: #64748B;">
                        </div>
                        <div>
                            <label style="font-size: 0.775rem; color: #64748B; font-weight: 600; display: block; margin-bottom: 0.25rem;">Skala Maksimal (Jumlah Poin)</label>
                            <input type="number" name="max_score" id="create_max_score" value="4" min="2" max="10" required
                                   onchange="renderScoreInputs('create')"
                                   style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; padding: 0.55rem 0.75rem; font-family: inherit;">
                        </div>
                    </div>
                </div>

                <!-- Template Teks Label Cepat -->
                <div style="background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 10px; padding: 0.75rem 0.85rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.45rem;">
                        <span style="font-size: 0.75rem; font-weight: 800; color: var(--color-navy-primary); text-transform: uppercase; letter-spacing: 0.03em;">
                            <i class="bi bi-lightning-charge-fill" style="color: #F59E0B;"></i> Terapkan Template Teks Cepat:
                        </span>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                        <button type="button" onclick="applyTextPreset('create', 'satisfaction')" class="template-quick-btn">
                            Pola Kepuasan (Sangat Tidak Puas - Sangat Puas)
                        </button>
                        <button type="button" onclick="applyTextPreset('create', 'agreement')" class="template-quick-btn">
                            Pola Persetujuan (Sangat Tidak Setuju - Sangat Setuju)
                        </button>
                        <button type="button" onclick="applyTextPreset('create', 'numbers')" class="template-quick-btn">
                            Pola Angka/Skor
                        </button>
                    </div>
                </div>

                <!-- Score Label Inputs Container -->
                <div>
                    <label style="display: block; font-size: 0.825rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem;">
                        Label Deskripsi Tiap Skala <span style="color: #EF4444;">*</span>
                    </label>
                    <div id="create_score_inputs_container" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <!-- Rendered by JS -->
                    </div>
                </div>

                <!-- Deskripsi Tambahan -->
                <div>
                    <label style="display: block; font-size: 0.825rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        Deskripsi / Catatan (Opsional)
                    </label>
                    <textarea name="description" id="create_scale_desc" rows="2" 
                              placeholder="Keterangan singkat skala ini..." 
                              style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem; padding: 0.6rem 0.85rem; font-family: inherit; resize: vertical;"></textarea>
                </div>

                <!-- Checkbox Is Default -->
                <div style="display: flex; align-items: center; gap: 0.6rem; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 0.65rem 0.85rem; border-radius: 8px;">
                    <input type="checkbox" name="is_default" value="1" id="create_is_default" style="width: 16px; height: 16px; cursor: pointer; accent-color: var(--color-navy-primary);">
                    <label for="create_is_default" style="font-size: 0.825rem; font-weight: 700; color: #334155; cursor: pointer;">
                        Jadikan skala default saat membuat kuesioner baru
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.65rem; margin-top: 1.5rem; border-top: 1px solid #E2E8F0; padding-top: 1rem;">
                <button type="button" onclick="closeCreateScaleModal()" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #64748B; padding: 0.65rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.65rem 1.35rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 10px rgba(12, 43, 100, 0.2);">
                    <i class="bi bi-check-circle-fill"></i> Simpan Master Skala
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Master Skala -->
<div id="editScaleModal" class="modal-backdrop-custom">
    <div class="modal-box-custom">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.85rem;">
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">Edit Master Skala Rating</h3>
                <p style="color: #64748B; font-size: 0.8rem; margin: 0.2rem 0 0 0;">Perbarui rentang skala dan label deskripsi masing-masing nilai.</p>
            </div>
            <button type="button" onclick="closeEditScaleModal()" style="background: none; border: none; font-size: 1.25rem; color: #94A3B8; cursor: pointer;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="" method="POST" id="editScaleForm">
            @csrf
            @method('PUT')
            <div style="display: flex; flex-direction: column; gap: 1.15rem;">
                <!-- Nama Skala -->
                <div>
                    <label style="display: block; font-size: 0.825rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        Nama Skala Rating <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="text" name="name" id="edit_scale_name" required 
                           style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem; padding: 0.65rem 0.85rem; font-family: inherit;">
                </div>

                <!-- Pilihan Cepat Rentang Skala -->
                <div>
                    <label style="display: block; font-size: 0.825rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">
                        Pilih Rentang Skala Nilai <span style="color: #EF4444;">*</span>
                    </label>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.6rem;">
                        <button type="button" class="preset-chip" id="chip_edit_4" onclick="setPresetScale('edit', 1, 4)">
                            <i class="bi bi-circle"></i> Skala 1 s/d 4 (4 Poin)
                        </button>
                        <button type="button" class="preset-chip" id="chip_edit_5" onclick="setPresetScale('edit', 1, 5)">
                            <i class="bi bi-circle"></i> Skala 1 s/d 5 (5 Poin)
                        </button>
                        <button type="button" class="preset-chip" id="chip_edit_10" onclick="setPresetScale('edit', 1, 10)">
                            <i class="bi bi-circle"></i> Skala 1 s/d 10 (10 Poin)
                        </button>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <div>
                            <label style="font-size: 0.775rem; color: #64748B; font-weight: 600; display: block; margin-bottom: 0.25rem;">Skala Minimal</label>
                            <input type="number" name="min_score" id="edit_min_score" value="1" min="1" max="1" readonly
                                   style="background: #F8FAFC; width: 100%; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; padding: 0.55rem 0.75rem; font-family: inherit; color: #64748B;">
                        </div>
                        <div>
                            <label style="font-size: 0.775rem; color: #64748B; font-weight: 600; display: block; margin-bottom: 0.25rem;">Skala Maksimal (Jumlah Poin)</label>
                            <input type="number" name="max_score" id="edit_max_score" value="4" min="2" max="10" required
                                   onchange="renderScoreInputs('edit')"
                                   style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.85rem; padding: 0.55rem 0.75rem; font-family: inherit;">
                        </div>
                    </div>
                </div>

                <!-- Template Teks Label Cepat -->
                <div style="background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 10px; padding: 0.75rem 0.85rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.45rem;">
                        <span style="font-size: 0.75rem; font-weight: 800; color: var(--color-navy-primary); text-transform: uppercase; letter-spacing: 0.03em;">
                            <i class="bi bi-lightning-charge-fill" style="color: #F59E0B;"></i> Terapkan Template Teks Cepat:
                        </span>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                        <button type="button" onclick="applyTextPreset('edit', 'satisfaction')" class="template-quick-btn">
                            Pola Kepuasan (Sangat Tidak Puas - Sangat Puas)
                        </button>
                        <button type="button" onclick="applyTextPreset('edit', 'agreement')" class="template-quick-btn">
                            Pola Persetujuan (Sangat Tidak Setuju - Sangat Setuju)
                        </button>
                        <button type="button" onclick="applyTextPreset('edit', 'numbers')" class="template-quick-btn">
                            Pola Angka/Skor
                        </button>
                    </div>
                </div>

                <!-- Score Label Inputs Container -->
                <div>
                    <label style="display: block; font-size: 0.825rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem;">
                        Label Deskripsi Tiap Skala <span style="color: #EF4444;">*</span>
                    </label>
                    <div id="edit_score_inputs_container" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <!-- Rendered by JS -->
                    </div>
                </div>

                <!-- Deskripsi Tambahan -->
                <div>
                    <label style="display: block; font-size: 0.825rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        Deskripsi / Catatan (Opsional)
                    </label>
                    <textarea name="description" id="edit_scale_desc" rows="2" 
                              style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem; padding: 0.6rem 0.85rem; font-family: inherit; resize: vertical;"></textarea>
                </div>

                <!-- Checkbox Is Default -->
                <div style="display: flex; align-items: center; gap: 0.6rem; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 0.65rem 0.85rem; border-radius: 8px;">
                    <input type="checkbox" name="is_default" value="1" id="edit_is_default" style="width: 16px; height: 16px; cursor: pointer; accent-color: var(--color-navy-primary);">
                    <label for="edit_is_default" style="font-size: 0.825rem; font-weight: 700; color: #334155; cursor: pointer;">
                        Jadikan skala default saat membuat kuesioner baru
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.65rem; margin-top: 1.5rem; border-top: 1px solid #E2E8F0; padding-top: 1rem;">
                <button type="button" onclick="closeEditScaleModal()" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #64748B; padding: 0.65rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.65rem 1.35rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 10px rgba(12, 43, 100, 0.2);">
                    <i class="bi bi-check-circle-fill"></i> Perbarui Master Skala
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete Confirmation -->
<div id="deleteScaleModal" class="modal-backdrop-custom">
    <div class="modal-box-custom" style="max-width: 440px;">
        <div style="text-align: center; margin-bottom: 1.25rem;">
            <div style="width: 52px; height: 52px; border-radius: 50%; background: #FEE2E2; color: #DC2626; display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 0.75rem;">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-navy-primary); margin: 0 0 0.35rem 0;">Hapus Master Skala?</h3>
            <p style="color: #64748B; font-size: 0.875rem; margin: 0; line-height: 1.45;">
                Anda yakin ingin menghapus skala <strong id="delete_scale_name_text" style="color: #1E293B;"></strong>?
            </p>
        </div>

        <form action="" method="POST" id="deleteScaleForm">
            @csrf
            @method('DELETE')
            <div style="display: flex; justify-content: center; gap: 0.65rem;">
                <button type="button" onclick="closeDeleteScaleModal()" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #64748B; padding: 0.65rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: #DC2626; color: #FFFFFF; padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 8px; border: none; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);">
                    <i class="bi bi-trash-fill"></i> Hapus Skala
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    const LABEL_TEMPLATES = {
        satisfaction: {
            4: ["Sangat Tidak Puas", "Tidak Puas", "Puas", "Sangat Puas"],
            5: ["Sangat Tidak Puas", "Tidak Puas", "Cukup Puas", "Puas", "Sangat Puas"],
            10: [
                "1 - Sangat Tidak Puas", "2 - Tidak Puas", "3 - Sangat Kurang", "4 - Kurang",
                "5 - Agak Cukup", "6 - Cukup Puas", "7 - Puas", "8 - Sangat Puas",
                "9 - Luar Biasa", "10 - Sangat Sempurna"
            ]
        },
        agreement: {
            4: ["Sangat Tidak Setuju", "Tidak Setuju", "Setuju", "Sangat Setuju"],
            5: ["Sangat Tidak Setuju", "Tidak Setuju", "Netral / Ragu-ragu", "Setuju", "Sangat Setuju"],
            10: [
                "1 - Sangat Tidak Setuju", "2 - Tidak Setuju", "3 - Sangat Kurang Setuju", "4 - Kurang Setuju",
                "5 - Cenderung Netral", "6 - Cenderung Setuju", "7 - Setuju", "8 - Cukup Setuju",
                "9 - Sangat Setuju", "10 - Mutlak Setuju"
            ]
        },
        numbers: {
            4: ["1 - Sangat Rendah", "2 - Rendah", "3 - Tinggi", "4 - Sangat Tinggi"],
            5: ["1 - Buruk", "2 - Kurang", "3 - Cukup", "4 - Baik", "5 - Sangat Baik"],
            10: [
                "1 - Sangat Buruk", "2 - Buruk", "3 - Kurang", "4 - Agak Kurang", "5 - Cukup",
                "6 - Lumayan", "7 - Baik", "8 - Sangat Baik", "9 - Hebat", "10 - Sempurna"
            ]
        }
    };

    function renderScoreInputs(mode, existingValues = {}) {
        const minScore = parseInt(document.getElementById(`${mode}_min_score`).value) || 1;
        const maxScore = parseInt(document.getElementById(`${mode}_max_score`).value) || 4;
        const container = document.getElementById(`${mode}_score_inputs_container`);
        if (!container) return;
        
        container.innerHTML = '';

        [4, 5, 10].forEach(num => {
            const chip = document.getElementById(`chip_${mode}_${num}`);
            if (chip) {
                if (maxScore === num) {
                    chip.classList.add('active');
                    chip.querySelector('i').className = 'bi bi-check-circle';
                } else {
                    chip.classList.remove('active');
                    chip.querySelector('i').className = 'bi bi-circle';
                }
            }
        });

        for (let score = minScore; score <= maxScore; score++) {
            let defaultVal = existingValues[score] || "";
            if (!defaultVal) {
                if (LABEL_TEMPLATES.satisfaction[maxScore] && LABEL_TEMPLATES.satisfaction[maxScore][score - minScore]) {
                    defaultVal = LABEL_TEMPLATES.satisfaction[maxScore][score - minScore];
                } else {
                    defaultVal = `Nilai ${score}`;
                }
            }

            const row = document.createElement('div');
            row.className = 'label-row-input';
            
            let badgeBg = 'var(--color-navy-primary)';
            if (score === minScore) badgeBg = '#DC2626';
            else if (score === maxScore) badgeBg = '#0D9444';

            row.innerHTML = `
                <div class="label-row-badge" style="background: ${badgeBg};">
                    ${score}
                </div>
                <div style="flex: 1;">
                    <input type="text" 
                           name="options[${score}]" 
                           id="${mode}_option_${score}" 
                           value="${escapeHtml(defaultVal)}" 
                           required 
                           placeholder="Label untuk nilai ${score}" 
                           style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem; padding: 0.55rem 0.75rem; font-family: inherit;">
                </div>
            `;
            container.appendChild(row);
        }
    }

    function setPresetScale(mode, min, max, defaultName = null) {
        document.getElementById(`${mode}_min_score`).value = min;
        document.getElementById(`${mode}_max_score`).value = max;
        if (defaultName && mode === 'create') {
            const nameInput = document.getElementById('create_scale_name');
            if (!nameInput.value || nameInput.value.startsWith('Skala')) {
                nameInput.value = defaultName;
            }
        }
        renderScoreInputs(mode);
    }

    function applyTextPreset(mode, type) {
        const minScore = parseInt(document.getElementById(`${mode}_min_score`).value) || 1;
        const maxScore = parseInt(document.getElementById(`${mode}_max_score`).value) || 4;
        
        let presetList = null;
        if (LABEL_TEMPLATES[type] && LABEL_TEMPLATES[type][maxScore]) {
            presetList = LABEL_TEMPLATES[type][maxScore];
        }

        for (let score = minScore; score <= maxScore; score++) {
            const input = document.getElementById(`${mode}_option_${score}`);
            if (input) {
                if (presetList && presetList[score - minScore]) {
                    input.value = presetList[score - minScore];
                } else {
                    input.value = `Nilai ${score}`;
                }
            }
        }
    }

    function openCreateScaleModal() {
        document.getElementById('createScaleForm').reset();
        document.getElementById('create_min_score').value = 1;
        document.getElementById('create_max_score').value = 4;
        document.getElementById('create_scale_name').value = 'Skala Kepuasan 1-4';
        renderScoreInputs('create');
        document.getElementById('createScaleModal').style.display = 'flex';
    }

    function closeCreateScaleModal() {
        document.getElementById('createScaleModal').style.display = 'none';
    }

    function openEditScaleModal(scale) {
        const form = document.getElementById('editScaleForm');
        form.action = `/admin/rating-scales/${scale.id}`;
        
        document.getElementById('edit_scale_name').value = scale.name;
        document.getElementById('edit_min_score').value = scale.min_score || 1;
        document.getElementById('edit_max_score').value = scale.max_score || 4;
        document.getElementById('edit_scale_desc').value = scale.description || '';
        document.getElementById('edit_is_default').checked = !!scale.is_default;

        const existingMap = {};
        if (Array.isArray(scale.options_json)) {
            scale.options_json.forEach(item => {
                existingMap[item.score] = item.label;
            });
        }

        renderScoreInputs('edit', existingMap);
        document.getElementById('editScaleModal').style.display = 'flex';
    }

    function closeEditScaleModal() {
        document.getElementById('editScaleModal').style.display = 'none';
    }

    function openDeleteScaleModal(id, name) {
        const form = document.getElementById('deleteScaleForm');
        form.action = `/admin/rating-scales/${id}`;
        document.getElementById('delete_scale_name_text').textContent = name;
        document.getElementById('deleteScaleModal').style.display = 'flex';
    }

    function closeDeleteScaleModal() {
        document.getElementById('deleteScaleModal').style.display = 'none';
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

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateScaleModal();
            closeEditScaleModal();
            closeDeleteScaleModal();
        }
    });

    // Close on click backdrop
    document.querySelectorAll('.modal-backdrop-custom').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
    });

    // Initial render on load
    document.addEventListener('DOMContentLoaded', function() {
        renderScoreInputs('create');
    });
</script>
@endsection
@endsection
