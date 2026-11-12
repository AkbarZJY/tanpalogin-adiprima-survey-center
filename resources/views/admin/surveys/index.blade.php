@extends('layouts.app')

@section('title', 'Manajemen Kuesioner Survei - Adiprima Survey Center')

@section('styles')
<style>
    .surveys-header {
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .surveys-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
    }

    .main-tabs-nav {
        display: flex;
        gap: 0.5rem;
        border-bottom: 2px solid #E2E8F0;
        margin-bottom: 1.5rem;
    }

    .main-tab-link {
        padding: 0.85rem 1.35rem;
        font-weight: 700;
        font-size: 0.925rem;
        color: #64748B;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .main-tab-link:hover {
        color: var(--color-navy-primary);
        background: #F8FAFC;
    }

    .main-tab-link.active {
        color: var(--color-navy-primary);
        border-bottom-color: var(--color-navy-primary);
        background: #EFF6FF;
        border-radius: 8px 8px 0 0;
    }

    @media (max-width: 768px) {
        .surveys-header {
            flex-direction: column;
            align-items: stretch;
        }

        .surveys-header-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
            gap: 0.5rem;
        }

        .surveys-header-actions a {
            justify-content: center;
            width: 100%;
        }

        .surveys-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="surveys-header">
    <div>
        <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--color-navy-primary); letter-spacing: -0.02em;">
            Manajemen Kuesioner & Survei
        </h1>
        <p style="color: #64748B; font-size: 0.9rem; margin-top: 0.25rem;">
            Kelola instrumen survei perusahaan berdasarkan kategori, atur masa aktif, dan kelola arsip survei yang telah selesai.
        </p>
    </div>

    <div class="surveys-header-actions" style="display: flex; align-items: center; gap: 0.65rem;">
        <a href="{{ route('admin.survey-categories.index') }}" style="background: #FFFFFF; border: 1.5px solid #CBD5E1; color: #334155; text-decoration: none; padding: 0.65rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.2s ease;">
            <i class="bi bi-tags-fill" style="color: #2563EB;"></i> Kelola Kategori Survei
        </a>

        <a href="{{ route('admin.surveys.create') }}" style="background: var(--color-navy-primary); color: #FFFFFF; text-decoration: none; padding: 0.65rem 1.15rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem; transition: background 0.2s ease; box-shadow: 0 4px 10px rgba(12, 43, 100, 0.2);">
            <i class="bi bi-plus-circle-fill"></i> Buat Kuesioner Baru
        </a>
    </div>
</div>

<!-- Primary Tabs: Active vs Archived -->
<div class="main-tabs-nav">
    <a href="{{ route('admin.surveys.index', ['tab' => 'active']) }}" class="main-tab-link {{ $tab !== 'archived' ? 'active' : '' }}">
        <i class="bi bi-clipboard-check-fill" style="color: #10B981;"></i>
        <span>Survei Aktif & Berjalan</span>
        <span style="background: {{ $tab !== 'archived' ? 'var(--color-navy-primary)' : '#E2E8F0' }}; color: {{ $tab !== 'archived' ? '#FFF' : '#475569' }}; font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 9999px;">
            {{ $activeCount }}
        </span>
    </a>

    <a href="{{ route('admin.surveys.index', ['tab' => 'archived']) }}" class="main-tab-link {{ $tab === 'archived' ? 'active' : '' }}">
        <i class="bi bi-archive-fill" style="color: #F59E0B;"></i>
        <span>Arsip Survei & Riwayat</span>
        <span style="background: {{ $tab === 'archived' ? 'var(--color-navy-primary)' : '#E2E8F0' }}; color: {{ $tab === 'archived' ? '#FFF' : '#475569' }}; font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 9999px;">
            {{ $archivedCount }}
        </span>
    </a>
</div>

@if($tab === 'archived')
<div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem;">
    <i class="bi bi-info-circle-fill" style="color: #D97706; font-size: 1.2rem; flex-shrink: 0; margin-top: 0.1rem;"></i>
    <div style="font-size: 0.85rem; color: #92400E; line-height: 1.45;">
        <b>Informasi Arsip:</b> Kuesioner dalam bagian arsip <b>tidak ditampilkan di halaman beranda peserta</b> dan form pengisian ditutup. Namun seluruh riwayat data responden, butir pertanyaan, dan grafik analitik tetap tersimpan aman serta dapat dibuka, diunduh (.xlsx), atau dipulihkan kapan saja.
    </div>
</div>
@endif

