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
            <div class="sb2-2">
                <div class="sb2-2-2">
                    <ul>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title">
                                    <h4>Edit New Warehouses</h4>
                                </div>
                                <div class="tab-inn">
                                    <form action="{{ route('grns.updateall') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="grn_tabe_id" value="{{ $grns->id }}">
                                            <div class="input-field col s4">
                                                <select class="form-control" id="warehouse_id" name="warehouse_id"
                                                    required>
                                                    <option value="" disabled {{ !$grns->warehouse ? 'selected' : ''
                                                        }}>Select Warehouse</option>
                                                    @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}" {{ $grns->warehouse &&
                                                        $grns->warehouse->id == $warehouse->id ? 'selected' : '' }}>{{
                                                        $warehouse->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="input-field col s4">
                                                <input type="date" name="received_date" class="validate"
                                                    value="{{ $grns->received_date ?? '' }}" required>

                                            </div>


                                            <div class="input-field col s4">
                                                <input type="text" name="grn_number" class="validate"
                                                    value="{{ old('grn_number', $grns->grn_number ?? '') }}" required>


                                                <label for="remarks">Grn Number</label>
                                            </div>
                                     

                                            <div class="row">
                                                <!-- Supplier Dropdown -->
                                                <div class="input-field col s6">
                                                    <select class="form-control" id="supplier_id" name="supplier_id"
                                                        required>
                                                        <!--<option value="" disabled {{ !$grns->warehouse ? 'selected' : '' }}>Select Warehouse</option>-->
                                                        @foreach ($suplliers as $supplier)
                                                        <option value="{{ $supplier->id }}" {{ isset($grns->supplier_id)
                                                            &&
                                                            $grns->supplier_id == $supplier->id ? 'selected' : '' }}> {{
                                                            $supplier->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Remarks Input -->
                                                <div class="input-field col s6">
                                                    <input type="text" name="remarks" class="validate"
                                                        value="{{ old('remarks', $grns->remarks ?? '') }}">
                                                    <label for="remarks">Remarks</label>
                                                </div>
                                            </div>

                                            <h3>GRN Items</h3>
                                            <div id="items">
                                                @foreach ($grns->items as $index => $item)
                                             
                                                  <div class="item" data-grn-id="{{ $grns->id }}" data-grn-item-id="{{ $item->id }}">
                                                    <div class="row product-item"
                                                        style="border: 2px solid #ccc; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                                                        <div class="col-12 col-md-4">
                                                            <label>Product</label>
                                                            <select class="form-control product" id="product"
                                                                name="items[{{ $index }}][product_id]" required>
                                                                <option value="" disabled>Select Product</option>
                                                                @foreach ($products as $product)
                                                                <option value="{{ $product->id }}" {{ $item->product_id
                                                                    ==
                                                                    $product->id ? 'selected' : '' }}>{{ $product->name
                                                                    }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-12 col-md-2">
                                                            <label>Brand</label>
                                                              <select class="form-control brand" id="brand" name="items[0][brand]">
                                                                <option value="" disabled selected>Brand</option>
                                                                @foreach ($brands as $brand)
                                                                <option value="{{ $brand->id }}">{{ $brand->name }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="input-field col s3">
                                                            <input type="text" id="code" name="items[0][code]"
                                                                class="validate code">
                                                            <label class="ls">Product Code</label>
                                                        </div>
                                                        
                                                        <div class="input-field col s2">
                                                            <input type="number" name="items[{{ $index }}][quantity]"
                                                                class="validate" value="{{ $item->quantity }}" required>
                                                            <label class="ls">Qty</label>
                                                        </div>
                                                           <input type="hidden" name="items[{{ $index }}][grn_item_id]" value="{{ $item->id }}">
                                                        <input type="hidden" id ='grn_id' name="grn_id" value="{{ $grns->id }}">
                                                        <div class="input-field col s4">
                                                            <input type="number"
                                                                name="items[{{ $index }}][purchase_price]"
                                                                class="validate" value="{{ $item->purchase_price }}"
                                                                required>
                                                            <label class="ls">Purches Price</label>
                                                        </div>
                                                        <div class="input-field col s4">
                                                            <input type="number"
                                                                name="items[{{ $index }}][supplier_discount]"
                                                                class="validate" value="{{ $item->supplier_discount }}"
                                                                required>
                                                            <label class="ls">Supplier Discount</label>
                                                        </div>
                                                        <div class="input-field col s4">
                                                            <input type="number" name="items[{{ $index }}][set_price]"
                                                                class="validate" value="{{ $item->set_price }}"
                                                                required>
                                                            <label class="ls">Set a price</label>
                                                        </div>
                                                        <div class="input-field col s4">
                                                            <input type="number"
                                                                name="items[{{ $index }}][retail_sell_discount]"
                                                                class="validate"
                                                                value="{{ $item->retail_sell_discount }}" required>
                                                            <label class="ls">Retails increase </label>
                                                        </div>
                                                        <div class="input-field col s4">
                                                            <input type="number"
                                                                name="items[{{ $index }}][wholesale_discount]"
                                                                class="validate" value="{{ $item->wholesale_discount }}"
                                                                required>
                                                            <label class="ls">whole Sales increase </label>
                                                        </div>
                                                        <div class="input-field col s4">
                                                            <input type="number"
                                                                name="items[{{ $index }}][whole_sell_price]"
                                                                class="validate" value="{{ $item->wholesale_price }}"
                                                                required>
                                                            <label class="ls">whole Sales price</label>
                                                        </div>
                                                    
                                                    <div class="input-field col s4">
                                                        <input type="number" name="items[{{ $index }}][retail_price]"
                                                            class="validate" value="{{ $item->retail_price }}" required>
                                                        <label class="ls">Retails price</label>
                                                    </div>
                                                    <div class="input-field col s2">
                                                        <input type="number" name="items[{{ $index }}][warranty_period]"
                                                            class="validate" value="{{ $item->warranty_period }}"
                                                            required>
                                                        <label class="ls">Warranty</label>
                                                    </div>
                                                    <div class="input-field col s2">
                                                        <input type="number" name="items[{{ $index }}][rack_id]"
                                                            class="validate" value="{{ $item->rack_id }}" required>
                                                        <label class="ls">Rack</label>
                                                    </div>
                                                    <div class="input-field col s1">
                                                        <button type="button" class="btn red"
                                                            onclick="removeItem(this)">
                                                            <i class="material-icons">close</i>
                                                        </button>
                                                    </div>
                                                    <div class="input-field col s1">
                                                        <button type="button" class="btn save-product" onclick="saveProduct(this)">
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <!-- Add More Button -->
                                        <div class="row">
                                            <div class="input-field col s2">
                                                <button type="button" class="btn green" onclick="addItem()">Add
                                                    Product</button>
                                            </div>
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--== BOTTOM FLOAT ICON ==-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('Public/js/materialize.min.js') }}"></script>

    <script>

  function saveProduct(button) {
    const item = button.closest(".product-item");
    const itemRow = button.closest('[data-new], .item');
    const isNew = itemRow.getAttribute('data-new') === 'true';

    const formData = new FormData();

    if (!isNew) {
        formData.append("grn_item_id", item.querySelector("input[name*='[grn_item_id]']").value);
    }

    formData.append("grn_id", document.getElementById("grn_id").value);
    formData.append("warehouse_id", document.getElementById("warehouse_id").value);
    formData.append("supplier_id", document.getElementById("supplier_id").value);
    formData.append("received_date", document.querySelector("input[name='received_date']").value);
    formData.append("grn_number", document.querySelector("input[name='grn_number']").value);
    formData.append("product_id", item.querySelector("select[name*='[product_id]']").value);
    formData.append("brand", item.querySelector("select[name*='[brand]']").value);
    formData.append("code", item.querySelector("input[name*='[code]']").value);
    formData.append("quantity", item.querySelector("input[name*='[quantity]']").value);
    formData.append("purches", item.querySelector("input[name*='[purchase_price]']").value);
    formData.append("suplier_discount", item.querySelector("input[name*='[supplier_discount]']").value);
    formData.append("set_price", item.querySelector("input[name*='[set_price]']").value);
    formData.append("retail_sell_dis", item.querySelector("input[name*='[retail_sell_discount]']").value);
    formData.append("whole_sell_dis", item.querySelector("input[name*='[wholesale_discount]']").value);
    formData.append("whole_sell_Price", item.querySelector("input[name*='[whole_sell_price]']").value);
    formData.append("retail_price", item.querySelector("input[name*='[retail_price]']").value);
    formData.append("warranty_period", item.querySelector("input[name*='[warranty_period]']").value);
    formData.append("rack", item.querySelector("input[name*='[rack_id]']").value);
    formData.append("_token", document.querySelector("input[name='_token']").value);

    const url = isNew 
        ? "{{ route('grns.createItem') }}" 
        : "{{ route('grns.updateItem') }}";

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false, // Required for FormData
        contentType: false,
        success: function (response) {
            alert("Product saved successfully!");

            if (isNew) {
                itemRow.removeAttribute('data-new'); // mark as saved
            }
        },
        error: function (xhr, status, error) {
            alert("Error saving product: " + xhr.responseText);
        },
    });
}

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
    <style>
.custom-confirmation {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.custom-confirmation .message {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    width: 300px;
}

.custom-confirmation .actions {
    margin-top: 15px;
    display: flex;
    justify-content: space-around;
}
</style>
 
    <script>
         let itemIndex = {{ count($grns->items) }}; // Start from existing items count

    function addItem() {
        const container = document.getElementById('items');

        const newItem = document.createElement('div');
        newItem.classList.add('item');
        newItem.setAttribute('data-new', 'true');

        newItem.innerHTML = `
            <div class="row product-item" style="border: 2px solid #ccc; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                <div class="col-12 col-md-4">
                    <label>Product</label>
                    <select class="form-control product" name="items[${itemIndex}][product_id]" required>
                        <option value="" disabled selected>Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label>Brand</label>
                    <select class="form-control brand" name="items[${itemIndex}][brand]">
                        <option value="" disabled selected>Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-field col s3">
                    <input type="text" name="items[${itemIndex}][code]" class="validate code">
                    <label>Product Code</label>
                </div>

                <div class="input-field col s2">
                    <input type="number" name="items[${itemIndex}][quantity]" class="validate" required>
                    <label>Qty</label>
                </div>

                <div class="input-field col s4">
                    <input type="number" name="items[${itemIndex}][purchase_price]" class="validate" required>
                    <label>Purchase Price</label>
                </div>

                <div class="input-field col s4">
                    <input type="number" name="items[${itemIndex}][supplier_discount]" class="validate" required>
                    <label>Supplier Discount</label>
                </div>

               <div class="input-field col s4">
                    <input type="number" name="items[${itemIndex}][set_price]" class="validate" step="0.01" required>
                    <label>Set a Price</label>
                </div>


                <div class="input-field col s4">
                    <input type="number" name="items[${itemIndex}][retail_sell_discount]" class="validate" required>
                    <label>Retail Increase</label>
                </div>

                <div class="input-field col s4">
                    <input type="number" name="items[${itemIndex}][wholesale_discount]" class="validate" required>
                    <label>Wholesale Increase</label>
                </div>

                <div class="input-field col s4">
                    <input type="number" name="items[${itemIndex}][whole_sell_price]" class="validate" step="0.01" required>
                    <label>Wholesale Price</label>
                </div>

                <div class="input-field col s4">
                    <input type="number" name="items[${itemIndex}][retail_price]" class="validate" step="0.01" required>
                    <label>Retail Price</label>
                </div>

                <div class="input-field col s2">
                    <input type="number" name="items[${itemIndex}][warranty_period]" class="validate" required>
                    <label>Warranty</label>
                </div>

                <div class="input-field col s2">
                    <input type="number" name="items[${itemIndex}][rack_id]" class="validate" required>
                    <label>Rack</label>
                </div>

                <div class="input-field col s1">
                    <button type="button" class="btn red" onclick="removeItem(this)">
                        <i class="material-icons">close</i>
                    </button>
                </div>

                <div class="input-field col s2">
              <button onclick="saveProduct(this)" class="btn btn-primary">
                Save
            </button>


                  
                </div>
            </div>
        `;

        container.appendChild(newItem);
        itemIndex++;
    }

   function removeItem(button) {
    let item = button.closest(".item");
    let grnId = item.getAttribute("data-grn-id");
    let grnItemId = item.getAttribute("data-grn-item-id");

    if (document.querySelectorAll(".product-item").length > 1) {
        
        // Custom Confirmation Dialog
        const confirmation = document.createElement("div");
        confirmation.innerHTML = `
            <div class="custom-confirmation">
                <div class="message">
                    <p>Are you sure you want to remove this item?</p>
                    <div class="actions">
                        <button id="confirmDelete" class="btn red">Delete</button>
                        <button id="cancelDelete" class="btn grey">Cancel</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(confirmation);

        // Add event listeners for the buttons
        document.getElementById("confirmDelete").onclick = () => {
            confirmation.remove(); // Remove confirmation dialog

            // AJAX call
            fetch(`/grn-item/delete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    grn_id: grnId,
                    grn_item_id: grnItemId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    item.remove();
                    alert("Item removed successfully.");
                } else {
                    alert(data.message || "Failed to remove item.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred. Please try again.");
            });
        };

        // Cancel button action
        document.getElementById("cancelDelete").onclick = () => {
            confirmation.remove();
        };

    } else {
        alert("At least one product is required.");
    }
}

    </script>
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

   <script>
    $(document).ready(function () {
        // This will work for dynamically added items and also static ones
        $('#items').on('change keyup', '.brand, .code', function () {
            filterProducts($(this).closest('.product-item'));
        });

        function filterProducts($row) {
          
            let brand = $row.find('.brand').val();
            let code = $row.find('.code').val();
            let warehouseId = $('#warehouse_id').val(); // get selected warehouse
           

            $.ajax({
                url: "{{ route('get.filtered.products') }}",
                type: "GET",
                dataType: "json",
                data: { brand: brand, code: code, warehouse_id: warehouseId },
                success: function (response) {
                    console.log("products", response);

                     let productSelect = $row.find('.product');
                    productSelect.empty();
                    productSelect.append('<option value="" disabled selected>Select Product</option>');

                    $.each(response, function (index, product) {
                        productSelect.append('<option value="' + product.id + '">' + product.name + '(' + product.category_name + ')</option>');
                    });

                    productSelect.prop('selectedIndex', 0);
                    productSelect.trigger('change');
                },
                error: function () {
                    console.log("Error fetching products.");
                }
            });
        } // <-- This closing brace was missing
    });
