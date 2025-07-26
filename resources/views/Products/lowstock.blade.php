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
                <!-- //side bar -->
                @include('includes.sidebar')
            </div>
            <div class="sb2-2">
                <div class="sb2-2-2">
                    <ul>
                        <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                        </li>
                        <li class="active-bre"><a href="#">All-Invoice</a>
                        </li>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                <div class="inn-title">
    <h4>Low Stock</h4>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <select id="supplierSelect">
            <option value="">Select Supplier</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
            @endforeach
        </select>
        <button onclick="filterLowStock()">Filter</button>
    </div>
</div>

<table class="table table-bordered" id="stockTable">
    <thead>
        <tr>
            <th>Product</th>
            <th>SKU</th>
            <th>Warehouse</th>
            <th>Stock</th>
            <th>Supplier</th>
        </tr>
    </thead>
    <tbody id="stockBody">
        {{-- Filled via JavaScript --}}
    </tbody>
</table>
<!-- Add this in your <head> or before your custom script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    filterLowStock(); // 👈 Automatically load low stock products when page loads
});

function filterLowStock() {
    const supplierId = $('#supplierSelect').val();

    $.ajax({
        url: '{{ route("stock.low.ajax") }}',
        type: 'GET',
        data: { supplier_id: supplierId },
        success: function(response) {
            let rows = '';
            if (response.length > 0) {
                response.forEach(item => {
                    rows += `
                        <tr>
                            <td>${item.product?.name ?? 'N/A'}</td>
                            <td>${item.product?.sku ?? '-'}</td>
                            <td>${item.warehouse?.name ?? 'N/A'}</td>
                            <td>${item.stock}</td>
                            <td>${item.product?.grn_supplier ?? 'N/A'}</td>
                        </tr>`;
                });
            } else {
                rows = '<tr><td colspan="5">No low stock items found.</td></tr>';
            }
            $('#stockBody').html(rows);
        },
        error: function() {
            alert('Error loading stock data.');
        }
    });
}
</script>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    nav a {
        color: #271f1f !important;
    }

    .flex.items-center.justify-between {
        color: #000000;
        background-color: #f9f9f9
    }
    </style>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const approvalToggles = document.querySelectorAll('.approvalToggle');

    approvalToggles.forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            const invoiceId = this.getAttribute('data-invoice-id');
            const description = this.checked ? 'approved' : 'rejected';
   const descriptionElement = this.closest('.switch').querySelector('.invoice-description');


            fetch('/update-invoice-description', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ id: invoiceId, description: description }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                         if (descriptionElement) {
                            descriptionElement.textContent = `Status: ${description}`;
                        }
                        alert(`Invoice ${description} successfully!`);
                    } else {
                        alert('Failed to update invoice. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        });
    });
});

    </script>
    @include('includes.js')
</body>

</html>