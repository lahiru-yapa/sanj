<!DOCTYPE html>
<html lang="en">

@include('includes.header')

<body>
    <!--== MAIN CONTRAINER ==-->
    @include('includes.topBar')

    <!--== BODY CONTNAINER ==-->
    <div class="container-fluid sb2">
        <div class="row">
            <div class="sb2-1">
                @include('includes.sidebar')
            </div>
            @if (session('returnerror'))
            <div class="alert alert-danger" style="text-align: center">
                {{ session('returnerror') }}
            </div>
            @endif

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <div class="sb2-2">
                <div class="sb2-2-2">
                    <ul>
                        <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                        </li>
                        <li class="active-bre"><a href="#"> "Use this invoice edit only to remove or change quantity. If you need to add items to the shop, please create a new invoice."</a>
                        </li>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title">
                                    <h4>Edit Invoice</h4>
                                  <button type="button" class="btn btn-light w-100" onclick="window.location='{{ route('invoice.index') }}'" style="margin-left:10px">
                                    All Invoice
                                </button>
                                </div>
                              

                                <div class="tab-inn">
                                 <form action="{{ route('admin.invoice.updateInvoice', $invoices->id) }}" method="POST">
    @csrf
    
    <div class="row">
        <div class="col-12 col-md-4 col-lg-3">
            <label for="shop-select">Select Shop</label>
           <select class="form-control" name="shop_id" id="shop-select" disabled>
    @foreach($shops as $item)
        <option value="{{ $item->id }}" {{ $invoices->shop_id == $item->id ? 'selected' : '' }}>
            {{ $item->name }}
        </option>
    @endforeach
</select>

            <div id="credit-limit-container" style="display: none;">
                <input type="text" id="credit-limit" class="form-control" readonly>
            </div>
        </div>

       <div class="col-12 col-md-4 col-lg-3">
    <label for="warehouse">Select Warehouse</label>
   <select class="form-control" name="warehouse" id="warehouse" >
    <option value="" disabled>-</option>
    @foreach($warehouse as $item)
        <option value="{{ $item->id }}" 
            {{ $item->id == $invoices->warehouse_id ? 'selected' : '' }}>
            {{ $item->name }}
        </option>
    @endforeach
</select>

<!-- Hidden input to ensure the selected value is submitted -->
<input type="hidden" name="warehouse" value="{{ $invoices->warehouse_id }}">
<input type="hidden" name="shop_id" value="{{ $invoices->shop_id }}">
</div>
        <div class="col-12 col-md-4 col-lg-3">
            <label for="product_name2">Select Product</label>
            <select class="form-control" name="product_name2" id="product_name2">
                <option value="" disabled selected>-</option>
            </select>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
        <p>Current Credit: {{ $invoices->shop->current_credit }} + Current Balance: {{ abs($invoices->shop->current_balance) }}</p>

           <p style="color:green">Current Balance: {{ $invoices->shop->current_credit + abs($invoices->shop->current_balance) }}</p>
        </div>
       
    </div>

    <input type="hidden" id="totalAmountInput" name="totalAmount" value="0.00">
    
    <div class="row mt-3">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table" id="product-details-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Stock</th>
                            <th>Count</th>
                             <th>Discount</th>
                              <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="selected-products-body">
                        @foreach($invoices->invoiceProducts as $invoiceProduct)
                       
                      @php
            $product = $invoiceProduct->product;
            $warehouse = $invoices->warehouse;
            $stock = $product->warehouses->firstWhere('id', $warehouse->id)?->pivot->stock ?? 0;
            $latestGrnItem = $product->grnItems->sortByDesc('created_at')->first();
        @endphp
                            <tr>
                      
                                <td><img src="{{ asset('storage/' . $invoiceProduct->product->photo) }}" alt="Product Image" style="width: 100px;"></td>
                                <td>{{ $invoiceProduct->product->name }}</td>
                          <td data-amount="{{ $invoiceProduct->price ?? 0 }}">
    {{ $invoiceProduct->price ?? 'N/A' }}
