            <div class="app-sidebar-menu">
                <div class="h-100" data-simplebar>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <div class="logo-box">
                            <a href="#" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('backend/assets/images/logo-light.png') }}" alt="" height="24">
                                </span>
                            </a>
                            <a href="#" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="" height="22">
                                </span>
                               <span class="logo-lg">
                                    <a href="{{ route('dashboard') }}">
                                        <img src="{{ asset('backend/assets/images/11g.png') }}" alt="Logo" height="60">
                                    </a>
                                </span>
                            </a>
                        </div>

                        <ul id="side-menu">

                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="{{ route('dashboard') }}" class="tp-link">
                                    <i data-feather="home"></i>
                                    <span>Dashboard</span>
                                </a>


                            </li>



                            <li class="menu-title">Pages</li>



                            @canany(['brand.view', 'brand.create', 'brand.update', 'brand.delete'])
                                <li>
                                    <a href="#sidebarAuth" data-bs-toggle="collapse">
                                        <i data-feather="tag"></i>
                                        <span>Brand Manage</span>
                                    </a>

                                    <div class="collapse" id="sidebarAuth">
                                        <ul class="nav-second-level">
                                            <li>
                                                <a href="{{ route('brand') }}" class="tp-link">All Brand</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                             @endcanany


                            <li>
                                <a href="#sidebarWarehouse" data-bs-toggle="collapse">
                                    <i data-feather="archive"></i>
                                    <span>Warehouse Manage</span>
                                    {{-- <span class="menu-arrow"></span> --}}
                                </a>
                                <div class="collapse" id="sidebarWarehouse">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('warehouse') }}" class="tp-link">All Warehouse</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                    <a href="#sidebarSuppliers" data-bs-toggle="collapse">
                                        <i data-feather="truck"></i>
                                        <span> Supplier Manage </span>
                                        {{-- <span class="menu-arrow"></span> --}}
                                    </a>

                                    <div class="collapse" id="sidebarSuppliers">
                                        <ul class="nav-second-level">
                                            <li>
                                                <a href="{{ route('suppliers') }}" class="tp-link">All Supplier</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            <li>
                                <a href="#sidebarBaseui" data-bs-toggle="collapse">
                                    <i data-feather="user"></i>
                                    <span> Customer Manage </span>
                                    {{-- <span class="menu-arrow"></span> --}}
                                </a>
                                <div class="collapse" id="sidebarBaseui">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('customers') }}" class="tp-link">All Customer</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#sidebarProduct" data-bs-toggle="collapse">
                                    <i data-feather="shopping-bag"></i>
                                    <span> Product Manage </span>
                                    {{-- <span class="menu-arrow"></span> --}}
                                </a>
                                <div class="collapse" id="sidebarProduct">
                                    <ul class="nav-second-level">

                                        <li>
                                            <a href="{{ route('categories') }}" class="tp-link">
                                                All Category
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('product') }}" class="tp-link">
                                                All Product
                                            </a>
                                        </li>

                                        {{-- All Warehouses --}}
                                        <li>
                                            <a data-bs-toggle="collapse"
                                            href="#warehouseMenu"
                                            role="button"
                                            aria-expanded="false"
                                            aria-controls="warehouseMenu"
                                            class="tp-link">

                                                All Warehouses
                                            </a>

                                            <div class="collapse" id="warehouseMenu">
                                                <ul class="nav-third-level">

                                                    @foreach ($warehouses as $warehouse)
                                                        <li>
                                                            <a href="{{ route('warehouse.products', $warehouse->id) }}"
                                                            class="tp-link">
                                                                {{ $warehouse->warehouse_name }}
                                                            </a>
                                                        </li>
                                                    @endforeach

                                                </ul>
                                            </div>
                                        </li>

                                    </ul>
                                </div>

                            </li>
                            <li>
                                <a href="#sidebarPurchase" data-bs-toggle="collapse">
                                    <i data-feather="shopping-cart"></i>
                                    <span> Purchase Manage </span>
                                    {{-- <span class="menu-arrow"></span> --}}
                                </a>
                                <div class="collapse" id="sidebarPurchase">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('purchase') }}" class="tp-link">All Purchase</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('return.purchase') }}" class="tp-link">Purchase Return</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#sidebarSale" data-bs-toggle="collapse">
                                    <i data-feather="dollar-sign"></i>
                                    <span> Sale Manage </span>
                                </a>
                                <div class="collapse" id="sidebarSale">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('all.sales') }}" class="tp-link">All Sales</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('allreturn.sales') }}" class="tp-link">Sale Return</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#sidebarDue" data-bs-toggle="collapse">
                                    <i data-feather="alert-circle"></i>
                                    <span> Due Setup </span>
                                </a>
                                <div class="collapse" id="sidebarDue">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('due.sale') }}" class="tp-link">All Sales Due</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#sidebarTransfer" data-bs-toggle="collapse">
                                    <i data-feather="repeat"></i>
                                    <span> Transfer Setup </span>
                                    {{-- <span class="menu-arrow"></span> --}}
                                </a>
                                <div class="collapse" id="sidebarTransfer">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('all.transfer') }}" class="tp-link">All Transfer</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                    <li>
                                <a href="#sidebarReport" data-bs-toggle="collapse">
                                    <i data-feather="file-text"></i>
                                    <span> Report Setup </span>
                                </a>
                                <div class="collapse" id="sidebarReport">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('all.reports') }}" class="tp-link">All Reports</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                        @can('permission.view')
                            <li class="menu-title mt-2">General</li>


                            <li>
                                <a href="#permission" data-bs-toggle="collapse">
                                    <i data-feather="shield"></i>
                                    <span>Role & Permission</span>
                                </a>

                                <div class="collapse" id="permission">
                                    <ul class="nav-second-level">



                                        @can('role.view')
                                        <li>
                                            <a href="{{ route('all.roles') }}" class="tp-link">All Roles</a>
                                        </li>
                                        @endcan

                                        @can('role.permission.assign')
                                        <li>
                                            <a href="{{ route('all.roles.permission') }}" class="tp-link">All Role in Permission</a>
                                        </li>
                                        @endcan
                                          @can('permission.view')
                                        <li>
                                            <a href="{{ route('all.permission') }}" class="tp-link">All Permission</a>
                                        </li>
                                        @endcan

                                    </ul>
                                </div>
                            </li>
                            @endcan

                  @can('admin.view')
                    <li>
                        <a href="{{ route('all.admin') }}">
                            <i data-feather="users"></i>
                            <span>Admin Manage</span>
                        </a>
                    </li>
                    @endcan

                    @can('activity-log.view')
                    <li>
                        <a href="{{ route('activity.log') }}">
                            <i data-feather="activity"></i>
                            <span>Activity Log</span>
                        </a>
                    </li>
                    @endcan
                        </ul>

                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
            </div>
