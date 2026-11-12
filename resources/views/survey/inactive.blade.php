@extends('layouts.app')

@section('title', 'Kuesioner Tidak Tersedia - ' . $survey->title)

@section('content')
<div style="max-width: 650px; margin: 4rem auto; text-align: center; background: #FFFFFF; padding: 3rem 2rem; border-radius: 16px; border: 1px solid var(--color-border); box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
    <div style="margin-bottom: 1.25rem; display: flex; justify-content: center; align-items: center; gap: 0.75rem;">
        <img src="{{ asset('images/adiprima-icon.svg') }}" alt="Logo PT Adiprima Suraprinta" style="width: 52px; height: 52px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
        <div style="width: 52px; height: 52px; border-radius: 12px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
            <i class="bi bi-clock-history"></i>
        </div>
    </div>
    
    <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--color-navy-primary); margin-bottom: 0.75rem;">
        {{ $survey->title }}
    </h2>
    
    <p style="color: #64748B; font-size: 1rem; line-height: 1.6; margin-bottom: 2rem;">
        {{ $message ?? 'Kuesioner ini saat ini tidak sedang membuka periode pengisian atau telah dinonaktifkan.' }}
    </p>

    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.25rem; margin-bottom: 2rem; display: inline-flex; flex-direction: column; gap: 0.5rem; text-align: left; width: 100%;">
        <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
            <span style="color: #64748B;">Masa Aktif:</span>
            <span style="font-weight: 700; color: var(--color-navy-primary);">
                @if($survey->start_date && $survey->end_date)
                    {{ $survey->start_date->format('d M Y') }} - {{ $survey->end_date->format('d M Y') }}
                @elseif($survey->start_date)
                    Mulai {{ $survey->start_date->format('d M Y') }}
                @elseif($survey->end_date)
                    Hingga {{ $survey->end_date->format('d M Y') }}
                @else
                    Belum ditentukan
                @endif
            </span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
            <span style="color: #64748B;">Status:</span>
            <span style="font-weight: 700; color: {{ $survey->is_active ? '#10B981' : '#EF4444' }};">
                {{ $survey->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
    </div>

    <div>
        <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--color-navy-primary); color: #FFFFFF; text-decoration: none; padding: 0.75rem 1.75rem; border-radius: 8px; font-weight: 700; font-size: 0.95rem; transition: background 0.2s ease;">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
