<?= $this->extend('layouts/main') ?>

<?= $this->section('extra_css') ?>
<style>
.canvas-wrap {
    cursor: default;
    position: relative;
}

#mapCanvas {
    position: absolute;
    top: 0;
    left: 0;
}

/* Popup unit */
.unit-popup {
    position: absolute;
    background: #0d1525ee;
    border: 0.5px solid #00cfff44;
    border-radius: 8px;
    padding: 12px 14px;
    width: 170px;
    backdrop-filter: blur(6px);
    z-index: 50;
    display: none;
}

.popup-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.popup-name {
    font-size: 12px;
    font-weight: 500;
    color: #00cfff;
}

.popup-close {
    color: #3a5070;
    cursor: pointer;
    font-size: 16px;
    line-height: 1;
}

.popup-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 3px;
}

.popup-key {
    font-size: 10px;
    color: #3a5070;
}

.popup-val {
    font-size: 10px;
    color: #7090b0;
}

.popup-divider {
    height: 0.5px;
    background: #1e2d4a;
    margin: 7px 0;
}

.popup-actions {
    display: flex;
    gap: 5px;
    margin-top: 8px;
}

.popup-btn {
    flex: 1;
    font-size: 10px;
    padding: 5px 0;
    border-radius: 4px;
    cursor: pointer;
    text-align: center;
    border: 0.5px solid;
}

.popup-btn-edit {
    background: #00cfff15;
    color: #00cfff;
    border-color: #00cfff44;
}

