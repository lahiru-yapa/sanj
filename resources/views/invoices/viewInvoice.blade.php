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
                                    <h4>Invoice </h4>
<div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
    <input type="date" id="startDate" value="{{ request()->get('start_date') }}" style="width: auto;">

    <input type="date" id="endDate" value="{{ request()->get('end_date') }}" style="width: auto;">

    <select id="shopSelect">
        <option value="">Select Shop</option>
        @foreach ($shops as $shop)
            <option value="{{ $shop->id }}" {{ request()->get('shop') == $shop->id ? 'selected' : '' }}>
                {{ $shop->name }}
            </option>
        @endforeach
    </select>

    <select id="filterStatusSelect">
        <option value="">Select Status</option>
        <option value="approved" {{ request()->get('status') == 'approved' ? 'selected' : '' }}>Approved</option>
        <option value="rejected" {{ request()->get('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>Pending</option>
    </select>

    <button onclick="filterInvoices()">Filter</button>
</div>
<script>

    function filterInvoices() {
        const selectedStatus = document.getElementById("filterStatusSelect").value;
        const startDate = document.getElementById("startDate").value;
        const endDate = document.getElementById("endDate").value;
        const shop = document.getElementById("shopSelect").value;

        // Construct the URL with filter parameters
        const url = new URL("{{ route('invoice.index') }}", window.location.origin);

        if (selectedStatus) url.searchParams.set('filter', selectedStatus);
        if (startDate) url.searchParams.set('start_date', startDate);
        if (endDate) url.searchParams.set('end_date', endDate);
        if (shop) url.searchParams.set('shop', shop);

        window.location.href = url.toString();
    }
</script>
                                </div>
                                <div class="tab-inn">
                                    <div class="table-responsive table-desi">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>invoice number</th>
                                                    <th>shop name </th>
                                                    <th>total_amount</th>
                                                    <th>paid_amount</th>
                                                    <th>due_date</th>
                                                    <th>status</th>
                                                    <th>actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                @foreach($invoices as $item)
                                                <tr>
                                                    <td>{{$item->invoice_number}}</td>
                                                    <td>{{$item->shop->name}}</td>
                                                    <td>{{$item->total_amount}}</td>
                                                    <td>{{$item->paid_amount}}</td>
                                                    <td>{{$item->created_at}}</td>

                                                    <td>
                                                    <div class="switch mar-bot-20">
    <label>
         <p class="invoice-description">Status: {{ $item->description }}</p> <!-- Status Display -->
        <input type="checkbox"  class="approvalToggle"  data-invoice-id="{{ $item->id }}" 
               {{ $item->description === 'approved' ? 'checked' : '' }}>
        <span class="lever"></span> 
    </label>
</div>
                                                    </td>
                                                 <td>
    <form method="POST" action="{{ route('invoices.action') }}">
        @csrf
        <input type="hidden" name="invoice_id" value="{{ $item->id }}">
           
        <select name="action" onchange="this.form.submit()">
          
          <option value="view">View</option>
                <option value="edit">Edit</option>
                <option value="delete">Delete</option>
          
            
        </select>
    </form>
</td>


                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <!-- Pagination Links -->
                                        <div class="d-flex justify-content-center">
                                            {{ $invoices->links() }}
                                        </div>
                                    </div>
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