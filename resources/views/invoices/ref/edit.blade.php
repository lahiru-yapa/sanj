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
                                    <button type="button" class="btn btn-light w-100" onclick="window.location='{{ route('ref.addinvoice') }}'">
            Add Invoice
        </button><button type="button" class="btn btn-light w-100" onclick="window.location='{{ route('refinvoice.index') }}'" style="margin-left:10px">
            All Invoice
        </button>
                                </div>
                              

                                <div class="tab-inn">
                                <form action="{{ route('ref2.invoice.updateInvoice', $invoices->id) }}" method="POST">
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
 <select class="form-control" name="warehouse" id="warehouse" disabled>
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
            <select class="form-control" name="product_name2" id="product_name2" disabled>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="selected-products-body">
                        @foreach($invoices->invoiceProducts as $invoiceProduct)
                       
                      @php
            $product = $invoiceProduct->product;
            $warehouse = $invoices->warehouse;
            $stock = $product->warehouses->firstWhere('id', $warehouse->id)?->pivot->stock ?? 0;

    
        @endphp
                            <tr>
                              
            <input type="hidden" class="grn_item_id" name="grn_item_id[{{ $invoiceProduct->product->id }}][{{$invoiceProduct->price}}]" value="{{ $invoiceProduct->grn_item_id}}">
        
                                <td><img src="{{ asset('storage/' . $invoiceProduct->product->photo) }}" alt="Product Image" style="width: 100px;"></td>
                                <td>{{ $product->name }}</td>

                     <td data-amount="{{ $invoiceProduct->price ?? 0 }}">
    {{ $invoiceProduct->price ?? 'N/A' }}
</td>

                                  <td>Stock: {{ $stock }}</td>
                                <td>
                                    <input type="number" name="counts[{{ $invoiceProduct->product->id }}][{{$invoiceProduct->price}}]" class="form-control count-input" value="{{ old('counts.' . $invoiceProduct->product->id, $invoiceProduct->quantity) }}"
>
                                </td>
<td>
    <input type="number" name="discount[{{ $invoiceProduct->product->id }}][{{$invoiceProduct->price}}]" class="form-control discount-input" value="{{ old('discount.' . $invoiceProduct->product->id, $invoiceProduct->discount) }}">
</td>

<td>
    <input type="number" step="0.01" name="final_price[{{ $invoiceProduct->product->id }}][{{$invoiceProduct->price}}]" class="form-control discounted-total-column" value="{{ old('final_price.' . $invoiceProduct->product->id, $invoiceProduct->final_price) }}">
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

    alert("Triggered! Warehouse ID: " + warehouseId + " | Brand ID: " + brandId);

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

                var productSelect = $('#product_name2');
                productSelect.empty();
                productSelect.append('<option value="" disabled selected>Select product</option>');

                $.each(response.data, function (index, item) {
                    productSelect.append('<option value="' + item.id + '">' + item.name + ' (' + item.stock + ') (' + item.category_name + ')</option>');
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

 
$(document).ready(function () {
    // Function to update selected products
    function updateSelectedProducts() {
        var selectedProducts = [];

        // Loop through each row and update the product list
        $('#selected-products-body tr').each(function () {
            var $row = $(this);

            var $countInput = $row.find('input[name^="counts"]');
            var productId = $countInput.length ? $countInput.attr('name').match(/\d+/)[0] : null;
            var productImage = $row.find('td:first-child img').attr('src') || '';
            var productCount = $countInput.length ? $countInput.val() : 0;
            var productDiscount = $row.find('.discount-input').val();
            var productFinalPrice = $row.find('.discounted-total-column').val();
            var productName = $row.find('td:nth-child(2)').text().trim();
            var productAmount = $row.find('td[data-amount]').data('amount');
            var grn_item_id = $row.find('.grn_item_id').val();
          
            if (productId) {
                selectedProducts.push({
                    id: productId,
                    name: productName,
                    amount: productAmount,
                    image: productImage,
                    count2: productCount,
                    discount: productDiscount,
                    final_price: productFinalPrice,
                    grn_item_id:grn_item_id
                });
            }
        });

        // Update the hidden input field
        $('#selected-products').val(JSON.stringify(selectedProducts));
    }

    // Trigger update on input changes or row removals
    $('#selected-products-body').on('input', 'input', updateSelectedProducts);

    // Trigger update when removing a row
    $('#selected-products-body').on('click', '.remove-product-btn', function () {
        if ($('#selected-products-body tr').length <= 1) {
            alert('At least one product must remain.');
            return;
        }
        $(this).closest('tr').remove();
        updateSelectedProducts();
    });

    // Initial trigger to populate with existing data
    updateSelectedProducts();
});

   
    $(document).ready(function() {
$('#selected-products-body').on('input', '.count-input, .discount-input', function () {
  
    calculateTotalAmount();
});
        // Function to calculate the total amount
   function calculateTotalAmount() {
            let totalAmount = 0;
        
            // Iterate through each row in the table
            $('#selected-products-body tr').each(function () {
         
               const amount = parseFloat($(this).find('td').eq(2).data('amount')) || 0;


                const qty = parseInt($(this).find('.count-input').val()) || 0;
                const discount = parseFloat($(this).find('.discount-input').val()) || 0;
       
                let itemTotal = amount * qty;
                // Apply discount
                let discountedTotal = itemTotal - (itemTotal * discount / 100);
   
  $(this).find('.discounted-total-column').val(discountedTotal.toFixed(2));


                if (!isNaN(amount) && !isNaN(qty)) {
                    totalAmount += discountedTotal; // Add discounted total to the overall total
                }
            });
         
            // Apply discount if provided
            const discountPercentage = parseFloat($('#discount_percentage').val()) || 0;
             
            if (discountPercentage > 0) {
                totalAmount = totalAmount - (totalAmount * discountPercentage / 100);
            }
         
            // Update the total amount display
            $('#total-amount').text(totalAmount.toFixed(2));
        
            // Update the hidden input field
            $('#totalAmountInput').val(totalAmount.toFixed(2));
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