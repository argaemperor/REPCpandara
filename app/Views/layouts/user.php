<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Admin Page' ?></title>
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">

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
        <li class="nav-item d-none d-sm-inline-block">
          <a href="<?= base_url('/') ?>" class="nav-link">Dashboard</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="<?= base_url('/profile') ?>" class="nav-link">Profile</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="<?= base_url('/settings') ?>" class="nav-link">Settings</a>
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


    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url('/') ?>" class="brand-link">
        <img src="<?= base_url('assets/dist/img/AdminLTELogo.png') ?>" alt="Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">CI4 Panel</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?= base_url('assets/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">Halo, Arga</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

            <?php if (session('level') == 1): ?>
              <li class="nav-item">
                <a href="<?= base_url('Admin/Dashboard') ?>" class="nav-link <?= uri_string() == '' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
                </a>
              </li>
            <?php endif; ?>

            <?php if (session('level') == 2): ?>
              <li class="nav-item">
                <a href="<?= base_url('User/Dashboard') ?>" class="nav-link <?= uri_string() == '' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
                </a>
              </li>
            <?php endif; ?>

            <?php if (session('level') == 1): ?>
              <?php $master_active = in_array(uri_string(), ['users', 'Jersey', 'EventMaster']); ?>
              <li class="nav-item <?= $master_active ? 'menu-open' : '' ?>">
                <a href="#" class="nav-link <?= $master_active ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Master
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?= base_url('users') ?>" class="nav-link <?= uri_string() == 'users' ? 'active' : '' ?>">
                      <i class="nav-icon fas fa-users"></i>
                      <p>Users</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= base_url('Jersey') ?>" class="nav-link <?= uri_string() == 'Jersey' ? 'active' : '' ?>">
                      <i class="nav-icon fas fa-briefcase"></i>
                      <p>Jersey</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= base_url('Admin/Event') ?>" class="nav-link <?= uri_string() == 'EventMaster' ? 'active' : '' ?>">
                      <i class="nav-icon fas fa-building"></i>
                      <p>Event Master</p>
                    </a>
                  </li>
                </ul>
              </li>
            <?php endif; ?>

            <?php if (in_array(session('level'), [1, 2])): ?>
              <li class="nav-item">
                <a href="<?= base_url('participants') ?>" class="nav-link <?= uri_string() == 'participants' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Participants</p>
                </a>
              </li>
            <?php endif; ?>

            <?php if (session('level') == 1): ?>
              <li class="nav-item">
                <a href="<?= base_url('Import') ?>" class="nav-link <?= uri_string() == 'Import' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-file-import"></i>
                  <p>Import</p>
                </a>
              </li>
            <?php endif; ?>
            <?php if (session('level') == 1): ?>
              <li class="nav-item">
                <a href="<?= base_url('settings') ?>" class="nav-link <?= uri_string() == 'settings' ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-cogs"></i>
                  <p>Settings</p>
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

  </div>

  <!-- AdminLTE Scripts -->
  <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>


  <!-- DataTables -->

  <script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
  <script>
    const base_url = "<?= base_url() ?>";
  </script>
  <script src="<?= base_url('assets/js/main-app.js') ?>"></script>
  <script src="<?= base_url('assets/js/master-event.js') ?>"></script>
  <script src="<?= base_url('assets/js/master-user.js') ?>"></script>
  <script src="<?= base_url('assets/js/participant.js') ?>"></script>



</body>

</html>