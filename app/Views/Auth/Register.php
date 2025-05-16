<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - REPC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- AdminLTE + Bootstrap -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">

    <style>
        .register-box {
            width: 600px;
        }

        .input-group-text {
            width: 42px;
            justify-content: center;
        }
    </style>
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>REPC</b> App</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register a new membership</p>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('register') ?>" method="post" id="registerForm">
                    <?= csrf_field() ?>

                    <div class="input-group mb-3">
                        <input type="text" name="id_number" class="form-control" placeholder="ID Number (KTP/SIM)" value="<?= old('id_number') ?>" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Full Name" value="<?= old('name') ?>" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-user"></i></div>
                        </div>
                    </div>

                    <!-- <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" value="<?= old('username') ?>" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-user-circle"></i></div>
                        </div>
                    </div> -->

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" value="<?= old('email') ?>" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="phone" class="form-control" value="<?= old('phone') ?>" placeholder="Phone">
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-phone"></i></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea name="address" class="form-control" rows="2" placeholder="Address" value="<?= old('address') ?>"></textarea>
                    </div>

                    <div class="input-group mb-3">
                        <input type="number" name="usia" class="form-control" placeholder="Usia" value="<?= old('usia') ?>" required min="10">
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-birthday-cake"></i></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <select name="EventId" class="form-control" value="<?= old('EventId') ?>" required>
                            <option value="">-- Pilih Event --</option>
                            <?php foreach ($events as $event): ?>
                                <option value="<?= $event['eventId'] ?>">
                                    <?= esc($event['eventName']) ?> - <?= esc($event['eventYears']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                        </div>
                    </div>



                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-lock"></i></div>
                        </div>
                    </div>

                    <div class="input-group mb-4">
                        <input type="password" name="password_confirm" class="form-control" placeholder="Retype password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-lock"></i></div>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="row">
                        <div class="col-6">
                            <a href="<?= base_url('login') ?>" class="btn btn-secondary btn-block">Batal</a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>

    <script>
        // Validasi JS
        $('#registerForm').on('submit', function(e) {
            const pass = $('[name="password"]').val();
            const confirm = $('[name="password_confirm"]').val();
            const usia = parseInt($('[name="usia"]').val());

            if (pass !== confirm) {
                alert("Password dan konfirmasi tidak cocok!");
                e.preventDefault();
            } else if (usia < 10) {
                alert("Usia minimal 10 tahun.");
                e.preventDefault();
            }
        });
    </script>
</body>

</html>