</td>
                                <td>{{ $stock }}</td>
                                <td>
                                    <input type="number" name="counts[{{ $invoiceProduct->product->id }}]" class="form-control count-input" value="{{ old('counts.' . $invoiceProduct->product->id, $invoiceProduct->quantity) }}"
>
                                </td>
<td>
    <input type="number" name="discount[{{ $invoiceProduct->product->id }}]" class="form-control discount-input" value="{{ old('discount.' . $invoiceProduct->product->id, $invoiceProduct->discount) }}">
</td>
<td>
    <input type="number" step="0.01" name="final_price[{{ $invoiceProduct->product->id }}]" 
           class="form-control discounted-amount" 
           value="{{ old('discount.' . $invoiceProduct->product->id, $invoiceProduct->final_price) }}">
</td>



                                <td><button type="button" class="btn btn-danger remove-product-btn">Remove</button></td>
                                  <!-- Hidden input for product ID -->
            <input type="hidden" name="product_ids[]" value="{{ $invoiceProduct->product->id }}">
       
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                <strong>Total Amount: $<span id="total-amount">0.00</span></strong>
            </div>
        </div>
    </div>

    <input type="hidden" id="selected-products" name="selected_products">
<div class="row">
            <div class="col-12 col-md-4 col-lg-3">
    <label for="discount_percentage" class="form-label">Discount Percentage:</label>
    <input type="number" class="form-control" name="discount_percentage" id="discount_percentage" placeholder="Enter discount percentage" 
           value="{{ old('discount_percentage', $invoices->discount) }}">
</div>

    </div>
    <div class="row mt-3">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-lg">Submit</button>
        </div>
    </div>
</form>


                                    <!-- Add the responsive styles -->
                                    <style>
                                    .input-field {
                                        margin-bottom: 20px;
                                    }

                                    .input-field select,
                                    .input-field input {
                                        width: 100%;
                                        padding: 10px;
                                        font-size: 14px;
                                    }

                                    table {
                                        width: 100%;
                                        margin-top: 20px;
                                        border-collapse: collapse;
                                    }

                                    table th,
                                    table td {
                                        padding: 10px;
                                        text-align: left;
                                    }

                                    table th {
                                        background-color: #f1f1f1;
                                    }

                                    

                                    @media (max-width: 768px) {
                                        .input-field.col.s12.m4.l3 {
                                            width: 100%;
                                            margin-bottom: 15px;
                                        }

                                        table th,
                                        table td {
                                            font-size: 12px;
                                            padding: 8px;
                                        }

                                        .input-field.col.s12 {
                                            text-align: center;
                                        }
                                    }

                                    /* Container for table to handle horizontal scrolling */
                                    .table-container {
                                        overflow-x: auto;
                                        /* Enable horizontal scrolling */
                                        -webkit-overflow-scrolling: touch;
                                        /* For smoother scrolling on mobile devices */
                                        margin-top: 20px;
                                    }

                                    /* Table Styles */
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        table-layout: auto;
                                        /* Let content dictate the table width */
                                    }

                                    table th,
                                    table td {
                                        padding: 10px;
                                        text-align: left;
                                        word-wrap: break-word;
                                        /* Prevent text from overflowing */
                                    }

                                    /* Header styles */
                                    table th {
                                        background-color: #f1f1f1;
                                    }

                                    /* Responsive adjustments for small screens */
                                    @media (max-width: 768px) {

                                        table th,
                                        table td {
                                            font-size: 12px;
                                            /* Smaller text size for mobile */
                                            padding: 8px;
                                        }

                                        .input-field.col.s12 {
                                            text-align: center;
                                        }

                                        /* Make the table row content more compact on small screens */
                                        .table-container {
                                            margin-top: 10px;
                                            /* Adjust spacing */
                                        }
                                    }

                                    /* Mobile view (default) - Full width */
                                    @media (max-width: 768px) {
                                        .input-field.col.s12 {
                                            width: 100%;
                                        }
                                    }

                                    /* Desktop view - 1/3 width */
                                    @media (min-width: 769px) {
                                        .input-field.col.s4 {
                                            width: 33.33%;
                                        }
                                    }

                                    .select-dropdown {
                                        padding-top: 20px !important;
                                    }
                                    </style>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--== BOTTOM FLOAT ICON ==-->
    <!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('Public/js/materialize.min.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var sidebar = document.getElementById('sidebarid');
        
        // Example: Slide in the sidebar when the page loads
        sidebar.style.transform = 'translateX(-100%)';
        sidebar.style.transition = 'transform 0.5s ease-in-out';
        setTimeout(() => {
            sidebar.style.transform = 'translateX(0)';
        }, 100);
    });
