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
                        <li class="active-bre"><a href="#">Add New Transfer</a>
                        </li>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                            <div class="inn-title grid-container">
    <h4 class="mb-0">Product Details</h4>
    <a href="{{ route('warehouse-transfer.create') }}" class="btn btn-primary">Add New Transfer</a>
</div>
<style>
    .grid-container {
    display: grid;
    grid-template-columns: auto max-content; /* Title takes available space, button adjusts */
    align-items: center; /* Vertically aligns elements */
    gap: 10px; /* Adds spacing between elements */
    padding: 10px;
    border: 1px solid #ddd;
    background-color: #f8f9fa;
}

</style>


                                <div class="tab-inn">
                                    <div class="table-responsive table-desi">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>From Warehouse</th>
                                                    <th>To Warehouse</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                             <tbody>
                                                @foreach ($transfers as $transfer)
                                                    <tr>
                                                        <td>{{ $warehouses[$transfer->from_warehouse_id] ?? 'N/A' }}</td>
                                                        <td>{{ $warehouses[$transfer->to_warehouse_id] ?? 'N/A' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($transfer->created_at)->format('Y-m-d') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
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