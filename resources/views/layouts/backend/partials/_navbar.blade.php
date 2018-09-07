<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-3 ml-auto">
        <form class="input-icon my-3 my-lg-0">
          <input type="search" class="form-control header-search" placeholder="Search&hellip;" tabindex="1">
          <div class="input-icon-addon">
            <i class="fe fe-search"></i>
          </div>
        </form>
      </div>
      <div class="col-lg order-lg-first">
        <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
          <li class="nav-item">
            <a href="{{ route('home') }}" 
               class="nav-link{{ Request::is('dashboard') ? ' active' : '' }}"
            ><i class="fe fe-home"></i> Home</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('users.index') }}" 
               class="nav-link{{ Request::segment(1) == 'users' ? ' active' : '' }}"
            ><i class="fe fe-users"></i> Users</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('expenses.index') }}" 
               class="nav-link{{ Request::segment(1) == 'expenses' ? ' active' : '' }}"
            ><i class="fe fe-file-text"></i> Expenses</a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" 
               class="nav-link {{ in_array(request()->segment(1), ['products', 'store', 'store-report', 'purchases', 'sales']) ? 'active' : '' }}" 
               data-toggle="dropdown"
            >
              <i class="fe fe-box"></i> Store
            </a>
            <div class="dropdown-menu dropdown-menu-arrow">
              <a href="{{ route('stores.report.index') }}" 
                 class="dropdown-item {{ request()->segment(1) == 'store-report' ? 'active' : '' }}"
              >Store Reporting</a>
              <a href="{{ route('purchases.index') }}" 
                 class="dropdown-item {{ request()->segment(1) == 'purchases' ? 'active' : '' }}"
              >Purchase Reports</a>
              <a href="{{ route('sales.index') }}" 
                 class="dropdown-item {{ request()->segment(1) == 'sales' ? 'active' : '' }}"
              >Sales Report</a>
              <a href="{{ route('stores.index') }}" 
                 class="dropdown-item {{ request()->segment(1) == 'store' ? 'active' : '' }}"
              >Store Management</a>
              <a href="{{ route('products.index') }}" 
                 class="dropdown-item {{ request()->segment(1) == 'products' ? 'active' : '' }}"
              >Product Management</a>
            </div>
          </li>
          <li class="nav-item">
            <a href="{{ route('vendors.index') }}" 
               class="nav-link{{ Request::segment(1) == 'vendors' ? ' active' : '' }}"
            ><i class="fe fe-folder"></i> Vendors</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('outlets.index') }}" 
               class="nav-link{{ Request::segment(1) == 'outlets' ? ' active' : '' }}"
            ><i class="fe fe-folder-minus"></i> Outlets</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('bankings.index') }}" 
               class="nav-link{{ Request::segment(1) == 'bankings' ? ' active' : '' }}"
            ><i class="fe fe-folder-minus"></i> Bankings</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>