</script>

   <script>
    document.addEventListener("input", function (event) {
        if (event.target.matches("input[name^='items'][name$='[purchase_price]'], input[name^='items'][name$='[supplier_discount]']")) {
            updateSetPrice(event.target, true); // manually triggered by base fields
        }

        if (event.target.matches("input[name^='items'][name$='[retail_sell_discount]']")) {
            updateRetailPrice(event.target);
        }

        if (event.target.matches("input[name^='items'][name$='[wholesale_discount]']")) {
            updateWholeSalePrice(event.target);
        }

        if (event.target.matches("input[name^='items'][name$='[set_price]']")) {
            updateWholeSalePrice(event.target); // only wholesale update here
        }
          if (event.target.matches("input[name^='items'][name$='[wholesale_discount]']")) {
    updateRetailPrice(event.target);
}
    });

    function updateSetPrice(element, updateRetail = false) {
        let itemRow = element.closest(".product-item");
        let purchaseInput = itemRow.querySelector("input[name^='items'][name$='[purchase_price]']");
        let discountInput = itemRow.querySelector("input[name^='items'][name$='[supplier_discount]']");
        let setPriceInput = itemRow.querySelector("input[name^='items'][name$='[set_price]']");

        let purchasePrice = parseFloat(purchaseInput.value) || 0;
        let discount = parseFloat(discountInput.value) || 0;

        let setPrice = purchasePrice - (purchasePrice * discount / 100);
        setPriceInput.value = setPrice.toFixed(2);

        // Always update wholesale price
        updateWholeSalePrice(setPriceInput);

        // Only update retail price if triggered from purchase/discount
        if (updateRetail) {
            updateRetailPrice(setPriceInput);
        }
    }

    function updateRetailPrice(element) {
        let itemRow = element.closest(".product-item");
        let setPriceInput = itemRow.querySelector("input[name^='items'][name$='[set_price]']");
        let retailIncreaseInput = itemRow.querySelector("input[name^='items'][name$='[retail_sell_discount]']");
        let retailPriceInput = itemRow.querySelector("input[name^='items'][name$='[retail_price]']");
        let wholesalePriceInput = itemRow.querySelector("input[name^='items'][name$='[whole_sell_price]']");

        let wholesalePrice = parseFloat(wholesalePriceInput.value) || 0;
        let retailIncrease = parseFloat(retailIncreaseInput.value) || 0;

        let retailPrice = wholesalePrice * (1 + retailIncrease / 100);
        retailPriceInput.value = retailPrice.toFixed(2);
    }

    function updateWholeSalePrice(element) {
        let itemRow = element.closest(".product-item");
        let setPriceInput = itemRow.querySelector("input[name^='items'][name$='[set_price]']");
        let wholesaleIncreaseInput = itemRow.querySelector("input[name^='items'][name$='[wholesale_discount]']");
        let wholesalePriceInput = itemRow.querySelector("input[name^='items'][name$='[whole_sell_price]']");

        if (!setPriceInput || !wholesaleIncreaseInput || !wholesalePriceInput) {
            return;
        }

        let setPrice = parseFloat(setPriceInput.value) || 0;
        let wholesaleIncrease = parseFloat(wholesaleIncreaseInput.value) || 0;

        let wholesalePrice = setPrice * (1 + wholesaleIncrease / 100);
        wholesalePriceInput.value = wholesalePrice.toFixed(2);
    }
</script>


    @include('includes.js2')
</body>

</html>