<!-- Category Filter Pills -->
<div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; overflow-x: auto; padding-bottom: 0.25rem;">
    <a href="{{ route('admin.surveys.index', ['tab' => $tab, 'category' => 'all']) }}" 
       style="padding: 0.5rem 0.95rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; text-decoration: none; border: 1.5px solid {{ empty($categoryFilter) || $categoryFilter == 'all' ? 'var(--color-navy-primary)' : '#CBD5E1' }}; background: {{ empty($categoryFilter) || $categoryFilter == 'all' ? 'var(--color-navy-primary)' : '#FFFFFF' }}; color: {{ empty($categoryFilter) || $categoryFilter == 'all' ? '#FFF' : '#475569' }}; white-space: nowrap;">
        Semua Kategori ({{ $tab === 'archived' ? $archivedCount : $activeCount }})
    </a>
    @foreach($categories as $cat)
        @php
            $cCount = ($tab === 'archived') ? $cat->archivedSurveys()->count() : $cat->unarchivedSurveys()->count();
            $isSelected = ($categoryFilter === $cat->name || $categoryFilter === $cat->slug);
        @endphp
        <a href="{{ route('admin.surveys.index', ['tab' => $tab, 'category' => $cat->slug]) }}" 
           style="padding: 0.5rem 0.95rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; text-decoration: none; border: 1.5px solid {{ $isSelected ? 'var(--color-navy-primary)' : '#CBD5E1' }}; background: {{ $isSelected ? 'var(--color-navy-primary)' : '#FFFFFF' }}; color: {{ $isSelected ? '#FFF' : '#475569' }}; white-space: nowrap;">
            {{ $cat->name }} ({{ $cCount }})
        </a>
    @endforeach
</div>