</script>


   <script>
 $(document).ready(function () {
  $('#warehouse, #brand').change(function () {
    let warehouseId = $('#warehouse').val();  
    let brandId = $('#brand').val() || ''; // Handle undefined


    if (warehouseId) {
        let url = "{{ url('get-products-by-warehouse') }}/" + warehouseId;

        if (brandId) {
            url += "/" + brandId;
        }

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            success: function (response) {
                console.log("products", response.data);

                var productSelect = $('#product_name2');
                productSelect.empty();
                productSelect.append('<option value="" disabled selected>Select product</option>');

              $.each(response.data, function (index, item) {
    productSelect.append(
        '<option value="' + item.id + '" data-price="' + item.grn_price + '" data-grn-item-id="' + item.grn_item_id + '">' +
        item.name + ' (' + item.stock + ') (' + item.category_name + ') - Rs. ' + item.grn_price +
        '</option>'
    );
});

                productSelect.prop('selectedIndex', 0);
                productSelect.trigger('change');
            },
            error: function () {
                console.log("Error fetching products.");
            }
        });
    }
});
});

   $(document).ready(function() {
       

        // Handle when user selects a product
        $('#product_name2').on('change', function() {
            var selectedProductName = $(this).val(); // Get the selected product name
            var selectedWarehouse = $('#warehouse').val(); // Get the selected warehouse ID
            let grnItemId = $(this).find(':selected').data('grn-item-id');

            if (!selectedWarehouse) {
                alert("Please select a warehouse first.");
                return;
            }
            // Fetch product details via AJAX
            $.ajax({
                url: "{{ route('products.details') }}", // Your route to fetch product details
                method: 'GET',
                data: {
                    product_name: selectedProductName,
                    wherehouse: selectedWarehouse,
                      grn_item_id: grnItemId,
                },
                success: function(response) {

                    if (response.image && response.amount) {
                        // Show the product details table
                        $('#product-details-table').fadeIn();

                        // Add the selected product to the table
                        var productRow = `
                       <tr data-product-id="${response.id}">
                <td><img src="${response.image}" alt="Product Image" style="width: 100px;"></td>
             
                         <td>${response.name}</td>
                            <td>${response.amount}</td>
                                <td>${response.stock}</td>
                                 <td>
                    <input type="number" name="counts[${response.id}]" class="form-control count-input" 
                           value="1" min="1" max="${Math.max(0, response.stock - 2)}"/>
                </td>
                    <td>
                    <input type="number" name="discount[${response.id}]" class="form-control discount-input" 
                           value="0" min="0" max="100"/>
                </td>
               <td>
    <input type="number" step="0.01" name="final_price[${response.id}]" class="form-control discounted-amount" value="0.00">
</td>

                      
                          <td>
                            <button type="button" class="btn btn-primary remove-product-btn">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </td>

                        </tr>
                    `;
               
                        $('#selected-products-body').append(productRow);

                        // Add product to the hidden input array
                        var selectedProducts = $('#selected-products').val() ? JSON.parse($(
                            '#selected-products').val()) : [];
                        selectedProducts.push({
                            id: response.id,
                            name: response.name,
                            amount: response.amount,
                            image: response.image,
                            warehouse_id: selectedWarehouse,
                            count: 1 // Default count value
                        });
                        $('#selected-products').val(JSON.stringify(selectedProducts));
                    }
                }
            });
        });


// Update selectedProducts when count input changes
$(document).on('input', `.count-input`, function() {
    var productId = $(this).closest('tr').data('product-id');
    var newCount = $(this).val();

    var selectedProducts = $('#selected-products').val() ? JSON.parse($('#selected-products').val()) : [];

    // Update count in the selectedProducts array
    selectedProducts = selectedProducts.map(product => {
        if (product.id == productId) {
            product.count = newCount;
        }
        return product;
    });

    $('#selected-products').val(JSON.stringify(selectedProducts));
});


        // Remove a product from the table when the remove button is clicked
        $('#selected-products-body,#selected-products-body2').on('click', '.remove-product-btn', function() {
            $(this).closest('tr').remove(); // Remove product row

            // Update the hidden input with the remaining selected products
            var selectedProducts = [];
            var selectedProducts2 = [];
            $('#selected-products-body tr').each(function() {
                var productName = $(this).find('td').eq(1).text();
                var productAmount = $(this).find('td').eq(2).text();
                var productImage = $(this).find('img').attr('src');

                selectedProducts.push({
                    id: response.id,
                    name: productName,
                    amount: productAmount,
                    image: productImage
                });
            });

            $('#selected-products').val(JSON.stringify(selectedProducts));
       
        });
    });
