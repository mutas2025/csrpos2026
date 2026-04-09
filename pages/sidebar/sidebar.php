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
        
        <!-- Dashboard -->
        <li id="statistics_sidebar" class="nav-item">
          <a href="home.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

                <!-- Users Link -->
        <li id="pos_sidebar" class="nav-item">
          <a href="pos.php" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>POS</p>
          </a>
        </li>

        <!-- Users Link -->
        <li id="users_sidebar" class="nav-item">
          <a href="users.php" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Users</p>
          </a>
        </li>
        
        <!-- Products Link -->
        <li id="products_sidebar" class="nav-item">
          <a href="productlist.php" class="nav-link">
            <i class="nav-icon fas fa-box"></i>
            <p>Products</p>
          </a>
        </li>

        <!-- Customer Link -->
        <li id="customers_sidebar" class="nav-item">
          <a href="customerlist.php" class="nav-link">
            <i class="nav-icon fas fa-id-card"></i>
            <p>Customers</p>
          </a>
        </li>

        <!-- Logout -->
        <li class="nav-item">
          <a href="logout.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Logout</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
  <!-- /.sidebar -->

</aside>

<!-- JAVASCRIPT TO HANDLE ACTIVE STATE (Place this at the bottom of the page or in your main script file) -->
<script>
  $(function() {
    // Get current page filename
    var path = window.location.pathname;
    var page = path.split("/").pop();

    // Remove all active classes first
    $('.nav-link').removeClass('active');

    // Apply active class based on current page
    if (page === 'home.php') {
      $('#statistics_sidebar .nav-link').addClass('active');
    } else if (page === 'users.php') {
      $('#users_sidebar .nav-link').addClass('active');
    } else if (page === 'productlist.php') {
      $('#products_sidebar .nav-link').addClass('active');
    } else if (page === 'customerlist.php') {
      $('#customers_sidebar .nav-link').addClass('active');
    }
  });
</script>