.popup-btn-del {
    background: #ff445515;
    color: #ff4455;
    border-color: #ff445544;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="canvas-wrap" id="canvasWrap">

    <div class="fs-badge" id="fsBadge">FULLSCREEN MAP MODE</div>
    <div class="map-badge" id="mapBadge" style="display:none; color:#00cfff;"></div>

    <!-- No map placeholder -->
    <div class="no-map" id="noMap">
        <i class="fa fa-map"></i>
        <p>BELUM ADA PETA</p>
        <?php if (session()->get('role') === 'superadmin'): ?>
        <a href="/map-settings" style="font-size:11px;color:#00cfff;border:0.5px solid #00cfff44;
                padding:5px 14px;border-radius:5px;text-decoration:none;margin-top:4px;">
            Upload Peta
        </a>
        <?php endif; ?>
    </div>

    <!-- Fabric canvas -->
    <canvas id="mapCanvas"></canvas>

    <!-- Popup info unit -->
    <div class="unit-popup" id="unitPopup">
        <div class="popup-header">
            <span class="popup-name" id="popupName">—</span>
            <span class="popup-close" onclick="closePopup()">×</span>
        </div>
        <div class="popup-row">
            <span class="popup-key">Jenis</span>
            <span class="popup-val" id="popupType">—</span>
        </div>
        <div class="popup-row">
            <span class="popup-key">Pos X</span>
            <span class="popup-val" id="popupX">—</span>
        </div>
        <div class="popup-row">
            <span class="popup-key">Pos Y</span>
            <span class="popup-val" id="popupY">—</span>
        </div>
        <div class="popup-divider"></div>
        <div class="popup-row">
            <span class="popup-key">Update</span>
            <span class="popup-val" id="popupTime">—</span>
        </div>
        <div style="margin-top:6px;">
            <span style="font-size:10px;" id="popupStartLabel">—</span>
        </div>
        <?php if (in_array(session()->get('role'), ['admin','superadmin'])): ?>
        <div class="popup-actions">
            <div class="popup-btn popup-btn-edit" id="popupBtnEdit">Edit</div>
            <div class="popup-btn popup-btn-del" id="popupBtnDel">Hapus</div>
        </div>
        <div style="margin-top:6px;">
            <div class="popup-btn" id="popupBtnStart" style="background:#22c55e15;color:#22c55e;border:0.5px solid #22c55e44;
              text-align:center;padding:5px 0;border-radius:4px;cursor:pointer;
              font-size:10px;">
                <i class="fa fa-flag" style="font-size:9px;"></i> Set Titik Awal
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Zoom controls -->
    <div class="zoom-ctrl">
        <div class="zoom-btn" id="zoomIn">+</div>
        <div class="zoom-level" id="zoomLevel">100%</div>
        <div class="zoom-btn" id="zoomOut">−</div>
        <div class="zoom-btn" id="zoomReset">
            <i class="fa fa-expand" style="font-size:10px;"></i>
        </div>
    </div>

    <!-- Toggle Trail -->
    <div style="position:absolute;bottom:16px;left:56px;z-index:10;">
        <div id="btnTrail" onclick="toggleTrail()" style="background:#0d1525cc;border:0.5px solid #00cfff44;border-radius:5px;
              padding:5px 10px;font-size:10px;color:#00cfff;cursor:pointer;
              display:flex;align-items:center;gap:5px;white-space:nowrap;">
            <i class="fa fa-route" style="font-size:10px;"></i> Trail
        </div>
    </div>

    <!-- Pan hint -->
    <div style="position:absolute;top:10px;right:16px;z-index:10;
            background:#0d1525cc;border:0.5px solid #1e2d4a;
            border-radius:6px;padding:4px 10px;font-size:10px;
            color:#00cfff;display:flex;align-items:center;gap:5px;">
        <i class="fa fa-hand" style="font-size:10px;"></i>
        Alt + Drag untuk geser peta
    </div>

    <?php if (session()->get('role') === 'superadmin'): ?>
    <div class="upload-hint" onclick="window.location='/map-settings'">
        <i class="fa fa-upload" style="font-size:10px;"></i> Ganti Peta
    </div>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const ROLE = '<?= session()->get('role') ?>';
const USER_ID = <?= session()->get('user_id') ?>;
const UNAME = '<?= session()->get('name') ?>';
const CAN_EDIT = ['admin', 'superadmin'].includes(ROLE);

const wrap = document.getElementById('canvasWrap');
const noMap = document.getElementById('noMap');
const badge = document.getElementById('mapBadge');

let canvas = null;
let bgImage = null;
let zoomLevel = 1;
let mapData = null;
let unitObjs = {};
let activePopupId = null;

const MIN_ZOOM = 0.3;
const MAX_ZOOM = 3;
const ZOOM_STEP = 0.15;

// ── Init Canvas ──
function initCanvas() {
    const w = wrap.offsetWidth;
    const h = wrap.offsetHeight;
    if (canvas) canvas.dispose();

    canvas = new fabric.Canvas('mapCanvas', {
        width: w,
        height: h,
        selection: false,
        preserveObjectStacking: true,
    });

    // ── Pan variables ──
    let isPanning = false;
    let lastPanX = 0;
    let lastPanY = 0;

    // ── Mouse Down — mulai pan kalau Alt key atau middle click ──
    canvas.on('mouse:down', (opt) => {
        const e = opt.e;

        // Alt + click ATAU middle mouse button → mulai pan
        if (e.altKey || e.button === 1) {
            isPanning = true;
            lastPanX = e.clientX;
            lastPanY = e.clientY;
            canvas.defaultCursor = 'grabbing';
            canvas.setCursor('grabbing');
            e.preventDefault();
            return;
        }

        // Klik area kosong → tutup popup
        if (!opt.target) closePopup();
    });

    // ── Mouse Move — geser viewport ──
    canvas.on('mouse:move', (opt) => {
        if (!isPanning) return;
        const e = opt.e;
        const dx = e.clientX - lastPanX;
        const dy = e.clientY - lastPanY;
        lastPanX = e.clientX;
        lastPanY = e.clientY;

        // Geser viewport
        const vpt = canvas.viewportTransform;
        vpt[4] += dx;
        vpt[5] += dy;

        canvas.requestRenderAll();
    });

    // ── Mouse Up — stop pan ──
    canvas.on('mouse:up', () => {
        if (isPanning) {
            isPanning = false;
            canvas.defaultCursor = 'default';
            canvas.setCursor('default');
        }
    });

    // ── Mouse Leave — stop pan ──
    canvas.on('mouse:out', () => {
        if (isPanning) {
            isPanning = false;
            canvas.defaultCursor = 'default';
        }
    });

    // ── Scroll zoom ──
    canvas.on('mouse:wheel', (opt) => {
        opt.e.preventDefault();
        opt.e.stopPropagation();
        opt.e.deltaY > 0 ? doZoomOut() : doZoomIn();
    });
}

// ── Load Peta ──
async function loadMap() {
    try {
        const res = await axios.get('/api/map/active');
        const map = res.data.map;
        if (!map) {
            noMap.style.display = 'flex';
            return;
        }

        mapData = map;
        noMap.style.display = 'none';
        badge.style.display = 'block';
        badge.textContent = map.name.toUpperCase();

        initCanvas();

        // Load bg image dengan Promise
        await new Promise((resolve) => {
            loadBgImage(map.image_path, resolve);
        });

        // Load units dulu, tunggu selesai
        await loadUnits();

        // Tunggu sebentar pastikan unitObjs sudah terisi semua
        await new Promise(resolve => setTimeout(resolve, 500));

        // Baru load trails
        await loadTrails();

    } catch (e) {
        console.error('Gagal load peta:', e);
    }
}

// ── Load BG Image ──
function loadBgImage(path, callback) {
    fabric.Image.fromURL('/' + path, (img) => {
        const w = wrap.offsetWidth;
        const h = wrap.offsetHeight;
        const scale = Math.min(w / img.width, h / img.height);

        img.set({
            left: (w - img.width * scale) / 2,
            top: (h - img.height * scale) / 2,
            scaleX: scale,
            scaleY: scale,
            selectable: false,
            evented: false,
            hasBorders: false,
            hasControls: false,
        });

        bgImage = img;
        canvas.add(img);
        canvas.sendToBack(img);

        window.mapMeta = {
            left: img.left,
            top: img.top,
            width: img.width * scale,
            height: img.height * scale,
        };

        canvas.renderAll();
        updateZoomLabel();
        if (callback) callback();
    }, {
        crossOrigin: 'anonymous'
    });
}

// ── Konversi posisi ──
function canvasToPercent(x, y) {
    const m = window.mapMeta;
    if (!m) return {
        px: 0.5,
        py: 0.5
    };

    // Kompensasi viewport transform (zoom + pan)
    const vpt = canvas.viewportTransform;
    const scaleX = vpt[0];
    const scaleY = vpt[3];
    const panX = vpt[4];
    const panY = vpt[5];

    // Konversi dari canvas coordinate ke real coordinate
    const realX = (x * scaleX + panX - m.left * scaleX) / (m.width * scaleX);
    const realY = (y * scaleY + panY - m.top * scaleY) / (m.height * scaleY);

    // Tanpa viewport (posisi object sudah dalam real coordinate)
    const pxRaw = (x - m.left) / m.width;
    const pyRaw = (y - m.top) / m.height;

    return {
        px: Math.min(1, Math.max(0, pxRaw)),
        py: Math.min(1, Math.max(0, pyRaw)),
    };
}

function percentToCanvas(px, py) {
    const m = window.mapMeta;
    if (!m) return {
        x: 0,
        y: 0
    };
    return {
        x: m.left + px * m.width,
        y: m.top + py * m.height,
    };
}
// ── Load Units ──
async function loadUnits() {
    try {
        const res = await axios.get('/api/units');
        const units = res.data.units;
        Object.values(unitObjs).forEach(o => canvas.remove(o));
        unitObjs = {};
        units.forEach(u => addUnitToCanvas(u));
        updateStats(units);
        updateSidebarList(units);
        updateFilterChips(units);
        canvas.renderAll();
    } catch (e) {
        console.error('Gagal load units:', e);
    }
}

// ── Trail Pergerakan ──
let trailLines = {}; // { unit_id: [fabricLine, ...] }
const MAX_TRAIL = 10; // maksimal titik trail per unit

// Gambar trail berdasarkan histori
async function loadTrails() {
    try {
        const res = await axios.get('/api/history?limit=200');
        const history = res.data.history;

        const grouped = {};
        history.forEach(h => {
            if (!grouped[h.unit_id]) grouped[h.unit_id] = [];
            grouped[h.unit_id].push(h);
        });

        clearAllTrails();

        Object.keys(grouped).forEach(unitId => {
            const points = grouped[unitId].slice(0, MAX_TRAIL).reverse();
            const color = grouped[unitId][0].color || '#00cfff';

            // Hanya gambar trail kalau unit sudah punya titik awal
            const unitObj = unitObjs[unitId];
            if (unitObj && unitObj.data.has_start_point) {
                drawTrail(unitId, points, color);
            }
        });

        canvas.renderAll();
    } catch (e) {
        console.error('Gagal load trail:', e);
    }
}
// Gambar garis trail untuk satu unit
function drawTrail(unitId, points, color = '#00cfff') {
    if (points.length < 2) return;
    const key = String(unitId); // ← konversi ke string
    clearTrail(key);
    trailLines[key] = [];

    for (let i = 0; i < points.length - 1; i++) {
        const fromPx = parseFloat(points[i].pos_x);
        const fromPy = parseFloat(points[i].pos_y);
        const toPx = parseFloat(points[i + 1].pos_x);
        const toPy = parseFloat(points[i + 1].pos_y);

        const dist = Math.abs(toPx - fromPx) + Math.abs(toPy - fromPy);
        if (dist < 0.001) continue;

        const from = percentToCanvas(fromPx, fromPy);
        const to = percentToCanvas(toPx, toPy);
        const opacity = 0.3 + (i / points.length) * 0.6;

        const line = new fabric.Line(
            [from.x, from.y, to.x, to.y], {
                stroke: color,
                strokeWidth: 1.5,
                strokeDashArray: [10, 5],
                opacity: opacity,
                selectable: false,
                evented: false,
            }
        );

        canvas.add(line);
        canvas.sendToBack(line);
        if (bgImage) canvas.sendToBack(bgImage);
        trailLines[key].push(line);
    }
}

// Tambah titik trail baru saat unit dipindahkan
function addTrailPoint(unitId, fromX, fromY, toX, toY, color = '#00cfff') {
    const key = String(unitId); // ← konversi ke string
    if (!trailLines[key]) trailLines[key] = [];

    const from = percentToCanvas(fromX, fromY);
    const to = percentToCanvas(toX, toY);

    const line = new fabric.Line(
        [from.x, from.y, to.x, to.y], {
            stroke: color,
            strokeWidth: 1.5,
            strokeDashArray: [10, 5],
            opacity: 0.5,
            selectable: false,
            evented: false,
        }
    );

    canvas.add(line);
    canvas.sendToBack(line);
    if (bgImage) canvas.sendToBack(bgImage);

    trailLines[key].push(line);

    if (trailLines[key].length > MAX_TRAIL) {
        const old = trailLines[key].shift();
        canvas.remove(old);
    }

    canvas.renderAll();
}

function clearTrail(unitId) {
    // Konversi ke string karena key di trailLines adalah string
    const key = String(unitId);
    if (trailLines[key]) {
        trailLines[key].forEach(l => canvas.remove(l));
        trailLines[key] = [];
        delete trailLines[key];
    }
    canvas.renderAll();
}

function clearAllTrails() {
    Object.keys(trailLines).forEach(key => clearTrail(key));
    trailLines = {};
}

// Toggle trail on/off
let trailVisible = true;

function toggleTrail() {
    trailVisible = !trailVisible;
    Object.values(trailLines).forEach(lines => {
        lines.forEach(l => l.visible = trailVisible);
    });
    canvas.renderAll();

    const btn = document.getElementById('btnTrail');
    if (btn) {
        btn.style.color = trailVisible ? '#00cfff' : '#3a5070';
        btn.style.borderColor = trailVisible ? '#00cfff44' : '#1e2d4a';
    }
}

// ── Render Unit ──
function addUnitToCanvas(unit) {
    const pos = percentToCanvas(parseFloat(unit.pos_x), parseFloat(unit.pos_y));
    const size = 52;
    const color = unit.color || '#00cfff';

    fabric.Image.fromURL(
        unit.icon_path ? '/' + unit.icon_path : '',
        (img) => {
            const circle = new fabric.Circle({
                radius: size / 2,
                fill: color + '18',
                stroke: color,
                // stroke: '#00cfff',
                strokeWidth: 1.5,
                originX: 'center',
                originY: 'center',
            });

            const label = new fabric.Text(unit.name, {
                fontSize: 12,
                fill: color,
                originX: 'center',
                originY: 'top',
                top: size / 2 + 5,
                fontFamily: 'Inter, sans-serif',
                backgroundColor: 'rgba(10,14,26,0.7)',
                padding: 2,
            });

            let items = [circle, label];
            if (img && img.width > 0) {
                img.set({
                    scaleX: (size * 0.62) / img.width,
                    scaleY: (size * 0.62) / img.height,
                    originX: 'center',
                    originY: 'center',
                });
                items = [circle, img, label];
            } else {
                const abbr = new fabric.Text(
                    unit.type_name ? unit.type_name.substring(0, 3) : '?', {
                        fontSize: 11,
                        fill: '#00cfff',
                        fontWeight: 'bold',
                        originX: 'center',
                        originY: 'center',
                        fontFamily: 'Inter, sans-serif',
                    });
                items = [circle, abbr, label];
            }

            const group = new fabric.Group(items, {
                left: pos.x,
                top: pos.y,
                originX: 'center',
                originY: 'center',
                selectable: true,
                hasControls: false,
                hasBorders: false,
                lockRotation: true,
                data: {
                    unit_id: unit.id,
                    name: unit.name,
                    type_name: unit.type_name,
                    color: color,
                    pos_x: unit.pos_x,
                    pos_y: unit.pos_y,
                    updated_at: unit.updated_at,
                    has_start_point: parseInt(unit.has_start_point) || 0,
                }
            });

            // Simpan posisi sebelum drag dimulai
            // let dragStartX = null;
            // let dragStartY = null;

            group.on('mousedown', (opt) => {
                // Simpan posisi awal saat mulai drag
                dragStartX = group.left;
                dragStartY = group.top;

                // Double click → popup
                if (opt.e.detail === 2) {
                    opt.e.stopPropagation();
                    showPopup(group, opt.e);
                }
            });

            let dragStartX = null;
            let dragStartY = null;

            group.on('mousedown', (opt) => {
                dragStartX = group.left;
                dragStartY = group.top;
                if (opt.e.detail === 2) {
                    opt.e.stopPropagation();
                    showPopup(group, opt.e);
                }
            });

            // Moving — realtime smooth seperti sebelumnya
            group.on('moving', () => {
                const {
                    px,
                    py
                } = canvasToPercent(group.left, group.top);
                if (socket && socket.connected) {
                    socket.emit('unit_moved', {
                        unit_id: unit.id,
                        name: unit.name,
                        type_name: unit.type_name,
                        color: color,
                        pos_x: px,
                        pos_y: py,
                        moved_by: UNAME,
                        timestamp: new Date().toISOString(),
                        is_final: false,
                        has_start_point: 0,
                        prev_x: null,
                        prev_y: null,
                    });
                }
            });

            // Modified — drag selesai, kirim data lengkap
            group.on('modified', () => {
                const {
                    px: newPx,
                    py: newPy
                } = canvasToPercent(group.left, group.top);

                let prevPx = parseFloat(group.data.pos_x);
                let prevPy = parseFloat(group.data.pos_y);

                if (dragStartX !== null && dragStartY !== null) {
                    const converted = canvasToPercent(dragStartX, dragStartY);
                    prevPx = converted.px;
                    prevPy = converted.py;
                }

                const dist = Math.abs(newPx - prevPx) + Math.abs(newPy - prevPy);

                // Gambar trail di canvas pengirim
                if (dist > 0.001 && group.data.has_start_point) {
                    addTrailPoint(unit.id, prevPx, prevPy, newPx, newPy, color);
                }

                group.data.pos_x = newPx;
                group.data.pos_y = newPy;
                group.data.updated_at = new Date().toISOString();
                dragStartX = null;
                dragStartY = null;

                savePosition(unit.id, newPx, newPy);
                addHistoryEntry(unit.name, unit.type_name, newPx, newPy);

                // Emit FINAL dengan data lengkap
                if (socket && socket.connected) {
                    socket.emit('unit_moved', {
                        unit_id: unit.id,
                        name: unit.name,
                        type_name: unit.type_name,
                        color: color,
                        pos_x: newPx,
                        pos_y: newPy,
                        prev_x: prevPx,
                        prev_y: prevPy,
                        moved_by: UNAME,
                        timestamp: new Date().toISOString(),
                        is_final: true, // ← tandai ini emit final
                        has_start_point: parseInt(group.data.has_start_point) || 0,
                    });
                }
            });

            canvas.add(group);
            unitObjs[unit.id] = group;
            canvas.renderAll();
        }, {
            crossOrigin: 'anonymous'
        }
    );
}

// ── Simpan posisi ke DB ──
async function savePosition(unitId, px, py) {
    try {
        await axios.post('/api/units/update-pos/' + unitId, {
            pos_x: px,
            pos_y: py
        });
    } catch (e) {
        console.error('Gagal simpan posisi:', e);
    }
}

// ──────────────────────────────────────────
// SOCKET.IO EVENT HANDLERS (Terima dari server)
// ──────────────────────────────────────────

// Terima pergerakan unit dari user lain
function setupSocketListeners() {

    socket.on('unit_moved', (data) => {
        const obj = unitObjs[data.unit_id];
        if (!obj) return;

        const pos = percentToCanvas(
            parseFloat(data.pos_x),
            parseFloat(data.pos_y)
        );

        // Selalu animasi pergerakan
        obj.animate({
            left: pos.x,
            top: pos.y
        }, {
            duration: 100,
            onChange: canvas.renderAll.bind(canvas),
            easing: fabric.util.ease.easeOutQuad,
            onComplete: () => {
                obj.setCoords();
            }
        });

        // Hanya proses trail saat emit FINAL
        if (data.is_final === true) {
            obj.data.pos_x = data.pos_x;
            obj.data.pos_y = data.pos_y;
            obj.data.updated_at = data.timestamp;

            // Gambar trail di penerima
            const hasStart = parseInt(data.has_start_point) || 0;
            if (hasStart && data.prev_x !== null && data.prev_x !== undefined) {
                const prevX = parseFloat(data.prev_x);
                const prevY = parseFloat(data.prev_y);
                const newX = parseFloat(data.pos_x);
                const newY = parseFloat(data.pos_y);
                const dist = Math.abs(newX - prevX) + Math.abs(newY - prevY);

                if (dist > 0.001) {
                    const trailColor = data.color || obj.data.color || '#00cfff';
                    addTrailPoint(data.unit_id, prevX, prevY, newX, newY, trailColor);
                }
            }

            addHistoryEntry(
                data.name, data.type_name,
                data.pos_x, data.pos_y,
                data.moved_by
            );
        }
    });

    // ── Unit dihapus (dari user lain) ──
    socket.on('unit_deleted', (data) => {
        console.log('📡 Received unit_deleted:', data);
        // Cek apakah unit masih ada di canvas
        // (kalau pengirim, sudah dihapus duluan jadi skip)
        const obj = unitObjs[data.unit_id];
        console.log('🎯 Object found:', obj ? 'YES' : 'NO');
        if (!obj) return;

        // Hapus trail
        clearTrail(data.unit_id);

        // Hapus dari canvas
        canvas.remove(obj);
        delete unitObjs[data.unit_id];
        canvas.renderAll();

        // Update sidebar & stats
        const remaining = Object.values(unitObjs).map(o => o.data);
        updateSidebarList(remaining);
        updateStats(remaining);
        updateFilterChips(remaining);

        toastr.warning('Sebuah unit telah dihapus.', '', {
            timeOut: 2500
        });
    });

    // ── Unit diedit (dari user lain) ──
    socket.on('unit_edited', (data) => {
        const obj = unitObjs[data.unit_id];
        if (!obj) return;

        // Update data
        obj.data.name = data.name;

        // Update label text di canvas
        const items = obj.getObjects();
        items.forEach(item => {
            // Label ada di posisi top > 0 (bukan icon/circle)
            if (item.type === 'text' && item.top > 0) {
                item.set('text', data.name);
            }
        });

        // Re-render canvas
        obj.setCoords();
        canvas.renderAll();

        // Update sidebar
        const allUnits = Object.values(unitObjs).map(o => o.data);
        updateSidebarList(allUnits);

        toastr.info(`Unit diupdate: ${data.name}`, '', {
            timeOut: 2500
        });
    });

    // ── Unit baru ditambah (dari user lain) ──
    socket.on('unit_added', (data) => {
        loadUnits().then(() => loadTrails());
        toastr.success(`Unit baru ditambahkan: ${data.name}`);
    });

    // Titik awal di-set oleh user lain
    socket.on('start_point_set', (data) => {
        // Hapus trail unit tersebut di canvas
        clearTrail(String(data.unit_id));

        // Update data object
        const obj = unitObjs[data.unit_id];
        if (obj) obj.data.has_start_point = 1;

        toastr.info(
            `Titik awal ${data.name} telah di-set`,
            '', {
                timeOut: 2500
            }
        );
    });
}
// ── Emit unit_added ──
async function openAddUnitModal() {
    const res = await axios.get('/api/unit-types');
    const types = res.data.types;

    if (!types.length) {
        Swal.fire({
            title: 'Belum Ada Jenis Unsur',
            text: 'Tambahkan jenis unsur di menu Unit Types terlebih dahulu.',
            icon: 'info',
            background: '#0d1220',
            color: '#c0d8f0',
            confirmButtonColor: '#00cfff',
        });
        return;
    }

    const options = types.map(t =>
        `<option value="${t.id}">${t.name}</option>`
    ).join('');

    Swal.fire({
        title: 'Tambah Unit Baru',
        background: '#0d1220',
        color: '#c0d8f0',
        html: `
    <div style="text-align:left;margin-bottom:12px;">
        <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">NAMA UNIT *</label>
        <input id="swalUnitName" type="text" placeholder="cth: KRI Diponegoro"
        style="width:100%;background:#0a1220;border:0.5px solid #1e2d4a;
                border-radius:6px;padding:9px 14px;font-size:12px;
                color:#c0d8f0;outline:none;">
    </div>
    <div style="text-align:left;">
        <label style="font-size:11px;color:#3a5070;letter-spacing:1px;
                      display:block;margin-bottom:6px;">JENIS UNSUR *</label>
        <select id="swalUnitType"
        style="width:100%;background:#0a1220;border:0.5px solid #1e2d4a;
                border-radius:6px;padding:9px 14px;font-size:12px;
                color:#c0d8f0;outline:none;">
        ${options}
        </select>
    </div>`,
        showCancelButton: true,
        confirmButtonText: 'Tambah Unit',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#00cfff',
        cancelButtonColor: '#1e2d4a',
        preConfirm: () => {
            const name = document.getElementById('swalUnitName').value.trim();
            const typeId = document.getElementById('swalUnitType').value;
            if (!name) {
                Swal.showValidationMessage('Nama unit wajib diisi!');
                return false;
            }
            return {
                name,
                unit_type_id: typeId
            };
        }
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const res = await axios.post('/api/units/store', result.value);
                const unit = res.data.unit;

                // Tambahkan unit langsung ke canvas tanpa reload
                if (unit) {
                    addUnitToCanvas(unit);
                    canvas.renderAll();

                    // Update sidebar & stats
                    const allUnits = Object.values(unitObjs).map(o => o.data);
                    updateSidebarList(
                        await axios.get('/api/units').then(r => r.data.units)
                    );
                }

                // Reload bersih untuk sync semua data
                await loadUnits();
                await loadTrails();

                // Emit ke semua user dengan data lengkap
                if (socket && socket.connected) {
                    socket.emit('unit_added', {
                        name: unit ? unit.name : ''
                    });
                }
            } catch (e) {
                toastr.error('Gagal tambah unit.');
            }
        }
    });
}



