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
                @canany(['Create Purchases', 'View Purchases'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('purchase.index') }}">
                            <i class="ri-shopping-cart-2-line"></i> <span data-key="t-dashboards">Purchases</span>
                        </a>
                    </li> <!-- end Dashboard Menu -->
                @endcanany
                @canany(['Create Sales', 'View Sales'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('sale.index') }}">
                            <i class="ri-shopping-bag-line"></i> <span data-key="t-dashboards">Sales</span>
                        </a>
                    </li> <!-- end Dashboard Menu -->
                @endcanany
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
                @canany(['Create Expenses', 'Create Transfers', 'Receivings', 'Payments', 'Account Adjustments'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#finance" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarApps">
                            <i class="ri-safe-2-line"></i><span data-key="t-apps">Finance Management</span>
                        </a>
                        <div class="collapse menu-dropdown" id="finance">
                            <ul class="nav nav-sm flex-column">
                                @can('Account Adjustments')
                                    <li class="nav-item">
                                        <a href="{{ route('account_adjustment.index') }}" class="nav-link"
                                            data-key="t-chat">Account Adjustments</a>
                                    </li>
                                @endcan
                                @can('Receivings')
                                    <li class="nav-item">
                                        <a href="{{ route('receivings.index') }}" class="nav-link" data-key="t-chat">Payment
                                            Receiving</a>
                                    </li>
                                @endcan
                                @can('Payments')
                                    <li class="nav-item">
                                        <a href="{{ route('payments.index') }}" class="nav-link" data-key="t-chat">Issue
                                            Payment</a>
                                    </li>
                                @endcan
                                @can('Transfers')
                                    <li class="nav-item">
                                        <a href="{{ route('transfers.index') }}" class="nav-link" data-key="t-chat">
                                            Transfers</a>
                                    </li>
                                @endcan
                                @can('Create Expenses')
                                    <li class="nav-item">
                                        <a href="{{ route('expenses.index') }}" class="nav-link" data-key="t-chat">Expenses</a>
                                    </li>
                                @endcan

                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['View Stocks', 'Transfer Stocks', 'Stock Adjustments'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#stock" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarApps">
                            <i class="ri-store-2-line"></i><span data-key="t-apps">Stock Management</span>
                        </a>
                        <div class="collapse menu-dropdown" id="stock">
                            <ul class="nav nav-sm flex-column">
                                @can('View Stocks')
                                    <li class="nav-item">
                                        <a href="{{ route('product_stock.index') }}" class="nav-link" data-key="t-chat">View
                                            Stock</a>
                                    </li>
                                @endcan
                                @can('Transfer Stocks')
                                    <li class="nav-item">
                                        <a href="{{ route('stockTransfer.index') }}" class="nav-link"
                                            data-key="t-chat">Transfer</a>
                                    </li>
                                @endcan
                                @can('Stock Adjustments')
                                    <li class="nav-item">
                                        <a href="{{ route('stockAdjustments.index') }}" class="nav-link"
                                            data-key="t-chat">Stock
                                            Adjustments</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
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
                                        <a href="{{ route('product.index') }}" class="nav-link" data-key="t-chat">Products
                                            List</a>
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
                                        <a href="{{ route('users.index') }}" class="nav-link" data-key="t-chat">Users
                                            List</a>
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

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
