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
                        <li class="active-bre"><a href="#"> Create Payments</a>
                        </li>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title">
                                 </div>
                                <div class="tab-inn">
                                <form action="{{ route('payments.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="input-field col s4">
            <label for="warehouse_id" class="ls">Select Shop</label>
           <select class="form-control" id="shop_id" name="shope_id" required>

                <option value="" disabled selected>Select Shop</option>
                @foreach ($shops as $shop)
                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                @endforeach
            </select>
        </div>
       
        <div class="input-field col s4">
            <input type="text" name="amount" class="validate">
             <label for="remarks" class="ls">Amount </label>
        </div> 
       <div class="input-field col s4">
    <input type="text" name="shop_balance" class="validate" id="shop_balance" readonly>
    <label for="remarks" class="ls">Shop balance</label>
</div>

         <div class="input-field col s4">
            <select name="payment_method" class="form-control" required>
                <option value="cash">Cash</option>
                <option value="bank">Bank</option>
                <option value="cheque">Cheque</option>
                <option value="card">Card</option>
            </select>
        </div>
    </div>
   <div class="input-field col s8">
    <h5>Invoices</h5>
    <table class="table table-bordered" id="invoice-table">
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Due Amount</th>
                <th>Payed Status</th>
                <th>Date</th>
                  <th>Select</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5" class="text-center">Select a shop to view invoices</td>
            </tr>
        </tbody>
    </table>
</div>
    <div class="row">
        <div class="input-field col s12">
            <button type="submit" class="waves-effect waves-light btn-large">Submit</button>
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
  <style>
      .ls{margin-top: -35px;}
      #invoice-table tbody {
    background-color: #f1f1f1;
    [type="radio"] {
    position: static !important;
    left: auto !important;
    opacity: 1 !important;
    margin-right: 5px;
}


}

  </style>
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
 @include('includes.js2')
 <!-- put this at the bottom -->
<script>
   document.addEventListener('DOMContentLoaded', function () {
    const shopSelect = document.getElementById('shop_id');
    if (!shopSelect) return;

    const tableBody = document.querySelector('#invoice-table tbody');
    const shopBalanceInput = document.getElementById('shop_balance'); // The input field for shop balance

    shopSelect.addEventListener('change', function () {
        const shopId = this.value;
        tableBody.innerHTML = `<tr><td colspan="5" class="text-center">Loading...</td></tr>`;

        fetch(`/payments/invoices/${shopId}`)
            .then(response => response.json())
            .then(data => {
                const invoices = data.invoices;
                const remainingBalanceSum = data.remaining_balance_sum;

                // Set the value of the input field for shop balance
                shopBalanceInput.value = remainingBalanceSum;

                if (invoices.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="5" class="text-center">No invoices found</td></tr>`;
                    return;
                }

                let rows = '';
                invoices.forEach(invoice => {
                    const due = Number(invoice.total_amount || 0) - Number(invoice.paid_amount || 0);
                    
    // Determine paid status text
    let statusText = '';
    if (invoice.paid_status == 0) {
        statusText = 'Unpaid';
    } else if (invoice.paid_status == 1) {
        statusText = 'Paid Completed';
    } else if (invoice.paid_status == 2) {
        statusText = 'Partial Completed';
    }
    
                    rows += `
                        <tr>
                            <td>#${invoice.id}</td>
                            <td>${Number(invoice.total_amount || 0).toFixed(2)}</td>
                            <td>${Number(invoice.paid_amount || 0).toFixed(2)}</td>
                            <td>${due.toFixed(2)}</td>
                             <td>${statusText}</td>
                            <td>${new Date(invoice.created_at).toLocaleDateString()}</td>
                            <td>
                                <div class="form-check text-center">
                                    <input class="form-check-input" type="radio" name="invoice_id" value="${invoice.id}" required>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                tableBody.innerHTML = rows;
            })
            .catch(error => {
                tableBody.innerHTML = `<tr><td colspan="5" class="text-center">Error loading invoices</td></tr>`;
                console.error(error);
            });
    });
});

</script>

</body>

</html>