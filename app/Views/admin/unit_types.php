<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="flex:1;overflow-y:auto;padding:24px;background:#0a0e1a;">

    <!-- Header -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <div style="font-size:16px;font-weight:500;color:#c0d8f0;letter-spacing:1px;">
                Unit Types
            </div>
            <div style="font-size:11px;color:#3a5070;margin-top:4px;">
                Kelola jenis unsur dan icon-nya
            </div>
        </div>
        <button onclick="openAddModal()" style="background:#00cfff18;border:0.5px solid #00cfff55;color:#00cfff;
                   font-size:12px;padding:8px 16px;border-radius:6px;cursor:pointer;
                   display:flex;align-items:center;gap:8px;">
            <i class="fa fa-plus" style="font-size:11px;"></i> Tambah Jenis Unsur
        </button>
    </div>

    <!-- Grid Unit Types -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));
              gap:12px;" id="unitTypeGrid">

        <?php if (empty($unitTypes)): ?>
        <div style="grid-column:1/-1;background:#0d1220;border:0.5px solid #1e2d4a;
                  border-radius:8px;padding:40px;text-align:center;color:#2a4060;
                  font-size:12px;">
            <i class="fa fa-layer-group" style="font-size:32px;margin-bottom:12px;display:block;"></i>
            Belum ada jenis unsur. Tambahkan sekarang!
        </div>
        <?php else: ?>
        <?php foreach ($unitTypes as $type): ?>
        <div style="background:#0d1220;border:0.5px solid #1e2d4a;border-radius:10px;
                  padding:16px;display:flex;flex-direction:column;align-items:center;
                  gap:10px;transition:border-color 0.2s;" onmouseover="this.style.borderColor='#00cfff33'"
            onmouseout="this.style.borderColor='#1e2d4a'">

            <!-- Icon -->
            <div style="width:56px;height:56px;border-radius:50%;background:#0a1628;
                    border:0.5px solid #1e2d4a;display:flex;align-items:center;
                    justify-content:center;overflow:hidden;">
                <?php if ($type['icon_path']): ?>
                <img src="/<?= $type['icon_path'] ?>" alt="icon" style="width:36px;height:36px;object-fit:contain;">
                <?php else: ?>
                <i class="fa fa-question" style="color:#2a4060;font-size:20px;"></i>
                <?php endif; ?>
            </div>

            <!-- Name -->
            <div style="font-size:13px;font-weight:500;color:#c0d8f0;text-align:center;">
                <?= esc($type['name']) ?>
            </div>

            <!-- Creator -->
            <div style="font-size:10px;color:#2a4060;text-align:center;">
                by <?= esc($type['creator_name'] ?? 'Unknown') ?>
            </div>

            <div style="display:flex;align-items:center;gap:6px;">
                <div style="width:28px;height:4px;border-radius:2px;
                    background:<?= $type['color'] ?? '#00cfff' ?>;
                    opacity:0.8;"></div>
                <span style="font-size:10px;color:#2a4060;">Trail color</span>
            </div>

            <!-- Actions -->
            <div style="display:flex;gap:6px;width:100%;">
                <button
                    onclick="openEditModal(<?= $type['id'] ?>, '<?= esc($type['name']) ?>', '<?= $type['icon_path'] ?>', '<?= $type['color'] ?? '#00cfff' ?>')"
                    style="flex:1;font-size:11px;padding:6px;border-radius:5px;
                        background:#00cfff18;color:#00cfff;border:0.5px solid #00cfff44;
                        cursor:pointer;">
                    <i class="fa fa-pen" style="font-size:10px;"></i> Edit
                </button>
                <button onclick="confirmDelete(<?= $type['id'] ?>, '<?= esc($type['name']) ?>')" style="flex:1;font-size:11px;padding:6px;border-radius:5px;
                        background:#ff445518;color:#ff6070;border:0.5px solid #ff445544;
                        cursor:pointer;">
                    <i class="fa fa-trash" style="font-size:10px;"></i> Hapus
                </button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- ── MODAL TAMBAH ── -->
