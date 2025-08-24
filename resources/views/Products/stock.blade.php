<!DOCTYPE html>
<html lang="en">

@include('includes.header')

<body>
    <!--== MAIN CONTAINER ==-->
    @include('includes.topBar')

    <!--== BODY CONTAINER ==-->
    <div class="container-fluid sb2">
        <div class="row">
            <div class="sb2-1">
                <!-- Side Bar -->
                @include('includes.sidebar')
            </div>

            <div class="sb2-2">
                <div class="sb2-2-2">
                    <ul>
                        <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                        <li class="active-bre"><a href="#">Stock Report</a></li>
                    </ul>
                </div>

                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                

                                <div class="table-responsive">
                                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                        <!-- Warehouse Filter -->
                                        <select id="warehouseSelect">
                                            <option value="">Warehouses</option>
                                            @foreach ($warehouse as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                      

                                        <select id="categorySelect">
                                            <option value="">Department</option>
                                            @foreach ($category as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        
                                        
                                        <!-- Low Stock Filter as Select -->
                                        <select id="lowStockSelect">
                                            <option value="">All Stock</option>
                                            <option value="1">Low Stock Only</option>
                                        </select>
                                    
                                        <!-- Filter Buttons -->
                                        <button onclick="applyFilters()">Filter</button>
                                        <button onclick="resetFilters()">Reset</button>
                                    </div>
                                    
                                    <table class="table table-bordered" id="stockTable">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                              
                                                <th>SKU</th>
                                                <th>Warehouse</th>
                                                <th>Stock</th>
                                                <th>Rack</th>
                                                <th>Supplier</th>
                                                <th>Department</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($grnItem as $stock)
                                            <tr 
                                                data-warehouse="{{ $stock->grn->warehouse->id }}" 
                                                data-category="{{ $stock->product->category_id }}" 
                                                data-low-stock="{{ $stock->product->low_stock }}"
                                            >
                                                <td>{{ $stock->product->name }}</td>
                                                <td>{{ $stock->product->sku }}</td>
                                                <td>{{ $stock->grn->warehouse->name }}</td>
                                                <td>{{ $stock->quantity }}</td>
                                                <td>{{ $stock->product->rackDetail->rack_name }}</td>
                                                <td>{{ $stock->grn->supplier->name }}</td>
                                                <td>{{ $stock->product->department->name }}</td>
                                            </tr>
                                        @endforeach
                                        
                                        </tbody>
                                    </table>
                                </div>
                            </div> <!-- box-inn-sp -->
                        </div> <!-- col-md-12 -->
                    </div> <!-- row -->
                </div> <!-- sb2-2-3 -->
            </div> <!-- sb2-2 -->
        </div> <!-- row -->
    </div> <!-- container-fluid -->

    <style>
        nav a {
            color: #271f1f !important;
        }

        .flex.items-center.justify-between {
            color: #000000;
            background-color: #f9f9f9;
        }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Stock Filter Script -->
    <script>
        function applyFilters() {
            const warehouseId = document.getElementById('warehouseSelect').value;
            const categoryId = document.getElementById('categorySelect').value;
            const lowStockOnly = document.getElementById('lowStockSelect').value;
        
            const rows = document.querySelectorAll('#stockTable tbody tr');
        
            rows.forEach(row => {
                const rowWarehouse = row.getAttribute('data-warehouse');
                const rowCategory = row.getAttribute('data-category');
                const rowLowStock = row.getAttribute('data-low-stock');
        
                let show = true;
        
                if (warehouseId && rowWarehouse !== warehouseId) show = false;
                if (categoryId && rowCategory !== categoryId) show = false;
                if (lowStockOnly === "1" && rowLowStock !== "1") show = false;
        
                row.style.display = show ? '' : 'none';
            });
        }
        
        function resetFilters() {
            document.getElementById('warehouseSelect').value = '';
            document.getElementById('categorySelect').value = '';
            document.getElementById('lowStockSelect').value = '';
            applyFilters();
        }
        </script>
  
    @include('includes.js')
</body>
</html>
