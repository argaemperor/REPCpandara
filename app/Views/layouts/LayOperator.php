<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Admin Page' ?></title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">

  <!-- Bootstrap 4 (AdminLTE) -->
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">

  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">

  <!-- Optional: Responsive & Hover style -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css') ?>">

  <!-- Optional: Custom style (jika kamu punya) -->
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
</head>


<body class="hold-transition sidebar-mini">

  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Sidebar toggle button-->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
          </a>
        </li>
        <!-- <li class="nav-item d-none d-sm-inline-block">
          <a href="<?= base_url('/') ?>" class="nav-link">Dashboard</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="<?= base_url('/profile') ?>" class="nav-link">Profile</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="<?= base_url('/settings') ?>" class="nav-link">Settings</a>
        </li> -->
      </ul>

      <!-- Right navbar -->
      <ul class="navbar-nav ml-auto">
        <!-- Search -->
        <li class="nav-item">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </form>
        </li>

        <!-- User Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a href="<?= base_url('/profile') ?>" class="dropdown-item">
              <i class="fas fa-user-cog mr-2"></i> Profile
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?= base_url('/logout') ?>" class="dropdown-item text-danger">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->


    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url('Operator/Dashboard') ?>" class="brand-link">
        <img src="<?= base_url('assets/img/PandaraLogo.png') ?>" alt="Logo" style="width:50px; height:50px; object-fit:cover; margin-left:5px; margin-top:2px;" class="img-circle elevation-3">
        <span class="brand-text font-weight-light">REPC Pandara Sport</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?= base_url('assets/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">Halo, <?= session('fullname') ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

            <?php if (session('level') == 3): ?>
              <li class="nav-item">
                <a href="<?= base_url('Operator/Dashboard') ?>" class="nav-link <?= uri_string() == '' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
                </a>
              </li>
            <?php endif; ?>

            <?php if (session('level') == 3): ?>
              <li class="nav-item">
                <a href="<?= base_url('Operator/participant') ?>" class="nav-link <?= uri_string() == 'participants' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Participants</p>
                </a>
              </li>
            <?php endif; ?>

            <?php if (session('level') == 3): ?>
              <li class="nav-item">
                <a href="<?= base_url('Operator/search-participant') ?>" class="nav-link <?= uri_string() == 'participants' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Search Barcode</p>
                </a>
              </li>
            <?php endif; ?>


            <li class="nav-item">
              <a href="<?= base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                <p>Logout</p>
              </a>
            </li>

          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper p-4">
      <?= $this->renderSection('content') ?>
    </div>
    <!-- Footer -->
    <footer class="main-footer text-center">
      <strong>&copy; <?= date('Y') ?> Pandara Sport</strong> All rights reserved.
    </footer>

  </div>
  <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>

  <script src="<?= base_url('assets/plugins/jszip/jszip.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/pdfmake/pdfmake.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/pdfmake/vfs_fonts.js') ?>"></script>

  <script>
    const base_url = "<?= base_url() ?>";
    const session_user_id = <?= session('user_id') ?>;
  </script>
  <script src="<?= base_url('assets/js/operator.js') ?>"></script>
  <?php if (uri_string() == 'Operator/participant'): ?>
    <script src="<?= base_url('assets/js/OP_participan.js') ?>"></script>
  <?php endif; ?>

</body>

</html>