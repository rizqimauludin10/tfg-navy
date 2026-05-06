<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TFG — Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background: #0a0e1a;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Inter', sans-serif;
    }

    /* Grid background */
    body::before {
        content: '';
        position: fixed;
        inset: 0;
        background-image:
            linear-gradient(#0e2035 1px, transparent 1px),
            linear-gradient(90deg, #0e2035 1px, transparent 1px);
        background-size: 50px 50px;
        z-index: 0;
    }

    /* WRAP diperbesar */
    .login-wrap {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 460px;
        /* sebelumnya 400 */
        padding: 24px;
    }

    .login-logo {
        text-align: center;
        margin-bottom: 36px;
    }

    .login-logo .brand {
        font-size: 30px;
        font-weight: 600;
        color: #00cfff;
        letter-spacing: 5px;
    }

    .login-logo .sub {
        font-size: 11px;
        color: #3a5070;
        letter-spacing: 2px;
        margin-top: 6px;
    }

    /* CARD lebih kontras + glow tipis */
    .login-card {
        background: #111a2e;
        /* lebih terang dari sebelumnya */
        border: 1px solid #00cfff33;
        border-radius: 14px;
        padding: 40px;
        /* lebih besar */
        box-shadow: 0 0 20px #00cfff10;
    }

    .login-card h5 {
        font-size: 14px;
        color: #8fb3d9;
        margin-bottom: 26px;
        font-weight: 400;
        letter-spacing: 1px;
    }

    .form-label {
        font-size: 11px;
        color: #4e6a8c;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }

    .form-control {
        background: #0c1426 !important;
        border: 1px solid #223556;
        border-radius: 6px;
        color: #c0d8f0 !important;
        font-size: 13px;
        padding: 11px 14px;
    }

    .form-control:focus {
        border-color: #00cfff88;
        box-shadow: 0 0 0 2px #00cfff20;
    }

    .form-control::placeholder {
        color: #2a4060;
    }

    .form-control:focus {
        border-color: #00cfff;
        box-shadow: 0 0 6px #00cfff55;
    }

    /* BUTTON lebih hidup */
    .btn-login {
        width: 100%;
        background: #00cfff22;
        border: 1px solid #00cfff66;
        color: #00cfff;
        font-size: 13px;
        padding: 12px;
        border-radius: 6px;
        letter-spacing: 2px;
        font-weight: 500;
        margin-top: 10px;
        transition: all 0.2s;
    }

    .btn-login:hover {
        background: #00cfff35;
        box-shadow: 0 0 12px #00cfff40;
    }

    /* Alert */
    .alert-danger-custom {
        background: #ff445518;
        border: 1px solid #ff445544;
        color: #ff8090;
        font-size: 12px;
        border-radius: 6px;
        padding: 10px 14px;
        margin-bottom: 20px;
    }

    /* Footer */
    .login-footer {
        text-align: center;
        margin-top: 24px;
        font-size: 11px;
        color: #2a4060;
        letter-spacing: 1px;
    }

    /* COPYRIGHT baru */
    .login-copy {
        text-align: center;
        margin-top: 10px;
        font-size: 10px;
        color: #1e2d4a;
        letter-spacing: 1px;
    }

    /* === SYSTEM STATUS TEXT === */
    .login-status {
        text-align: center;
        font-size: 10px;
        color: #00ff9c;
        letter-spacing: 2px;
        margin-bottom: 16px;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .login-status {
        animation: blink 2s infinite;
    }

    /* === CORNER BRACKET (HUD STYLE) === */
    /* === CARD BASE === */
    .login-card {
        position: relative;
        background: #111a2e;
        border: 1px solid #00cfff33;
        border-radius: 14px;
        padding: 40px;
        box-shadow: 0 0 20px #00cfff10;
    }

    /* === 4 CORNER FRAME === */
    .login-card::before,
    .login-card::after,
    .login-card .corner::before,
    .login-card .corner::after {
        content: '';
        position: absolute;
        width: 28px;
        height: 28px;
        border: 4px solid #00cfff80;

    }

    /* TOP LEFT */
    .login-card::before {
        top: 0;
        left: 0;
        border-right: none;
        border-bottom: none;
    }

    /* TOP RIGHT */
    .login-card::after {
        top: 0;
        right: 0;
        border-left: none;
        border-bottom: none;
    }

    /* BOTTOM LEFT */
    .login-card .corner::before {
        bottom: 0;
        left: 0;
        border-right: none;
        border-top: none;
    }

    /* BOTTOM RIGHT */
    .login-card .corner::after {
        bottom: 0;
        right: 0;
        border-left: none;
        border-top: none;
    }

    .login-card:hover {
        box-shadow: 0 0 25px #00cfff25, 0 0 10px #00cfff20 inset;
        transition: 0.3s ease;
    }



    /* === SCAN LINE ANIMATION === */
    body::after {
        content: '';
        position: fixed;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, #00cfff55, transparent);
        top: 0;
        left: 0;
        z-index: 0;
        animation: scan 6s linear infinite;
        pointer-events: none;
    }

    @keyframes scan {
        0% {
            top: 0%;
            opacity: 0;
        }

        10% {
            opacity: 1;
        }

        50% {
            top: 100%;
            opacity: 0.6;
        }

        100% {
            top: 100%;
            opacity: 0;
        }
    }
    </style>
</head>

<body>
    <div class="login-wrap">
        <div class="login-logo">
            <div class="brand">TFG</div>
            <div class="sub">TACTICAL FLOOR GAME</div>
        </div>
        <div class="login-status">SYSTEM ONLINE • ACCESS LEVEL REQUIRED</div>

        <div class="login-card">
            <h5>SIGN IN TO CONTINUE</h5>

            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-danger-custom">
                <i class="fa fa-circle-exclamation me-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>

            <form action="/auth/login" method="POST">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">EMAIL</label>
                    <input type="email" name="email" class="form-control" placeholder="email@tnial.mil.id"
                        value="<?= old('email') ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">PASSWORD</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa fa-right-to-bracket me-2"></i>LOGIN
                </button>
            </form>
            <div class="corner"></div>
        </div>

        <div class="login-footer">TFG v1.0 • SECURE ACCESS</div>
        <div class="login-copy">© 2026 RIZQIMAULUDIN</div>
    </div>
</body>

</html>