// ── Edit Unit ──
function editUnit(id, currentName) {
    closePopup();
    Swal.fire({
        title: 'Edit Nama Unit',
        input: 'text',
        inputValue: currentName,
        background: '#0d1220',
        color: '#c0d8f0',
        inputAttributes: {
            style: 'background:#0a1220;border:0.5px solid #1e2d4a;color:#c0d8f0;border-radius:6px;padding:8px 12px;'
        },
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#00cfff',
        cancelButtonColor: '#1e2d4a',
    }).then(async (result) => {
        if (result.isConfirmed && result.value.trim()) {
            try {
                const newName = result.value.trim();
                await axios.post('/api/units/update/' + id, {
                    name: newName
                });

                // Update langsung di canvas pengirim
                const obj = unitObjs[id];
                if (obj) {
                    obj.data.name = newName;

                    // Update label text
                    const items = obj.getObjects();
                    items.forEach(item => {
                        if (item.type === 'text' && item.top > 0) {
                            item.set('text', newName);
                        }
                    });

                    obj.setCoords();
                    canvas.renderAll();
                }

                // Update sidebar
                const allUnits = Object.values(unitObjs).map(o => o.data);
                updateSidebarList(allUnits);

                toastr.success('Unit berhasil diupdate!');

                // Emit ke semua user lain
                if (socket && socket.connected) {
                    socket.emit('unit_edited', {
                        unit_id: id,
                        name: newName
                    });
                }

            } catch (e) {
                toastr.error('Gagal update unit.');
            }
        }
    });
}
// ── Hapus Unit ──
function deleteUnit(id, name) {
    closePopup();
    Swal.fire({
        title: 'Hapus Unit?',
        html: 'Unit <strong style="color:#00cfff">' + name + '</strong> akan dihapus.',
        icon: 'warning',
        background: '#0d1220',
        color: '#c0d8f0',
        showCancelButton: true,
        confirmButtonColor: '#ff4455',
        cancelButtonColor: '#1e2d4a',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.post('/api/units/delete/' + id);

                // Hapus langsung di canvas pengirim
                const obj = unitObjs[id];
                if (obj) {
                    clearTrail(id);
                    canvas.remove(obj);
                    delete unitObjs[id];
                    canvas.renderAll();
                }

                // Update sidebar & stats
                const remaining = Object.values(unitObjs).map(o => o.data);
                updateSidebarList(remaining);
                updateStats(remaining);
                updateFilterChips(remaining);

                toastr.success('Unit berhasil dihapus!');

                // Emit ke semua user lain
                if (socket && socket.connected) {
                    socket.emit('unit_deleted', {
                        unit_id: id
                    });
                }

            } catch (e) {
                toastr.error('Gagal hapus unit.');
            }
        }
    });
}

