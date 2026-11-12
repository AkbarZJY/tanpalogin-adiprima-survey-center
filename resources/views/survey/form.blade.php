@extends('layouts.app')

@section('title', $survey->title . ' - Form Survei')

@section('styles')
<style>
    .survey-container {
        max-width: 860px;
        margin: 0 auto;
        padding-bottom: 5rem;
    }

    /* Modern Stepper Header */
    .stepper-header-card {
        background: #FFFFFF;
        border: 1px solid var(--color-border);
        border-radius: 16px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .stepper-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
        position: relative;
    }

    .step-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        font-size: 0.85rem;
        color: #94A3B8;
        white-space: nowrap;
        flex-shrink: 0;
        cursor: default;
        transition: all 0.2s ease;
    }

    .step-item.active {
        color: var(--color-navy-primary);
        font-weight: 800;
    }

    .step-item.completed {
        color: #10B981;
    }

    .step-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #F1F5F9;
        border: 2px solid #E2E8F0;
        color: #64748B;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.825rem;
        font-weight: 800;
        flex-shrink: 0;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .step-item.active .step-badge {
        background-color: var(--color-navy-primary);
        border-color: var(--color-navy-primary);
        color: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(12, 43, 100, 0.18);
        transform: scale(1.05);
    }

    .step-item.completed .step-badge {
        background-color: #10B981;
        border-color: #10B981;
        color: #FFFFFF;
    }

    /* Mobile Progress Bar */
    .mobile-stepper-summary {
        display: none;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .stepper-progress-bar-bg {
        width: 100%;
        height: 6px;
        background: #F1F5F9;
        border-radius: 9999px;
        margin-top: 0.75rem;
        overflow: hidden;
    }

    .stepper-progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #2563EB, #10B981);
        border-radius: 9999px;
        transition: width 0.35s ease;
    }

    .form-card {
        background: #FFFFFF;
        border-radius: 18px;
        border: 1px solid var(--color-border);
        padding: 2rem;
        box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.04);
        position: relative;
    }

    .dimension-separator-badge {
        background: linear-gradient(135deg, var(--color-navy-primary), #1E40AF);
        color: #FFFFFF;
        padding: 0.75rem 1.15rem;
        border-radius: 12px;
        font-weight: 800;
        font-size: 0.95rem;
        margin: 2rem 0 1.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 4px 12px rgba(12, 43, 100, 0.15);
    }

    .question-block {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        padding: 1.35rem;
        margin-bottom: 1.35rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }

    .question-block:focus-within,
    .question-block:hover {
        border-color: #93C5FD;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.06);
    }

    .sub-rating-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 0.85rem;
    }

    .sub-rating-card.reality-card {
        background: #F8FAFC;
        border-left: 3px solid var(--color-navy-primary);
    }

    .sub-rating-card.expectation-card {
        background: #F8FAFC;
        border-left: 3px solid #2563EB;
    }

    .scale-options {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
        margin-top: 0.4rem;
    }

    .scale-btn {
        background: #FFFFFF;
        border: 1.5px solid #CBD5E1;
        border-radius: 10px;
        padding: 0.7rem 0.35rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        user-select: none;
        min-height: 58px;
        position: relative;
    }

    .scale-btn:active {
        transform: scale(0.96);
    }

    .scale-btn:hover {
        border-color: var(--color-blue-accent);
        background: #EFF6FF;
    }

    .scale-btn input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .scale-btn:has(input[type="radio"]:checked) {
        border-color: var(--color-navy-primary);
        background: #EFF6FF;
        box-shadow: 0 0 0 2px var(--color-navy-primary);
    }

    .scale-btn:has(input[type="radio"]:checked) .scale-num {
        color: var(--color-navy-primary);
    }

    .scale-btn:has(input[type="radio"]:checked) .scale-text {
        color: var(--color-navy-primary);
        font-weight: 700;
    }

    .scale-label {
        font-size: 0.75rem;
        color: #475569;
        font-weight: 500;
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
        align-items: center;
        line-height: 1.2;
        width: 100%;
    }

    .scale-num {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1E293B;
    }

    .scale-text {
        font-size: 0.7rem;
        color: #64748B;
        text-align: center;
    }

    /* Dynamic Reason Box */
    .reason-box {
        margin-top: 0.85rem;
        background: #FEF2F2;
        border: 1.5px solid #F87171;
        border-radius: 12px;
        padding: 1rem;
        display: none;
        animation: slideDownReason 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideDownReason {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .reason-box textarea {
        width: 100%;
        border: 1px solid #FCA5A5;
        border-radius: 8px;
        padding: 0.75rem;
        font-family: inherit;
        font-size: 0.875rem;
        margin-top: 0.4rem;
        background: #FFFFFF;
        color: #1E293B;
        box-sizing: border-box;
    }

    .reason-box textarea:focus {
        outline: none;
        border-color: #DC2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
    }

    .demographics-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .form-group-item {
        margin-bottom: 0.25rem;
    }

    .form-group-item label {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 0.4rem;
    }

    .form-group-item input,
    .form-group-item select {
        width: 100%;
        padding: 0.8rem 0.9rem;
        border: 1.5px solid #CBD5E1;
        border-radius: 10px;
        font-size: 0.9rem;
        background-color: #FFFFFF;
        font-family: inherit;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        -webkit-appearance: none;
        appearance: none;
    }

    .form-group-item select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 14px 10px;
        padding-right: 2.25rem;
    }

    .form-group-item input:focus,
    .form-group-item select:focus {
        outline: none;
        border-color: var(--color-blue-accent);
        box-shadow: 0 0 0 3.5px rgba(37, 99, 235, 0.12);
    }

    /* Navigation Buttons */
    .btn-nav-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding-top: 1.25rem;
        border-top: 1px solid #E2E8F0;
        gap: 1rem;
    }

    .btn-stepper {
        padding: 0.85rem 1.75rem;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: 48px;
        user-select: none;
    }

    .btn-stepper:active {
        transform: scale(0.97);
    }

    .btn-next {
        background-color: var(--color-navy-primary);
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(12, 43, 100, 0.25);
    }

    .btn-next:hover {
        background-color: var(--color-navy-dark);
    }

    .btn-prev {
        background-color: #F8FAFC;
        color: #475569;
        border: 1.5px solid #CBD5E1;
    }

    .btn-prev:hover {
        background-color: #E2E8F0;
    }

    /* Mobile Responsiveness Improvements */
    @media (max-width: 768px) {
        .form-card {
            padding: 1.25rem 1rem;
            border-radius: 14px;
        }

        .stepper-header-card {
            padding: 0.85rem 1rem;
            border-radius: 14px;
            margin-bottom: 1rem;
        }

        .stepper-header {
            display: none;
        }

        .mobile-stepper-summary {
            display: flex;
        }

        .demographics-grid {
            grid-template-columns: 1fr;
            gap: 0.85rem;
        }

        .scale-options {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.45rem;
        }

        .scale-btn {
            padding: 0.65rem 0.35rem;
            min-height: 52px;
        }

        .question-block {
            padding: 1rem 0.85rem;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .dimension-separator-badge {
            font-size: 0.875rem;
            padding: 0.65rem 0.85rem;
            margin: 1.5rem 0 1rem 0;
        }

        .btn-nav-wrapper {
            flex-direction: row;
            gap: 0.65rem;
            margin-top: 1.5rem;
            padding-top: 1rem;
        }

        .btn-stepper {
            flex: 1;
            padding: 0.8rem 1rem;
            font-size: 0.9rem;
            min-height: 48px;
        }
    }
</style>
@endsection

@section('content')
<div class="survey-container">
    
    <!-- Stepper Navigation Header Card -->
    <div class="stepper-header-card">
        <!-- Desktop Stepper -->
        <div class="stepper-header">
            <div class="step-item active" id="stepIndicator1">
                <div class="step-badge" id="stepBadge1">1</div>
                <span>Prolog</span>
            </div>
            <div class="step-item" id="stepIndicator2">
                <div class="step-badge" id="stepBadge2">2</div>
                <span>Identitas</span>
            </div>
            <div class="step-item" id="stepIndicator3">
                <div class="step-badge" id="stepBadge3">3</div>
                <span>Dimensi Inti</span>
            </div>
            @if($sectionCQuestions->count() > 0)
            <div class="step-item" id="stepIndicator4">
                <div class="step-badge" id="stepBadge4">4</div>
                <span>Pertanyaan Umum</span>
            </div>
            @endif
        </div>

        <!-- Mobile Stepper Summary & Progress Bar -->
        <div class="mobile-stepper-summary">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span id="mobileStepBadge" style="background: var(--color-navy-primary); color: #FFF; font-weight: 800; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 9999px;">
                    Langkah 1 dari {{ $sectionCQuestions->count() > 0 ? '4' : '3' }}
                </span>
                <span id="mobileStepTitle" style="font-weight: 700; font-size: 0.85rem; color: #1E293B;">
                    Prolog & Petunjuk
                </span>
            </div>
            <span id="mobileStepPct" style="font-size: 0.75rem; font-weight: 700; color: #10B981;">
                25%
            </span>
        </div>
        <div class="stepper-progress-bar-bg">
            <div class="stepper-progress-bar-fill" id="stepperProgressFill" style="width: 25%;"></div>
        </div>
    </div>

    <form id="surveyForm" action="{{ route('survey.store', $survey->slug) }}" method="POST">
        @csrf
        <input type="hidden" name="started_at" value="{{ now() }}">

        <!-- STEP 1: PROLOG -->
        <div class="form-card step-panel" id="step1">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="margin-bottom: 1.15rem;">
                    <img src="{{ asset('images/logo-adiprima.png') }}" alt="PT Adiprima Suraprinta - Jawa Pos Group" style="height: 44px; width: auto; max-width: 100%; object-fit: contain;">
                </div>
                <h1 style="font-size: 1.45rem; font-weight: 800; color: #0F172A; margin-bottom: 0.35rem; line-height: 1.3;">
                    {{ $survey->title }}
                </h1>
                <p style="color: #64748B; font-size: 0.9rem; max-width: 600px; margin: 0 auto; line-height: 1.5;">
                    {{ $survey->description ?: 'Terima kasih telah bersedia meluangkan waktu untuk mengisi kuesioner survei PT Adiprima Suraprinta.' }}
                </p>
            </div>

            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.15rem; margin-bottom: 1.5rem; font-size: 0.85rem; color: #334155; line-height: 1.6;">
                <h4 style="font-weight: 700; color: var(--color-navy-primary); margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                    <i class="bi bi-info-circle-fill" style="color: #2563EB;"></i> Petunjuk Pengisian:
                </h4>
                <p style="margin-bottom: 0.4rem;">Survei ini bertujuan untuk memperoleh masukan dari pegawai sebagai dasar dalam meningkatkan kualitas lingkungan kerja dan kemajuan perusahaan.</p>
                <p style="margin-bottom: 0.4rem;">Seluruh jawaban yang Bapak/Ibu berikan akan dijaga <strong>kerahasiaannya</strong> dan hanya digunakan untuk kepentingan analisis survei.</p>
                
                @if($survey->start_date && $survey->end_date)
                <div style="display: flex; align-items: center; gap: 0.5rem; background: #EFF6FF; color: #1E40AF; padding: 0.55rem 0.75rem; border-radius: 8px; font-weight: 600; font-size: 0.785rem; margin-top: 0.75rem;">
                    <i class="bi bi-calendar-event"></i> Periode Pengisian: {{ $survey->start_date->isoFormat('D MMMM Y') }} s/d {{ $survey->end_date->isoFormat('D MMMM Y') }}
                </div>
                @endif
            </div>

            <div style="text-align: center;">
                <button type="button" class="btn-stepper btn-next" onclick="goToStep(2)" style="width: 100%; max-width: 320px; font-size: 0.95rem;">
                    Mulai Mengisi Kuesioner <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: BAGIAN A - IDENTITAS RESPONDEN -->
        <div class="form-card step-panel" id="step2" style="display: none;">
            <div style="margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin-bottom: 0.25rem;">Bagian A. Identitas Responden</h2>
                <p style="color: #64748B; font-size: 0.85rem;">Periksa kembali kesesuaian data demografi Anda sebelum melanjutkan.</p>
            </div>

            <div class="demographics-grid">
                <div class="form-group-item">
                    <label>1. Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required>
                </div>

                <div class="form-group-item">
                    <label>2. Jenis Kelamin *</label>
                    <select name="gender" id="gender" onchange="evaluateQuestionEligibility()" required>
                        <option value="Laki-laki" {{ (old('gender', $user->gender ?? '') == 'Laki-laki') ? 'selected' : '' }}>1. Laki-laki</option>
                        <option value="Perempuan" {{ (old('gender', $user->gender ?? '') == 'Perempuan') ? 'selected' : '' }}>2. Perempuan</option>
                    </select>
                </div>

                <div class="form-group-item">
                    <label>3. Usia (Tahun) *</label>
                    <input type="number" name="age" value="{{ old('age', $user->age ?? 28) }}" min="17" max="80" required>
                </div>

                <div class="form-group-item">
                    <label>4. Pendidikan Terakhir *</label>
                    <select name="education" required>
                        @foreach($educations as $index => $edu)
                            <option value="{{ $edu }}" {{ (old('education', $user->education ?? '') == $edu) ? 'selected' : '' }}>{{ $index + 1 }}. {{ $edu }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group-item">
                    <label>5. Status Kepegawaian *</label>
                    <select name="employment_status" id="employment_status" onchange="handleEmploymentStatusChange()" required>
                        <option value="Tetap" {{ (old('employment_status', $user->employment_status ?? '') == 'Tetap') ? 'selected' : '' }}>1. Tetap</option>
                        <option value="Kontrak" {{ (old('employment_status', $user->employment_status ?? '') == 'Kontrak') ? 'selected' : '' }}>2. Kontrak</option>
                        <option value="Outsourcing" {{ (old('employment_status', $user->employment_status ?? '') == 'Outsourcing') ? 'selected' : '' }}>3. Outsourcing</option>
                    </select>
                </div>

                <div class="form-group-item" id="outsourcing_company_wrapper" style="display: none;">
                    <label for="outsourcing_company">Nama Outsourcing *</label>
                    <select name="outsourcing_company" id="outsourcing_company">
                        <option value="">-- Pilih Nama Outsourcing --</option>
                        @foreach($outsourcingCompanies as $index => $os)
                            <option value="{{ $os }}" {{ old('outsourcing_company') == $os ? 'selected' : '' }}>{{ $index + 1 }}. {{ $os }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group-item">
                    <label>6. Lama Bekerja *</label>
                    <select name="tenure" required>
                        @foreach($tenures as $index => $ten)
                            <option value="{{ $ten }}" {{ (old('tenure', $user->tenure ?? '') == $ten) ? 'selected' : '' }}>{{ $index + 1 }}. {{ $ten }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group-item">
                    <label>7. Departemen *</label>
                    <select name="department" required>
                        @foreach($departments as $index => $dept)
                            <option value="{{ $dept }}" {{ (old('department', $user->department ?? '') == $dept) ? 'selected' : '' }}>{{ $index + 1 }}. {{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group-item">
                    <label>8. Jabatan *</label>
                    <select name="position" id="position" onchange="evaluateQuestionEligibility()" required>
                        @foreach($positions as $index => $pos)
                            <option value="{{ $pos }}" {{ (old('position', $user->position ?? '') == $pos) ? 'selected' : '' }}>{{ $index + 1 }}. {{ $pos }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="btn-nav-wrapper">
                <button type="button" class="btn-stepper btn-prev" onclick="goToStep(1)">
                    <i class="bi bi-arrow-left"></i> Kembali
                </button>
                <button type="button" class="btn-stepper btn-next" onclick="goToStep(3)">
                    Selanjutnya <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3: BAGIAN B - DIMENSI UTAMA & PERTANYAAN -->
        <div class="form-card step-panel" id="step3" style="display: none;">
            @php
                $surveyScaleMax = $survey->rating_scale ?? 4;
                $scaleLabels = [];
                if ($survey->ratingScale && is_array($survey->ratingScale->options_json)) {
                    foreach ($survey->ratingScale->options_json as $item) {
                        $scaleLabels[$item['score']] = $item['label'];
                    }
                }
            @endphp
            <div style="margin-bottom: 1.25rem;">
                <h2 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin-bottom: 0.25rem;">
                    BAGIAN B. PENILAIAN TINGKAT HARAPAN DAN KENYATAAN
                </h2>
                <p style="color: #64748B; font-size: 0.825rem; line-height: 1.5;">
                    Nilai setiap butir pada skala 1–{{ $surveyScaleMax }} ({{ $survey->ratingScale?->name ?? 'Standar' }}):<br>
                    <strong style="color: #2563EB;">Harapan: 1 s/d {{ $surveyScaleMax }}</strong><br>
                    <strong style="color: var(--color-navy-primary);">Kenyataan: 1 ({{ $scaleLabels[1] ?? 'Sangat Rendah' }}) &ndash; {{ $surveyScaleMax }} ({{ $scaleLabels[$surveyScaleMax] ?? 'Sangat Tinggi' }})</strong>
                </p>
            </div>

            @php $currentDimension = ''; @endphp
            @foreach($sectionBQuestions as $question)
                @php $dimName = $question->dimensionName; @endphp
                @if($dimName !== $currentDimension)
                    @php $currentDimension = $dimName; @endphp
                    <div class="dimension-separator-badge">
                        <i class="bi bi-bookmark-star-fill"></i> Dimensi: {{ $currentDimension }}
                    </div>
                @endif

                <div class="question-block" id="q_block_{{ $question->id }}"
                     data-qnum="{{ $question->question_number }}"
                     data-applies-emp="{{ $question->applies_to_employment_status ?? '' }}"
                     data-applies-pos="{{ $question->applies_to_positions ?? '' }}"
                     data-applies-gender="{{ $question->applies_to_gender ?? '' }}">
                    <div style="display: flex; gap: 0.6rem; margin-bottom: 0.75rem; align-items: flex-start;">
                        <span style="background: var(--color-navy-primary); color: #FFF; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; flex-shrink: 0; margin-top: 0.1rem;">
                            {{ $question->question_number }}
                        </span>
                        <div>
                            @if($question->indicator_title)
                                <small style="color: #64748B; font-weight: 700; text-transform: uppercase; font-size: 0.7rem; display: block; margin-bottom: 0.15rem;">
                                    {{ $question->indicator_title }}
                                </small>
                            @endif
                            <h4 style="font-size: 0.95rem; font-weight: 700; color: #0F172A; line-height: 1.45;">
                                {{ $question->question_text }}
                            </h4>
                        </div>
                    </div>

                    @if($question->question_type === 'multiple_choice')
                        <div class="mcq-options" style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem;">
                            @php
                                $options = is_array($question->options_json) ? $question->options_json : json_decode($question->options_json ?? '[]', true);
                                if (empty($options)) {
                                    $options = ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'];
                                }
                            @endphp
                            @foreach($options as $optIndex => $optText)
                            <label style="display: flex; align-items: center; gap: 0.65rem; padding: 0.65rem 0.85rem; border: 1.5px solid #E2E8F0; border-radius: 8px; cursor: pointer; transition: all 0.2s; background: #FFFFFF;">
                                <input type="radio" name="choice_{{ $question->id }}" value="{{ $optText }}" required style="accent-color: var(--color-navy-primary); width: 16px; height: 16px;">
                                <span style="font-size: 0.875rem; color: #1E293B; font-weight: 600;">{{ $optText }}</span>
                            </label>
                            @endforeach
                        </div>

                        @if($question->require_reason_on_low_score)
                        <div class="reason-box" id="reason_box_{{ $question->id }}" style="display: block; margin-top: 0.75rem;">
                            <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.35rem;">
                                <i class="bi bi-chat-left-text-fill" style="color: #2563EB;"></i> Keterangan / Alasan Tambahan (Opsional):
                            </label>
                            <textarea name="reason_{{ $question->id }}" id="reason_text_{{ $question->id }}" rows="2" placeholder="Tuliskan keterangan atau alasan pendukung jawaban Anda di sini..."></textarea>
                        </div>
                        @endif

                    @elseif($question->question_type === 'single_rating')
                        <div class="sub-rating-card reality-card">
                            <label style="font-size: 0.8rem; font-weight: 800; color: var(--color-navy-primary); display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.4rem;">
                                <i class="bi bi-star-fill" style="color: #10B981;"></i> Penilaian (Skala 1 - {{ $surveyScaleMax }}):
                            </label>
                            <div class="scale-options">
                                @for($s = 1; $s <= $surveyScaleMax; $s++)
                                <label class="scale-btn">
                                    <input type="radio" name="reality_{{ $question->id }}" value="{{ $s }}" @if($question->require_reason_on_low_score) onchange="handleRealityChange({{ $question->id }}, {{ $s }}, {{ $question->low_score_threshold ?? 2 }})" @endif required>
                                    <span class="scale-label">
                                        <span class="scale-num">{{ $s }}</span>
                                        @if(isset($scaleLabels[$s]))
                                            <span class="scale-text">{{ $scaleLabels[$s] }}</span>
                                        @endif
                                    </span>
                                </label>
                                @endfor
                            </div>
                        </div>

                        @if($question->require_reason_on_low_score)
                        <div class="reason-box" id="reason_box_{{ $question->id }}" style="display: none;">
                            <label style="font-size: 0.8rem; font-weight: 800; color: #991B1B; display: flex; align-items: center; gap: 0.35rem; line-height: 1.3;">
                                <i class="bi bi-exclamation-triangle-fill"></i> Alasan (Wajib diisi jika skor &le; {{ $question->low_score_threshold ?? 2 }}):
                            </label>
                            <textarea name="reason_{{ $question->id }}" id="reason_text_{{ $question->id }}" rows="2" placeholder="Tuliskan masukan atau alasan spesifik Anda di sini..."></textarea>
                        </div>
                        @endif

                    @elseif($question->question_type === 'essay')
                        <div style="margin-top: 0.5rem;">
                            <textarea name="essay_{{ $question->id }}" id="essay_text_{{ $question->id }}" rows="3" style="width:100%; padding:0.75rem; border:1.5px solid #CBD5E1; border-radius:10px; font-family:inherit; font-size:0.875rem;" placeholder="Tuliskan jawaban atau tanggapan Anda di sini..." required></textarea>
                        </div>

                    @else
                        <!-- Dual Rating (Default Section B) -->
                        <!-- 1. Sub-card Tingkat Harapan -->
                        <div class="sub-rating-card expectation-card">
                            <label style="font-size: 0.8rem; font-weight: 800; color: #1E40AF; display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.4rem;">
                                <i class="bi bi-star-fill" style="color: #2563EB;"></i> a) Tingkat Harapan (Expectation):
                            </label>
                            <div class="scale-options">
                                @php
                                    $expLabels = [
                                        1 => 'Sangat Tidak Diharapkan',
                                        2 => 'Tidak Diharapkan',
                                        3 => $surveyScaleMax == 5 ? 'Cukup Diharapkan' : 'Diharapkan',
                                        4 => $surveyScaleMax == 5 ? 'Diharapkan' : 'Sangat Diharapkan',
                                        5 => 'Sangat Diharapkan'
                                    ];
                                @endphp
                                @for($s = 1; $s <= $surveyScaleMax; $s++)
                                <label class="scale-btn">
                                    <input type="radio" name="expectation_{{ $question->id }}" value="{{ $s }}" required>
                                    <span class="scale-label">
                                        <span class="scale-num">{{ $s }}</span>
                                        <span class="scale-text">{{ $expLabels[$s] ?? "Skala $s" }}</span>
                                    </span>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <!-- 2. Sub-card Tingkat Kenyataan -->
                        <div class="sub-rating-card reality-card">
                            <label style="font-size: 0.8rem; font-weight: 800; color: var(--color-navy-primary); display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.4rem;">
                                <i class="bi bi-check2-circle" style="color: #10B981;"></i> b) Tingkat Kenyataan (Perception):
                            </label>
                            <div class="scale-options">
                                @for($s = 1; $s <= $surveyScaleMax; $s++)
                                <label class="scale-btn">
                                    <input type="radio" name="reality_{{ $question->id }}" value="{{ $s }}" @if($question->require_reason_on_low_score) onchange="handleRealityChange({{ $question->id }}, {{ $s }}, {{ $question->low_score_threshold ?? 2 }})" @endif required>
                                    <span class="scale-label">
                                        <span class="scale-num">{{ $s }}</span>
                                        <span class="scale-text">{{ $scaleLabels[$s] ?? "Nilai $s" }}</span>
                                    </span>
                                </label>
                                @endfor
                            </div>
                        </div>

                        @if($question->require_reason_on_low_score)
                        <!-- Dynamic Reason Box -->
                        <div class="reason-box" id="reason_box_{{ $question->id }}" style="display: none;">
                            <label style="font-size: 0.8rem; font-weight: 800; color: #991B1B; display: flex; align-items: center; gap: 0.35rem; line-height: 1.3;">
                                <i class="bi bi-exclamation-triangle-fill"></i> Alasan (Wajib diisi karena skor Kenyataan &le; {{ $question->low_score_threshold ?? 2 }}):
                            </label>
                            <textarea name="reason_{{ $question->id }}" id="reason_text_{{ $question->id }}" rows="2" placeholder="Tuliskan masukan atau alasan spesifik Anda di sini..."></textarea>
                        </div>
                        @endif
                    @endif
                </div>
            @endforeach

            <div class="btn-nav-wrapper">
                <button type="button" class="btn-stepper btn-prev" onclick="goToStep(2)">
                    <i class="bi bi-arrow-left"></i> Kembali
                </button>
                @if($sectionCQuestions->count() > 0)
                    <button type="button" class="btn-stepper btn-next" onclick="goToStep(4)">
                        Selanjutnya <i class="bi bi-arrow-right"></i>
                    </button>
                @else
                    <button type="submit" class="btn-stepper btn-next" style="background: #10B981;">
                        <i class="bi bi-send-fill"></i> Kirim Survei
                    </button>
                @endif
            </div>
        </div>

        <!-- STEP 4: BAGIAN C - PERTANYAAN UMUM & TAMBAHAN -->
        @if($sectionCQuestions->count() > 0)
        <div class="form-card step-panel" id="step4" style="display: none;">
            <div style="margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin-bottom: 0.25rem;">BAGIAN C. PERTANYAAN UMUM</h2>
                <p style="color: #64748B; font-size: 0.85rem;">Pertanyaan umum dan masukan tambahan untuk menyempurnakan survei Anda.</p>
            </div>

            @foreach($sectionCQuestions as $question)
                <div class="question-block" id="q_block_{{ $question->id }}"
                     data-qnum="C{{ $question->question_number }}"
                     data-applies-emp="{{ $question->applies_to_employment_status ?? '' }}"
                     data-applies-pos="{{ $question->applies_to_positions ?? '' }}"
                     data-applies-gender="{{ $question->applies_to_gender ?? '' }}">
                    <div style="display: flex; gap: 0.6rem; margin-bottom: 0.75rem; align-items: flex-start;">
                        <span style="background: var(--color-navy-primary); color: #FFF; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; flex-shrink: 0; margin-top: 0.1rem;">
                            C{{ $question->question_number }}
                        </span>
                        <h4 style="font-size: 0.95rem; font-weight: 700; color: #0F172A; line-height: 1.45;">
                            {{ $question->question_text }}
                        </h4>
                    </div>

                    @if($question->question_type === 'multiple_choice')
                        <div class="mcq-options" style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem;">
                            @php
                                $options = is_array($question->options_json) ? $question->options_json : json_decode($question->options_json ?? '[]', true);
                                if (empty($options)) {
                                    $options = ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'];
                                }
                            @endphp
                            @foreach($options as $optIndex => $optText)
                            <label style="display: flex; align-items: center; gap: 0.65rem; padding: 0.65rem 0.85rem; border: 1.5px solid #E2E8F0; border-radius: 8px; cursor: pointer; transition: all 0.2s; background: #FFFFFF;">
                                <input type="radio" name="choice_{{ $question->id }}" value="{{ $optText }}" required style="accent-color: var(--color-navy-primary); width: 16px; height: 16px;">
                                <span style="font-size: 0.875rem; color: #1E293B; font-weight: 600;">{{ $optText }}</span>
                            </label>
                            @endforeach
                        </div>

                        @if($question->require_reason_on_low_score)
                        <div class="reason-box" id="reason_box_{{ $question->id }}" style="display: block; margin-top: 0.75rem;">
                            <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.35rem;">
                                <i class="bi bi-chat-left-text-fill" style="color: #2563EB;"></i> Keterangan / Alasan Tambahan (Opsional):
                            </label>
                            <textarea name="reason_{{ $question->id }}" id="reason_text_{{ $question->id }}" rows="2" placeholder="Tuliskan keterangan atau alasan pendukung jawaban Anda di sini..."></textarea>
                        </div>
                        @endif

                    @elseif($question->question_type === 'single_rating' || ($question->question_number === 1 && empty($question->question_type)))
                        <div class="scale-options">
                            @foreach([1 => '1 (Sangat Kecil)', 2 => '2 (Kecil)', 3 => '3 (Besar)', 4 => '4 (Sangat Besar)'] as $val => $txt)
                            <label class="scale-btn">
                                <input type="radio" name="general_{{ $question->id }}" value="{{ $val }}" onchange="handleC1Change({{ $val }})" required>
                                <span class="scale-label">
                                    <span class="scale-num">{{ $val }}</span>
                                    <span class="scale-text">{{ $txt }}</span>
                                </span>
                            </label>
                            @endforeach
                        </div>
                    @elseif($question->question_type === 'essay' || ($question->question_number === 2 && empty($question->question_type)))
                        <div id="c2_container">
                            <textarea name="general_reason_{{ $question->id }}" id="general_reason_text" rows="3" style="width:100%; padding:0.75rem; border:1.5px solid #CBD5E1; border-radius:10px; font-family:inherit; font-size:0.875rem;" placeholder="Tuliskan pandangan atau saran perbaikan Anda di sini..."></textarea>
                        </div>
                    @endif
                </div>
            @endforeach

            <div class="btn-nav-wrapper">
                <button type="button" class="btn-stepper btn-prev" onclick="goToStep(3)">
                    <i class="bi bi-arrow-left"></i> Kembali
                </button>
                <button type="submit" class="btn-stepper btn-next" style="background: #10B981;">
                    <i class="bi bi-send-fill"></i> Kirim Jawaban Survei
                </button>
            </div>
        </div>
        @endif

    </form>
</div>
@endsection

@section('scripts')
<script>
    let currentStep = 1;
    const totalSteps = {{ $sectionCQuestions->count() > 0 ? 4 : 3 }};
    const stepTitles = {
        1: 'Prolog & Petunjuk',
        2: 'Identitas Responden',
        3: 'Dimensi Inti',
        4: 'Pertanyaan Umum'
    };

    function goToStep(step) {
        if (step > currentStep) {
            if (currentStep === 2 && !validateStep2()) return;
            if (currentStep === 3 && !validateStep3()) return;
        }

        document.querySelectorAll('.step-panel').forEach(panel => panel.style.display = 'none');
        const targetPanel = document.getElementById('step' + step);
        if (targetPanel) {
            targetPanel.style.display = 'block';
        }

        for (let i = 1; i <= totalSteps; i++) {
            const ind = document.getElementById('stepIndicator' + i);
            const badge = document.getElementById('stepBadge' + i);
            if (!ind || !badge) continue;
            if (i < step) {
                ind.className = 'step-item completed';
                badge.innerHTML = '<i class="bi bi-check-lg"></i>';
            } else if (i === step) {
                ind.className = 'step-item active';
                badge.innerHTML = i;
            } else {
                ind.className = 'step-item';
                badge.innerHTML = i;
            }
        }

        // Update mobile summary
        const pct = Math.round((step / totalSteps) * 100);
        const mobileBadge = document.getElementById('mobileStepBadge');
        const mobileTitle = document.getElementById('mobileStepTitle');
        const mobilePct = document.getElementById('mobileStepPct');
        const fillBar = document.getElementById('stepperProgressFill');

        if (mobileBadge) mobileBadge.textContent = `Langkah ${step} dari ${totalSteps}`;
        if (mobileTitle) mobileTitle.textContent = stepTitles[step] || '';
        if (mobilePct) mobilePct.textContent = `${pct}%`;
        if (fillBar) fillBar.style.width = `${pct}%`;

        currentStep = step;
        if (step >= 3) {
            evaluateQuestionEligibility();
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function toggleOutsourcingField() {
        const empStatusEl = document.getElementById('employment_status');
        const wrapper = document.getElementById('outsourcing_company_wrapper');
        const selectEl = document.getElementById('outsourcing_company');
        if (!empStatusEl || !wrapper || !selectEl) return;

        if (empStatusEl.value === 'Outsourcing') {
            wrapper.style.display = 'block';
            selectEl.required = true;
        } else {
            wrapper.style.display = 'none';
            selectEl.required = false;
            selectEl.value = '';
        }
    }

    function handleEmploymentStatusChange() {
        toggleOutsourcingField();
        evaluateQuestionEligibility();
    }

    function evaluateQuestionEligibility() {
        const empStatusEl = document.getElementById('employment_status');
        const posEl = document.getElementById('position');
        const genderEl = document.getElementById('gender');

        const userEmp = empStatusEl ? empStatusEl.value.trim().toLowerCase() : '';
        const userPos = posEl ? posEl.value.trim().toLowerCase() : '';
        const userGender = genderEl ? genderEl.value.trim().toLowerCase() : '';

        const leadershipList = ['general manager', 'manager', 'ast. manager', 'supervisor', 'ast. supervisor', 'karu'];

        document.querySelectorAll('.question-block').forEach(block => {
            const appliesEmp = (block.getAttribute('data-applies-emp') || '').trim().toLowerCase();
            const appliesPos = (block.getAttribute('data-applies-pos') || '').trim().toLowerCase();
            const appliesGender = (block.getAttribute('data-applies-gender') || '').trim().toLowerCase();
            const qNum = (block.getAttribute('data-qnum') || '').trim();

            let empMatch = true;
            if (appliesEmp && !appliesEmp.includes('semua')) {
                if (appliesEmp.includes('outsourcing')) {
                    empMatch = userEmp.includes('outsourcing');
                } else if (appliesEmp.includes('kontrak')) {
                    empMatch = userEmp.includes('kontrak') && !userEmp.includes('outsourcing');
                } else if (appliesEmp.includes('tetap')) {
                    empMatch = userEmp.includes('tetap');
                } else {
                    empMatch = (appliesEmp === userEmp);
                }
            }

            let posMatch = true;
            if (appliesPos && !appliesPos.includes('semua')) {
                const positions = appliesPos.split(',').map(p => p.trim());
                posMatch = positions.includes(userPos);
                if (!posMatch) {
                    for (let p of positions) {
                        if (p.includes('karu ke atas') || p.includes('leadership')) {
                            if (leadershipList.includes(userPos)) {
                                posMatch = true;
                                break;
                            }
                        }
                    }
                }
            }

            let genderMatch = true;
            if (appliesGender && !appliesGender.includes('semua')) {
                genderMatch = (appliesGender === userGender);
            }

            // Legacy fallback for Question 6 if no explicit attributes set
            let legacyMatch = true;
            if (qNum === '6' && !appliesEmp && !appliesPos) {
                legacyMatch = (userEmp === 'tetap' && leadershipList.includes(userPos));
            }

            const isEligible = empMatch && posMatch && genderMatch && legacyMatch;

            if (isEligible) {
                block.style.display = 'block';
                // Restore required attributes for rating / choice inputs
                const expInputs = block.querySelectorAll('input[name^="expectation_"]');
                const realInputs = block.querySelectorAll('input[name^="reality_"]');
                const choiceInputs = block.querySelectorAll('input[name^="choice_"]');
                const generalInputs = block.querySelectorAll('input[name^="general_"]');
                const essayInput = block.querySelector('textarea[name^="essay_"]');

                if (expInputs.length > 0) expInputs.forEach(r => r.required = true);
                if (realInputs.length > 0) realInputs.forEach(r => r.required = true);
                if (choiceInputs.length > 0) choiceInputs.forEach(r => r.required = true);
                if (generalInputs.length > 0) generalInputs.forEach(r => r.required = true);
                if (essayInput) essayInput.required = true;
            } else {
                block.style.display = 'none';
                // Remove required and reset values
                block.querySelectorAll('input[type="radio"]').forEach(r => {
                    r.required = false;
                    r.checked = false;
                });
                block.querySelectorAll('textarea').forEach(t => {
                    t.required = false;
                    t.value = '';
                });
                const reasonBox = block.querySelector('.reason-box');
                if (reasonBox) reasonBox.style.display = 'none';
            }
        });
    }

    function evaluateBonusEligibility() {
        evaluateQuestionEligibility();
    }

    function handleRealityChange(qId, val, threshold = 2) {
        const reasonBox = document.getElementById('reason_box_' + qId);
        const reasonText = document.getElementById('reason_text_' + qId);
        if (!reasonBox) return;

        if (val <= threshold) {
            reasonBox.style.display = 'block';
            if (reasonText) reasonText.required = true;
        } else {
            reasonBox.style.display = 'none';
            if (reasonText) {
                reasonText.required = false;
                reasonText.value = '';
            }
        }
    }

    function handleC1Change(val) {
        const c2Text = document.getElementById('general_reason_text');
        if (c2Text) {
            if (val <= 2) {
                c2Text.required = true;
                c2Text.placeholder = 'Wajib diisi karena Anda memilih skala 1 atau 2 pada pertanyaan C1...';
            } else {
                c2Text.required = false;
                c2Text.placeholder = 'Tuliskan alasan Anda di sini (opsional)...';
            }
        }
    }

    function validateStep2() {
        const step2Inputs = document.querySelectorAll('#step2 [required]');
        for (let input of step2Inputs) {
            if (!input.value.trim()) {
                if (input.id === 'outsourcing_company') {
                    alert('Silakan pilih nama perusahaan outsourcing Anda (KBM, BAP, atau GCI).');
                } else {
                    alert('Silakan lengkapi seluruh data identitas responden pada Bagian A.');
                }
                input.focus();
                return false;
            }
        }
        return true;
    }

    function validateStep3() {
        const step3Blocks = document.querySelectorAll('#step3 .question-block');
        for (let block of step3Blocks) {
            if (block.style.display === 'none') continue;
            
            const expInputs = block.querySelectorAll('input[name^="expectation_"]');
            const realInputs = block.querySelectorAll('input[name^="reality_"]');
            const choiceInputs = block.querySelectorAll('input[name^="choice_"]');
            const essayInput = block.querySelector('textarea[name^="essay_"]');
            
            if (expInputs.length > 0 && realInputs.length > 0) {
                const expChecked = block.querySelector('input[name^="expectation_"]:checked');
                const realChecked = block.querySelector('input[name^="reality_"]:checked');
                if (!expChecked || !realChecked) {
                    alert('Silakan berikan penilaian Tingkat Harapan dan Tingkat Kenyataan untuk seluruh butir pertanyaan.');
                    block.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                const qId = block.id.replace('q_block_', '');
                const reasonBox = document.getElementById('reason_box_' + qId);
                const reasonText = document.getElementById('reason_text_' + qId);
                if (reasonBox && reasonBox.style.display !== 'none') {
                    if (!reasonText || !reasonText.value.trim()) {
                        alert('Silakan isi kolom Alasan untuk pertanyaan dengan nilai yang memerlukan alasan.');
                        if (reasonText) reasonText.focus();
                        return false;
                    }
                }
            } else if (realInputs.length > 0) {
                const realChecked = block.querySelector('input[name^="reality_"]:checked');
                if (!realChecked) {
                    alert('Silakan berikan penilaian untuk butir pertanyaan ini.');
                    block.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                const qId = block.id.replace('q_block_', '');
                const reasonBox = document.getElementById('reason_box_' + qId);
                const reasonText = document.getElementById('reason_text_' + qId);
                if (reasonBox && reasonBox.style.display !== 'none') {
                    if (!reasonText || !reasonText.value.trim()) {
                        alert('Silakan isi kolom Alasan untuk pertanyaan dengan nilai yang memerlukan alasan.');
                        if (reasonText) reasonText.focus();
                        return false;
                    }
                }
            } else if (choiceInputs.length > 0) {
                const choiceChecked = block.querySelector('input[name^="choice_"]:checked');
                if (!choiceChecked) {
                    alert('Silakan pilih salah satu jawaban untuk pertanyaan pilihan ganda.');
                    block.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
            } else if (essayInput) {
                if (!essayInput.value.trim()) {
                    alert('Silakan lengkapi jawaban uraian Anda.');
                    essayInput.focus();
                    return false;
                }
            }
        }
        return true;
    }

    function validateFullForm() {
        if (!validateStep2()) {
            goToStep(2);
            return false;
        }
        if (!validateStep3()) {
            goToStep(3);
            return false;
        }

        const step4 = document.getElementById('step4');
        if (step4) {
            const step4Blocks = step4.querySelectorAll('.question-block');
            for (let block of step4Blocks) {
                if (block.style.display === 'none') continue;

                const choiceInputs = block.querySelectorAll('input[name^="choice_"]');
                const generalInputs = block.querySelectorAll('input[name^="general_"]');
                const reasonBox = block.querySelector('.reason-box');

                if (choiceInputs.length > 0) {
                    const choiceChecked = block.querySelector('input[name^="choice_"]:checked');
                    if (!choiceChecked) {
                        alert('Silakan pilih salah satu jawaban untuk pertanyaan pada Bagian C.');
                        goToStep(4);
                        block.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return false;
                    }
                } else if (generalInputs.length > 0) {
                    const genChecked = block.querySelector('input[name^="general_"]:checked');
                    if (!genChecked) {
                        alert('Silakan berikan penilaian pada pertanyaan Bagian C.');
                        goToStep(4);
                        block.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return false;
                    }
                }

                if (reasonBox && reasonBox.style.display !== 'none') {
                    const rText = reasonBox.querySelector('textarea');
                    if (rText && rText.required && !rText.value.trim()) {
                        alert('Silakan lengkapi alasan pada pertanyaan Bagian C.');
                        goToStep(4);
                        rText.focus();
                        return false;
                    }
                }
            }
        }

        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleOutsourcingField();
        evaluateQuestionEligibility();
        const form = document.getElementById('surveyForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!validateFullForm()) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
</script>
@endsection
