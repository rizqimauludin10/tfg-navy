<?= $this->extend('layouts/main') ?>

<?= $this->section('extra_css') ?>
<style>
.users-wrap {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
    background: #0a0e1a;
}

.tbl {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.tbl th {
    font-size: 10px;
    color: #2a4060;
    letter-spacing: 1px;
    padding: 10px 14px;
    border-bottom: 0.5px solid #1e2d4a;
    text-align: left;
    white-space: nowrap;
    background: #0d1220;
    position: sticky;
    top: 0;
    z-index: 1;
}

.tbl td {
    padding: 10px 14px;
    border-bottom: 0.5px solid #0e1a2a;
    color: #7090b0;
    vertical-align: middle;
}

.tbl tr:hover td {
    background: #0d1525;
}

.role-badge {
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 10px;
    white-space: nowrap;
    border: 0.5px solid;
}

.role-superadmin {
    background: #a855f718;
    color: #a855f7;
    border-color: #a855f744;
}

.role-admin {
    background: #00cfff18;
    color: #00cfff;
    border-color: #00cfff44;
}

.role-pelaku {
    background: #22c55e18;
    color: #22c55e;
    border-color: #22c55e44;
}

.action-btn {
    font-size: 10px;
    padding: 4px 10px;
    border-radius: 4px;
    cursor: pointer;
    border: 0.5px solid;
    margin-right: 4px;
    white-space: nowrap;
}

.btn-edit {
    background: #00cfff15;
    color: #00cfff;
    border-color: #00cfff44;
}

.btn-reset {
    background: #f59e0b15;
    color: #f59e0b;
    border-color: #f59e0b44;
}

.btn-del {
    background: #ff445515;
    color: #ff4455;
    border-color: #ff445544;
}

.form-ctrl {
    width: 100%;
    background: #0a1220;
    border: 0.5px solid #1e2d4a;
    border-radius: 6px;
    padding: 9px 14px;
    font-size: 12px;
    color: #c0d8f0;
    outline: none;
}

.form-ctrl:focus {
    border-color: #00cfff44;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="users-wrap">

    <!-- Header -->
    <div style="display:flex;align-items:center;justify-content:space-between;
              margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="font-size:16px;font-weight:500;color:#c0d8f0;letter-spacing:1px;">
                User Management
            </div>
            <div style="font-size:11px;color:#3a5070;margin-top:4px;">
                Kelola akun dan role user
            </div>
        </div>
        <button onclick="openAddModal()" style="background:#00cfff18;border:0.5px solid #00cfff55;color:#00cfff;
                   font-size:12px;padding:8px 16px;border-radius:6px;cursor:pointer;
                   display:flex;align-items:center;gap:8px;">
            <i class="fa fa-user-plus" style="font-size:11px;"></i> Tambah User
        </button>
    </div>

    <!-- Stats -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));
              gap:10px;margin-bottom:20px;">
        <?php
      $total      = count($users);
      $superadmin = count(array_filter($users, fn($u) => $u['role'] === 'superadmin'));
      $admin      = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
      $pelaku     = count(array_filter($users, fn($u) => $u['role'] === 'pelaku'));
    ?>
        <div style="background:#0d1220;border:0.5px solid #1e2d4a;border-radius:8px;
                padding:14px;text-align:center;">
            <div style="font-size:22px;font-weight:600;color:#00cfff;"><?= $total ?></div>
            <div style="font-size:10px;color:#2a4060;letter-spacing:1px;margin-top:2px;">TOTAL USER</div>
        </div>
        <div style="background:#0d1220;border:0.5px solid #a855f744;border-radius:8px;
                padding:14px;text-align:center;">
            <div style="font-size:22px;font-weight:600;color:#a855f7;"><?= $superadmin ?></div>
            <div style="font-size:10px;color:#2a4060;letter-spacing:1px;margin-top:2px;">SUPERADMIN</div>
        </div>
        <div style="background:#0d1220;border:0.5px solid #00cfff44;border-radius:8px;
                padding:14px;text-align:center;">
            <div style="font-size:22px;font-weight:600;color:#00cfff;"><?= $admin ?></div>
            <div style="font-size:10px;color:#2a4060;letter-spacing:1px;margin-top:2px;">ADMIN</div>
        </div>
        <div style="background:#0d1220;border:0.5px solid #22c55e44;border-radius:8px;
                padding:14px;text-align:center;">
            <div style="font-size:22px;font-weight:600;color:#22c55e;"><?= $pelaku ?></div>
            <div style="font-size:10px;color:#2a4060;letter-spacing:1px;margin-top:2px;">PELAKU</div>
        </div>
    </div>

    <!-- Table -->
    <div style="background:#0d1220;border:0.5px solid #1e2d4a;
              border-radius:8px;overflow:hidden;">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>NAMA</th>
                    <th>EMAIL</th>
                    <th>ROLE</th>
                    <th>DIBUAT</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px;color:#2a4060;">
                        Belum ada user
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $i => $user): ?>
                <tr>
                    <td style="color:#2a4060;"><?= $i + 1 ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:30px;height:30px;border-radius:50%;
                            background:#00cfff22;border:1px solid #00cfff44;
                            display:flex;align-items:center;justify-content:center;
                            font-size:11px;color:#00cfff;font-weight:600;flex-shrink:0;">
                                <?= strtoupper(substr($user['name'], 0, 2)) ?>
                            </div>
                            <span style="color:#c0d8f0;"><?= esc($user['name']) ?></span>
                            <?php if ($user['id'] == session()->get('user_id')): ?>
                            <span style="font-size:10px;color:#3a5070;">(you)</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?= esc($user['email']) ?></td>
                    <td>
                        <span class="role-badge role-<?= $user['role'] ?>">
                            <?= strtoupper($user['role']) ?>
                        </span>
                    </td>
                    <td style="color:#3a5070;white-space:nowrap;">
                        <?= date('d M Y', strtotime($user['created_at'])) ?>
                    </td>
                    <td>
                        <?php if ($user['id'] != session()->get('user_id')): ?>
                        <button class="action-btn btn-edit" onclick="openEditModal(
                          <?= $user['id'] ?>,
                          '<?= esc($user['name']) ?>',
                          '<?= esc($user['email']) ?>',
                          '<?= $user['role'] ?>')">
                            <i class="fa fa-pen"></i> Edit
                        </button>
                        <button class="action-btn btn-reset"
                            onclick="openResetModal(<?= $user['id'] ?>, '<?= esc($user['name']) ?>')">
                            <i class="fa fa-key"></i> Reset PW
                        </button>
                        <button class="action-btn btn-del"
                            onclick="confirmDelete(<?= $user['id'] ?>, '<?= esc($user['name']) ?>')">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                        <?php else: ?>
                        <span style="font-size:11px;color:#1e2d4a;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ── MODAL TAMBAH ── -->