// ── Popup ──
function showPopup(group, e) {
    const popup = document.getElementById('unitPopup');
    const data = group.data;
    activePopupId = data.unit_id;

    document.getElementById('popupName').textContent =
        data.name;
    document.getElementById('popupType').textContent =
        data.type_name || '—';
    document.getElementById('popupX').textContent =
        (parseFloat(data.pos_x) * 100).toFixed(1) + '%';
    document.getElementById('popupY').textContent =
        (parseFloat(data.pos_y) * 100).toFixed(1) + '%';
    document.getElementById('popupTime').textContent =
        data.updated_at ?
        new Date(data.updated_at).toLocaleTimeString('id-ID') :
        '—';
    // Tampilkan status titik awal
    const startLabel = document.getElementById('popupStartLabel');
    if (startLabel) {
        startLabel.textContent = data.has_start_point ? '✓ Titik awal sudah di-set' : 'Belum ada titik awal';
        startLabel.style.color = data.has_start_point ? '#22c55e' : '#3a5070';
    }

    const wrapRect = wrap.getBoundingClientRect();
    let px = e.clientX - wrapRect.left + 12;
    let py = e.clientY - wrapRect.top - 20;
    if (px + 180 > wrap.offsetWidth) px = px - 200;
    if (py + 200 > wrap.offsetHeight) py = py - 200;

    popup.style.left = px + 'px';
    popup.style.top = py + 'px';
    popup.style.display = 'block';

    if (CAN_EDIT) {
        document.getElementById('popupBtnEdit').onclick =
            () => editUnit(data.unit_id, data.name);
        document.getElementById('popupBtnDel').onclick =
            () => deleteUnit(data.unit_id, data.name);
        document.getElementById('popupBtnStart').onclick =
            () => setStartPoint(data.unit_id, data.name, group);
    }
}

