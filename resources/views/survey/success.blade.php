@extends('layouts.app')

@section('title', 'Terima Kasih - ' . $survey->title)

@section('styles')
<style>
    .success-card-box {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 24px;
        padding: 3.5rem 2.5rem;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.08);
    }

    @media (max-width: 640px) {
        .success-card-box {
            padding: 2rem 1.25rem;
            border-radius: 16px;
        }
    }
</style>
@endsection

@section('content')
<div style="max-width: 650px; margin: 2rem auto 0 auto; text-align: center;">
    <div class="success-card-box">
        
        <div style="margin-bottom: 1.25rem; display: flex; justify-content: center; align-items: center; gap: 0.75rem;">
            <img src="{{ asset('images/adiprima-icon.svg') }}" alt="Logo PT Adiprima Suraprinta" style="width: 52px; height: 52px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div style="width: 52px; height: 52px; background: #D1FAE5; color: #10B981; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 2rem;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>

        <h1 style="font-size: 1.85rem; font-weight: 800; color: #0F172A; margin-bottom: 0.5rem; letter-spacing: -0.02em;">
            Terima kasih!
        </h1>
        <p style="color: #475569; font-size: 1rem; line-height: 1.6; margin-bottom: 1.75rem;">
            Jawaban kuesioner Anda telah berhasil disimpan.<br>
            Kontribusi dan masukan Anda sangat berarti bagi kemajuan dan pengembangan PT Adiprima Suraprinta.
        </p>

        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1rem; margin-bottom: 1.75rem; display: flex; align-items: center; justify-content: center; gap: 0.6rem; color: #64748B; font-size: 0.825rem; text-align: left;">
            <i class="bi bi-shield-lock-fill" style="color: var(--color-navy-primary); font-size: 1.2rem; flex-shrink: 0;"></i>
            <span>Jawaban Anda bersifat rahasia dan hanya digunakan untuk keperluan analisis survei internal perusahaan.</span>
        </div>

        <a href="{{ route('home') }}" style="background: var(--color-navy-primary); color: #FFFFFF; text-decoration: none; padding: 0.85rem 2rem; border-radius: 10px; font-weight: 700; font-size: 0.95rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s ease; width: 100%; max-width: 280px; box-shadow: 0 4px 12px rgba(12, 43, 100, 0.2);">
            <i class="bi bi-house-door-fill"></i> Kembali ke Beranda
        </a>

    </div>
</div>
@endsection
