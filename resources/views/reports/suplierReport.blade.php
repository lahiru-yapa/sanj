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
                
            </div>


 <div class="sb2-2">
     <h2 class="text-center">Suplier Reports</h2>

    <div class="row">
    <!-- Sales Chart -->
    <div class="col-md-12" style="margin-bottom: 100px !important;">
            <div class="box-inn-sp">
                <div class="inn-title">
                    <div class="container d-flex justify-content-center align-items-center flex-column">
                       
<table class="table table-bordered">
    <thead>
        <tr>
            <th>GRN Number</th>
            <th>Supplier</th>
            <th>Received Date</th>
            <th>Warehouse</th>
            <th>GRN Total Amount</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($grns as $grn)
            <tr>
                <td>{{ $grn->grn_number }}</td>
                <td>{{ $grn->supplier_name }}</td>
                <td>{{ $grn->received_date }}</td>
                <td>{{ $grn->warehouse_id }}</td>
                <td>Rs. {{ number_format($grn->total_amount, 2) }}</td> <!-- Display GRN Total Amount -->
                <td>{{ $grn->remarks }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No GRNs found</td>
            </tr>
        @endforelse
    </tbody>
</table>


  
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