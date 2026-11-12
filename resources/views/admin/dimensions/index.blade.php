@extends('layouts.app')

@section('title', 'Master Dimensi Survei - Adiprima Survey Center')

@section('styles')
<style>
    .dimensions-header {
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .dimensions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .modal-dialog-box {
        background: #FFFFFF;
        border-radius: 16px;
        width: 95%;
        max-width: 520px;
        padding: 1.75rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        margin: 1rem auto;
    }

    .category-pill {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.825rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .dimensions-header {
            flex-direction: column;
            align-items: stretch;
        }

        .dimensions-header button {
            justify-content: center;
            width: 100%;
        }

        .dimensions-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .modal-dialog-box {
            padding: 1.25rem;
            border-radius: 12px;
        }
    }
</style>
@endsection

@section('content')
<div class="dimensions-header">
    <div>
        <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--color-navy-primary); letter-spacing: -0.02em;">
            Dimensi Indikator Survei
        </h1>
        <p style="color: #64748B; font-size: 0.9rem; margin-top: 0.25rem;">
            Dimensi pertanyaan dikelompokkan secara terstruktur berdasarkan <b>Kategori Survei</b> untuk standardisasi kuesioner dan analitik.
        </p>
    </div>
    <button onclick="openModal('createDimensionModal')" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.75rem 1.25rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: background 0.2s ease; box-shadow: 0 4px 10px rgba(12, 43, 100, 0.2);">
        <i class="bi bi-plus-circle-fill"></i> Tambah Dimensi Baru
    </button>
</div>

<!-- Category Filter Pills -->
<div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; overflow-x: auto; padding-bottom: 0.25rem;">
    <a href="{{ route('admin.dimensions.index', ['category' => 'all']) }}" 
       class="category-pill"
       style="border: 1.5px solid {{ empty($categoryFilter) || $categoryFilter == 'all' ? 'var(--color-navy-primary)' : '#CBD5E1' }}; background: {{ empty($categoryFilter) || $categoryFilter == 'all' ? 'var(--color-navy-primary)' : '#FFFFFF' }}; color: {{ empty($categoryFilter) || $categoryFilter == 'all' ? '#FFF' : '#475569' }};">
        <i class="bi bi-grid-fill"></i> Semua Kategori ({{ $dimensions->count() }})
    </a>
    @foreach($categories as $cat)
        @php
            $isSelected = ($categoryFilter == $cat->id || $categoryFilter === $cat->slug || $categoryFilter === $cat->name);
        @endphp
        <a href="{{ route('admin.dimensions.index', ['category' => $cat->slug]) }}" 
           class="category-pill"
           style="border: 1.5px solid {{ $isSelected ? 'var(--color-navy-primary)' : '#CBD5E1' }}; background: {{ $isSelected ? 'var(--color-navy-primary)' : '#FFFFFF' }}; color: {{ $isSelected ? '#FFF' : '#475569' }};">
            <i class="bi {{ $cat->icon ?: 'bi-tags-fill' }}"></i> {{ $cat->name }} ({{ $cat->dimensions_count }})
        </a>
    @endforeach
</div>