function closePopup() {
    document.getElementById('unitPopup').style.display = 'none';
    activePopupId = null;
}

// ── Set Titik Awal ──
function setStartPoint(id, name, group) {
    closePopup();
    Swal.fire({
        title: 'Set Titik Awal?',
        html: `Posisi <strong style="color:#00cfff">${name}</strong> 
                saat ini akan dijadikan titik awal.<br>
                <span style="color:#ff6070;font-size:11px;">
                  ⚠ Histori trail sebelumnya akan dihapus.
                </span>`,
        icon: 'question',
        background: '#0d1220',
        color: '#c0d8f0',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#1e2d4a',
        confirmButtonText: '<i class="fa fa-flag"></i> Set Titik Awal',
        cancelButtonText: 'Batal',
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.post('/api/units/set-start/' + id);

                // Hapus trail lama di canvas
                clearTrail(String(id));

                // Update data group
                group.data.has_start_point = 1;

                toastr.success('Titik awal berhasil disimpan!');

                // Emit ke user lain
                if (socket && socket.connected) {
                    socket.emit('start_point_set', {
                        unit_id: id,
                        name: name
                    });
                }

            } catch (e) {
                toastr.error('Gagal set titik awal.');
            }
        }
    });
}

// ── History Panel ──
function addHistoryEntry(name, typeName, px, py, movedBy = null) {
    const list = document.getElementById('historyList');
    const now = new Date().toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });
    const byText = movedBy ? `<span style="color:#00cfff66;font-size:9px;">by ${movedBy}</span>` : '';
    const entry = document.createElement('div');
    entry.className = 'history-entry';
    entry.innerHTML = `
    <div class="h-dot" style="background:#00cfff;"></div>
    <div class="h-content">
      <div class="h-name">${name}</div>
      <div class="h-pos">x:${(px*100).toFixed(1)}% y:${(py*100).toFixed(1)}%</div>
      ${byText}
    </div>
    <span class="h-time">${now}</span>`;
    list.insertBefore(entry, list.firstChild);
    while (list.children.length > 30) list.removeChild(list.lastChild);
}

