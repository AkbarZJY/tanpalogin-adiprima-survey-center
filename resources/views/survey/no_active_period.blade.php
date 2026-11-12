@extends('layouts.app')

@section('title', 'Periode Tidak Aktif')

@section('content')
<div style="max-width: 600px; margin: 4rem auto; text-align: center;">
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 20px; padding: 3rem 2rem; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);">
        <div style="font-size: 3rem; color: #F59E0B; margin-bottom: 1rem;">
            <i class="bi bi-clock-history"></i>
        </div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0F172A; margin-bottom: 0.5rem;">Tidak Ada Periode Survei Aktif</h2>
        <p style="color: #64748B; font-size: 0.95rem; margin-bottom: 1.5rem;">
            Saat ini survei <strong>{{ $survey->title }}</strong> sedang tidak membuka periode pengisian. Silakan hubungi tim HRGA untuk informasi lebih lanjut.
        </p>
        <a href="{{ route('home') }}" style="background: var(--color-navy-primary); color: #FFF; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 700;">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