<!-- Dimension Cards Grid -->
<div class="dimensions-grid">
    @forelse($dimensions as $dim)
    <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: 12px; padding: 1.25rem 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: {{ $dim->color ?? '#2563EB' }};"></div>
        
        <div>
            <!-- Category Badge & Code -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; margin-bottom: 0.45rem;">
                        <span style="font-size: 0.725rem; font-weight: 800; padding: 0.15rem 0.55rem; border-radius: 6px; background: #EFF6FF; color: #2563EB;">
                            <i class="bi {{ $dim->category?->icon ?: 'bi-tag-fill' }}"></i> {{ $dim->category?->name ?? 'Kategori Umum' }}
                        </span>
                        <span style="font-size: 0.725rem; font-weight: 800; padding: 0.15rem 0.55rem; border-radius: 6px; background: {{ $dim->color ?? '#2563EB' }}15; color: {{ $dim->color ?? '#2563EB' }};">
                            {{ $dim->code ?? 'DIM' }} &bull; Urutan #{{ $dim->order }}
                        </span>
                    </div>
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--color-navy-primary); margin: 0; line-height: 1.3;">
                        {{ $dim->name }}
                    </h3>
                </div>
                <div style="width: 18px; height: 18px; border-radius: 50%; background: {{ $dim->color ?? '#2563EB' }}; flex-shrink: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.15);" title="Warna: {{ $dim->color }}"></div>
            </div>

            <p style="color: #64748B; font-size: 0.85rem; line-height: 1.45; margin-bottom: 1.25rem;">
                {{ $dim->description ?: 'Tidak ada deskripsi.' }}
            </p>
        </div>

        <div style="border-top: 1px solid #F1F5F9; padding-top: 0.85rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
            <div style="display: flex; gap: 0.75rem; font-size: 0.775rem; color: #64748B; flex-wrap: wrap;">
                <span><i class="bi bi-file-earmark-text" style="color: #2563EB;"></i> <b>{{ $dim->questions_count }}</b> Soal Terpasang</span>
                <span><i class="bi bi-collection" style="color: #7C3AED;"></i> <b>{{ $dim->question_templates_count }}</b> Bank Template</span>
            </div>
            <div style="display: flex; gap: 0.35rem;">
                <button onclick="editDimension({{ json_encode($dim) }})" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #334155; padding: 0.35rem 0.6rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer;" title="Edit Dimensi">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <form action="{{ route('admin.dimensions.destroy', $dim->id) }}" method="POST" onsubmit="return confirm('Hapus dimensi {{ $dim->name }}?');" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: #FEE2E2; border: 1px solid #FCA5A5; color: #DC2626; padding: 0.35rem 0.6rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer;" title="Hapus Dimensi">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; background: #FFFFFF; border: 1px dashed var(--color-border); border-radius: 12px; padding: 3rem; text-align: center; color: #64748B;">
        <i class="bi bi-tags" style="font-size: 2.5rem; color: #94A3B8; margin-bottom: 0.5rem; display: block;"></i>
        <p style="font-size: 1rem; font-weight: 600;">Belum ada dimensi pertanyaan untuk kategori ini.</p>
        <p style="font-size: 0.875rem;">Klik "Tambah Dimensi Baru" untuk mulai menambahkan dimensi.</p>
    </div>
    @endforelse
</div>

<!-- Modal Create Dimension -->
<div id="createDimensionModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 1rem;">
    <div class="modal-dialog-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">
                Tambah Dimensi Baru
            </h3>
            <button onclick="closeModal('createDimensionModal')" style="background: none; border: none; font-size: 1.25rem; color: #94A3B8; cursor: pointer;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.dimensions.store') }}" method="POST">
            @csrf
            
            <!-- Category Selection (Mandatory) -->
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Kategori Survei *</label>
                <select name="survey_category_id" id="createDimCategoryId" onchange="onDimCategoryChanged('create')" required style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem; background: #FFFFFF; font-weight: 600;">
                    <option value="">-- Pilih Kategori Survei --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (isset($categoryFilter) && ($categoryFilter == $cat->id || $categoryFilter == $cat->slug)) ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <small style="color: #64748B; font-size: 0.75rem; margin-top: 0.2rem; display: block;">Dimensi ini hanya akan muncul saat mengelola kuesioner dengan kategori survei yang dipilih.</small>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Nama Dimensi *</label>
                <input type="text" name="name" required placeholder="Contoh: Basic Needs / K3 / Kepuasan Layanan" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.4rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Kode Singkat *</label>
                    <select id="createDimCodeSelect" onchange="onDimCodeSelectChanged('create')" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem; background: #FFFFFF; font-weight: 600;">
                        <option value="">-- Pilih Kode Singkat --</option>
                        <optgroup label="Standar Engagement">
                            <option value="BN">BN (Basic Needs)</option>
                            <option value="IC">IC (Individual Contribution)</option>
                            <option value="TW">TW (Teamwork)</option>
                            <option value="GW">GW (Growth)</option>
                            <option value="GN">GN (General / Umum)</option>
                        </optgroup>
                        <optgroup label="Standar Budaya & K3">
                            <option value="BK">BK (Budaya Kerja)</option>
                            <option value="AKH">AKH (Nilai AKHLAK)</option>
                            <option value="K3">K3 (Keselamatan Kerja)</option>
                            <option value="5R">5R (5R / Kerapian)</option>
                            <option value="LP">LP (Leadership)</option>
                        </optgroup>
                        <optgroup label="Standar Layanan & Kepuasan">
                            <option value="KP">KP (Kualitas Produk)</option>
                            <option value="CS">CS (Customer Satisfaction)</option>
                            <option value="SQ">SQ (Service Quality)</option>
                            <option value="LOG">LOG (Logistik)</option>
                        </optgroup>
                        <option value="__custom__">✏️ [+] Tulis Kode Baru Lainnya...</option>
                    </select>
                    <input type="text" name="code" id="createDimCodeCustom" placeholder="Ketik kode (misal: BN, K3)..." style="display: none; width: 100%; margin-top: 0.35rem; padding: 0.6rem 0.85rem; border: 1px solid #93C5FD; border-radius: 8px; font-size: 0.875rem; background: #EFF6FF; text-transform: uppercase;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Urutan *</label>
                    <input type="number" name="order" id="createDimOrder" value="1" min="1" required style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem;">
                </div>
            </div>
            <!-- Category used hint -->
            <div id="createDimTakenHint" style="margin-bottom: 1rem; font-size: 0.75rem; color: #64748B; min-height: 20px;"></div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Warna Identitas (Grafik)</label>
                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <input type="color" name="color" value="#2563EB" style="width: 44px; height: 38px; border: 1px solid var(--color-border); border-radius: 6px; cursor: pointer; padding: 2px;">
                    <span style="font-size: 0.8rem; color: #64748B;">Pilih warna representasi grafik.</span>
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Deskripsi Dimensi</label>
                <textarea name="description" rows="3" placeholder="Penjelasan ruang lingkup dimensi ini..." style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem; font-family: inherit; resize: vertical;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.6rem;">
                <button type="button" onclick="closeModal('createDimensionModal')" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #64748B; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.6rem 1.15rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Simpan Dimensi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Dimension -->
