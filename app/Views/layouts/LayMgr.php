<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Admin Page' ?></title>

  <!-- CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/sweetalert2.min.css') ?>">
  <style>
    th.no-sort::after,
    th.no-sort::before {
      display: none !important;
      content: none !important;
    }

    th.no-sort {
      pointer-events: none;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
          </a>
        </li>
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

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url('EventMgr/Dashboard') ?>" class="brand-link">
        <img src="<?= base_url('assets/img/PandaraLogo.png') ?>" alt="Logo" class="img-circle elevation-3" style="width:50px; height:50px; object-fit:cover; margin-left:5px; margin-top:2px;">
        <span class="brand-text font-weight-light">REPC Pandara Sport</span>
      </a>

      <div class="sidebar">
        <!-- User Panel -->
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

            <?php if (session('level') == 2): ?>
              <li class="nav-item">
                <a href="<?= base_url('EventMgr/Dashboard') ?>" class="nav-link <?= uri_string() == 'EventMgr/Dashboard' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('EventMgr/participantCO') ?>" class="nav-link <?= uri_string() == 'checkoutScan' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-shopping-cart"></i>
                  <p>Checkout Peserta </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('EventMgr/participant') ?>" class="nav-link <?= uri_string() == 'EventMgr/participant' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Participant</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('admin/import') ?>" class="nav-link <?= uri_string() == 'admin/import' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-file-import"></i>
                  <p>Import Data</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('admin/export') ?>" class="nav-link <?= uri_string() == 'admin/export' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-file-export"></i>
                  <p>Export Data</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('EventMgr/Event') ?>" class="nav-link <?= uri_string() == 'EventMgr/Event' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-calendar-alt"></i>
                  <p>Event List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('EventManager/users') ?>" class="nav-link <?= uri_string() == 'users' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-user-cog"></i>
                  <p>Operator List</p>
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
      </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper p-4">
      <?= $this->renderSection('content') ?>
    </div>

  </div>

  <!-- JS Scripts -->
  <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/sweetalert2.all.min.js') ?>"></script>


  <script>
    const base_url = "<?= base_url() ?>";
  </script>

  <!-- Custom JS -->
  <script src="<?= base_url('assets/js/Mgr_participant.js') ?>"></script>
</body>

</html>