<!-- Surveys Grid -->
<div class="surveys-grid">
    @forelse($surveys as $srv)
    @php
        $isActive = $srv->is_active;
        $isArchived = $srv->is_archived;
        $isWithinDates = $srv->isWithinActiveDate();
    @endphp
    <div style="background: #FFFFFF; border: 1px solid {{ $isArchived ? '#E2E8F0' : 'var(--color-border)' }}; border-radius: 14px; padding: 1.25rem 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: space-between; opacity: {{ $isArchived ? '0.92' : '1' }};">
        <div>
            <!-- Header Card -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: {{ $isArchived ? '#F1F5F9' : 'linear-gradient(135deg, #0C2B64, #2563EB)' }}; color: {{ $isArchived ? '#64748B' : '#FFFFFF' }}; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: {{ $isArchived ? 'none' : '0 4px 10px rgba(37,99,235,0.2)' }}; flex-shrink: 0;">
                        <i class="bi {{ $isArchived ? 'bi-archive-fill' : ($srv->icon ?: 'bi-clipboard-data') }}"></i>
                    </div>
                    <div>
                        <span style="font-size: 0.725rem; font-weight: 800; color: #2563EB; background: #EFF6FF; padding: 0.15rem 0.45rem; border-radius: 4px;">
                            {{ $srv->categoryModel?->name ?? $srv->category }}
                        </span>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--color-navy-primary); margin-top: 0.2rem; line-height: 1.3;">
                            {{ $srv->title }}
                        </h3>
                    </div>
                </div>

                <!-- Status Badge -->
                @if($isArchived)
                    <span style="background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; padding: 0.2rem 0.55rem; border-radius: 9999px; font-size: 0.725rem; font-weight: 700; display: flex; align-items: center; gap: 0.35rem;">
                        <i class="bi bi-archive-fill" style="font-size: 0.7rem;"></i> Diarsipkan
                    </span>
                @else
                    <form action="{{ route('admin.surveys.toggle', $srv->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: {{ $isActive ? '#DEF7EC' : '#FEE2E2' }}; color: {{ $isActive ? '#03543F' : '#9B1C1C' }}; border: 1px solid {{ $isActive ? '#84E1BC' : '#FCA5A5' }}; padding: 0.25rem 0.55rem; border-radius: 9999px; font-size: 0.725rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.35rem;" title="Klik untuk mengubah status aktif">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $isActive ? '#10B981' : '#EF4444' }};"></span>
                            {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </form>
                @endif
            </div>

            <p style="color: #64748B; font-size: 0.85rem; line-height: 1.45; margin-bottom: 1.25rem;">
                {{ $srv->description ?: 'Tidak ada deskripsi kuesioner.' }}
            </p>

            <!-- Validity Range Card -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 1.25rem; font-size: 0.8rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                    <span style="color: #64748B; display: flex; align-items: center; gap: 0.35rem;">
                        <i class="bi bi-calendar-range"></i> Masa Aktif:
                    </span>
                    <span style="font-weight: 700; color: #1E293B;">
                        @if($srv->start_date && $srv->end_date)
                            {{ $srv->start_date->format('d M') }} - {{ $srv->end_date->format('d M Y') }}
                        @elseif($srv->start_date)
                            Mulai {{ $srv->start_date->format('d M Y') }}
                        @elseif($srv->end_date)
                            Hingga {{ $srv->end_date->format('d M Y') }}
                        @else
                            <span style="color: #94A3B8;">Tidak dibatasi</span>
                        @endif
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #64748B; display: flex; align-items: center; gap: 0.35rem;">
                        <i class="bi bi-clock-history"></i> Status:
                    </span>
                    @if($isArchived)
                        <span style="color: #64748B; font-weight: 700;">Arsip Tersimpan</span>
                    @elseif($isWithinDates)
                        <span style="color: #16A34A; font-weight: 700;">Sedang Dibuka</span>
                    @elseif($srv->start_date && now()->lt($srv->start_date))
                        <span style="color: #D97706; font-weight: 700;">Belum Dibuka</span>
                    @else
                        <span style="color: #DC2626; font-weight: 700;">Telah Berakhir</span>
                    @endif
                </div>
            </div>

            <!-- Stats -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1.25rem;">
                <div style="background: #EFF6FF; border-radius: 8px; padding: 0.65rem; text-align: center;">
                    <div style="font-size: 1.2rem; font-weight: 800; color: #1E40AF;">{{ $srv->questions_count }}</div>
                    <div style="font-size: 0.725rem; color: #3B82F6; font-weight: 600;">Butir Pertanyaan</div>
                </div>
                <div style="background: #F0FDF4; border-radius: 8px; padding: 0.65rem; text-align: center;">
                    <div style="font-size: 1.2rem; font-weight: 800; color: #166534;">{{ $srv->responses_count }}</div>
                    <div style="font-size: 0.725rem; color: #16A34A; font-weight: 600;">Responden Masuk</div>
                </div>
            </div>
        </div>

        <!-- Action Footer -->
        <div style="border-top: 1px solid #F1F5F9; padding-top: 1rem; display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 0.4rem;">
                <a href="{{ route('admin.dashboard', ['survey_id' => $srv->id]) }}" style="background: var(--color-navy-primary); color: #FFFFFF; text-decoration: none; padding: 0.45rem 0.75rem; border-radius: 6px; font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <i class="bi bi-pie-chart-fill"></i> Hasil Analytics
                </a>

                <a href="{{ route('admin.surveys.questions', $srv->id) }}" style="background: #F1F5F9; color: #334155; border: 1px solid #CBD5E1; text-decoration: none; padding: 0.45rem 0.65rem; border-radius: 6px; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.35rem;" title="Kelola Butir Soal">
                    <i class="bi bi-ui-checks"></i> Soal
                </a>
            </div>

            <div style="display: flex; gap: 0.35rem; align-items: center;">
                @if($isArchived)
                    <!-- Unarchive Form -->
                    <form action="{{ route('admin.surveys.unarchive', $srv->id) }}" method="POST" onsubmit="return confirm('Aktifkan kembali survei {{ $srv->title }} ke daftar aktif?');" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: #DEF7EC; color: #03543F; border: 1px solid #84E1BC; padding: 0.45rem 0.65rem; border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.3rem;" title="Pulihkan / Aktifkan Kembali">
                            <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                        </button>
                    </form>
                @else
                    <!-- Archive Form -->
                    <form action="{{ route('admin.surveys.archive', $srv->id) }}" method="POST" onsubmit="return confirm('Pindahkan survei {{ $srv->title }} ke Arsip? Survei tidak akan muncul di beranda peserta.');" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; padding: 0.45rem 0.65rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer;" title="Arsipkan Survei">
                            <i class="bi bi-archive"></i>
                        </button>
                    </form>

                    <a href="{{ route('admin.surveys.edit', $srv->id) }}" style="background: #F1F5F9; color: #334155; border: 1px solid #CBD5E1; padding: 0.45rem 0.65rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; text-decoration: none;" title="Edit Pengaturan & Tanggal">
                        <i class="bi bi-gear-fill"></i>
                    </a>
                @endif

                <form action="{{ route('admin.surveys.destroy', $srv->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus survei {{ $srv->title }} secara permanen? Seluruh jawaban responden akan ikut terhapus.');" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; padding: 0.45rem 0.65rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer;" title="Hapus Survei Permanen">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; background: #FFFFFF; border: 1px dashed var(--color-border); border-radius: 12px; padding: 3rem; text-align: center; color: #64748B;">
        <i class="bi {{ $tab === 'archived' ? 'bi-archive' : 'bi-kanban' }}" style="font-size: 2.5rem; color: #94A3B8; display: block; margin-bottom: 0.5rem;"></i>
        <p style="font-size: 1rem; font-weight: 600;">
            {{ $tab === 'archived' ? 'Belum ada survei yang diarsipkan.' : 'Belum ada instrumen kuesioner aktif.' }}
        </p>
        <p style="font-size: 0.875rem;">
            {{ $tab === 'archived' ? 'Survei yang telah selesai dapat dipindahkan ke arsip agar beranda tetap rapi.' : 'Klik "Buat Kuesioner Baru" untuk mulai membuat survei.' }}
        </p>
    </div>
    @endforelse
</div>
@endsection
