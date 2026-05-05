<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TFG — Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

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

    .login-wrap {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 400px;
        padding: 20px;
    }

    .login-logo {
        text-align: center;
        margin-bottom: 32px;
    }

    .login-logo .brand {
        font-size: 28px;
        font-weight: 600;
        color: #00cfff;
        letter-spacing: 4px;
    }

    .login-logo .sub {
        font-size: 11px;
        color: #3a5070;
        letter-spacing: 2px;
        margin-top: 4px;
    }

    .login-card {
        background: #0d1220;
        border: 0.5px solid #1e2d4a;
        border-radius: 12px;
        padding: 32px;
    }

    .login-card h5 {
        font-size: 14px;
        color: #7090b0;
        margin-bottom: 24px;
        font-weight: 400;
        letter-spacing: 1px;
    }

    .form-label {
        font-size: 11px;
        color: #3a5070;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }

    .form-control {
        background: #0a1220 !important;
        border: 0.5px solid #1e2d4a;
        border-radius: 6px;
        color: #c0d8f0 !important;
        font-size: 13px;
        padding: 10px 14px;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        border-color: #00cfff55;
        box-shadow: 0 0 0 2px #00cfff15;
        outline: none;
    }

    .form-control::placeholder {
        color: #2a4060;
    }

    .input-group-text {
        background: #0a1220;
        border: 0.5px solid #1e2d4a;
        color: #3a5070;
        border-right: none;
    }

    .btn-login {
        width: 100%;
        background: #00cfff18;
        border: 0.5px solid #00cfff55;
        color: #00cfff;
        font-size: 13px;
        padding: 11px;
        border-radius: 6px;
        letter-spacing: 2px;
        font-weight: 500;
        margin-top: 8px;
        transition: background 0.2s;
    }

    .btn-login:hover {
        background: #00cfff28;
        color: #00cfff;
    }

    .alert-danger-custom {
        background: #ff445518;
        border: 0.5px solid #ff445544;
        color: #ff8090;
        font-size: 12px;
        border-radius: 6px;
        padding: 10px 14px;
        margin-bottom: 20px;
    }

    .login-footer {
        text-align: center;
        margin-top: 20px;
        font-size: 11px;
        color: #1e2d4a;
        letter-spacing: 1px;
    }
    </style>
</head>

<body>
    <div class="login-wrap">
        <div class="login-logo">
            <div class="brand">TFG</div>
            <div class="sub">TACTICAL FLOOR GAME</div>
        </div>

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
                    <input type="email" name="email" class="form-control" placeholder="email@tracksys.com"
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
        </div>

        <div class="login-footer">TRACKSYS v1.0 &nbsp;•&nbsp; SECURE ACCESS</div>
    </div>
</body>

</html>