<div id="modalAdd" style="display:none;position:fixed;inset:0;background:#000000aa;
                           z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#0d1220;border:0.5px solid #1e2d4a;border-radius:12px;
              padding:24px;width:100%;max-width:440px;margin:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;
                margin-bottom:20px;">
            <div style="font-size:14px;font-weight:500;color:#c0d8f0;">Tambah User Baru</div>
            <div onclick="closeAddModal()" style="color:#3a5070;cursor:pointer;font-size:18px;">&times;</div>
        </div>
        <form action="/users/store" method="POST">
            <?= csrf_field() ?>
            <div style="margin-bottom:14px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">NAMA LENGKAP *</label>
                <input type="text" name="name" class="form-ctrl" placeholder="Nama lengkap" required>
            </div>
            <div style="margin-bottom:14px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">EMAIL *</label>
                <input type="email" name="email" class="form-ctrl" placeholder="email@tracksys.com" required>
            </div>
            <div style="margin-bottom:14px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">PASSWORD *</label>
                <input type="password" name="password" class="form-ctrl" placeholder="Min. 6 karakter" required
                    minlength="6">
            </div>
            <div style="margin-bottom:20px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">ROLE *</label>
                <select name="role" class="form-ctrl" required>
                    <option value="">— Pilih Role —</option>
                    <option value="superadmin">Superadmin</option>
                    <option value="admin">Admin</option>
                    <option value="pelaku">Pelaku</option>
                </select>
            </div>
            <div style="display:flex;gap:8px;">
                <button type="button" onclick="closeAddModal()" style="flex:1;padding:9px;border-radius:6px;font-size:12px;
                       background:transparent;border:0.5px solid #1e2d4a;
                       color:#5070a0;cursor:pointer;">Batal</button>
                <button type="submit" style="flex:1;padding:9px;border-radius:6px;font-size:12px;
                       background:#00cfff18;border:0.5px solid #00cfff55;
                       color:#00cfff;cursor:pointer;">
                    <i class="fa fa-user-plus me-1"></i> Tambah
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ── MODAL EDIT ── -->
<div id="modalEdit" style="display:none;position:fixed;inset:0;background:#000000aa;
                            z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#0d1220;border:0.5px solid #1e2d4a;border-radius:12px;
              padding:24px;width:100%;max-width:440px;margin:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;
                margin-bottom:20px;">
            <div style="font-size:14px;font-weight:500;color:#c0d8f0;">Edit User</div>
            <div onclick="closeEditModal()" style="color:#3a5070;cursor:pointer;font-size:18px;">&times;</div>
        </div>
        <form id="editForm" action="" method="POST">
            <?= csrf_field() ?>
            <div style="margin-bottom:14px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">NAMA LENGKAP *</label>
                <input type="text" id="editName" name="name" class="form-ctrl" required>
            </div>
            <div style="margin-bottom:14px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">EMAIL *</label>
                <input type="email" id="editEmail" name="email" class="form-ctrl" required>
            </div>
            <div style="margin-bottom:20px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">ROLE *</label>
                <select id="editRole" name="role" class="form-ctrl" required>
                    <option value="superadmin">Superadmin</option>
                    <option value="admin">Admin</option>
                    <option value="pelaku">Pelaku</option>
                </select>
            </div>
            <div style="display:flex;gap:8px;">
                <button type="button" onclick="closeEditModal()" style="flex:1;padding:9px;border-radius:6px;font-size:12px;
                       background:transparent;border:0.5px solid #1e2d4a;
                       color:#5070a0;cursor:pointer;">Batal</button>
                <button type="submit" style="flex:1;padding:9px;border-radius:6px;font-size:12px;
                       background:#00cfff18;border:0.5px solid #00cfff55;
                       color:#00cfff;cursor:pointer;">
                    <i class="fa fa-floppy-disk me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ── MODAL RESET PASSWORD ── -->
