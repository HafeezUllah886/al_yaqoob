<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <h3 class="text-white">{{ projectNameShort() }}</h3>
            </span>
            <span class="logo-lg">
                <h3 class="text-white mt-3">{{ projectNameMedium() }}</h3>
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <h3 class="text-white">{{ projectNameShort() }}</h3>
            </span>
            <span class="logo-lg">
                <h3 class="text-white mt-3">{{ projectNameMedium() }}</h3>
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    {{--   <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                    alt="Header Avatar">
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text">{{ auth()->user()->name }}</span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text"><i
                            class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span
                            class="align-middle">Online</span></span>
                </span>
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
          
            <h6 class="dropdown-header">Welcome {{ auth()->user()->name }}!</h6>
            <a class="dropdown-item" href="{{ route('profile') }}"><i
                    class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Profile</span></a>
            <a class="dropdown-item" href="auth-logout-basic.html"><i
                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle"
                    data-key="t-logout">Logout</span></a>
        </div>
    </div> --}}
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('dashboard') }}">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li> <!-- end Dashboard Menu -->
                @canany(['Create Accounts', 'View Accounts'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#accounts" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarApps">
                            <i class="ri-book-3-line"></i><span data-key="t-apps">Accounts Management</span>
                        </a>
                        <div class="collapse menu-dropdown" id="accounts">
                            <ul class="nav nav-sm flex-column">
                                @can('Create Accounts')
                                    <li class="nav-item">
                                        <a href="{{ route('account.create') }}" class="nav-link" data-key="t-chat">Create
                                            Account</a>
                                    </li>
                                @endcan
                                @can('View Business Accounts')
                                    <li class="nav-item">
                                        <a href="{{ route('accountsList', 'Business') }}" class="nav-link"
                                            data-key="t-chat">Business Accounts</a>
                                    </li>
                                @endcan
                                @can('View Vendor Accounts')
                                    <li class="nav-item">
                                        <a href="{{ route('accountsList', 'Vendor') }}" class="nav-link"
                                            data-key="t-chat">Vendor Accounts</a>
                                    </li>
                                @endcan
                                @can('View Customer Accounts')
                                    <li class="nav-item">
                                        <a href="{{ route('accountsList', 'Customer') }}" class="nav-link"
                                            data-key="t-chat">Customer Accounts</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['View Users', 'View Roles'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sales" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarApps">
                            <i class="ri-group-line"></i><span data-key="t-apps">Users Management</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sales">
                            <ul class="nav nav-sm flex-column">
                                @can('View Users')
                                    <li class="nav-item">
                                        <a href="{{ route('users.index') }}" class="nav-link" data-key="t-chat">Users List</a>
                                    </li>
                                @endcan
                                @can('View Roles')
                                    <li class="nav-item">
                                        <a href="{{ route('roles.index') }}" class="nav-link" data-key="t-chat">Roles</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @can('View Branches')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('branches.index') }}">
                            <i class="ri-building-line"></i> <span data-key="t-dashboards">Branches</span>
                        </a>
                    </li> <!-- end Dashboard Menu -->
                @endcan
                @canany(['View Products', 'View Categories', 'View Units'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#products" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarApps">
                            <i class="ri-product-hunt-line"></i><span data-key="t-apps">Products Management</span>
                        </a>
                        <div class="collapse menu-dropdown" id="products">
                            <ul class="nav nav-sm flex-column">
                                @can('View Products')
                                    <li class="nav-item">
                                        <a href="{{ route('product.index') }}" class="nav-link" data-key="t-chat">List</a>
                                    </li>
                                @endcan
                                @can('View Categories')
                                    <li class="nav-item">
                                        <a href="{{ route('categories.index') }}" class="nav-link"
                                            data-key="t-chat">Categories</a>
                                    </li>
                                @endcan
                                @can('View Units')
                                    <li class="nav-item">
                                        <a href="{{ route('units.index') }}" class="nav-link" data-key="t-chat">Units</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
