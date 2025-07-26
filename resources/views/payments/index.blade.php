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
                        <li class="active-bre"><a href="#">Dashboard</a>
                        </li>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title">
      
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
                                            @forelse($payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->invoice->invoice_number ?? 'N/A' }}</td>
                                                    <td>{{ $payment->shop->name ?? 'N/A' }}</td>
                                                    <td>{{ number_format($payment->invoice->total_amount ?? 0, 2) }}</td>
                                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                                    <td>{{ $payment->invoice->due_date ?? 'N/A' }}</td>
                                                    <td>
                                                        @if(($payment->invoice->total_amount ?? 0) <= $payment->amount)
                                                            <span class="">Paid</span>
                                                        @else
                                                            <span class="">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                     
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7">No payments found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        </table>
                                        <!-- Pagination Links -->
                                       
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('includes.js')
</body>

</html>