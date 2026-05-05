<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="flex:1;overflow-y:auto;padding:24px;background:#0a0e1a;">

    <!-- Header -->
    <div style="margin-bottom:24px;">
        <div style="font-size:16px;font-weight:500;color:#c0d8f0;letter-spacing:1px;">
            Map Settings
        </div>
        <div style="font-size:11px;color:#3a5070;margin-top:4px;">
            Upload dan kelola gambar peta operasi
        </div>
    </div>

    <!-- Upload Form -->
    <div style="background:#0d1220;border:0.5px solid #1e2d4a;border-radius:10px;
              padding:20px;margin-bottom:24px;max-width:600px;">
        <div style="font-size:11px;color:#2a4060;letter-spacing:2px;margin-bottom:16px;">
            UPLOAD PETA BARU
        </div>

        <form action="/map-settings/upload" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div style="margin-bottom:14px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">NAMA PETA</label>
                <input type="text" name="map_name" placeholder="cth: Peta Operasi Zona Alpha" style="width:100%;background:#0a1220;border:0.5px solid #1e2d4a;
                      border-radius:6px;padding:9px 14px;font-size:12px;
                      color:#c0d8f0;outline:none;">
            </div>

            <div style="margin-bottom:18px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">FILE PETA (PNG / JPG)</label>
                <div style="border:0.5px dashed #1e2d4a;border-radius:6px;padding:20px;
                    text-align:center;cursor:pointer;transition:border-color 0.2s;" id="dropArea">
                    <i class="fa fa-cloud-arrow-up" style="font-size:24px;color:#2a4060;
                                                  margin-bottom:8px;display:block;"></i>
                    <div style="font-size:11px;color:#2a4060;">
                        Klik atau drag file ke sini
                    </div>
                    <div style="font-size:10px;color:#1a2a40;margin-top:4px;">
                        Rekomendasi: 1920×1080px, max 5MB
                    </div>
                    <input type="file" name="map_image" id="mapFile" accept=".jpg,.jpeg,.png" style="display:none;"
                        required>
                </div>
                <div style="font-size:11px;color:#00cfff;margin-top:6px;display:none;" id="fileName"></div>
            </div>

            <button type="submit" style="background:#00cfff18;border:0.5px solid #00cfff55;
                     color:#00cfff;font-size:12px;padding:9px 20px;
                     border-radius:6px;cursor:pointer;letter-spacing:1px;">
                <i class="fa fa-upload me-2"></i>UPLOAD PETA
            </button>
        </form>
    </div>

    <!-- Daftar Peta -->
    <div style="max-width:600px;">
        <div style="font-size:11px;color:#2a4060;letter-spacing:2px;margin-bottom:12px;">
            DAFTAR PETA
        </div>

        <?php if (empty($maps)): ?>
        <div style="background:#0d1220;border:0.5px solid #1e2d4a;border-radius:8px;
                  padding:20px;text-align:center;color:#2a4060;font-size:12px;">
            Belum ada peta diupload
        </div>
        <?php else: ?>
        <?php foreach ($maps as $map): ?>
        <div style="background:#0d1220;border:0.5px solid <?= $map['is_active'] ? '#00cfff44' : '#1e2d4a' ?>;
                  border-radius:8px;padding:14px 16px;margin-bottom:8px;
                  display:flex;align-items:center;gap:14px;">

            <!-- Preview -->
            <img src="/<?= $map['image_path'] ?>" alt="map" style="width:80px;height:50px;object-fit:cover;
                    border-radius:5px;border:0.5px solid #1e2d4a;flex-shrink:0;">

            <!-- Info -->
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;color:#c0d8f0;white-space:nowrap;
                      overflow:hidden;text-overflow:ellipsis;">
                    <?= esc($map['name']) ?>
                </div>
                <div style="font-size:10px;color:#2a4060;margin-top:2px;">
                    Upload: <?= date('d M Y H:i', strtotime($map['created_at'])) ?>
                </div>
                <?php if ($map['is_active']): ?>
                <span style="font-size:10px;background:#00cfff18;color:#00cfff;
                         border:0.5px solid #00cfff44;border-radius:10px;
                         padding:1px 8px;margin-top:4px;display:inline-block;">
                    AKTIF
                </span>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div style="display:flex;gap:6px;flex-shrink:0;">
                <?php if (!$map['is_active']): ?>
                <a href="/map-settings/set-active/<?= $map['id'] ?>" style="font-size:10px;padding:5px 10px;border-radius:5px;
                    background:#00cfff18;color:#00cfff;border:0.5px solid #00cfff44;
                    text-decoration:none;">
                    Set Aktif
                </a>
                <?php endif; ?>
                <a href="/map-settings/delete/<?= $map['id'] ?>" onclick="return confirm('Hapus peta ini?')" style="font-size:10px;padding:5px 10px;border-radius:5px;
                    background:#ff445518;color:#ff6070;border:0.5px solid #ff445544;
                    text-decoration:none;">
                    Hapus
                </a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Drag & drop area
const dropArea = document.getElementById('dropArea');
const mapFile = document.getElementById('mapFile');
const fileName = document.getElementById('fileName');

dropArea.addEventListener('click', () => mapFile.click());

dropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropArea.style.borderColor = '#00cfff55';
});
dropArea.addEventListener('dragleave', () => {
    dropArea.style.borderColor = '#1e2d4a';
});
dropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dropArea.style.borderColor = '#1e2d4a';
    if (e.dataTransfer.files[0]) {
        mapFile.files = e.dataTransfer.files;
        showFileName(e.dataTransfer.files[0].name);
    }
});

mapFile.addEventListener('change', () => {
    if (mapFile.files[0]) showFileName(mapFile.files[0].name);
});

function showFileName(name) {
    fileName.style.display = 'block';
    fileName.textContent = '✓ ' + name;
}
</script>
<?= $this->endSection() ?>