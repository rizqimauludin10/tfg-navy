<?= $this->extend('layouts/main') ?>

<?= $this->section('extra_css') ?>
<style>
.history-table-wrap {
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

.type-badge {
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 10px;
    background: #00cfff18;
    color: #00cfff;
    border: 0.5px solid #00cfff33;
    white-space: nowrap;
}

.filter-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-select {
    background: #0d1220;
    border: 0.5px solid #1e2d4a;
    border-radius: 6px;
    padding: 7px 12px;
    font-size: 12px;
    color: #7090b0;
    outline: none;
    cursor: pointer;
}

.filter-select:focus {
    border-color: #00cfff44;
}

.filter-input {
    background: #0d1220;
    border: 0.5px solid #1e2d4a;
    border-radius: 6px;
    padding: 7px 12px;
    font-size: 12px;
    color: #7090b0;
    outline: none;
}

.filter-input:focus {
    border-color: #00cfff44;
}

.btn-filter {
    background: #00cfff18;
    border: 0.5px solid #00cfff44;
    color: #00cfff;
    font-size: 12px;
    padding: 7px 14px;
    border-radius: 6px;
    cursor: pointer;
    white-space: nowrap;
}

.btn-filter:hover {
    background: #00cfff28;
}

.btn-reset {
    background: transparent;
    border: 0.5px solid #1e2d4a;
    color: #3a5070;
    font-size: 12px;
    padding: 7px 14px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-reset:hover {
    border-color: #3a5070;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #2a4060;
}

.empty-state i {
    font-size: 36px;
    margin-bottom: 12px;
    display: block;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="history-table-wrap">

    <!-- Header -->
    <div style="display:flex;align-items:center;justify-content:space-between;
              margin-bottom:20px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="font-size:16px;font-weight:500;color:#c0d8f0;letter-spacing:1px;">
                Position History
            </div>
            <div style="font-size:11px;color:#3a5070;margin-top:4px;">
                Log seluruh pergerakan unsur
            </div>
        </div>
        <div style="font-size:12px;color:#2a4060;" id="totalCount">
            Memuat...
        </div>
    </div>

    <!-- Filter Bar -->
    <div style="background:#0d1220;border:0.5px solid #1e2d4a;border-radius:8px;
              padding:14px 16px;margin-bottom:16px;">
        <div class="filter-bar">
            <!-- Filter by unit -->
            <select class="filter-select" id="filterUnit">
                <option value="">Semua Unit</option>
                <?php foreach ($units as $unit): ?>
                <option value="<?= $unit['id'] ?>"><?= esc($unit['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Filter by date -->
            <input type="date" class="filter-input" id="filterDate" style="color-scheme:dark;">

            <!-- Filter by limit -->
            <select class="filter-select" id="filterLimit">
                <option value="50">50 data</option>
                <option value="100">100 data</option>
                <option value="200">200 data</option>
                <option value="500">500 data</option>
            </select>

            <button class="btn-filter" onclick="loadHistory()">
                <i class="fa fa-filter me-1"></i> Filter
            </button>
            <button class="btn-reset" onclick="resetFilter()">
                <i class="fa fa-rotate-left me-1"></i> Reset
            </button>
        </div>
    </div>

    <!-- Table -->
    <div style="background:#0d1220;border:0.5px solid #1e2d4a;border-radius:8px;
              overflow:hidden;">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>UNIT</th>
                    <th>JENIS</th>
                    <th>POS X</th>
                    <th>POS Y</th>
                    <th>DIPINDAHKAN OLEH</th>
                    <th>WAKTU</th>
                </tr>
            </thead>
            <tbody id="historyTableBody">
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:#2a4060;">
                        <i class="fa fa-spinner fa-spin"></i> Memuat data...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
async function loadHistory() {
    const unitId = document.getElementById('filterUnit').value;
    const date = document.getElementById('filterDate').value;
    const limit = document.getElementById('filterLimit').value;

    const params = new URLSearchParams();
    if (unitId) params.append('unit_id', unitId);
    if (date) params.append('date', date);
    if (limit) params.append('limit', limit);

    const tbody = document.getElementById('historyTableBody');
    tbody.innerHTML = `
      <tr>
        <td colspan="7" style="text-align:center;padding:40px;color:#2a4060;">
          <i class="fa fa-spinner fa-spin"></i> Memuat...
        </td>
      </tr>`;

    try {
        const res = await axios.get('/api/history?' + params.toString());
        const history = res.data.history;

        document.getElementById('totalCount').textContent =
            `Total: ${history.length} data`;

        if (!history.length) {
            tbody.innerHTML = `
          <tr>
            <td colspan="7">
              <div class="empty-state">
                <i class="fa fa-clock-rotate-left"></i>
                Tidak ada data histori
              </div>
            </td>
          </tr>`;
            return;
        }

        tbody.innerHTML = history.map((h, i) => `
        <tr>
          <td style="color:#2a4060;">${i + 1}</td>
          <td style="color:#c0d8f0;font-weight:500;">${h.unit_name || '—'}</td>
          <td>
            <span class="type-badge">${h.type_name || '—'}</span>
          </td>
          <td style="font-family:monospace;color:#00cfff;">
            ${(parseFloat(h.pos_x) * 100).toFixed(2)}%
          </td>
          <td style="font-family:monospace;color:#00cfff;">
            ${(parseFloat(h.pos_y) * 100).toFixed(2)}%
          </td>
          <td>${h.moved_by_name || '—'}</td>
          <td style="color:#3a5070;white-space:nowrap;">
            ${formatTime(h.timestamp)}
          </td>
        </tr>`).join('');

    } catch (e) {
        tbody.innerHTML = `
        <tr>
          <td colspan="7" style="text-align:center;padding:40px;color:#ff6070;">
            Gagal memuat data histori.
          </td>
        </tr>`;
    }
}

function resetFilter() {
    document.getElementById('filterUnit').value = '';
    document.getElementById('filterDate').value = '';
    document.getElementById('filterLimit').value = '50';
    loadHistory();
}

function formatTime(ts) {
    if (!ts) return '—';
    const d = new Date(ts);
    return d.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }) + ' ' + d.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
}

// Load saat halaman buka
loadHistory();
</script>
<?= $this->endSection() ?>