@extends('layouts.app')

@section('title', 'Dashboard Analytics - Adiprima Survey Center')

@section('content')
<div style="max-width: 600px; margin: 4rem auto; text-align: center; background: #FFFFFF; border: 1px dashed var(--color-border); border-radius: 16px; padding: 3rem 2rem;">
    <i class="bi bi-kanban" style="font-size: 3rem; color: #94A3B8; margin-bottom: 1rem; display: block;"></i>
    <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--color-navy-primary); margin-bottom: 0.5rem;">
        Belum Ada Kuesioner Survei
    </h2>
    <p style="color: #64748B; font-size: 0.95rem; margin-bottom: 2rem;">
        Sistem belum memiliki instrumen survei aktif. Silakan buat kuesioner baru untuk mulai melihat analytics.
    </p>
    <a href="{{ route('admin.surveys.create') }}" style="background: var(--color-navy-primary); color: #FFFFFF; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 700; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem;">
        <i class="bi bi-plus-circle-fill"></i> Buat Kuesioner Baru
    </a>
</div>
@endsection
