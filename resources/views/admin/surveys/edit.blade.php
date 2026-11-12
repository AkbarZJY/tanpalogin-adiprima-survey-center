@extends('layouts.app')

@section('title', 'Edit Kuesioner - ' . $survey->title)

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.surveys.index') }}" style="color: #64748B; text-decoration: none; font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kuesioner
        </a>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--color-navy-primary); letter-spacing: -0.02em;">
            Edit Pengaturan Kuesioner & Masa Aktif
        </h1>
        <p style="color: #64748B; font-size: 0.95rem; margin-top: 0.25rem;">
            Perbarui data survei, atur ulang masa aktif, atau ubah petunjuk pengisian kuesioner.
        </p>
    </div>

    <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: 14px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <form action="{{ route('admin.surveys.update', $survey->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">
                    Judul Kuesioner *
                </label>
                <input type="text" name="title" required value="{{ old('title', $survey->title) }}" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.95rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #334155;">
                            Kategori Survei *
                        </label>
                        <a href="{{ route('admin.survey-categories.index') }}" target="_blank" style="font-size: 0.75rem; color: #2563EB; text-decoration: none; font-weight: 600;">
                            <i class="bi bi-gear"></i> Kelola Kategori
                        </a>
                    </div>
                    <select name="category_select" id="categorySelect" onchange="toggleCustomCategory(this)" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.95rem; background: #FFF; cursor: pointer;">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}" {{ old('category', $survey->category) == $cat->name ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                        <option value="__custom__" {{ !$categories->pluck('name')->contains(old('category', $survey->category)) ? 'selected' : '' }}>
                            + Kategori Baru / Ketik Manual
                        </option>
                    </select>

                    <input type="text" name="category" id="customCategoryInput" value="{{ old('category', $survey->category) }}" placeholder="Ketik nama kategori..." style="width: 100%; margin-top: 0.5rem; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.9rem; display: none;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">
                        Pilih Ikon Kuesioner
                    </label>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <div style="width: 44px; height: 44px; border-radius: 8px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; border: 1px solid #BFDBFE; flex-shrink: 0;">
                            <i class="bi {{ old('icon', $survey->icon ?: 'bi-clipboard-data') }}" id="surveyEditIconPreviewI"></i>
                        </div>
                        <select name="icon" id="surveyEditIconSelect" onchange="onSurveyEditIconChange(this)" style="width: 100%; padding: 0.725rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.9rem; background: #FFF; font-weight: 600;">
                            <option value="bi-clipboard-data" {{ old('icon', $survey->icon) == 'bi-clipboard-data' ? 'selected' : '' }}>📋 Clipboard Data (Umum / Kuesioner)</option>
                            <option value="bi-people-fill" {{ old('icon', $survey->icon) == 'bi-people-fill' ? 'selected' : '' }}>👥 People (Budaya Kerja & Tim)</option>
                            <option value="bi-person-heart" {{ old('icon', $survey->icon) == 'bi-person-heart' ? 'selected' : '' }}>💖 Person Heart (Employee Engagement)</option>
                            <option value="bi-emoji-smile-fill" {{ old('icon', $survey->icon) == 'bi-emoji-smile-fill' ? 'selected' : '' }}>😊 Emoji Smile (Customer Satisfaction / CS)</option>
                            <option value="bi-heart-pulse-fill" {{ old('icon', $survey->icon) == 'bi-heart-pulse-fill' ? 'selected' : '' }}>💓 Heart Pulse (Kesejahteraan & Vitalitas)</option>
                            <option value="bi-award-fill" {{ old('icon', $survey->icon) == 'bi-award-fill' ? 'selected' : '' }}>🎖️ Award (Penghargaan & Apresiasi)</option>
                            <option value="bi-shield-check" {{ old('icon', $survey->icon) == 'bi-shield-check' ? 'selected' : '' }}>🛡️ Shield Check (K3, Kepatuhan & Safety)</option>
                            <option value="bi-graph-up-arrow" {{ old('icon', $survey->icon) == 'bi-graph-up-arrow' ? 'selected' : '' }}>📈 Graph Up (Pertumbuhan & Kinerja)</option>
                            <option value="bi-briefcase-fill" {{ old('icon', $survey->icon) == 'bi-briefcase-fill' ? 'selected' : '' }}>💼 Briefcase (Pekerjaan & Manajemen)</option>
                            <option value="bi-chat-dots-fill" {{ old('icon', $survey->icon) == 'bi-chat-dots-fill' ? 'selected' : '' }}>💬 Chat Dots (Komunikasi & Umpan Balik)</option>
                            <option value="bi-lightbulb-fill" {{ old('icon', $survey->icon) == 'bi-lightbulb-fill' ? 'selected' : '' }}>💡 Lightbulb (Inovasi & Pengembangan)</option>
                            <option value="bi-building" {{ old('icon', $survey->icon) == 'bi-building' ? 'selected' : '' }}>🏢 Building (Korporat & Lingkungan Kerja)</option>
                            <option value="bi-star-fill" {{ old('icon', $survey->icon) == 'bi-star-fill' ? 'selected' : '' }}>⭐ Star (Kualitas & Layanan Prima)</option>
                            <option value="bi-speedometer2" {{ old('icon', $survey->icon) == 'bi-speedometer2' ? 'selected' : '' }}>⏱️ Speedometer (Produktivitas & Efisiensi)</option>
                            <option value="bi-mortarboard-fill" {{ old('icon', $survey->icon) == 'bi-mortarboard-fill' ? 'selected' : '' }}>🎓 Mortarboard (Pelatihan & Edukasi)</option>
                            <option value="bi-trophy-fill" {{ old('icon', $survey->icon) == 'bi-trophy-fill' ? 'selected' : '' }}>🏆 Trophy (Prestasi & Keunggulan)</option>
                            <option value="bi-gear-fill" {{ old('icon', $survey->icon) == 'bi-gear-fill' ? 'selected' : '' }}>⚙️ Gear (Operasional Pabrik / Teknis)</option>
                            <option value="bi-tags-fill" {{ old('icon', $survey->icon) == 'bi-tags-fill' ? 'selected' : '' }}>🏷️ Tags (Kategori Umum)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Rating Scale Selection -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--color-navy-primary); margin: 0; display: flex; align-items: center; gap: 0.4rem;">
                        <i class="bi bi-star-half" style="color: #F59E0B;"></i> Skala Rating Kuesioner *
                    </h4>
                    <a href="{{ route('admin.rating-scales.index') }}" target="_blank" style="font-size: 0.75rem; color: #2563EB; text-decoration: none; font-weight: 600;">
                        <i class="bi bi-gear"></i> Kelola Master Skala
                    </a>
                </div>
                <p style="font-size: 0.8rem; color: #64748B; margin-bottom: 0.85rem;">
                    Pilih rentang skala nilai yang digunakan pada kuesioner ini (misal: 1-4, 1-5, atau 1-10). Mengubah skala rating ini akan otomatis menyesuaikan skala seluruh butir soal rating di kuesioner ini.
                </p>

                <select name="rating_scale_id" id="ratingScaleSelect" onchange="updateScalePreview(this)" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.95rem; background: #FFF; font-weight: 600; cursor: pointer;">
                    @foreach($ratingScales as $scale)
                        <option value="{{ $scale->id }}" 
                                data-points="{{ json_encode($scale->options_json) }}"
                                {{ old('rating_scale_id', $survey->rating_scale_id ?? $ratingScales->where('is_default', true)->first()?->id ?? $ratingScales->first()?->id) == $scale->id ? 'selected' : '' }}>
                            {{ $scale->name }} (Rentang 1 s/d {{ $scale->max_score }}) {{ $scale->is_default ? '— [Default Sistem]' : '' }}
                        </option>
                    @endforeach
                </select>

                <!-- Live preview of scale labels -->
                <div id="scalePreviewBox" style="margin-top: 0.75rem; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
                </div>
            </div>

            <!-- Active Dates Section -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--color-navy-primary); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                    <i class="bi bi-calendar-check" style="color: #2563EB;"></i> Pengaturan Masa Aktif Kuesioner
                </h4>
                <p style="font-size: 0.8rem; color: #64748B; margin-bottom: 1rem;">
                    Responden hanya diizinkan mengisi kuesioner pada rentang tanggal berikut.
                </p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">
                            Tanggal Mulai (Start Date)
                        </label>
                        <input type="date" name="start_date" value="{{ old('start_date', $survey->start_date ? $survey->start_date->format('Y-m-d') : '') }}" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.9rem; background: #FFFFFF;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">
                            Tanggal Selesai (End Date)
                        </label>
                        <input type="date" name="end_date" value="{{ old('end_date', $survey->end_date ? $survey->end_date->format('Y-m-d') : '') }}" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.9rem; background: #FFFFFF;">
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">
                    Deskripsi / Petunjuk Pengisian
                </label>
                <textarea name="description" rows="4" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.95rem; font-family: inherit; resize: vertical;">{{ old('description', $survey->description) }}</textarea>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 700; color: #1E293B; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $survey->is_active ? 'checked' : '' }} style="width: 18px; height: 18px;">
                    Aktifkan Kuesioner Ini (Dapat Diakses oleh Karyawan)
                </label>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #F1F5F9; padding-top: 1.5rem;">
                <a href="{{ route('admin.surveys.questions', $survey->id) }}" style="color: #2563EB; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <i class="bi bi-ui-checks"></i> Kelola Butir Pertanyaan &rarr;
                </a>

                <div style="display: flex; gap: 1rem;">
                    <a href="{{ route('admin.surveys.index') }}" style="background: #F1F5F9; color: #64748B; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem;">
                        Batal
                    </a>
                    <button type="submit" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.75rem 1.75rem; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-check-circle-fill"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function onSurveyEditIconChange(select) {
        const previewI = document.getElementById('surveyEditIconPreviewI');
        if (previewI) {
            previewI.className = 'bi ' + select.value;
        }
    }

    function toggleCustomCategory(select) {
        const customInput = document.getElementById('customCategoryInput');
        if (select.value === '__custom__') {
            customInput.style.display = 'block';
            customInput.focus();
            customInput.required = true;
        } else {
            customInput.style.display = 'none';
            customInput.value = select.value;
            customInput.required = false;
        }
    }

    function updateScalePreview(select) {
        const previewBox = document.getElementById('scalePreviewBox');
        if (!previewBox) return;
        previewBox.innerHTML = '';

        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption) return;

        try {
            const points = JSON.parse(selectedOption.getAttribute('data-points') || '[]');
            points.forEach(pt => {
                const badge = document.createElement('div');
                badge.style.cssText = 'background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 6px; padding: 0.25rem 0.6rem; font-size: 0.775rem; display: flex; align-items: center; gap: 0.35rem;';
                badge.innerHTML = `<span style="display:inline-flex; align-items:center; justify-content:center; width:18px; height:18px; border-radius:50%; background:#2563EB; color:#FFF; font-weight:700; font-size:0.675rem;">${pt.score}</span> <span style="font-weight:600; color:#334155;">${pt.label}</span>`;
                previewBox.appendChild(badge);
            });
        } catch (e) {
            console.error('Failed to parse points', e);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('categorySelect');
        if (select) toggleCustomCategory(select);
        const iconSelect = document.getElementById('surveyEditIconSelect');
        if (iconSelect) onSurveyEditIconChange(iconSelect);
        const scaleSelect = document.getElementById('ratingScaleSelect');
        if (scaleSelect) updateScalePreview(scaleSelect);
    });
</script>
@endsection