<div id="modalAdd" style="display:none;position:fixed;inset:0;background:#000000aa;
                        z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#0d1220;border:0.5px solid #1e2d4a;border-radius:12px;
    padding:24px;width:100%;max-width:420px;margin:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <div style="font-size:14px;font-weight:500;color:#c0d8f0;">Tambah Jenis Unsur</div>
            <div onclick="closeAddModal()" style="color:#3a5070;cursor:pointer;font-size:18px;line-height:1;">&times;
            </div>
        </div>

        <form action="/unit-types/store" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div style="margin-bottom:16px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">NAMA JENIS UNSUR *</label>
                <input type="text" name="name" placeholder="cth: KRI, Pesawat, Tank, Heli" style="width:100%;background:#0a1220;border:0.5px solid #1e2d4a;
                      border-radius:6px;padding:9px 14px;font-size:12px;
                      color:#c0d8f0;outline:none;" required>
            </div>

            <div style="margin-bottom:16px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                display:block;margin-bottom:6px;">WARNA TRAIL</label>
                <div style="display:flex;align-items:center;gap:10px;">
                    <input type="color" name="color" id="addColor" value="#00cfff" style="width:40px;height:36px;border:0.5px solid #1e2d4a;
                  border-radius:6px;background:#0a1220;cursor:pointer;padding:2px;">
                    <div style="flex:1;">
                        <div style="font-size:11px;color:#2a4060;">
                            Warna untuk garis trail pergerakan unit ini di peta
                        </div>
                        <div style="margin-top:6px;display:flex;gap:6px;flex-wrap:wrap;" id="addColorPresets">
                            <div onclick="setColor('addColor','#00cfff')" title="Cyan"
                                style="width:20px;height:20px;border-radius:50%;background:#00cfff;cursor:pointer;">
                            </div>
                            <div onclick="setColor('addColor','#a855f7')" title="Purple"
                                style="width:20px;height:20px;border-radius:50%;background:#a855f7;cursor:pointer;">
                            </div>
                            <div onclick="setColor('addColor','#22c55e')" title="Green"
                                style="width:20px;height:20px;border-radius:50%;background:#22c55e;cursor:pointer;">
                            </div>
                            <div onclick="setColor('addColor','#f59e0b')" title="Amber"
                                style="width:20px;height:20px;border-radius:50%;background:#f59e0b;cursor:pointer;">
                            </div>
                            <div onclick="setColor('addColor','#ff4455')" title="Red"
                                style="width:20px;height:20px;border-radius:50%;background:#ff4455;cursor:pointer;">
                            </div>
                            <div onclick="setColor('addColor','#3b82f6')" title="Blue"
                                style="width:20px;height:20px;border-radius:50%;background:#3b82f6;cursor:pointer;">
                            </div>
                            <div onclick="setColor('addColor','#f97316')" title="Orange"
                                style="width:20px;height:20px;border-radius:50%;background:#f97316;cursor:pointer;">
                            </div>
                            <div onclick="setColor('addColor','#ec4899')" title="Pink"
                                style="width:20px;height:20px;border-radius:50%;background:#ec4899;cursor:pointer;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">ICON (PNG / JPG / SVG)</label>

                <!-- Preview area -->
                <div style="display:flex;align-items:center;gap:14px;">
                    <div id="addIconPreview" style="width:60px;height:60px;border-radius:50%;background:#0a1628;
                      border:0.5px solid #1e2d4a;display:flex;align-items:center;
                      justify-content:center;flex-shrink:0;">
                        <i class="fa fa-image" style="color:#2a4060;font-size:20px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div onclick="document.getElementById('addIconFile').click()" style="background:#0a1220;border:0.5px dashed #1e2d4a;border-radius:6px;
                        padding:10px;text-align:center;cursor:pointer;font-size:11px;
                        color:#2a4060;transition:border-color 0.2s;" onmouseover="this.style.borderColor='#00cfff44'"
                            onmouseout="this.style.borderColor='#1e2d4a'">
                            <i class="fa fa-upload" style="margin-right:6px;"></i>Pilih File
                        </div>
                        <div style="font-size:10px;color:#1a2a40;margin-top:4px;">
                            Opsional. Max 2MB
                        </div>
                    </div>
                </div>
                <input type="file" id="addIconFile" name="icon_file" accept=".png,.jpg,.jpeg,.svg" style="display:none;"
                    onchange="previewIcon(this, 'addIconPreview')">
            </div>

            <div style="display:flex;gap:8px;">
                <button type="button" onclick="closeAddModal()" style="flex:1;padding:9px;border-radius:6px;font-size:12px;
                       background:transparent;border:0.5px solid #1e2d4a;
                       color:#5070a0;cursor:pointer;">
                    Batal
                </button>
                <button type="submit" style="flex:1;padding:9px;border-radius:6px;font-size:12px;
                       background:#00cfff18;border:0.5px solid #00cfff55;
                       color:#00cfff;cursor:pointer;">
                    <i class="fa fa-plus me-1"></i> Tambah
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ── MODAL EDIT ── -->
<div id="modalEdit" style="display:none;position:fixed;inset:0;background:#000000aa;
                            z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#0d1220;border:0.5px solid #1e2d4a;border-radius:12px;
              padding:24px;width:100%;max-width:420px;margin:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <div style="font-size:14px;font-weight:500;color:#c0d8f0;">Edit Jenis Unsur</div>
            <div onclick="closeEditModal()" style="color:#3a5070;cursor:pointer;font-size:18px;line-height:1;">&times;
            </div>
        </div>

        <form id="editForm" action="" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div style="margin-bottom:16px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">NAMA JENIS UNSUR *</label>
                <input type="text" id="editName" name="name" style="width:100%;background:#0a1220;border:0.5px solid #1e2d4a;
                      border-radius:6px;padding:9px 14px;font-size:12px;
                      color:#c0d8f0;outline:none;" required>
            </div>

            <div style="margin-bottom:16px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                display:block;margin-bottom:6px;">WARNA TRAIL</label>
                <div style="display:flex;align-items:center;gap:10px;">
                    <input type="color" name="color" id="editColor" value="#00cfff" style="width:40px;height:36px;border:0.5px solid #1e2d4a;
                  border-radius:6px;background:#0a1220;cursor:pointer;padding:2px;">
                    <div style="flex:1;">
                        <div style="margin-top:6px;display:flex;gap:6px;flex-wrap:wrap;">
                            <div onclick="setColor('editColor','#00cfff')"
                                style="width:20px;height:20px;border-radius:50%;background:#00cfff;cursor:pointer;">
                            </div>
                            <div onclick="setColor('editColor','#a855f7')"
                                style="width:20px;height:20px;border-radius:50%;background:#a855f7;cursor:pointer;">
                            </div>
                            <div onclick="setColor('editColor','#22c55e')"
                                style="width:20px;height:20px;border-radius:50%;background:#22c55e;cursor:pointer;">
                            </div>
                            <div onclick="setColor('editColor','#f59e0b')"
                                style="width:20px;height:20px;border-radius:50%;background:#f59e0b;cursor:pointer;">
                            </div>
                            <div onclick="setColor('editColor','#ff4455')"
                                style="width:20px;height:20px;border-radius:50%;background:#ff4455;cursor:pointer;">
                            </div>
                            <div onclick="setColor('editColor','#3b82f6')"
                                style="width:20px;height:20px;border-radius:50%;background:#3b82f6;cursor:pointer;">
                            </div>
                            <div onclick="setColor('editColor','#f97316')"
                                style="width:20px;height:20px;border-radius:50%;background:#f97316;cursor:pointer;">
                            </div>
                            <div onclick="setColor('editColor','#ec4899')"
                                style="width:20px;height:20px;border-radius:50%;background:#ec4899;cursor:pointer;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">GANTI ICON (opsional)</label>
                <div style="display:flex;align-items:center;gap:14px;">
                    <div id="editIconPreview" style="width:60px;height:60px;border-radius:50%;background:#0a1628;
                      border:0.5px solid #1e2d4a;display:flex;align-items:center;
                      justify-content:center;flex-shrink:0;overflow:hidden;">
                    </div>
                    <div style="flex:1;">
                        <div onclick="document.getElementById('editIconFile').click()" style="background:#0a1220;border:0.5px dashed #1e2d4a;border-radius:6px;
                        padding:10px;text-align:center;cursor:pointer;font-size:11px;
                        color:#2a4060;" onmouseover="this.style.borderColor='#00cfff44'"
                            onmouseout="this.style.borderColor='#1e2d4a'">
                            <i class="fa fa-upload" style="margin-right:6px;"></i>Ganti Icon
                        </div>
                    </div>
                </div>
                <input type="file" id="editIconFile" name="icon_file" accept=".png,.jpg,.jpeg,.svg"
                    style="display:none;" onchange="previewIcon(this, 'editIconPreview')">
            </div>

            <div style="display:flex;gap:8px;">
                <button type="button" onclick="closeEditModal()" style="flex:1;padding:9px;border-radius:6px;font-size:12px;
                       background:transparent;border:0.5px solid #1e2d4a;
                       color:#5070a0;cursor:pointer;">
                    Batal
                </button>
                <button type="submit" style="flex:1;padding:9px;border-radius:6px;font-size:12px;
                       background:#00cfff18;border:0.5px solid #00cfff55;
                       color:#00cfff;cursor:pointer;">
                    <i class="fa fa-floppy-disk me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// ── Modal Add ──
