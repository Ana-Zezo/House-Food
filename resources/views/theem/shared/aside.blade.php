  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">


      <ul class="sidebar-nav" id="sidebar-nav">

          <li class="nav-item">
              <a class="nav-link " href="{{ route('home') }}">
                  <i class="bi bi-grid"></i>
                  <span>Admin Panel</span>
              </a>
          </li><!-- End Dashboard Nav -->


          <li class="nav-heading">Management</li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{ route('dashboard.users.index') }}">
                  <i class="fa-solid fa-users"></i>
                  <span>Users</span>
              </a>
          </li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{ route('dashboard.chefs.index') }}">
                  <i class="fa-solid fa-kitchen-set"></i>
                  <span>Chefs</span>
              </a>
          </li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{ route('dashboard.foods.index') }}">
                  <i class="fa-solid fa-utensils"></i>
                  <span>Foods</span>
              </a>
          </li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{ route('dashboard.categories.index') }}">
                  <i class="fa-solid fa-layer-group"></i>
                  <span>Categories</span>
              </a>
          </li>

          <li class="nav-heading">Operations</li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{ route('dashboard.withdraws.index') }}">
                  <i class="bi bi-wallet2"></i>
                  <span>Withdraws</span>
              </a>
          </li>



      </ul>

  </aside><!-- End Sidebar-->
