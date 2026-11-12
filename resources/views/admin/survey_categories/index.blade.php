@extends('layouts.app')

@section('title', 'Kelola Kategori Survei - Adiprima Survey Center')

@section('styles')
<style>
    .cat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .cat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
    }

    .cat-card {
        background: #FFFFFF;
        border: 1px solid var(--color-border);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
    }

    .cat-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 8px 16px -2px rgba(0, 0, 0, 0.06);
    }

    .modal-backdrop-custom {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 1300;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-box-custom {
        background: #FFFFFF;
        width: 100%;
        max-width: 540px;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-height: 90vh;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
<div>
    <!-- Top Header -->
    <div class="cat-header">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                <a href="{{ route('admin.surveys.index') }}" style="color: #64748B; text-decoration: none; font-size: 0.85rem; font-weight: 600;">
                    <i class="bi bi-arrow-left"></i> Kelola Survei
                </a>
                <span style="color: #CBD5E1;">/</span>
                <span style="color: var(--color-navy-primary); font-size: 0.85rem; font-weight: 700;">Kategori Survei</span>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--color-navy-primary); letter-spacing: -0.02em;">
                Kategori Instrumen Survei
            </h1>
            <p style="color: #64748B; font-size: 0.9rem; margin-top: 0.25rem;">
                Kelola rumpun/kategori survei perusahaan (misal: Survey Budaya Kerja, Employee Engagement Survey, Customer Satisfaction Survey).
            </p>
        </div>

        <button onclick="openAddModal()" style="background: var(--color-navy-primary); color: #FFFFFF; border: none; padding: 0.75rem 1.25rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; box-shadow: 0 4px 10px rgba(12, 43, 100, 0.2);">
            <i class="bi bi-plus-circle-fill"></i> Tambah Kategori Baru
        </button>
    </div>

    <!-- Category Grid -->
    <div class="cat-grid">
        @forelse($categories as $cat)
        <div class="cat-card">
            <div>
                <!-- Top Badge & Actions -->
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: {{ $cat->color ? $cat->color . '15' : '#EFF6FF' }}; color: {{ $cat->color ?: '#2563EB' }}; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; border: 1.5px solid {{ $cat->color ? $cat->color . '30' : '#DBEAFE' }};">
                            <i class="bi {{ $cat->icon ?: 'bi-clipboard-data' }}"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.3;">
                                {{ $cat->name }}
                            </h3>
                            <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.15rem;">
                                Slug: <code style="background: #F1F5F9; padding: 0.1rem 0.35rem; border-radius: 4px; color: #475569;">{{ $cat->slug }}</code>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.survey-categories.toggle', $cat->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: {{ $cat->is_active ? '#DEF7EC' : '#FEE2E2' }}; color: {{ $cat->is_active ? '#03543F' : '#991B1B' }}; border: 1px solid {{ $cat->is_active ? '#84E1BC' : '#FCA5A5' }}; padding: 0.2rem 0.55rem; border-radius: 9999px; font-size: 0.725rem; font-weight: 700; cursor: pointer;" title="Klik untuk mengubah status">
                            {{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </form>
                </div>

                <p style="color: #64748B; font-size: 0.85rem; line-height: 1.45; margin-bottom: 1.25rem; min-height: 38px;">
                    {{ $cat->description ?: 'Tidak ada deskripsi kategori.' }}
                </p>

                <!-- Stats per Category -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1.25rem;">
                    <div>
                        <div style="font-size: 0.7rem; color: #64748B; font-weight: 600;">Survei Berjalan:</div>
                        <div style="font-size: 1.15rem; font-weight: 800; color: #059669;">
                            {{ $cat->active_surveys_count }} <small style="font-size: 0.75rem; font-weight: 600;">Edisi</small>
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.7rem; color: #64748B; font-weight: 600;">Arsip / Riwayat:</div>
                        <div style="font-size: 1.15rem; font-weight: 800; color: #64748B;">
                            {{ $cat->archived_surveys_count }} <small style="font-size: 0.75rem; font-weight: 600;">Edisi</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Footer -->
            <div style="border-top: 1px solid #F1F5F9; padding-top: 1rem; display: flex; justify-content: space-between; align-items: center; gap: 0.5rem;">
                <a href="{{ route('admin.dashboard', ['category' => $cat->name]) }}" style="color: #2563EB; font-weight: 700; font-size: 0.825rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <i class="bi bi-pie-chart-fill"></i> Lihat Dashboard
                </a>

                <div style="display: flex; gap: 0.35rem;">
                    <button onclick="openEditModal({{ json_encode($cat) }})" style="background: #F1F5F9; color: #334155; border: 1px solid #CBD5E1; padding: 0.45rem 0.65rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer;" title="Edit Kategori">
                        <i class="bi bi-pencil-fill"></i> Edit
                    </button>
                    <form action="{{ route('admin.survey-categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $cat->name }}?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; padding: 0.45rem 0.65rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer;" title="Hapus Kategori">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; background: #FFFFFF; border: 1.5px dashed var(--color-border); border-radius: 16px; padding: 3rem; text-align: center; color: #64748B;">
            <i class="bi bi-tags" style="font-size: 2.5rem; color: #94A3B8; display: block; margin-bottom: 0.5rem;"></i>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Belum Ada Kategori Survei</h3>
            <p style="font-size: 0.875rem;">Klik tombol "Tambah Kategori Baru" untuk membuat kategori survei pertama Anda.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div id="addModal" class="modal-backdrop-custom">
    <div class="modal-box-custom">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: #0F172A; margin: 0;">Tambah Kategori Survei</h3>
            <button type="button" onclick="closeAddModal()" style="background: transparent; border: none; font-size: 1.4rem; color: #64748B; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('admin.survey-categories.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Nama Kategori *</label>
                <input type="text" name="name" required placeholder="Contoh: Survey Budaya Kerja" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.9rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Pilih Ikon Kategori</label>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <div style="width: 42px; height: 42px; border-radius: 8px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; border: 1px solid #BFDBFE; flex-shrink: 0;">
                            <i class="bi bi-clipboard-data" id="addIconPreviewI"></i>
                        </div>
                        <select name="icon" id="addIconSelect" onchange="onIconSelectChange(this, 'addIconPreviewI')" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.875rem; background: #FFFFFF; font-weight: 600;">
                            <option value="bi-clipboard-data">📋 Clipboard Data (Umum / Kuesioner)</option>
                            <option value="bi-people-fill">👥 People (Budaya Kerja & Tim)</option>
                            <option value="bi-person-heart">💖 Person Heart (Employee Engagement)</option>
                            <option value="bi-emoji-smile-fill">😊 Emoji Smile (Customer Satisfaction / CS)</option>
                            <option value="bi-heart-pulse-fill">💓 Heart Pulse (Kesejahteraan & Vitalitas)</option>
                            <option value="bi-award-fill">🎖️ Award (Penghargaan & Apresiasi)</option>
                            <option value="bi-shield-check">🛡️ Shield Check (K3, Kepatuhan & Safety)</option>
                            <option value="bi-graph-up-arrow">📈 Graph Up (Pertumbuhan & Kinerja)</option>
                            <option value="bi-briefcase-fill">💼 Briefcase (Pekerjaan & Manajemen)</option>
                            <option value="bi-chat-dots-fill">💬 Chat Dots (Komunikasi & Umpan Balik)</option>
                            <option value="bi-lightbulb-fill">💡 Lightbulb (Inovasi & Pengembangan)</option>
                            <option value="bi-building">🏢 Building (Korporat & Lingkungan Kerja)</option>
                            <option value="bi-star-fill">⭐ Star (Kualitas & Layanan Prima)</option>
                            <option value="bi-speedometer2">⏱️ Speedometer (Produktivitas & Efisiensi)</option>
                            <option value="bi-mortarboard-fill">🎓 Mortarboard (Pelatihan & Kompetensi)</option>
                            <option value="bi-trophy-fill">🏆 Trophy (Prestasi & Keunggulan)</option>
                            <option value="bi-gear-fill">⚙️ Gear (Operasional & Teknis)</option>
                            <option value="bi-tags-fill">🏷️ Tags (Kategori Umum)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Warna Aksen</label>
                    <input type="color" name="color" value="#2563EB" style="width: 100%; height: 42px; padding: 0.2rem; border: 1px solid #CBD5E1; border-radius: 8px; cursor: pointer;">
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Urutan Tampilan</label>
                <input type="number" name="order" value="0" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.9rem;">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Deskripsi Singkat</label>
                <textarea name="description" rows="3" placeholder="Tujuan atau cakupan kuesioner pada kategori ini..." style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.9rem; resize: vertical;"></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 700; color: #1E293B; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" checked style="width: 18px; height: 18px;">
                    Aktifkan Kategori Ini
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #F1F5F9; padding-top: 1.25rem;">
                <button type="button" onclick="closeAddModal()" style="background: #F1F5F9; color: #64748B; border: none; padding: 0.65rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: var(--color-navy-primary); color: #FFF; border: none; padding: 0.65rem 1.5rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem; cursor: pointer;">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div id="editModal" class="modal-backdrop-custom">
    <div class="modal-box-custom">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: #0F172A; margin: 0;">Edit Kategori Survei</h3>
            <button type="button" onclick="closeEditModal()" style="background: transparent; border: none; font-size: 1.4rem; color: #64748B; cursor: pointer;">&times;</button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Nama Kategori *</label>
                <input type="text" name="name" id="editName" required style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.9rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Pilih Ikon Kategori</label>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <div style="width: 42px; height: 42px; border-radius: 8px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; border: 1px solid #BFDBFE; flex-shrink: 0;">
                            <i class="bi bi-clipboard-data" id="editIconPreviewI"></i>
                        </div>
                        <select name="icon" id="editIcon" onchange="onIconSelectChange(this, 'editIconPreviewI')" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.875rem; background: #FFFFFF; font-weight: 600;">
                            <option value="bi-clipboard-data">📋 Clipboard Data (Umum / Kuesioner)</option>
                            <option value="bi-people-fill">👥 People (Budaya Kerja & Tim)</option>
                            <option value="bi-person-heart">💖 Person Heart (Employee Engagement)</option>
                            <option value="bi-emoji-smile-fill">😊 Emoji Smile (Customer Satisfaction / CS)</option>
                            <option value="bi-heart-pulse-fill">💓 Heart Pulse (Kesejahteraan & Vitalitas)</option>
                            <option value="bi-award-fill">🎖️ Award (Penghargaan & Apresiasi)</option>
                            <option value="bi-shield-check">🛡️ Shield Check (K3, Kepatuhan & Safety)</option>
                            <option value="bi-graph-up-arrow">📈 Graph Up (Pertumbuhan & Kinerja)</option>
                            <option value="bi-briefcase-fill">💼 Briefcase (Pekerjaan & Manajemen)</option>
                            <option value="bi-chat-dots-fill">💬 Chat Dots (Komunikasi & Umpan Balik)</option>
                            <option value="bi-lightbulb-fill">💡 Lightbulb (Inovasi & Pengembangan)</option>
                            <option value="bi-building">🏢 Building (Korporat & Lingkungan Kerja)</option>
                            <option value="bi-star-fill">⭐ Star (Kualitas & Layanan Prima)</option>
                            <option value="bi-speedometer2">⏱️ Speedometer (Produktivitas & Efisiensi)</option>
                            <option value="bi-mortarboard-fill">🎓 Mortarboard (Pelatihan & Kompetensi)</option>
                            <option value="bi-trophy-fill">🏆 Trophy (Prestasi & Keunggulan)</option>
                            <option value="bi-gear-fill">⚙️ Gear (Operasional & Teknis)</option>
                            <option value="bi-tags-fill">🏷️ Tags (Kategori Umum)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Warna Aksen</label>
                    <input type="color" name="color" id="editColor" style="width: 100%; height: 42px; padding: 0.2rem; border: 1px solid #CBD5E1; border-radius: 8px; cursor: pointer;">
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Urutan Tampilan</label>
                <input type="number" name="order" id="editOrder" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.9rem;">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Deskripsi Singkat</label>
                <textarea name="description" id="editDescription" rows="3" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.9rem; resize: vertical;"></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 700; color: #1E293B; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="editIsActive" value="1" style="width: 18px; height: 18px;">
                    Aktifkan Kategori Ini
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #F1F5F9; padding-top: 1.25rem;">
                <button type="button" onclick="closeEditModal()" style="background: #F1F5F9; color: #64748B; border: none; padding: 0.65rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: var(--color-navy-primary); color: #FFF; border: none; padding: 0.65rem 1.5rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem; cursor: pointer;">
                    Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function onIconSelectChange(select, previewIconId) {
        const iconEl = document.getElementById(previewIconId);
        if (iconEl) {
            iconEl.className = 'bi ' + select.value;
        }
    }

    function openAddModal() {
        document.getElementById('addModal').style.display = 'flex';
    }

    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }

    function openEditModal(cat) {
        document.getElementById('editForm').action = '/admin/survey-categories/' + cat.id;
        document.getElementById('editName').value = cat.name || '';
        
        const editIconSelect = document.getElementById('editIcon');
        if (editIconSelect) {
            editIconSelect.value = cat.icon || 'bi-clipboard-data';
            onIconSelectChange(editIconSelect, 'editIconPreviewI');
        }

        document.getElementById('editColor').value = cat.color || '#2563EB';
        document.getElementById('editOrder').value = cat.order || 0;
        document.getElementById('editDescription').value = cat.description || '';
        document.getElementById('editIsActive').checked = !!cat.is_active;

        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        if (event.target === addModal) closeAddModal();
        if (event.target === editModal) closeEditModal();
    }
</script>
@endsection