<div id="modalReset" style="display:none;position:fixed;inset:0;background:#000000aa;
                             z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#0d1220;border:0.5px solid #1e2d4a;border-radius:12px;
              padding:24px;width:100%;max-width:400px;margin:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;
                margin-bottom:20px;">
            <div style="font-size:14px;font-weight:500;color:#c0d8f0;">Reset Password</div>
            <div onclick="closeResetModal()" style="color:#3a5070;cursor:pointer;font-size:18px;">&times;</div>
        </div>
        <div id="resetUserName" style="font-size:12px;color:#3a5070;margin-bottom:16px;"></div>
        <form id="resetForm" action="" method="POST">
            <?= csrf_field() ?>
            <div style="margin-bottom:20px;">
                <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">PASSWORD BARU *</label>
                <input type="password" name="new_password" id="newPassword" class="form-ctrl"
                    placeholder="Min. 6 karakter" required minlength="6">
            </div>
            <div style="display:flex;gap:8px;">
                <button type="button" onclick="closeResetModal()" style="flex:1;padding:9px;border-radius:6px;font-size:12px;
                       background:transparent;border:0.5px solid #1e2d4a;
                       color:#5070a0;cursor:pointer;">Batal</button>
                <button type="submit" style="flex:1;padding:9px;border-radius:6px;font-size:12px;
                       background:#f59e0b18;border:0.5px solid #f59e0b55;
                       color:#f59e0b;cursor:pointer;">
                    <i class="fa fa-key me-1"></i> Reset
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
function openEditModal(id, name, email, role) {
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRole').value = role;
    document.getElementById('editForm').action = '/users/update/' + id;
    document.getElementById('modalEdit').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('modalEdit').style.display = 'none';
}

// ── Modal Reset Password ──
function openResetModal(id, name) {
    document.getElementById('resetUserName').textContent =
        'Reset password untuk: ' + name;
    document.getElementById('resetForm').action = '/users/reset-password/' + id;
    document.getElementById('newPassword').value = '';
    document.getElementById('modalReset').style.display = 'flex';
}

function closeResetModal() {
    document.getElementById('modalReset').style.display = 'none';
}

// ── Confirm Delete ──
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Hapus User?',
        html: 'User <strong style="color:#00cfff">' + name +
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
            window.location = '/users/delete/' + id;
        }
    });
}

// ── Tutup modal klik backdrop ──
['modalAdd', 'modalEdit', 'modalReset'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
});
</script>
<?= $this->endSection() ?>