<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TFG — <?= $title ?? 'Dashboard' ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Toastr -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        background: #0a0e1a;
        font-family: 'Inter', 'Segoe UI', sans-serif;
        font-size: 13px;
        color: #e0e6f0;
        height: 100vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    /* ── TOPBAR ── */
    .topbar {
        height: 50px;
        background: #0d1220;
        border-bottom: 0.5px solid #1e2d4a;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 14px;
        flex-shrink: 0;
        z-index: 100;
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .brand-text {
        font-size: 15px;
        font-weight: 600;
        color: #00cfff;
        letter-spacing: 3px;
    }

    .brand-sep {
        width: 0.5px;
        height: 18px;
        background: #1e2d4a;
    }

    .page-title {
        font-size: 12px;
        color: #5070a0;
        letter-spacing: 1px;
    }

    .status-pill {
        display: flex;
        align-items: center;
        gap: 5px;
        background: #0e1a2a;
        border: 0.5px solid #1e2d4a;
        border-radius: 20px;
        padding: 3px 10px;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #00ff99;
        animation: blink 2s infinite;
    }

    .status-dot.off {
        background: #ff4455;
        animation: none;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1
        }

        50% {
            opacity: 0.3
        }
    }

    .status-text {
        font-size: 10px;
        color: #3a6040;
        letter-spacing: 1px;
    }

    .toggle-btn {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        background: #0e1a2a;
        border: 0.5px solid #1e2d4a;
        color: #3a5070;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .toggle-btn:hover {
        color: #00cfff;
        border-color: #00cfff44;
    }

    .toggle-btn.active {
        color: #00cfff;
        border-color: #00cfff55;
        background: #0e2040;
    }

    .btn-add-unit {
        background: #00cfff18;
        border: 0.5px solid #00cfff55;
        color: #00cfff;
        font-size: 11px;
        padding: 5px 12px;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .btn-add-unit:hover {
        background: #00cfff28;
    }

    .user-chip {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #0e1a2a;
        border: 0.5px solid #1e2d4a;
        border-radius: 20px;
        padding: 3px 10px 3px 5px;
        cursor: pointer;
        position: relative;
    }

    .user-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #00cfff22;
        border: 1px solid #00cfff55;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        color: #00cfff;
        font-weight: 600;
    }

    .user-name {
        font-size: 11px;
        color: #5070a0;
    }

    .user-role {
        font-size: 10px;
        color: #2a4060;
    }

    /* User dropdown */
    .user-dropdown {
        position: absolute;
        top: 38px;
        right: 0;
        background: #0d1220;
        border: 0.5px solid #1e2d4a;
        border-radius: 8px;
        padding: 6px;
        min-width: 150px;
        display: none;
        z-index: 999;
    }

    .user-chip:hover .user-dropdown {
        display: block;
    }

    .dropdown-item-custom {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 5px;
        font-size: 12px;
        color: #5070a0;
        cursor: pointer;
        text-decoration: none;
    }

    .dropdown-item-custom:hover {
        background: #0e1a2a;
        color: #c0d8f0;
    }

    .dropdown-item-custom.danger {
        color: #ff6070;
    }

    .dropdown-item-custom.danger:hover {
        background: #ff445518;
    }

    /* ── BODY LAYOUT ── */
    .app-body {
        flex: 1;
        display: flex;
        overflow: hidden;
    }

    /* ── SIDEBAR ── */
    .sidebar {
        width: 220px;
        background: #0d1220;
        border-right: 0.5px solid #1e2d4a;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        transition: width 0.25s ease, opacity 0.2s ease;
        overflow: hidden;
    }

    .sidebar.collapsed {
        width: 0;
        opacity: 0;
    }

    .sidebar-logo {
        padding: 16px;
        border-bottom: 0.5px solid #1e2d4a;
        flex-shrink: 0;
    }

    .logo-main {
        font-size: 14px;
        font-weight: 600;
        color: #00cfff;
        letter-spacing: 3px;
        white-space: nowrap;
    }

    .logo-sub {
        font-size: 9px;
        color: #2a3a50;
        letter-spacing: 2px;
        margin-top: 2px;
        white-space: nowrap;
    }

    .nav-section-label {
        font-size: 9px;
        color: #1e2d4a;
        letter-spacing: 2px;
        padding: 12px 14px 6px;
        white-space: nowrap;
    }

    .nav-link-custom {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        color: #3a5070;
        cursor: pointer;
        border-radius: 0;
        font-size: 12px;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.15s;
        border-left: 2px solid transparent;
    }

    .nav-link-custom:hover {
        background: #0e1a2a;
        color: #7090b0;
    }

    .nav-link-custom.active {
        background: #0e2040;
        color: #00cfff;
        border-left-color: #00cfff;
    }

    .nav-link-custom i {
        width: 14px;
        text-align: center;
        font-size: 12px;
    }

    /* Active units list */
    .units-list {
        flex: 1;
        overflow-y: auto;
        padding: 8px;
        border-top: 0.5px solid #1e2d4a;
    }

    .units-list::-webkit-scrollbar {
        width: 3px;
    }

    .units-list::-webkit-scrollbar-track {
        background: transparent;
    }

    .units-list::-webkit-scrollbar-thumb {
        background: #1e2d4a;
        border-radius: 2px;
    }

    .units-list-title {
        font-size: 9px;
        color: #1e2d4a;
        letter-spacing: 2px;
        padding: 4px 6px 8px;
        white-space: nowrap;
    }

    .unit-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 8px;
        border-radius: 6px;
        margin-bottom: 2px;
        cursor: pointer;
        transition: background 0.15s;
    }

    .unit-item:hover {
        background: #0e1a2a;
    }

    .unit-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .unit-item-name {
        font-size: 11px;
        color: #5070a0;
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .unit-item-badge {
        font-size: 9px;
        padding: 1px 6px;
        border-radius: 10px;
        white-space: nowrap;
    }

    /* ── MAIN CONTENT ── */
    .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
        overflow: hidden;
    }

    /* ── RIGHT PANEL ── */
    .right-panel {
        width: 210px;
        background: #0d1220;
        border-left: 0.5px solid #1e2d4a;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        transition: width 0.25s ease, opacity 0.2s ease;
        overflow: hidden;
    }

    .right-panel.collapsed {
        width: 0;
        opacity: 0;
    }

    .panel-section {
        padding: 12px;
        border-bottom: 0.5px solid #1e2d4a;
        flex-shrink: 0;
    }

    .panel-title {
        font-size: 9px;
        color: #1e2d4a;
        letter-spacing: 2px;
        margin-bottom: 8px;
        white-space: nowrap;
    }

    .search-input {
        width: 100%;
        background: #0a1220;
        border: 0.5px solid #1e2d4a;
        border-radius: 5px;
        padding: 6px 10px;
        font-size: 11px;
        color: #7090b0;
        outline: none;
        transition: border-color 0.2s;
    }

    .search-input:focus {
        border-color: #00cfff44;
    }

    .search-input::placeholder {
        color: #2a4060;
    }

    .filter-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 8px;
    }

    .f-chip {
        font-size: 10px;
        padding: 3px 9px;
        border-radius: 20px;
        cursor: pointer;
        border: 0.5px solid;
        white-space: nowrap;
        transition: opacity 0.15s;
    }

    .f-chip:hover {
        opacity: 0.8;
    }

    .f-chip.chip-all {
        background: #00cfff18;
        color: #00cfff;
        border-color: #00cfff44;
    }

    .f-chip.chip-custom {
        background: #0e1a2a;
        color: #5070a0;
        border-color: #1e2d4a;
    }

    .history-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 10px 12px;
    }

    .history-scroll::-webkit-scrollbar {
        width: 3px;
    }

    .history-scroll::-webkit-scrollbar-thumb {
        background: #1e2d4a;
        border-radius: 2px;
    }

    .history-entry {
        display: flex;
        gap: 8px;
        margin-bottom: 10px;
        align-items: flex-start;
    }

    .h-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        margin-top: 4px;
        flex-shrink: 0;
    }

    .h-content {
        flex: 1;
        min-width: 0;
    }

    .h-name {
        font-size: 11px;
        color: #5070a0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .h-pos {
        font-size: 10px;
        color: #2a4060;
        font-family: monospace;
    }

    .h-time {
        font-size: 10px;
        color: #1e2d4a;
        white-space: nowrap;
        padding-top: 2px;
    }

    /* ── STATS BAR ── */
    .stats-bar {
        height: 42px;
        background: #0d1220;
        border-top: 0.5px solid #1e2d4a;
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    .stat-item {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        height: 100%;
        border-right: 0.5px solid #1e2d4a;
    }

    .stat-item:last-child {
        border-right: none;
    }

    .stat-val {
        font-size: 15px;
        font-weight: 600;
        color: #00cfff;
    }

    .stat-label {
        font-size: 10px;
        color: #2a4060;
        letter-spacing: 1px;
    }

    /* ── CANVAS AREA ── */
    .canvas-wrap {
        flex: 1;
        position: relative;
        overflow: hidden;
        background: #060c18;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Fullscreen badge */
    .fs-badge {
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        background: #0d1525cc;
        border: 0.5px solid #00cfff22;
        border-radius: 20px;
        padding: 3px 14px;
        font-size: 10px;
        color: #00cfff44;
        letter-spacing: 2px;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s;
        white-space: nowrap;
        z-index: 10;
    }

    .fs-badge.show {
        opacity: 1;
    }

    /* Map name badge */
    .map-badge {
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        background: #0d1525cc;
        border: 0.5px solid #1e2d4a;
        border-radius: 20px;
        padding: 4px 16px;
        font-size: 10px;
        color: #3a5570;
        letter-spacing: 2px;
        white-space: nowrap;
        z-index: 10;
        transition: opacity 0.3s;
    }

    /* Zoom controls */
    .zoom-ctrl {
        position: absolute;
        bottom: 16px;
        left: 16px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        z-index: 10;
    }

    .zoom-btn {
        width: 30px;
        height: 30px;
        background: #0d1525cc;
        border: 0.5px solid #1e2d4a;
        border-radius: 5px;
        color: #4a6080;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        font-weight: 300;
        transition: color 0.2s;
        user-select: none;
    }

    .zoom-btn:hover {
        color: #00cfff;
        border-color: #00cfff44;
    }

    .zoom-level {
        font-size: 9px;
        color: #2a4060;
        text-align: center;
        padding: 2px 0;
        font-family: monospace;
    }

    /* Upload hint */
    .upload-hint {
        position: absolute;
        bottom: 16px;
        right: 16px;
        background: #0d1525cc;
        border: 0.5px solid #1e2d4a;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 10px;
        color: #2a4060;
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: color 0.2s, border-color 0.2s;
        z-index: 10;
    }

    .upload-hint:hover {
        color: #00cfff;
        border-color: #00cfff44;
    }

    /* No map placeholder */
    .no-map {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        color: #1e2d4a;
    }

    .no-map i {
        font-size: 40px;
    }

    .no-map p {
        font-size: 12px;
        letter-spacing: 1px;
    }

    /* Fabric canvas */
    #mapCanvas {
        display: block;
    }

    /* Flash messages */
    .flash-error {
        background: #ff445518;
        border: 0.5px solid #ff445544;
        color: #ff8090;
        font-size: 12px;
        border-radius: 6px;
        padding: 8px 14px;
    }
    </style>

    <?= $this->renderSection('extra_css') ?>
</head>

<body>

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <div class="toggle-btn active" id="btnSidebar" title="Toggle Sidebar">
                <i class="fa fa-table-columns" style="font-size:12px;"></i>
            </div>
            <span class="brand-text">TFG</span>
            <div class="brand-sep"></div>
            <span class="page-title"><?= strtoupper($title ?? 'DASHBOARD') ?></span>
        </div>
        <div class="topbar-right">
            <!-- Realtime status -->
            <div class="status-pill">
                <div class="status-dot off" id="statusDot"></div>
                <span class="status-text" id="statusText">CONNECTING</span>
            </div>

            <!-- Add Unit (hanya admin & superadmin) -->
            <?php if (in_array(session()->get('role'), ['admin', 'superadmin'])): ?>
            <div class="btn-add-unit" id="btnAddUnit">
                <i class="fa fa-plus" style="font-size:10px;"></i> Add Unit
            </div>
            <?php endif; ?>

            <!-- User chip -->
            <div class="user-chip">
                <div class="user-avatar">
                    <?= strtoupper(substr(session()->get('name'), 0, 2)) ?>
                </div>
                <div>
                    <div class="user-name"><?= session()->get('name') ?></div>
                    <div class="user-role"><?= strtoupper(session()->get('role')) ?></div>
                </div>
                <div class="user-dropdown">
                    <a href="/auth/logout" class="dropdown-item-custom danger">
                        <i class="fa fa-right-from-bracket" style="font-size:11px;"></i> Logout
                    </a>
                </div>
            </div>

            <div class="toggle-btn active" id="btnPanel" title="Toggle Panel">
                <!-- <i class="fa-solid fa-sidebar-flip" style="font-size:12px;"></i> -->
                <i class="fa-solid fa-bars" style="font-size:12px;"></i>
            </div>
        </div>
    </div>

    <!-- APP BODY -->
    <div class=" app-body">

        <!-- SIDEBAR -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <div class="logo-main">TFG</div>
                <div class="logo-sub">TACTICAL FLOOR GAME</div>
            </div>

            <div style="overflow-y:auto;flex:1;display:flex;flex-direction:column;">
                <div class="nav-section-label">MENU</div>

                <a href="/dashboard" class="nav-link-custom <?= uri_string() == 'dashboard' ? 'active' : '' ?>">
                    <i class="fa fa-map"></i> Live Map
                </a>

                <?php if (in_array(session()->get('role'), ['admin', 'superadmin'])): ?>
                <a href="/unit-types"
                    class="nav-link-custom <?= str_starts_with(uri_string(), 'unit-types') ? 'active' : '' ?>">
                    <i class="fa fa-layer-group"></i> Unit Types
                </a>
                <?php endif; ?>

                <?php if (session()->get('role') === 'superadmin'): ?>
                <a href="/users" class="nav-link-custom <?= str_starts_with(uri_string(), 'users') ? 'active' : '' ?>">
                    <i class="fa fa-users"></i> Users
                </a>
                <a href="/map-settings"
                    class="nav-link-custom <?= str_starts_with(uri_string(), 'map-settings') ? 'active' : '' ?>">
                    <i class="fa fa-image"></i> Map Settings
                </a>
                <?php endif; ?>

                <a href="/history"
                    class="nav-link-custom <?= str_starts_with(uri_string(), 'history') ? 'active' : '' ?>">
                    <i class="fa fa-clock-rotate-left"></i> History
                </a>

                <div class="nav-section-label" style="margin-top:auto;">ACTIVE UNITS</div>
                <div class="units-list" id="sidebarUnitList">
                    <div style="font-size:11px;color:#1e2d4a;padding:6px 8px;">Loading...</div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <?= $this->renderSection('content') ?>

            <!-- STATS BAR -->
            <div class="stats-bar">
                <div class="stat-item">
                    <span class="stat-val" id="statTotal">0</span>
                    <span class="stat-label">TOTAL UNITS</span>
                </div>
                <div class="stat-item" id="statDynamic">
                    <span class="stat-val">—</span>
                    <span class="stat-label">JENIS</span>
                </div>
                <div class="stat-item">
                    <span class="stat-val" id="statOnline">0</span>
                    <span class="stat-label">ONLINE</span>
                </div>
                <div class="stat-item">
                    <span class="stat-val" style="font-size:11px;" id="statRole">
                        <?= strtoupper(session()->get('role')) ?>
                    </span>
                    <span class="stat-label">ROLE</span>
                </div>
                <div class="stat-item">
                    <span class="stat-val" style="font-size:11px;" id="statConn">OFF</span>
                    <span class="stat-label">REALTIME</span>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel" id="rightPanel">
            <div class="panel-section">
                <div class="panel-title">SEARCH & FILTER</div>
                <input type="text" class="search-input" id="searchUnit" placeholder="Search unit...">
                <div class="filter-chips" id="filterChips">
                    <span class="f-chip chip-all active" data-type="all">All</span>
                </div>
            </div>
            <div class="panel-section" style="padding-bottom:8px;">
                <div class="panel-title">POSITION HISTORY</div>
            </div>
            <div class="history-scroll" id="historyList">
                <div style="font-size:11px;color:#1e2d4a;text-align:center;padding-top:20px;">
                    No activity yet
                </div>
            </div>
        </div>

    </div><!-- end app-body -->

    <!-- Fabric.js — tambahkan sebelum script lain -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <!-- Socket.io Client -->
    <script src="https://cdn.jsdelivr.net/npm/socket.io-client@4.6.1/dist/socket.io.min.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // ── Toastr config ──
    toastr.options = {
        positionClass: 'toast-bottom-right',
        timeOut: 3000,
        progressBar: true,
        closeButton: true,
    };

    // ── Toggle Sidebar ──
    const sidebar = document.getElementById('sidebar');
    const rightPanel = document.getElementById('rightPanel');
    const btnSidebar = document.getElementById('btnSidebar');
    const btnPanel = document.getElementById('btnPanel');
    const fsBadge = document.getElementById('fsBadge');
    const mapBadge = document.getElementById('mapBadge');

    let sidebarOpen = true;
    let panelOpen = true;

    function checkFullscreen() {
        if (!sidebarOpen && !panelOpen) {
            fsBadge && fsBadge.classList.add('show');
            mapBadge && (mapBadge.style.opacity = '0');
        } else {
            fsBadge && fsBadge.classList.remove('show');
            mapBadge && (mapBadge.style.opacity = '1');
        }
    }

    btnSidebar.addEventListener('click', () => {
        sidebarOpen = !sidebarOpen;
        sidebar.classList.toggle('collapsed', !sidebarOpen);
        btnSidebar.classList.toggle('active', sidebarOpen);
        checkFullscreen();
    });

    btnPanel.addEventListener('click', () => {
        panelOpen = !panelOpen;
        rightPanel.classList.toggle('collapsed', !panelOpen);
        btnPanel.classList.toggle('active', panelOpen);
        checkFullscreen();
    });

    // ── Realtime status helper ──
    function setStatus(online) {
        const dot = document.getElementById('statusDot');
        const text = document.getElementById('statusText');
        const conn = document.getElementById('statConn');
        if (online) {
            dot.classList.remove('off');
            text.textContent = 'REALTIME';
            text.style.color = '#3a6040';
            conn.textContent = 'ON';
            conn.style.color = '#00ff99';
        } else {
            dot.classList.add('off');
            text.textContent = 'OFFLINE';
            text.style.color = '#604040';
            conn.textContent = 'OFF';
            conn.style.color = '#ff4455';
        }
    }

    // ── Flash messages → Toastr ──
    <?php if (session()->getFlashdata('success')): ?>
    toastr.success('<?= session()->getFlashdata('success') ?>');
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    toastr.error('<?= session()->getFlashdata('error') ?>');
    <?php endif; ?>

    // ── Socket.io Global Init ──
    const SOCKET_URL = 'http://localhost:3000';
    let socket = null;

    function initSocket() {
        socket = io(SOCKET_URL, {
            transports: ['websocket', 'polling'],
            reconnection: true,
            reconnectionDelay: 2000,
        });

        socket.on('connect', () => {
            setStatus(true);
            // Kirim info user ke server
            socket.emit('user_join', {
                user_id: <?= session()->get('user_id') ?>,
                name: '<?= session()->get('name') ?>',
                role: '<?= session()->get('role') ?>',
            });
        });

        socket.on('disconnect', () => {
            setStatus(false);
        });

        socket.on('connect_error', () => {
            setStatus(false);
        });

        // Update jumlah user online di stats bar
        socket.on('online_count', (count) => {
            const el = document.getElementById('statOnline');
            if (el) el.textContent = count;
        });
    }

    // Jalankan socket saat halaman load
    initSocket();
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>