// ── Update Stats ──
function updateStats(units) {
    document.getElementById('statTotal').textContent = units.length;
    const counts = {};
    units.forEach(u => {
        counts[u.type_name] = (counts[u.type_name] || 0) + 1;
    });
    const top = Object.keys(counts).sort((a, b) => counts[b] - counts[a])[0];
    const el = document.getElementById('statDynamic');
    if (top) {
        el.innerHTML = `
      <span class="stat-val">${counts[top]}</span>
      <span class="stat-label">${top.toUpperCase()}</span>`;
    }
}

// ── Sidebar List ──
function updateSidebarList(units) {
    const list = document.getElementById('sidebarUnitList');
    if (!units.length) {
        list.innerHTML = '<div style="font-size:11px;color:#1e2d4a;padding:8px;">Belum ada unit</div>';
        return;
    }
    list.innerHTML = units.map(u => `
    <div class="unit-item" onclick="focusUnit(${u.id})">
      <div class="unit-dot" style="background:#00cfff;"></div>
      <span class="unit-item-name">${u.name}</span>
      <span class="unit-item-badge"
            style="background:#0e2040;color:#00cfff;border:0.5px solid #00cfff44;">
        ${u.type_name || '?'}
      </span>
    </div>`).join('');
}

// ── Filter Chips ──
function updateFilterChips(units) {
    const types = [...new Set(units.map(u => u.type_name).filter(Boolean))];
    const chips = document.getElementById('filterChips');
    chips.innerHTML = `
    <span class="f-chip chip-all" data-type="all"
          onclick="filterUnits('all',this)">All</span>`;
    types.forEach(t => {
        chips.innerHTML += `
      <span class="f-chip chip-custom" data-type="${t}"
            onclick="filterUnits('${t}',this)">${t}</span>`;
    });
}

