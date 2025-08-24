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
                        <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                        </li>
                        <li class="active-bre"><a href="#">Create grn</a>
                        </li>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title">
                                    <h4>Add New GRN</h4>
                                </div>
                                <style>
                                .ls{margin-top:-25px;}
                                </style>
                                <div class="tab-inn">
                                    <form action="{{ route('grns.storeall') }}" method="POST">
                                        @csrf
                                        <div class="row">

                                            <div class="col-12 col-md-4">
                                                <label for="warehouse_id">Select Warehouse</label>
                                                <select class="form-control warehouse_id" id="warehouse_id" name="warehouse_id" required>
                                                    <option value="" disabled selected>Select Warehouse</option>
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-field col s4">
                                                <input type="date" name="received_date" class="validate">

                                            </div>

                                            <div class="input-field col s4">
                                                <input type="text" name="grn_number" class="validate">
                                                <label for="remarks" class="ls">Grn Number</label>
                                            </div>

                                        </div>


                                        <div class="row">
                                            <div class="col-12 col-md-6">
    <label for="supplier_id">Select Supplier</label>
    <select class="form-control" id="supplier_id" name="supplier_id" required>
        <option value="" disabled selected>Select Supplier</option>
        @foreach ($supllier as $supplier)
            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
        @endforeach
    </select>
</div>


                                            <div class="input-field col s6">
                                                <input type="text" name="remarks" class="validate">
                                                <label for="remarks" class="ls">Remarks</label>
                                            </div>
                                        </div>

                                        <h3>GRN Items</h3>
                                        <div id="items">
    <div class="item">
        <div class="row product-item" style="border: 2px solid #ccc; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
            <div class="col-12 col-md-4">
                    <label>Product</label>
                    <select class="form-control product" id="product" name="items[0][product_id]" required>
                        <option value="" disabled selected>Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->category->name }}) ({{ $product->department->name }})({{ $product->realCategory->name }})({{ $product->bike->name }})</option>
                        @endforeach  
                    </select>
                </div>
            {{-- <div class="col-12 col-md-2">
                    <label>Brand</label>
                    <select class="form-control brand" id="brand" name="items[0][brand]">
                        <option value="" disabled selected>Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div> --}}
           
            <div class="input-field col s2">
                <input type="number" name="items[0][quantity]" class="validate" required placeholder="Qty">
             
            </div>
           <div class="input-field col s4">
                <input type="number" name="items[0][purches]" class="validate" required placeholder="Purches Price">
          
            </div>
            <div class="input-field col s4">
                <input type="number" name="items[0][suplier_discount]" class="validate" required>
                <label class="ls">Supplier Discount</label>
            </div> <div class="input-field col s4">
                <input type="number" name="items[0][set_price]" class="validate" required readonly>
                <label class="ls">Set a price</label>
            </div>
          
            <div class="input-field col s4">
                <input type="number" name="items[0][whole_sell_dis]" class="validate" required>
                <label class="ls">whole Sales increase </label>
            </div>
              <div class="input-field col s4">
                <input type="number" name="items[0][retail_sell_dis]" class="validate" required>
                <label class="ls">Retails increase </label>
            </div>
            <div class="input-field col s4">
                <input type="number" name="items[0][whole_sell_Price]" class="validate" required readonly>
                <label class="ls">whole Sales price</label>
            </div><div class="input-field col s4">
                <input type="number" name="items[0][retail_price]" class="validate" required readonly>
                <label class="ls">Retails price</label>
           </div>
            <div class="input-field col s2">
                <input type="text" name="items[0][warranty_period]" class="validate">
                <label class="ls">Warranty</label>
            </div>
           
        
           <div class="input-field col s1">
   
</div>

<div class="input-field col s1">
    <button id="save-grn-button" type="button" class="btn save-product" onclick="saveProduct(this)">
        Save
    </button>

</div>
        </div>
    </div>
</div>

<!-- Add More Button -->
<div class="row">
    <div class="input-field col s2">
        <button type="button" class="btn green" onclick="addItem()">Add Product</button>
    </div>
