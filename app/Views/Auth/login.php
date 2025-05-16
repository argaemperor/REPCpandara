<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- AdminLTE + Bootstrap 4 -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
    <style>
        .modal-xl-custom {
            max-width: 1000px;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>REPC</b> App</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Pandara Sport</p>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Email..." required>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-user"></i></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-lock"></i></div>
                        </div>
                    </div>

                    <!-- Tombol Register kiri dan Login kanan -->
                    <div class="row">
                        <div class="col-6">
                            <a href="<?= base_url('register') ?>" class="btn btn-outline-primary btn-block">Register</a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>




        <!-- JS Scripts -->
        <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
        <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
        <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>
</body>

</html>