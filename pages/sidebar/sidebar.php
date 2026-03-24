<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <a class="brand-link user-panel pb-3 mb-3 d-flex">
    <img src="../../dist/img/itcsologo.webp" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light text-lg">CLEAN COPY</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <!-- Dashboard / Statistics -->
        <li id="statistics_sidebar" class="nav-item">
          <a href="home.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Management Menu (Parent) -->
        <li id="management_sidebar" class="nav-item menu-open">
          <a href="home.php" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>
              Management
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          
          <!-- Child Items -->
          <ul class="nav nav-treeview">
            <!-- Users Link -->
            <li class="nav-item">
              <a href="users.php" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>Users</p>
              </a>
            </li>
            
            <!-- Products Link -->
            <li class="nav-item">
              <a href="productlist.php" class="nav-link">
                <i class="nav-icon fas fa-box"></i>
                <p>Products</p>
              </a>
            </li>

            <!-- Customer Link -->
            <li class="nav-item">
              <a href="customerlist.php" class="nav-link">
                <i class="nav-icon fas fa-id-card"></i>
                <p>Customers</p>
              </a>
            </li>

              <li class="nav-item">
              <a href="logout.php" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Logout</p>
              </a>
            </li>
          </ul>
        </li>



      </ul>
    </nav>
  </div>
  <!-- /.sidebar -->

</aside>