function filterUnits(type, el) {
    document.querySelectorAll('.f-chip').forEach(c => c.classList.remove('chip-all'));
    el.classList.add('chip-all');
    Object.values(unitObjs).forEach(obj => {
        obj.visible = (type === 'all') ? true : (obj.data.type_name === type);
    });
    canvas.renderAll();
}

// ── Search ──
document.getElementById('searchUnit').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    Object.values(unitObjs).forEach(obj => {
        obj.visible = obj.data.name.toLowerCase().includes(q);
    });
    canvas.renderAll();
});

// ── Focus Unit ──
function focusUnit(id) {
    const obj = unitObjs[id];
    if (!obj) return;
    canvas.setActiveObject(obj);
    const center = canvas.getCenter();
    canvas.viewportTransform[4] = center.left - obj.left * zoomLevel;
    canvas.viewportTransform[5] = center.top - obj.top * zoomLevel;
    canvas.renderAll();
}

// ── Zoom ──
function doZoomIn() {
    zoomLevel = Math.min(MAX_ZOOM, parseFloat((zoomLevel + ZOOM_STEP).toFixed(2)));
    applyZoom();
}

function doZoomOut() {
    zoomLevel = Math.max(MIN_ZOOM, parseFloat((zoomLevel - ZOOM_STEP).toFixed(2)));
    applyZoom();
}