function openAddModal() {
    document.getElementById('modalAdd').style.display = 'flex';
}

function closeAddModal() {
    document.getElementById('modalAdd').style.display = 'none';
}

// ── Modal Edit ──
function openEditModal(id, name, iconPath, color) {
    document.getElementById('editName').value = name;
    document.getElementById('editColor').value = color || '#00cfff';
    document.getElementById('editForm').action = '/unit-types/update/' + id;

    // Tampilkan icon preview
    const preview = document.getElementById('editIconPreview');
    if (iconPath && iconPath !== 'null') {
        preview.innerHTML =
            '<img src="/' + iconPath + '" style="width:36px;height:36px;object-fit:contain;">';
    } else {
        preview.innerHTML =
            '<i class="fa fa-image" style="color:#2a4060;font-size:20px;"></i>';
    }

    document.getElementById('modalEdit').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('modalEdit').style.display = 'none';
}

// ── Preview icon sebelum upload ──
function previewIcon(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.innerHTML =
                '<img src="' + e.target.result +
                '" style="width:36px;height:36px;object-fit:contain;">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function setColor(inputId, color) {
    document.getElementById(inputId).value = color;
}

// ── Confirm delete ──
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Hapus Jenis Unsur?',
        html: 'Jenis unsur <strong style="color:#00cfff">' + name +
            '</strong> akan dihapus permanen.',
        icon: 'warning',
        background: '#0d1220',
        color: '#c0d8f0',
        showCancelButton: true,
        confirmButtonColor: '#ff4455',
        cancelButtonColor: '#1e2d4a',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = '/unit-types/delete/' + id;
        }
    });
}

// ── Tutup modal klik backdrop ──
document.getElementById('modalAdd').addEventListener('click', function(e) {
    if (e.target === this) closeAddModal();
});
document.getElementById('modalEdit').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
<?= $this->endSection() ?>