</div>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <!--<button type="submit" class="waves-effect waves-light btn-large">Save-->
                                                <!--    GRN</button>-->
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

    let itemIndex = 1; // Start index for new items

    function addItem() {
        let itemContainer = document.getElementById("items");
        let firstItem = document.querySelector(".product-item"); // Select the first product item

        if (!firstItem) return; // Exit if no items exist

        let newItem = firstItem.cloneNode(true); // Clone the first item
        newItem.querySelectorAll("input, select").forEach((field) => {
            let name = field.getAttribute("name");
            if (name) {
                let newName = name.replace(/\[0\]/g, `[${itemIndex}]`); // Update index
                field.setAttribute("name", newName);
            }
            field.value = ""; // Clear values
        });

 // Find the Save button in the cloned item and enable it
    let saveButton = newItem.querySelector(".save-product");
    saveButton.disabled = false;
    
    // Add click event to disable the button after it's clicked
    saveButton.onclick = function() {
        saveProduct(saveButton);
    };
    
        itemContainer.appendChild(newItem); // Append the new item
        itemIndex++; // Increase index
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
    
    function saveProduct(button) {
    let item = button.closest(".product-item");
   
    // Collect product data
    let formData = {
        warehouse_id: document.getElementById("warehouse_id").value,
        supplier_id: document.getElementById("supplier_id").value,
        received_date: document.querySelector("input[name='received_date']").value,
        grn_number: document.querySelector("input[name='grn_number']").value,
        product_id: item.querySelector("select[name*='[product_id]']").value,
        quantity: item.querySelector("input[name*='[quantity]']").value,
        purches: item.querySelector("input[name*='[purches]']").value,
        suplier_discount: item.querySelector("input[name*='[suplier_discount]']").value,
        set_price: item.querySelector("input[name*='[set_price]']").value,
        retail_sell_dis: item.querySelector("input[name*='[retail_sell_dis]']").value,
        whole_sell_dis: item.querySelector("input[name*='[whole_sell_dis]']").value,
        whole_sell_Price: item.querySelector("input[name*='[whole_sell_Price]']").value,
        retail_price: item.querySelector("input[name*='[retail_price]']").value,
        warranty_period: item.querySelector("input[name*='[warranty_period]']").value,
        _token: document.querySelector("input[name='_token']").value, // CSRF token
    };
 // Send AJAX request
$.ajax({
    url: "{{ route('grns.storeItem') }}", // Create this route
    type: "POST",
    data: formData,
    success: function (response) {
       
        // Display the success message from the response
         if (response.success === true) {
            alert(response.message);
            button.setAttribute('disabled', true);
        } else {
            alert("Failed to save product. Please try again.");
        }
    },
    error: function (xhr, status, error) {
        try {
            const errorResponse = JSON.parse(xhr.responseText);
            // If the server sends a specific error message, display it
            if (errorResponse.message) {
                alert("Error saving product: " + errorResponse.message);
            } else {
                alert("An unexpected error occurred.");
            }
        } catch (e) {
            alert("An unexpected error occurred.");
        }
    },
});

}


     document.addEventListener("input", function(event) {
        if (event.target.matches("input[name^='items'][name$='[purches]'], input[name^='items'][name$='[suplier_discount]']")) {
            updateSetPrice(event.target);
        }
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
document.addEventListener("input", function(event) {
    if (event.target.matches("input[name^='items'][name$='[purches]'], input[name^='items'][name$='[suplier_discount]']")) {
        updateSetPrice(event.target);
    }

    if (event.target.matches("input[name^='items'][name$='[retail_sell_dis]']")) {
        updateRetailPrice(event.target);
    }

    if (event.target.matches("input[name^='items'][name$='[whole_sell_dis]']")) {
        updateWholeSalePrice(event.target);
    }
});

function updateSetPrice(element) {
    let itemRow = element.closest(".product-item");
    let purchesInput = itemRow.querySelector("input[name^='items'][name$='[purches]']");
    let discountInput = itemRow.querySelector("input[name^='items'][name$='[suplier_discount]']");
    let setPriceInput = itemRow.querySelector("input[name^='items'][name$='[set_price]']");

    if (!purchesInput || !discountInput || !setPriceInput) {
        return; // Exit if any input is missing
    }

    let purches = parseFloat(purchesInput.value) || 0;
    let discount = parseFloat(discountInput.value) || 0;
    let setPrice = purches - (purches * discount / 100);
    
    setPriceInput.value = setPrice.toFixed(2);

    // Update Retail & Wholesale Prices if Increase fields are already filled
    updateRetailPrice(setPriceInput);
    updateWholeSalePrice(setPriceInput);
}

function updateRetailPrice(element) {
    let itemRow = element.closest(".product-item");
    let setPriceInput = itemRow.querySelector("input[name^='items'][name$='[set_price]']");
    let retailIncreaseInput = itemRow.querySelector("input[name^='items'][name$='[retail_sell_dis]']");
    let retailPriceInput = itemRow.querySelector("input[name^='items'][name$='[retail_price]']");
    let wholesalePriceInput = itemRow.querySelector("input[name^='items'][name$='[whole_sell_Price]']");

    if (!setPriceInput || !retailIncreaseInput || !retailPriceInput) {
        return; // Exit if any input is missing
    }

    let setPrice = parseFloat(wholesalePriceInput.value) || 0;
    let retailIncrease = parseFloat(retailIncreaseInput.value) || 0;

    let retailPrice = setPrice * (1 + retailIncrease / 100);
    retailPriceInput.value = retailPrice.toFixed(2);
}

function updateWholeSalePrice(element) {
    let itemRow = element.closest(".product-item");
    let setPriceInput = itemRow.querySelector("input[name^='items'][name$='[set_price]']");
    let wholesaleIncreaseInput = itemRow.querySelector("input[name^='items'][name$='[whole_sell_dis]']");
    let wholesalePriceInput = itemRow.querySelector("input[name^='items'][name$='[whole_sell_Price]']");

    if (!setPriceInput || !wholesaleIncreaseInput || !wholesalePriceInput) {
        return; // Exit if any input is missing
    }

    let setPrice = parseFloat(setPriceInput.value) || 0;
    let wholesaleIncrease = parseFloat(wholesaleIncreaseInput.value) || 0;

    let wholesalePrice = setPrice * (1 + wholesaleIncrease / 100);
    wholesalePriceInput.value = wholesalePrice.toFixed(2); // FIXED THIS LINE
}
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
           console.log("brand",brand,"code",code)

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



    @include('includes.js2')
</body>

</html>