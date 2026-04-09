<aside class="main-sidebar sidebar-dark-primary elevation-4">
  
  <!-- Brand Logo -->
  <a href="home.php" class="brand-link user-panel pb-3 mb-3 d-flex">
    <img src="../../dist/img/itcsologo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light text-lg">POS System</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <!-- Dashboard (Standalone) -->
        <li id="dashboard_sidebar" class="nav-item">
          <a href="home.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Group: Operations (POS, Transactions, Sales) -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cogs"></i>
            <p>
              Operations
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li id="pos_sidebar" class="nav-item">
              <a href="pos.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>POS System</p>
              </a>
            </li>
            <li id="transactions_sidebar" class="nav-item">
              <a href="transaction.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Transactions</p>
              </a>
            </li>
            <li id="sales_sidebar" class="nav-item">
              <a href="sales.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales Report</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Group: Inventory (Products, Customers) -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-boxes"></i>
            <p>
              Inventory & CRM
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li id="products_sidebar" class="nav-item">
              <a href="productlist.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Products</p>
              </a>
            </li>
            <li id="customers_sidebar" class="nav-item">
              <a href="customerlist.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Customers</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Settings / Users -->
        <li id="users_sidebar" class="nav-item">
          <a href="users.php" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>User Management</p>
          </a>
        </li>

        <!-- Logout Divider -->
        <li class="nav-header">ACCOUNT</li>
        
        <li id="logout_sidebar" class="nav-item">
          <a href="logout.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
            <p class="text-danger">Logout</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
  <!-- /.sidebar -->

</aside>