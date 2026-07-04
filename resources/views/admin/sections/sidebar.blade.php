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
                                    <img src="{{ asset('backend/assets/images/11g.png') }}" alt="" height="60">
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

                                {{-- <div class="collapse" id="sidebarDashboards">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="index.html" class="tp-link">Analytical</a>
                                        </li>
                                        <li>
                                            <a href="ecommerce.html" class="tp-link">E-commerce</a>
                                        </li>
                                    </ul>
                                </div> --}}
                            </li>

                            <!-- <li>
                                <a href="landing.html" target="_blank">
                                    <i data-feather="globe"></i>
                                    <span> Landing </span>
                                </a>
                            </li> -->

                            <li class="menu-title">Pages</li>

                            <li>
                                <a href="#sidebarAuth" data-bs-toggle="collapse">
                                    <i data-feather="tag"></i>
                                    <span> Brand Manage </span>
                                    {{-- <span class="menu-arrow"></span> --}}
                                </a>
                                <div class="collapse" id="sidebarAuth">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('brand') }}" class="tp-link">All Brand</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

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
                                            <a href="{{ route('categories') }}" class="tp-link">All Category</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('product') }}" class="tp-link">All Product</a>
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
                                    {{-- <span class="menu-arrow"></span> --}}
                                </a>
                                <div class="collapse" id="sidebarSale">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="#" class="tp-link">All Customer</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#sidebarDue" data-bs-toggle="collapse">
                                    <i data-feather="alert-circle"></i>
                                    <span> Due Setup </span>
                                    {{-- <span class="menu-arrow"></span> --}}
                                </a>
                                <div class="collapse" id="sidebarDue">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="#" class="tp-link">All Customer</a>
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
                                            <a href="#" class="tp-link">All Customer</a>
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
                                            <a href="#" class="tp-link">All Customer</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            {{-- this is for review --}}
                                {{-- <li>
                                    <a href="#sidebarReview" data-bs-toggle="collapse">
                                        <i data-feather="star"></i>
                                        <span> Reviews Setup</span>
                                    </a>
                                    <div class="collapse" id="sidebarReview">
                                        <ul class="nav-second-level">
                                            <li>
                                                <a href="{{ route('all.review') }}" class="tp-link">All Review</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li> --}}

                                {{-- This is for slider --}}
                                {{-- <li>
                                    <a href="#sidebarSlider" data-bs-toggle="collapse">
                                        <i data-feather="image"></i>
                                        <span> Slider Setup </span>
                                    </a>

                                    <div class="collapse" id="sidebarSlider">
                                        <ul class="nav-second-level">
                                            <li>
                                                <a href="{{ route('finances') }}" class="tp-link">Get Slider</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li> --}}

                                {{-- this is for feature --}}
                                 {{-- <li>
                                    <a href="#sidebarFeature" data-bs-toggle="collapse">
                                        <i data-feather="image"></i>
                                        <span>Feature Setup</span>
                                    </a>

                                    <div class="collapse" id="sidebarFeature">
                                        <ul class="nav-second-level">
                                            <li>
                                                <a href="{{ route('features') }}" class="tp-link">All Features</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li> --}}

                            <li class="menu-title mt-2">General</li>



                            <li>
                                <a href="widgets.html" class="tp-link">
                                    <i data-feather="aperture"></i>
                                    <span> Widgets </span>
                                </a>
                            </li>

                            <li>
                                <a href="#sidebarAdvancedUI" data-bs-toggle="collapse">
                                    <i data-feather="cpu"></i>
                                    <span> Extended UI </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarAdvancedUI">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="extended-carousel.html" class="tp-link">Carousel</a>
                                        </li>
                                        <li>
                                            <a href="extended-notifications.html" class="tp-link">Notifications</a>
                                        </li>
                                        <li>
                                            <a href="extended-offcanvas.html" class="tp-link">Offcanvas</a>
                                        </li>
                                        <li>
                                            <a href="extended-range-slider.html" class="tp-link">Range Slider</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>



                            <li>
                                <a href="#sidebarForms" data-bs-toggle="collapse">
                                    <i data-feather="briefcase"></i>
                                    <span> Forms </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarForms">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="forms-elements.html" class="tp-link">General Elements</a>
                                        </li>
                                        <li>
                                            <a href="forms-validation.html" class="tp-link">Validation</a>
                                        </li>
                                        <li>
                                            <a href="forms-quilljs.html" class="tp-link">Quilljs Editor</a>
                                        </li>
                                        <li>
                                            <a href="forms-pickers.html" class="tp-link">Picker</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#sidebarTables" data-bs-toggle="collapse">
                                    <i data-feather="table"></i>
                                    <span> Tables </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarTables">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="tables-basic.html" class="tp-link">Basic Tables</a>
                                        </li>
                                        <li>
                                            <a href="tables-datatables.html" class="tp-link">Data Tables</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#sidebarCharts" data-bs-toggle="collapse">
                                    <i data-feather="pie-chart"></i>
                                    <span> Apex Charts </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarCharts">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href='charts-line.html'>Line</a>
                                        </li>
                                        <li>
                                            <a href='charts-area.html'>Area</a>
                                        </li>
                                        <li>
                                            <a href='charts-column.html'>Column</a>
                                        </li>
                                        <li>
                                            <a href='charts-bar.html'>Bar</a>
                                        </li>
                                        <li>
                                            <a href='charts-mixed.html'>Mixed</a>
                                        </li>
                                        <li>
                                            <a href='charts-timeline.html'>Timeline</a>
                                        </li>
                                        <li>
                                            <a href='charts-rangearea.html'>Range Area</a>
                                        </li>
                                        <li>
                                            <a href='charts-funnel.html'>Funnel</a>
                                        </li>
                                        <li>
                                            <a href='charts-candlestick.html'>Candlestick</a>
                                        </li>
                                        <li>
                                            <a href='charts-boxplot.html'>Boxplot</a>
                                        </li>
                                        <li>
                                            <a href='charts-bubble.html'>Bubble</a>
                                        </li>
                                        <li>
                                            <a href='charts-scatter.html'>Scatter</a>
                                        </li>
                                        <li>
                                            <a href='charts-heatmap.html'>Heatmap</a>
                                        </li>
                                        <li>
                                            <a href='charts-treemap.html'>Treemap</a>
                                        </li>
                                        <li>
                                            <a href='charts-pie.html'>Pie</a>
                                        </li>
                                        <li>
                                            <a href='charts-radialbar.html'>Radialbar</a>
                                        </li>
                                        <li>
                                            <a href='charts-radar.html'>Radar</a>
                                        </li>
                                        <li>
                                            <a href='charts-polararea.html'>Polar</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#sidebarMaps" data-bs-toggle="collapse">
                                    <i data-feather="map"></i>
                                    <span> Maps </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarMaps">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="maps-google.html" class="tp-link">Google Maps</a>
                                        </li>
                                        <li>
                                            <a href="maps-vector.html" class="tp-link">Vector Maps</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                        </ul>

                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
            </div>
