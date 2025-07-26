<!DOCTYPE html>
<html lang="en">

@include('includes.header')

<body onload="toggleDivs()">
    <!--== MAIN CONTRAINER ==-->
    @include('includes.topBar')

    <!--== BODY CONTNAINER ==-->
    <div class="container-fluid sb2">
        <div class="row">
            <div class="sb2-1">
                <!-- //side bar -->
                @include('includes.sidebar')
            </div>
            <div class="sb2-2">
                <div class="sb2-2-2">
                    <ul>
                        <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                        </li>
                        <li class="active-bre"><a href="#">Dashboard</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="sb2-2">
                <div class="row">
                    <!-- Sales Chart -->
                    <div class="col-md-12">
                        <div class="box-inn-sp">
                            <div class="inn-title">
                                <div class="">
                                    <div class="row">
                                        <!-- Each toggle gets col-sm-6 for tablets and col-12 for mobile -->
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
                                            <div class="switch">
                                                <label>
                                                    <input type="checkbox" id="chkOne" onclick="toggleDivs()">
                                                    <span class="lever"></span> Sales Report
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
                                            <div class="switch">
                                                <label>
                                                    <input type="checkbox" id="chkTwo" onclick="toggleDivs()">
                                                    <span class="lever"></span> Product Wise Sale
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
                                            <div class="switch">
                                                <label>
                                                    <input type="checkbox" id="chkThree" onclick="toggleDivs()">
                                                    <span class="lever"></span> Stock Summary
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
                                            <div class="switch">
                                                <label>
                                                    <input type="checkbox" id="chkFour" onclick="toggleDivs()">
                                                    <span class="lever"></span> Profit Or Loss
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2" style="margin-top: 20px;">
                                            <div class="switch">
                                                <label>
                                                    <input type="checkbox" id="chkFive" onclick="toggleDivs()">
                                                    <span class="lever"></span> Supplier Reports
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2" style="margin-top: 20px;">
                                            <div class="switch">
                                                <label>
                                                    <input type="checkbox" id="chkSix" onclick="toggleDivs()">
                                                    <span class="lever"></span> Profit & Loss Report
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2" style="margin-top: 20px;">
                                            <div class="switch">
                                                <label>
                                                    <input type="checkbox" id="chkSeven" onclick="toggleDivs()">
                                                    <span class="lever"></span> Ref Sales X Report
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sb2-2">

                <div class="row" id="onediv" style="display:none;">
                    <!-- Sales Chart -->
                    <div class="col-md-12" style="margin-bottom: 100px !important;">
                        <div class="box-inn-sp">
                            <div class="inn-title" style="text-align: center;">
                                <h5 style="margin: 15px">Salse Report</h5>
                                <div class="container d-flex justify-content-center align-items-center flex-column">
                                    <div class="row">
                                        <!-- Sales Start Date -->
                                        <div class="col-12 col-md-2 mb-3">
                                            <input type="date" id="sales_start_filter" class="form-control"
                                                placeholder="Start Date">
                                        </div>

                                        <!-- Sales End Date -->
                                        <div class="col-12 col-md-2 mb-3">
                                            <input type="date" id="sales_end_filter" class="form-control"
                                                placeholder="End Date">
                                        </div>
                                        <!-- Invoice Status -->
                                        <div class="col-12 col-md-2 mb-4">
                                            <select id="sales_shop_filter" class="form-control">
                                                <option value="">Shop</option>
                                                @foreach ($shops as $shop)
                                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-2 mb-4">
                                            <select id="sales_ref_filter" class="form-control">
                                                <option value="">Ref</option>
                                                @foreach ($refs as $ref)
                                                <option value="{{ $ref->id }}">{{ $ref->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Filter Button -->
                                        <div class="col-12 col-md-2 mb-3">
                                            <button onclick="filterSalesChart()"
                                                class="btn btn-primary w-100">Filter</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered" id="sales_table">
                                      <thead style="color: white; background-color: #343a40;">
                                            <tr>
                                                <th>Invoice No</th>
                                                <th>Invoice Date</th>
                                                <th>Shop</th>
                                                <th>Ref</th>
                                                <th>Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be injected here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>


                        <div class="box-inn-sp">
                            <div class="inn-title">
                                <div class="container d-flex justify-content-center align-items-center flex-column">
                                    <!-- Sales Start Date -->
                                    <!--<div class="row">-->
                                    <!-- Sales Start Date -->
                                    <!--    <div class="col-12 col-md-3 mb-3">-->
                                    <!--        <input type="date" id="sales_start_date" class="form-control">-->
                                    <!--    </div>-->

                                    <!-- Sales End Date -->
                                    <!--    <div class="col-12 col-md-3 mb-3">-->
                                    <!--        <input type="date" id="sales_end_date" class="form-control">-->
                                    <!--    </div>-->

                                    <!-- Sales Filter -->
                                    <!--    <div class="col-12 col-md-3 mb-3">-->
                                    <!--        <select id="sales_filter" class="form-select">-->
                                    <!--            <option value="daily">Daily</option>-->
                                    <!--            <option value="monthly">Monthly</option>-->
                                    <!--        </select>-->
                                    <!--    </div>-->

                                    <!-- Filter Button -->
                                    <!--    <div class="col-12 col-md-3 mb-3">-->
                                    <!--        <button onclick="updateSalesChart()"-->
                                    <!--            class="btn btn-primary w-100">Filter</button>-->
                                    <!--    </div>-->
                                    <!--</div>-->

                                </div>
                                <!-- Sales Chart -->
                                <div id="sales-chart" style="width:100%; height:400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="twodiv" style="display:none;">
                    <!-- Sales Chart -->
                    <div class="col-md-12" style="margin-bottom: 100px !important;">
                        <div class="box-inn-sp">
                        <div class="">
    <h4 style="margin: 20px 0;text-align:center">Product Wise Report</h4>
 <div class="container">
    <div class="row justify-content-center mb-4">
        <!-- Filters -->
        <div class="col-12 col-md-2 mb-3">
            <input type="date" id="product_start_date" class="form-control" placeholder="Start Date">
        </div>

        <div class="col-12 col-md-2 mb-3">
            <input type="date" id="product_end_date" class="form-control" placeholder="End Date">
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
            <select id="product_shop_filter" class="form-control">
                <option value="">Shop</option>
                @foreach ($shops as $shop)
                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
            <select id="product_ref_filter" class="form-control">
                <option value="">Ref</option>
                @foreach ($refs as $ref)
                    <option value="{{ $ref->id }}">{{ $ref->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
            <select id="product_product_filter" class="form-control">
                <option value="">Product</option>
                @foreach ($products as $product)
                    @php
                        $price = $product->grnItems->first()->set_price ?? '0.00';
                    @endphp
                    <option value="{{ $product->id }}">
                        {{ $product->name }} - Rs. {{ number_format($price, 2) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
            <select id="product_bike_filter" class="form-control">
                <option value="">Bike</option>
                @foreach ($bikes as $bike)
                    <option value="{{ $bike->id }}">{{ $bike->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
            <select id="product_parts_filter" class="form-control">
                <option value="">Parts</option>
                @foreach ($categorys as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
            <select id="product_brand_filter" class="form-control">
                <option value="">Brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
            <button onclick="updateProductSalesChart2()" class="btn btn-primary w-100">
                Filter
            </button>
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
          <button onclick="exportTableToExcel('product_wise_table')" class="btn w-100" style="background-color: #217346; color: white;">
    Excel
</button>

        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive mt-4">
     <table class="table table-bordered" id="product_wise_table">
      <thead style="background-color: red; color: white;">
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Amount</th>
            <th class="d-none d-md-table-cell">Shop Name</th>
            <th class="d-none d-md-table-cell">Ref Name</th>
            <th>Invoice Date</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center">No data available</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th class="text-end">Total:</th>
            <th id="sum_qty">0</th>
            <th id="sum_total">0.00</th>
            <th colspan="3"></th>
        </tr>
    </tfoot>
</table>


            </div>
        </div>
    </div>
</div>

<!-- Sticky Table Header CSS -->
<style>
    thead th {
        position: sticky;
        top: 0;
        background-color: #343a40;
        z-index: 1;
    }
    .mbi{
    margin-bottom: 20px;
    }
</style>

<script>
       function updateProductSalesChart() {
                    let startDate = $('#product_start_date').val();
                     let endDate = $('#product_end_date').val();
                   
                    $.ajax({
                        url: "{{ route('product.sales.report') }}",
                        type: "GET",
                        data: { start_date: startDate, end_date: endDate },
                        success: function (response) {
                            let categories = response.map(item => item.product_name);
                            let values = response.map(item => parseFloat(item.total_sold));

                            Highcharts.chart('product-sales-chart', {
                                chart: { type: 'bar' },
                                title: { text: 'Product-wise Sales (selected date range all)' },
                                xAxis: { categories: categories },
                                yAxis: { title: { text: 'Units Sold' } },
                                series: [{ name: 'Total Sold', data: values }]
                            });
                        }
                    });
                }
function updateProductSalesChart2() {
     updateProductSalesChart(); // ✅ Now it will work without error
    let formData = {
        start_date: $('#product_start_date').val(),
        end_date: $('#product_end_date').val(),
        shop_id: $('#product_shop_filter').val(),
        ref_id: $('#product_ref_filter').val(),
        product_id: $('#product_product_filter').val(),
        bike_id: $('#product_bike_filter').val(),
        part_id: $('#product_parts_filter').val(),
        brand_id: $('#product_brand_filter').val(),
    };

    $.ajax({
        url: "{{ route('filter.product.sales') }}",
        method: 'GET',
        data: formData,
        success: function(response) {
            let tbodyHtml = '';
            let totalQty = 0;
            let totalAmount = 0;

           if (response.length > 0) {
    response.forEach(function(item) {
        let cleanAmount = parseFloat(item.total_amount.replace(/,/g, ''));
        let cleanQty = parseFloat(item.total_qty);

        tbodyHtml += `
            <tr>
                <td>${item.product_name}</td>
                <td>${item.total_qty}</td>
                <td>Rs. ${cleanAmount.toFixed(2)}</td>
                <td class="d-none d-md-table-cell">${item.shop_name}</td>
                <td class="d-none d-md-table-cell">${item.ref_name}</td>
                <td>${item.invoice_date}</td>
            </tr>
        `;
        totalQty += cleanQty;
        totalAmount += cleanAmount;
    });
} else {
    tbodyHtml = `
        <tr>
            <td colspan="6" class="text-center">No data found</td>
        </tr>
    `;
}

$('#product_wise_table tbody').html(tbodyHtml);
$('#sum_qty').text(totalQty);
$('#sum_total').text('Rs. ' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2}));

        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

</script>


<!-- Export to Excel Script -->
<script>
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    filename = filename ? filename + '.xls' : 'product_wise_report.xls';
    
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
        navigator.msSaveOrOpenBlob( blob, filename);
    } else {
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}
</script>

</div>

                        <div class="box-inn-sp">
                            <div class="inn-title">
                                <div class="container d-flex justify-content-center align-items-center flex-column">
                                    <!-- Sales Start Date -->
                                    <!--<div class="row">-->
                                    <!-- Sales Start Date -->
                                    <!--    <div class="col-12 col-md-4 mb-4">-->
                                    <!--        <input type="date" id="product_start_date">-->
                                    <!--    </div>-->

                                    <!-- Sales End Date -->
                                    <!--    <div class="col-12 col-md-4 mb-4">-->
                                    <!--        <input type="date" id="product_end_date">-->
                                    <!--    </div>-->


                                    <!-- Filter Button -->
                                    <!--    <div class="col-12 col-md-4 mb-4">-->
                                    <!--        <button onclick="updateProductSalesChart()"-->
                                    <!--            class="btn btn-primary w-100">Filter</button>-->
                                    <!--    </div>-->
                                    <!--</div>-->

                                </div>
                                <!-- Sales Chart -->
                                <div id="product-sales-chart" style="width:100%; height:350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

               
            </div>

 <div class="row" id="threediv" style="display:none;">
                    <!-- Sales Chart -->
                    <div class="col-md-12" style="margin-bottom: 100px !important;">
                        <div class="box-inn-sp">
                            <div class="inn-title">
                                    <h4 style="margin: 20px 0;text-align:center">Stock Report</h4>
 <div class="container">
    <div class="row justify-content-center mb-4">
        <!-- Filters -->
        <div class="col-12 col-md-2 mb-3">
            <input type="date" id="stock_start_date" class="form-control" placeholder="Start Date">
        </div>

        <div class="col-12 col-md-2 mb-3">
            <input type="date" id="stock_end_date" class="form-control" placeholder="End Date">
        </div>
        <div class="col-12 col-md-2 mb-3 mbi">
            <select id="stock_product_filter" class="form-control">
                <option value="">Product</option>
                @foreach ($products as $product)
                    @php
                        $price = $product->grnItems->first()->set_price ?? '0.00';
                    @endphp
                    <option value="{{ $product->id }}">
                        {{ $product->name }} - Rs. {{ number_format($price, 2) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
            <select id="stock_bike_filter" class="form-control">
                <option value="">Bike</option>
                @foreach ($bikes as $bike)
                    <option value="{{ $bike->id }}">{{ $bike->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
            <select id="stock_parts_filter" class="form-control">
                <option value="">Parts</option>
                @foreach ($categorys as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
            <select id="stock_brand_filter" class="form-control">
                <option value="">Brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
            <button onclick="updatestock()" class="btn btn-primary w-100">
                Filter
            </button>
        </div>

        <div class="col-12 col-md-2 mb-3 mbi">
          <button onclick="exportTableToExcel('stock_wise_table')" class="btn w-100" style="background-color: #217346; color: white;">
    Excel
</button>

        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive mt-4">
     <table class="table table-bordered" id="stock_wise_table">
      <thead style="background-color: red; color: white;">
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Amount</th>
            <th class="d-none d-md-table-cell">Shop Name</th>
            <th class="d-none d-md-table-cell">Ref Name</th>
            <th>Invoice Date</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center">No data available</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th class="text-end">Total:</th>
            <th id="sum_qty">0</th>
            <th id="sum_total">0.00</th>
            <th colspan="3"></th>
        </tr>
    </tfoot>
</table>


            </div>
        </div>
    </div>
</div>

                                <!-- stock Chart -->
                                <div id="stock-chart" style="width:100%; height:400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="fourdiv" style="display:none;">
                    <!-- Sales Chart -->
                    <div class="col-md-12">
                        <div class="box-inn-sp" style="text-align:center">
                            <h4 style="padding: 24px;">Profit and Loss Report</h4>
                            <div class="inn-title">
                                <div class="container d-flex justify-content-center align-items-center flex-column">
                                    <!-- Sales Start Date -->
                                    <div class="row">
                                        <!-- Sales Start Date -->
                                        <div class="col-12 col-md-3 mb-4">
                                            <input type="date" id="start_date">
                                        </div>

                                        <!-- Sales End Date -->
                                        <div class="col-12 col-md-3 mb-4">
                                            <input type="date" id="end_date">
                                        </div>

                                        <div class="col-12 col-md-3 mb-4">
                                            <select id="interval_selector">
                                                <option value="day">Day Wise</option>
                                                <option value="month">Month Wise</option>
                                            </select>
                                        </div>

                                        <!-- Filter Button -->
                                        <div class="col-12 col-md-3 mb-4">
                                            <button onclick="updateProfitChart()"
                                                class="btn btn-primary w-100">Filter</button>
                                        </div>
                                    </div>

                                </div>
                                <!-- profit/loss Chart -->

                                <div id="profit-chart" style="height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="sixdiv" style="display:none;">
                    <!-- Sales Chart -->
                    <div class="col-md-12">
                        <div class="box-inn-sp" style="text-align:center">
                            <h4 style="padding: 24px;">Profit and Loss Report</h4>
                            <div class="inn-title">
                                <div class="container d-flex justify-content-center align-items-center flex-column">
                                    <div class="row">
                                        <!-- Start Date -->
                                        <div class="col-12 col-md-2 mb-4">
                                            <input type="date" id="profit_start_date" class="form-control">
                                        </div>

                                        <!-- End Date -->
                                        <div class="col-12 col-md-2 mb-4">
                                            <input type="date" id="profit_end_date" class="form-control">
                                        </div>

                                        <!-- Invoice Status -->
                                        <div class="col-12 col-md-2 mb-4">
                                            <select id="invoice_status" class="form-control">
                                                <option value="">All Status</option>
                                                <option value="approved">Approved</option>
                                                <option value="pending">Pending</option>
                                            </select>
                                        </div>

                                        <!-- Paid Status -->
                                        <div class="col-12 col-md-2 mb-4">
                                            <select id="paid_status" class="form-control">
                                                <option value="">All Paid</option>
                                                <option value="paid">Paid</option>
                                                <option value="partial">Partial</option>
                                                <option value="unpaid">Unpaid</option>
                                            </select>
                                        </div>

                                        <!-- Filter Button -->
                                        <div class="col-12 col-md-2 mb-4">
                                            <button onclick="lossOrProfit()"
                                                class="btn btn-primary w-100">Filter</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- profit/loss Chart -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">

                                        <tbody id="profitTableBody">
                                            <!-- Data will be loaded here via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="fivediv" style="display:none;">

                    <!-- Sales Chart -->
                    <div class="col-md-12" style="margin-bottom: 100px !important;">
                        <div class="box-inn-sp" style="text-align:center">
                            <h4 style="padding: 24px;">Supplier Reports</h4>
                            <div class="inn-title">

                                <div>
                                    <!-- Sales Start Date -->
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <select id="profit" class="form-select">
                                                    <option value="">Select Suppliers</option>
                                                    @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <input type="date" id="profit_start_date" class="form-control">
                                            </div>

                                            <div class="col-md-3">
                                                <input type="date" id="profit_end_date" class="form-control">
                                            </div>

                                            <div class="col-md-2 d-flex align-items-end">
                                                <button id="profitfilterBtn"
                                                    class="btn btn-primary w-100">Filter</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>GRN Number</th>
                                                        <th>Supplier</th>
                                                        <th>Received Date</th>
                                                        <th>Total Amount</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="grnTableBody">
                                                    <!-- Data will be loaded here via AJAX -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="sevendiv" style="display:none;">
                    <!-- Sales Chart -->
                    <div class="col-md-12">
                        <div class="box-inn-sp" style="text-align:center">
                            <h4 style="padding: 24px;">Ref Sales X Report</h4>
                            <div class="inn-title">
                                <div class="container d-flex justify-content-center align-items-center flex-column">
                                    <div class="row">
                                        <!-- Start Date -->
                                        <div class="col-12 col-md-2 mb-4">
                                            <input type="date" id="profit_start_date" class="form-control">
                                        </div>

                                        <!-- End Date -->
                                        <div class="col-12 col-md-2 mb-4">
                                            <input type="date" id="profit_end_date" class="form-control">
                                        </div>

                                        <!-- Invoice Status -->
                                        <div class="col-12 col-md-2 mb-4">
                                            <select id="invoice_status" class="form-control">
                                                <option value="">All Status</option>
                                                <option value="approved">Approved</option>
                                                <option value="pending">Pending</option>
                                            </select>
                                        </div>

                                        <!-- Paid Status -->
                                        <div class="col-12 col-md-2 mb-4">
                                            <select id="paid_status" class="form-control">
                                                <option value="">All Paid</option>
                                                <option value="paid">Paid</option>
                                                <option value="partial">Partial</option>
                                                <option value="unpaid">Unpaid</option>
                                            </select>
                                        </div>

                                        <!-- Filter Button -->
                                        <div class="col-12 col-md-2 mb-4">
                                            <button onclick="lossOrProfit()"
                                                class="btn btn-primary w-100">Filter</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- profit/loss Chart -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">

                                        <tbody id="profitTableBody">
                                            <!-- Data will be loaded here via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://code.highcharts.com/highcharts.js"></script>


            <script>
                function lossOrProfit() {
                    const startDate = document.getElementById('profit_start_date').value;
                    const endDate = document.getElementById('profit_end_date').value;
                    const invoiceStatus = document.getElementById('invoice_status').value;
                    const paidStatus = document.getElementById('paid_status').value;

                    const url = new URL('/profit-loss', window.location.origin);

                    if (startDate) url.searchParams.append('start_date', startDate);
                    if (endDate) url.searchParams.append('end_date', endDate);
                    if (invoiceStatus) url.searchParams.append('invoice_status', invoiceStatus);
                    if (paidStatus) url.searchParams.append('paid_status', paidStatus);

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            const tbody = document.getElementById('profitTableBody');
                            tbody.innerHTML = `
                <tr>
                    <th>Sales Revenue</th>
                    <td>${data.salesRevenue}</td>
                </tr>
                <tr>
                    <th>COGS</th>
                    <td>${data.cogs}</td>
                </tr>
                <tr>
                    <th>Gross Profit</th>
                    <td>${data.grossProfit}</td>
                </tr>
                <tr>
                    <th>Expenses</th>
                    <td>${data.expenses}</td>
                </tr>
                <tr>
                    <th>Net Profit</th>
                    <td>${data.netProfit}</td>
                </tr>
                <tr>
                    <th>Outstanding Invoices</th>
                    <td>${data.outstandingInvoices}</td>
                </tr>
            `;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert("Something went wrong!");
                        });
                }

                $(document).ready(function () {
                    $('#filterBtn').click(function () {
                        let supplier_id = $('#supplier').val();
                        let suppliers_start_date = $('#suppliers_start_date').val();
                        let suppliers_end_date = $('#suppliers_end_date').val();

                        $.ajax({
                            url: "{{ route('report.filter') }}",
                            type: "GET",
                            data: {
                                supplier_id: supplier_id,
                                start_date: suppliers_start_date,
                                end_date: suppliers_end_date
                            },
                            success: function (response) {
                                let rows = '';
                                response.forEach(function (grn) {
                                    rows += `
                            <tr>
                                <td>${grn.grn_number}</td>
                                <td>${grn.supplier_name}</td>
                                <td>${grn.received_date}</td>
                                <td>${grn.total_amount}</td>
                                <td>${grn.remarks}</td>
                            </tr>`;
                                });
                                $('#grnTableBody').html(rows);
                            }
                        });
                    });
                });

                function updateProfitChart() {
                    let startDate = document.getElementById("start_date").value;
                    let endDate = document.getElementById("end_date").value;
                    let interval = document.getElementById("interval_selector").value;

                    // Fetch data from the backend with selected date range and interval (day/month)
                    fetch(`/get-profit-report?start_date=${startDate}&end_date=${endDate}&interval=${interval}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log(data); // Log the whole data to check its structure

                            let incomeValues = data.income_values.map(val => parseFloat(val) || 0);
                            let purchaseValues = data.purchase_values.map(val => parseFloat(val) || 0);
                            let profitValues = data.profit_values.map(val => parseFloat(val) || 0);

                            console.log(incomeValues, purchaseValues, profitValues); // Log the arrays to check values

                            // Create or update the Highcharts chart
                            Highcharts.chart('profit-chart', {
                                chart: {
                                    type: 'column' // Column chart to represent the data
                                },
                                title: {
                                    text: 'Profit and Loss Report', // Set the chart title
                                    style: {
                                        fontSize: '18px', // Add a font size
                                        color: '#333', // Set a color for the title text
                                        fontWeight: 'bold' // Make the title bold for better visibility
                                    }
                                },
                                yAxis: {
                                    title: {
                                        text: 'profit or loss'
                                    },
                                    min: 0 // Ensure the Y-axis starts from 0
                                },
                                series: [{
                                    name: 'Total Income',
                                    data: incomeValues // Data for Total Income
                                }, {
                                    name: 'Total Purchase',
                                    data: purchaseValues // Data for Total Purchase
                                }, {
                                    name: 'Profit',
                                    data: profitValues // Data for Profit
                                }]
                            });
                        })
                        .catch(error => console.error('Error loading profit report:', error));
                }

                // Event listener for filter button click
                document.getElementById('filter_button').addEventListener('click', function () {
                    updateProfitChart(); // Update the chart when the filter button is clicked
                });


            </script>

            <script>

                function updateStockChart() {
                    fetch(`/stock-summary`)
                        .then(response => response.json())
                        .then(data => {
                            // Ensure Y-axis values are numbers
                            let numericValues = data.values.map(value => Number(value));

                            Highcharts.chart('stock-chart', {
                                chart: { type: 'column' },
                                title: { text: 'Stock Summary Report' },
                                xAxis: { categories: data.categories },
                                yAxis: { title: { text: 'Stock Quantity' } },
                                series: [{ name: 'Available Stock', data: numericValues }]
                            });
                        })
                        .catch(error => console.error('Error loading stock summary:', error));

                }

                function updateSalesChart() {
                    let startDate = $('#sales_start_filter').val();
                    let endDate = $('#sales_end_filter').val();
                    let filter = $('#sales_filter').val();

                    $.ajax({
                        url: "{{ route('sales.report') }}",
                        type: "GET",
                        data: { start_date: startDate, end_date: endDate, filter: filter },
                        success: function (response) {
                            let categories = response.map(item => item.period);
                            let values = response.map(item => parseFloat(item.total_sales));

                            Highcharts.chart('sales-chart', {
                                chart: { type: 'line' },
                                title: { text: 'Sales Report  (selected date range all sales)' },
                                xAxis: { categories: categories },
                                yAxis: { title: { text: 'Total Sales (LKR)' } },
                                series: [{ name: 'Total Sales', data: values }]
                            });
                        }
                    });
                }

         

                // Load charts initially
                updateSalesChart();
                updateProductSalesChart();
                updateStockChart();

            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    let divOne = document.getElementById("onediv");
                    let divTwo = document.getElementById("twodiv");
                    let divThree = document.getElementById("threediv");
                    let divFour = document.getElementById("fourdiv");
                    let divFive = document.getElementById("fivediv");
                    let divSix = document.getElementById("sixdiv");
                    let divSeven = document.getElementById("sevendiv");

                    if (divOne && divTwo && divThree && divFour && divFive && divSix) {
                        divOne.style.display = document.getElementById("chkOne").checked ? "block" : "none";
                        divTwo.style.display = document.getElementById("chkTwo").checked ? "block" : "none";
                        divThree.style.display = document.getElementById("chkThree").checked ? "block" : "none";
                        divFour.style.display = document.getElementById("chkFour").checked ? "block" : "none";
                        divFive.style.display = document.getElementById("chkFive").checked ? "block" : "none";
                        divSix.style.display = document.getElementById("chkSix").checked ? "block" : "none";
                        divSeven.style.display = document.getElementById("chkSeven").checked ? "block" : "none";
                    } else {
                        console.error('One or more divs are missing!');
                    }

                    // Attach event listeners
                    document.getElementById("chkOne").addEventListener("click", toggleDivs);
                    document.getElementById("chkTwo").addEventListener("click", toggleDivs);
                    document.getElementById("chkThree").addEventListener("click", toggleDivs);
                    document.getElementById("chkFour").addEventListener("click", toggleDivs);
                    document.getElementById("chkFive").addEventListener("click", toggleDivs);
                    document.getElementById("chkSix").addEventListener("click", toggleDivs);
                    document.getElementById("chkSeven").addEventListener("click", toggleDivs);
                    // Call toggleDivs on page load-
                    toggleDivs();
                });

                function toggleDivs() {
                    let divOne = document.getElementById("onediv");
                    let divTwo = document.getElementById("twodiv");
                    let divThree = document.getElementById("threediv");
                    let divFour = document.getElementById("fourdiv");
                    let divFive = document.getElementById("fivediv");
                    let divSix = document.getElementById("sixdiv");
                    let divSeven = document.getElementById("sevendiv");

                    divOne.style.display = document.getElementById("chkOne").checked ? "block" : "none";
                    divTwo.style.display = document.getElementById("chkTwo").checked ? "block" : "none";
                    divThree.style.display = document.getElementById("chkThree").checked ? "block" : "none";
                    divFour.style.display = document.getElementById("chkFour").checked ? "block" : "none";
                    divFive.style.display = document.getElementById("chkFive").checked ? "block" : "none";
                    divSix.style.display = document.getElementById("chkSix").checked ? "block" : "none";
                    divSeven.style.display = document.getElementById("chkSeven").checked ? "block" : "none";
                }
            </script>

            <script>
                function filterSalesChart() {
                    updateSalesChart();
                    var startDate = $('#sales_start_filter').val();
                    var endDate = $('#sales_end_filter').val();
                    var shopId = $('#sales_shop_filter').val();
                    var refId = $('#sales_ref_filter').val();

                    $.ajax({
                        url: "{{ route('sales.filter') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            start_date: startDate,
                            end_date: endDate,
                            shop_id: shopId,
                            ref_id: refId
                        },
                        success: function (response) {
                            var tbody = $('#sales_table tbody');
                            tbody.empty(); // clear old data first
                            var totalAmountSum = 0;
                            if (response.length > 0) {
                                $.each(response, function (index, sale) {
                                    var row = `
                        <tr>
                            <td>${sale.invoice_no ?? ''}</td>
                            <td>${sale.invoice_date ?? ''}</td>
                            <td>${sale.shop_name ?? ''}</td>
                            <td>${sale.ref_name ?? ''}</td>
                            <td>${parseFloat(sale.total_amount).toFixed(2)}</td>
                        </tr>
                    `;
                                    tbody.append(row);
                                     totalAmountSum += parseFloat(sale.total_amount); 
                                });
                                
                                 // Add a row for the sum below the data rows
                    var sumRow = `
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total Sum</strong></td>
                            <td><strong>${totalAmountSum.toFixed(2)}</strong></td>
                        </tr>
                    `;
                    tbody.append(sumRow);
                            } else {
                                tbody.append(`<tr><td colspan="5" class="text-center">No sales found!</td></tr>`);
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                            alert('Something went wrong!');
                        }
                    });
                }

            </script>

            <style>
                /* Custom toggle switch styling */
                .switch {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    font-size: 18px;
                }

                .switch input {
                    display: none;
                }

                .switch .lever {
                    width: 50px;
                    height: 25px;
                    background: #ccc;
                    border-radius: 50px;
                    position: relative;
                    cursor: pointer;
                    transition: 0.3s;
                }

                .switch .lever::before {
                    content: "";
                    width: 20px;
                    height: 20px;
                    background: white;
                    border-radius: 50%;
                    position: absolute;
                    top: 2.5px;
                    left: 3px;
                    transition: 0.3s;
                }

                .switch input:checked+.lever {
                    background: #28a745;
                    /* Green when checked */
                }

                .switch input:checked+.lever::before {
                    left: 27px;
                }
            </style>
            <!-- JavaScript -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="{{ asset('Public/js/materialize.min.js') }}"></script>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var sidebar = document.getElementById('sidebarid');

                    // Example: Slide in the sidebar when the page loads
                    sidebar.style.transform = 'translateX(-100%)';
                    sidebar.style.transition = 'transform 0.5s ease-in-out';
                    setTimeout(() => {
                        sidebar.style.transform = 'translateX(0)';
                    }, 100);
                });
            </script>
            @include('includes.js2')
</body>

</html>