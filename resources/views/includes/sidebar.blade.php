<div class="sb2-1">
    <!--== USER INFO ==-->
   
    <!--== LEFT MENU ==-->
    <div class="sb2-13" id="sidebarid">
        <ul class="collapsible" data-collapsible="accordion">
            <li><a href="{{ route('dashboard') }}" class="menu-active"><i class="fa fa-bar-chart"
                        aria-hidden="true"></i>
                    Dashboard</a>
            </li>

            @if(auth()->check() && auth()->user()->role === 'admin')
            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fa fa-user" aria-hidden="true"></i>
                    Users</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('alluser') }}">All Users</a></li>
                        <li><a href="{{ route('adduser') }}">Add New user</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fa fa-warehouse" aria-hidden="true"></i>
                    Warehouses</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('warehouses.index') }}">All Warehouses</a></li>
                        <li><a href="{{ route('warehouses.create') }}">Add New Warehouses</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-store"></i>
                    Shopes</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('allshopes') }}">All Shopes</a></li>
                        <li><a href="{{ route('addshopes') }}">Add Shopes</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-truck"></i>
                    Supplier</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('allsuppliers') }}">All Supplier</a></li>
                        <li><a href="{{ route('addsuppliers') }}">Add New Supplier</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-chart-bar"></i>
                    Reports</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('sales.report.index') }}">Reports</a></li>
                        <li><a href="{{ route('products.lowstock') }}">Low Stock Products</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-wallet"></i>
                    Invoice Payment (developing)</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('payments.index') }}">Payments</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-file-invoice"></i>
                    GRN</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('grns.index') }}">All GRN</a></li>
                        <li><a href="{{ route('grns.create') }}">Add New GRN</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-box-open"></i>
                    Products</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('allproduct') }}">All Products</a></li>
                        <li><a href="{{ route('addproduct') }}">Add Products</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-arrow-circle-left"></i>
                    Returns</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('allReturns') }}">All Returns</a></li>
                        <li><a href="{{ route('addReturns') }}">Add Returns</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-dollar-sign"></i>
                    Financial</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('allfinancial') }}">All Expense</a></li>
                        <li><a href="{{ route('addfinancial') }}">Add Expense</a></li>
                         <li><a href="{{ route('payments.index') }}">All Payments</a></li>
                           <li><a href="{{ route('payments.create') }}">Add New Payments</a></li>
                    </ul>
                </div>
            </li>
            @endif

            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-file-invoice-dollar"></i>
                    Invoice</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <li><a href="{{ route('invoice.index') }}">All Invoice</a></li>
                        @endif
                        @if(auth()->check() && auth()->user()->role === 'ref' || auth()->check() && auth()->user()->role == 'retail')
                        <li><a href="{{ route('refinvoice.index') }}">All Invoice</a></li>
                        <li><a href="{{ route('ref.addinvoice') }}">Add Invoice</a></li>
                        @endif
                        @if(auth()->check() && auth()->user()->role === 'stock')
                        <li><a href="{{ route('stockinvoice.index') }}">All Invoice</a></li>
                        @endif
                    </ul>
                </div>
            </li>

            @if(auth()->check() && auth()->user()->role === 'admin')
            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-trash"></i>

                    Bin</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('bin_products') }}">Bin Products</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-bicycle"></i>  <!-- Bicycle icon -->
                    Department</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('add-brand') }}">Add Department</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-hard-hat"></i>
 <!-- Tire icon -->

                    Category</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('real-categories.index') }}">Add Category</a></li>
                    </ul>
                </div>
            </li>
             <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-bicycle"></i>  <!-- Bicycle icon -->
                    Sub Category</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('bikes.index') }}">Sub Category</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-bicycle"></i>  <!-- Bicycle icon -->
                    Sub Department</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('department.index') }}">Sub Department</a></li>
                    </ul>
                </div>
            </li>
             <li><a href="javascript:void(0)" class="collapsible-header"><i class="fas fa-exchange-alt"></i> <!-- exchange icon -->

                    Warehouse Transfer</a>
                <div class="collapsible-body left-sub-menu">
                    <ul>
                        <li><a href="{{ route('warehouse-transfer.index') }}">All Transfers</a></li>
                         <li><a href="{{ route('warehouse-transfer.create') }}">Add Transfer</a></li>
                    </ul>
                </div>
            </li>
            
            @endif
        </ul>
    </div>
</div>
