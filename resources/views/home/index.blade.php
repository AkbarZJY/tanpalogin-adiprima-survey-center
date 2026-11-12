@extends('layouts.app')

@section('title', 'Beranda Survei - Adiprima Survey Center')

@section('styles')
<style>
    .home-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    .survey-list-card {
        background: #FFFFFF;
        border: 1px solid var(--color-border);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease;
    }

    .survey-list-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 8px 16px -2px rgba(0, 0, 0, 0.06);
    }

    .survey-card-actions {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .survey-list-card {
            flex-direction: column;
            align-items: stretch;
            padding: 1.25rem;
            gap: 1.25rem;
        }

        .survey-card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            width: 100%;
        }

        .survey-card-actions a,
        .survey-card-actions button {
            justify-content: center;
            text-align: center;
        }

        .survey-card-actions a.btn-primary-form {
            grid-column: 1 / -1;
            padding: 0.75rem 1rem !important;
            font-size: 0.9rem !important;
        }
    }
</style>
@endsection

@section('content')
<div class="home-container">
    <!-- Welcome Header -->
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem; font-weight: 800; color: #0F172A; margin-bottom: 0.35rem; letter-spacing: -0.02em;">
            @auth
                Selamat datang, {{ Auth::user()->name }}!
            @else
                Selamat datang di Adiprima Survey Center!
            @endauth
        </h1>
        <p style="color: #64748B; font-size: 0.925rem;">
            @auth
                @if(Auth::user()->isAdmin())
                    Kelola dan pantau seluruh instrumen kuesioner survei perusahaan PT Adiprima Suraprinta dalam satu tempat terpadu.
                @else
                    Pilih survei yang tersedia di bawah ini untuk memulai pengisian kuesioner.
                @endif
            @else
                Pilih survei yang tersedia di bawah ini untuk memulai pengisian kuesioner.
            @endauth
        </p>
    </div>

    <!-- Survey Catalog Grid Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
        <h2 style="font-size: 1.15rem; font-weight: 800; color: #1E293B;">Daftar Instrumen Kuesioner Perusahaan</h2>
        <span style="font-size: 0.8rem; background: #E2E8F0; color: #475569; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 700;">
            {{ $surveys->count() }} Survei @auth{{ Auth::user()->isAdmin() ? 'Terdaftar' : 'Aktif' }}@else{{ 'Aktif' }}@endauth
        </span>
    </div>

    @php
        $groupedSurveys = $surveys->groupBy('category');
        $isAdmin = Auth::check() && Auth::user()->isAdmin();
    @endphp

    @forelse($groupedSurveys as $categoryName => $catSurveys)
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.85rem;">
            @php
                $catIcon = match(trim($categoryName)) {
                    'Survey Budaya Kerja', 'Budaya Kerja' => 'bi-people-fill',
                    'Employee Engagement Survey', 'Engagement Survey' => 'bi-graph-up-arrow',
                    'Customer Satisfaction Survey', 'Kepuasan Pelanggan' => 'bi-award-fill',
                    default => 'bi-clipboard-check-fill'
                };
            @endphp
            <div style="width: 28px; height: 28px; border-radius: 6px; background: var(--color-navy-primary); color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                <i class="bi {{ $catIcon }}"></i>
            </div>
            <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">
                {{ $categoryName }}
            </h3>
            <span style="font-size: 0.75rem; color: #64748B; font-weight: 600; background: #F1F5F9; padding: 0.15rem 0.5rem; border-radius: 9999px;">
                {{ $catSurveys->count() }} Kuesioner
            </span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($catSurveys as $survey)
            <div class="survey-list-card">
                <!-- Left Info -->
                <div style="display: flex; align-items: flex-start; gap: 1rem; flex: 1;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: {{ $survey->slug == 'engagement-survey' ? '#ECFDF5' : '#EFF6FF' }}; color: {{ $survey->slug == 'engagement-survey' ? '#059669' : '#2563EB' }}; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
                        <i class="bi {{ $survey->icon ?: 'bi-clipboard-data' }}"></i>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem; flex-wrap: wrap;">
                            <h4 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin: 0;">{{ $survey->title }}</h4>
                            @if($isAdmin)
                                @if($survey->is_active)
                                    <span style="background: #D1FAE5; color: #065F46; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 6px;">AKTIF</span>
                                @else
                                    <span style="background: #FEE2E2; color: #991B1B; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 6px;">NONAKTIF</span>
                                @endif
                            @else
                                <span style="background: #D1FAE5; color: #065F46; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 6px;">TERSEDIA</span>
                            @endif

                            @if($survey->start_date && $survey->end_date)
                                <small style="color: #64748B; font-size: 0.75rem;">
                                    ({{ $survey->start_date->format('d M') }} - {{ $survey->end_date->format('d M Y') }})
                                </small>
                            @endif
                        </div>
                        <p style="color: #64748B; font-size: 0.85rem; line-height: 1.45; margin: 0;">{{ $survey->description }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="survey-card-actions">
                    @if($isAdmin)
                        <button onclick="openShareModal('{{ $survey->title }}', '{{ route('survey.form', $survey->slug) }}')" style="background: #F8FAFC; border: 1px solid #CBD5E1; color: #334155; padding: 0.55rem 0.85rem; border-radius: 8px; font-size: 0.825rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.35rem;" title="Bagi Tautan">
                            <i class="bi bi-share-fill" style="color: #2563EB;"></i> Share
                        </button>
                        
                        <a href="{{ route('admin.surveys.questions', $survey->id) }}" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; text-decoration: none; padding: 0.55rem 0.85rem; border-radius: 8px; font-size: 0.825rem; font-weight: 600; display: flex; align-items: center; gap: 0.35rem;" title="Question Builder">
                            <i class="bi bi-ui-checks"></i> Soal
                        </a>

                        <a href="{{ route('admin.dashboard', ['survey_id' => $survey->id]) }}" style="background: #EFF6FF; border: 1px solid #BFDBFE; color: #1D4ED8; text-decoration: none; padding: 0.55rem 0.85rem; border-radius: 8px; font-size: 0.825rem; font-weight: 700; display: flex; align-items: center; gap: 0.35rem;">
                            <i class="bi bi-pie-chart-fill"></i> Analytics
                        </a>
                    @endif

                    <a href="{{ route('survey.form', $survey->slug) }}" class="btn-primary-form" style="background: #ECFDF5; border: 1.5px solid #A7F3D0; color: #047857; text-decoration: none; padding: 0.55rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 800; display: flex; align-items: center; gap: 0.35rem;">
                        <i class="bi bi-file-earmark-text-fill"></i> Isi Form Survei <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div style="background: #FFFFFF; border: 1.5px dashed #CBD5E1; border-radius: 14px; padding: 3rem 1.5rem; text-align: center;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: #F1F5F9; color: #94A3B8; display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 0.85rem;">
            <i class="bi bi-calendar-x"></i>
        </div>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Tidak Ada Survei yang Sedang Aktif</h3>
        <p style="color: #64748B; font-size: 0.875rem; max-width: 440px; margin: 0 auto;">
            Saat ini belum ada instrumen kuesioner yang aktif untuk diisi. Silakan hubungi tim HR / Administrator jika Anda memiliki pertanyaan.
        </p>
    </div>
    @endforelse
</div>

<!-- Modal Generate Link Survey -->
@auth
@if(Auth::user()->isAdmin())
<div id="shareModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1300; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #FFFFFF; width: 100%; max-width: 480px; border-radius: 16px; padding: 1.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 id="modalSurveyTitle" style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin: 0;">Generate Link Survei</h3>
            <button onclick="closeShareModal()" style="background: transparent; border: none; font-size: 1.4rem; color: #64748B; cursor: pointer;">&times;</button>
        </div>
        <p style="font-size: 0.85rem; color: #64748B; margin-bottom: 1.25rem;">Salin link survei di bawah ini untuk dibagikan kepada karyawan. Karyawan <strong>tidak perlu login</strong> untuk mengisi survei.</p>

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Tautan Survei Langsung</label>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <input type="text" id="shareUrlInput" readonly style="flex: 1; min-width: 200px; padding: 0.65rem 0.75rem; font-size: 0.825rem; border: 1.5px solid #CBD5E1; border-radius: 8px; background: #F8FAFC; color: #0F172A;">
                <button onclick="copyShareUrl()" style="background: #0C2B64; color: #FFFFFF; border: none; padding: 0.65rem 1rem; border-radius: 8px; font-size: 0.825rem; font-weight: 700; cursor: pointer;">
                    <i class="bi bi-clipboard"></i> Salin
                </button>
            </div>
        </div>

        <div style="background: #F1F5F9; border-radius: 12px; padding: 1rem; text-align: center;">
            <small style="color: #64748B; font-weight: 600; display: block; margin-bottom: 0.5rem;">Pratinjau Kode QR Survei</small>
            <img id="qrCodeImg" src="" alt="QR Code Survei" style="width: 130px; height: 130px; border-radius: 8px; border: 1px solid #E2E8F0; padding: 0.35rem; background: #FFF; display: inline-block;">
        </div>
    </div>
</div>
@endif
@endauth
@endsection

@section('scripts')
<script>
    function openShareModal(title, url) {
        document.getElementById('modalSurveyTitle').innerText = 'Share Link - ' + title;
        document.getElementById('shareUrlInput').value = url;
        document.getElementById('qrCodeImg').src = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + encodeURIComponent(url);
        document.getElementById('shareModal').style.display = 'flex';
    }

    function closeShareModal() {
        var modal = document.getElementById('shareModal');
        if (modal) modal.style.display = 'none';
    }

    function copyShareUrl() {
        const input = document.getElementById('shareUrlInput');
        input.select();
        document.execCommand('copy');
        alert('Tautan survei telah disalin ke clipboard!');
    }
</script>
@endsection