function applyZoom() {
    const center = canvas.getCenter();
    canvas.zoomToPoint(
        new fabric.Point(center.left, center.top),
        zoomLevel
    );
    updateZoomLabel();
    canvas.requestRenderAll();
}

function updateZoomLabel() {
    document.getElementById('zoomLevel').textContent =
        Math.round(zoomLevel * 100) + '%';
}

document.getElementById('zoomIn').addEventListener('click', doZoomIn);
document.getElementById('zoomOut').addEventListener('click', doZoomOut);
document.getElementById('zoomReset').addEventListener('click', () => {
    zoomLevel = 1;
    // Reset zoom DAN pan
    canvas.setViewportTransform([1, 0, 0, 1, 0, 0]);
    updateZoomLabel();
    canvas.requestRenderAll();
});

// ── Add Unit button ──
const btnAddUnit = document.getElementById('btnAddUnit');
if (btnAddUnit) btnAddUnit.addEventListener('click', openAddUnitModal);

// ── Resize ──
window.addEventListener('resize', () => {
    if (!mapData) return;
    canvas.setWidth(wrap.offsetWidth);
    canvas.setHeight(wrap.offsetHeight);
    loadBgImage(mapData.image_path, () => {
        Object.values(unitObjs).forEach(o => canvas.remove(o));
        unitObjs = {};
        loadUnits();
    });
});

// ── Init ──
loadMap().then(() => {
    // Setup socket listeners setelah canvas siap
    // Tunggu socket connect dulu
    const waitSocket = setInterval(() => {
        if (socket && socket.connected) {
            clearInterval(waitSocket);
            setupSocketListeners();
        }
    }, 300);
});
</script>
<?= $this->endSection() ?>