<div id="editDimensionModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 1rem;">
    <div class="modal-dialog-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-navy-primary); margin: 0;">
                Edit Dimensi
            </h3>
            <button onclick="closeModal('editDimensionModal')" style="background: none; border: none; font-size: 1.25rem; color: #94A3B8; cursor: pointer;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="editDimensionForm" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Category Selection (Mandatory) -->
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Kategori Survei *</label>
                <select id="editDimCategoryId" name="survey_category_id" onchange="onDimCategoryChanged('edit')" required style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem; background: #FFFFFF; font-weight: 600;">
                    <option value="">-- Pilih Kategori Survei --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Nama Dimensi *</label>
                <input type="text" id="editDimName" name="name" required style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.4rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Kode Singkat *</label>
                    <select id="editDimCodeSelect" onchange="onDimCodeSelectChanged('edit')" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem; background: #FFFFFF; font-weight: 600;">
                        <option value="">-- Pilih Kode Singkat --</option>
                        <optgroup label="Standar Engagement">
                            <option value="BN">BN (Basic Needs)</option>
                            <option value="IC">IC (Individual Contribution)</option>
                            <option value="TW">TW (Teamwork)</option>
                            <option value="GW">GW (Growth)</option>
                            <option value="GN">GN (General / Umum)</option>
                        </optgroup>
                        <optgroup label="Standar Budaya & K3">
                            <option value="BK">BK (Budaya Kerja)</option>
                            <option value="AKH">AKH (Nilai AKHLAK)</option>
                            <option value="K3">K3 (Keselamatan Kerja)</option>
                            <option value="5R">5R (5R / Kerapian)</option>
                            <option value="LP">LP (Leadership)</option>
                        </optgroup>
                        <optgroup label="Standar Layanan & Kepuasan">
                            <option value="KP">KP (Kualitas Produk)</option>
                            <option value="CS">CS (Customer Satisfaction)</option>
                            <option value="SQ">SQ (Service Quality)</option>
                            <option value="LOG">LOG (Logistik)</option>
                        </optgroup>
                        <option value="__custom__">✏️ [+] Tulis Kode Baru Lainnya...</option>
                    </select>
                    <input type="text" name="code" id="editDimCodeCustom" placeholder="Ketik kode baru..." style="display: none; width: 100%; margin-top: 0.35rem; padding: 0.6rem 0.85rem; border: 1px solid #93C5FD; border-radius: 8px; font-size: 0.875rem; background: #EFF6FF; text-transform: uppercase;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Urutan *</label>
                    <input type="number" id="editDimOrder" name="order" min="1" required style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem;">
                </div>
            </div>
            <!-- Category used hint -->
            <div id="editDimTakenHint" style="margin-bottom: 1rem; font-size: 0.75rem; color: #64748B; min-height: 20px;"></div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Warna Identitas</label>
                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <input type="color" id="editDimColor" name="color" style="width: 44px; height: 38px; border: 1px solid var(--color-border); border-radius: 6px; cursor: pointer; padding: 2px;">
                    <span style="font-size: 0.8rem; color: #64748B;">Pilih warna chart.</span>
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Deskripsi Dimensi</label>
                <textarea id="editDimDesc" name="description" rows="3" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.875rem; font-family: inherit; resize: vertical;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.6rem;">
                <button type="button" onclick="closeModal('editDimensionModal')" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #64748B; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.6rem 1.15rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    const allDimensionsData = @json($allDimensionsData ?? []);

    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
        if (modalId === 'createDimensionModal') {
            onDimCategoryChanged('create');
        }
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function onDimCodeSelectChanged(mode) {
        const select = document.getElementById(mode === 'create' ? 'createDimCodeSelect' : 'editDimCodeSelect');
        const custom = document.getElementById(mode === 'create' ? 'createDimCodeCustom' : 'editDimCodeCustom');
        
        if (select.value === '__custom__') {
            custom.style.display = 'block';
            custom.required = true;
            custom.value = '';
            custom.focus();
        } else {
            custom.style.display = 'none';
            custom.required = false;
            custom.value = select.value;
        }
    }

    function onDimCategoryChanged(mode, currentDimId = null) {
        const catSelect = document.getElementById(mode === 'create' ? 'createDimCategoryId' : 'editDimCategoryId');
        const hintEl = document.getElementById(mode === 'create' ? 'createDimTakenHint' : 'editDimTakenHint');
        const orderInput = document.getElementById(mode === 'create' ? 'createDimOrder' : 'editDimOrder');
        if (!catSelect || !hintEl) return;

        const catId = catSelect.value;
        if (!catId) {
            hintEl.innerHTML = '';
            return;
        }

        const dimsInCat = allDimensionsData.filter(d => d.survey_category_id == catId && (currentDimId === null || d.id != currentDimId));
        
        if (dimsInCat.length === 0) {
            hintEl.innerHTML = '<span style="color: #10B981; font-weight: 600;">ℹ️ Belum ada dimensi di kategori ini. Anda dapat menggunakan urutan #1.</span>';
            if (mode === 'create') orderInput.value = 1;
            return;
        }

        const takenOrders = dimsInCat.map(d => parseInt(d.order)).filter(n => !isNaN(n));
        const maxOrder = takenOrders.length > 0 ? Math.max(...takenOrders) : 0;
        
        if (mode === 'create') {
            orderInput.value = maxOrder + 1;
        }

        const items = dimsInCat.map(d => `<span style="display: inline-block; background: #F1F5F9; border: 1px solid #E2E8F0; padding: 0.15rem 0.45rem; border-radius: 4px; margin: 0.15rem; font-size: 0.725rem;"><b>#${d.order}</b> (${d.code || '-'}) ${d.name}</span>`).join(' ');

        hintEl.innerHTML = `<span style="font-weight: 700; color: #475569;">Sudah terpakai di kategori ini:</span> ${items}`;
    }

    function editDimension(dim) {
        document.getElementById('editDimensionForm').action = `/admin/dimensions/${dim.id}`;
        document.getElementById('editDimCategoryId').value = dim.survey_category_id || '';
        document.getElementById('editDimName').value = dim.name || '';
        document.getElementById('editDimOrder').value = dim.order || 1;
        document.getElementById('editDimColor').value = dim.color || '#2563EB';
        document.getElementById('editDimDesc').value = dim.description || '';

        // Handle code select & custom
        const codeSelect = document.getElementById('editDimCodeSelect');
        const codeCustom = document.getElementById('editDimCodeCustom');
        const dimCode = (dim.code || '').trim();
        
        const optionExists = Array.from(codeSelect.options).some(opt => opt.value === dimCode);
        if (optionExists && dimCode !== '') {
            codeSelect.value = dimCode;
            codeCustom.style.display = 'none';
            codeCustom.value = dimCode;
        } else if (dimCode !== '') {
            codeSelect.value = '__custom__';
            codeCustom.style.display = 'block';
            codeCustom.value = dimCode;
        } else {
            codeSelect.value = '';
            codeCustom.style.display = 'none';
            codeCustom.value = '';
        }

        onDimCategoryChanged('edit', dim.id);
        openModal('editDimensionModal');
    }

    window.onclick = function(event) {
        if (event.target.id === 'createDimensionModal') closeModal('createDimensionModal');
        if (event.target.id === 'editDimensionModal') closeModal('editDimensionModal');
    }
</script>
@endsection
@endsection
