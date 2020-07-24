  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="<?= base_url() ?>assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">VideoMuslim TV</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img style="margin-top: 13px; " src="<?= base_url() ?>assets/dist/img/user8-128x128.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Ahlan Wasahlan,</a>
          <a href="#" class="d-block"><?= $this->session->userdata('nama'); ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="nav-flat">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?= site_url('admin/welcome') ?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= site_url('admin/kategori') ?>" class="nav-link">
              <i class="nav-icon fas fa-puzzle-piece"></i>
              <p>
                Kategori
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= site_url('admin/kajian') ?>" class="nav-link">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>
                Kajian
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= site_url('admin/ustadz') ?>" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Ustadz
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= site_url('admin/pengguna') ?>" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Pengguna
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= site_url('admin/logout') ?>" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
              <p>
                Keluar
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>