</script>
    <script>
    $(document).ready(function() {
        // Hide the credit limit container on page load
        $('#credit-limit-container').hide();

        // Show the container and populate credit limit after shop selection
        $('#shop-select').change(function() {
            const shopId = $(this).val();
            if (shopId) {
                $.ajax({
                    url: "{{ route('shops.creditLimit') }}",
                    type: 'GET',
                    data: {
                        shop_id: shopId
                    },
                    success: function(response) {
                        if (response.credit_limit !== undefined) {
                            $('#credit-limit-container').html('Credit Limit: ' + response
                                .credit_limit);
                            $('#credit-limit-container').fadeIn(); // Show the container
                        } else {
                            $('#credit-limit').val('');
                            $('#credit-limit-container').fadeOut(); // Hide the container
                        }
                    },
                    error: function() {
                 
                        $('#credit-limit').val('');
                        $('#credit-limit-container')
                            .fadeOut(); // Hide the container on error
                    }
                });
            } else {
                $('#credit-limit').val('');
                $('#credit-limit-container').fadeOut(); // Hide the container if no shop is selected
            }
        });
    });

 

    $(document).ready(function() {
       
 // Initialize selectedProducts with existing products from the backend-rendered table
 var selectedProducts = [];

$('#selected-products-body tr').each(function() {
    var $row = $(this); // cache current row

    var $countInput = $row.find('input[name^="counts"]'); // find input inside the row
    var productId = $countInput.length ? $countInput.attr('name').match(/\d+/)[0] : null; // extract product id from input name
    var productName = $row.find('td:nth-child(2)').text().trim(); // 2nd <td> => product name
    var productAmount = $row.find('td:nth-child(3)').text().trim(); // 3rd <td> => product amount
    var productImage = $row.find('td:first-child img').attr('src') || ''; // 1st <td> image src
    var productCount = $countInput.length ? $countInput.val() : 0; // get current input value
    var productDiscount = $row.find('.discount-input').val(); // Get the discount value
    var productFinalPrice = $row.find('.discounted-amount').val(); // Get the final price after discount


    if (productId) { // if product id found
        selectedProducts.push({
            id: productId,
            name: productName,
            amount: productAmount,
            image: productImage,
            count: productCount,
           discount: productDiscount, 
            final_price: productFinalPrice // Add final price to the object
        });
    }
});

// Store the initial selected products in the hidden input
$('#selected-products').val(JSON.stringify(selectedProducts));
        
        $('#selected-products-body').on('click', '.remove-product-btn', function() {
    // Only allow removal if more than one row remains
    if ($('#selected-products-body tr').length <= 1) {
        alert('At least one product must remain.');
        return; // Prevent removal
    }

    // Remove product row
    $(this).closest('tr').remove();
    // Update the hidden input with the remaining selected products
    var selectedProducts = [];
    $('#selected-products-body tr').each(function() {
         var productId = $(this).find('input[type="hidden"]').val(); // Get the value of the hidden input

        var productName = $(this).find('td').eq(1).text();
        var productAmount = $(this).find('td').eq(2).text();
        var productImage = $(this).find('img').attr('src');
        var productCount = $(this).find('.count-input').val(); // ✅ get value from input field
 var productDiscount = $(this).find('.discount-input').val(); // Get the discount value
 var productFinalPrice = $row.find('.discounted-amount').val(); // Get the final price after discount

       selectedProducts.push({
    id: productId,
    name1: productName,
    amount: productAmount,
    image: productImage,
    count1: productCount, // ← should be `count`, not `amount` again
     discount: productDiscount, // Add the discount value
       final_price: productFinalPrice 
});

    });

    $('#selected-products').val(JSON.stringify(selectedProducts));
});

    });

   
    $(document).ready(function() {
$('#selected-products-body').on('input', '.count-input, .discount-input', function () {
   
    calculateTotalAmount();
});
        // Function to calculate the total amount
      // Function to calculate the total amount
        function calculateTotalAmount() {
            let totalAmount = 0;
            let totalAmount2 = 0;
            
             // Get the discount_percentage value
    let discountPercentage = parseFloat($('#discount_percentage').val()) || 0;
            // Iterate through each row in the table
            $('#selected-products-body tr').each(function() {
                const amount = parseFloat($(this).find('td').eq(2).text()) || 0; // Amount column
                const qty = parseInt($(this).find('.count-input').val()) || 0; // Quantity input
                 const discount = parseFloat($(this).find('.discount-input').val()) || 0;

                let itemTotal = amount * qty;

                // Apply discount
                let discountedTotal = itemTotal - (itemTotal * discount / 100);
       
                // Update discounted amount in the row
             
                $(this).find('.discounted-amount').val(discountedTotal.toFixed(2));
                $(this).find('.discounted-amount').text(discountedTotal.toFixed(2));
                     totalAmount += discountedTotal;
          
            });
            // Apply total/global discount if any
    if (discountPercentage > 0) {
     
        totalAmount = totalAmount - (totalAmount * discountPercentage / 100);
    }

             // Iterate through each row in the table
             $('#selected-products-body tr').each(function() {
                const amount2 = parseFloat($(this).find('td').eq(2).text()) || 0; // Amount column
                const qty2 = parseInt($(this).find('.count-input').val()) || 0; // Quantity input
                

                if (!isNaN(amount2) && !isNaN(qty2)) {
                    totalAmount2 += amount2 * qty2;
                }
            });
console.log("discountPercentage",discountPercentage)
            // Update the total amount display
            $('#total-amount').text(totalAmount.toFixed(2));
            $('#total-amount2').text(totalAmount2.toFixed(2));
            // Update the hidden input field
            $('#totalAmountInput').val(totalAmount.toFixed(2));
            $('#totalAmountInput2').val(totalAmount2.toFixed(2));
        }
        // Recalculate total amount on quantity input change
        $('#selected-products-body').on('input', '.count-input', function() {
         
            calculateTotalAmount();
        });
        $('#discount_percentage').on('keyup change', function() {
    calculateTotalAmount();
});


        // Observe changes in the table body for row addition/removal
        const tableBody = document.getElementById('selected-products-body');
        const observer = new MutationObserver(function(mutationsList) {
            for (const mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    calculateTotalAmount();
                }
            }
        });

        // Configure the observer to watch for child nodes
        observer.observe(tableBody, {
            childList: true
        });

        // Initialize total amount calculation on page load
        calculateTotalAmount();
      

    });
    </script>
    
    @include('includes.js2')
</body>

</html>