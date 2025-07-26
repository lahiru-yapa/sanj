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
                        <li class="active-bre"><a href="#"> All Returns</a>
                        </li>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title">
                                    <h4>Returns Details</h4>
                                 
                                    <!-- Dropdown Structure -->
  
                                </div>
                                <div class="tab-inn">
                                    <div class="table-responsive table-desi">
                                       
                                       <table class="table table-bordered">
    <thead>
        <tr>
            <th>Invoice Number</th>
            <th>Product Name</th>
            <th>Shop Name</th>
            <th>Salable Status</th>
            <th>Edit</th>
            <th>View</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($returnedProducts as $return)
            @foreach ($return->returnItems as $item)
                <tr>
                    <td>{{ $return->invoice->invoice_number ?? 'N/A' }}</td>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $return->shop->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($item->salable_status) }}</td>
                       <td>
                           <a href="{{ route('productReturn.edit', ['id' => $return->id, 'product_id' => $item->product_id]) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                </tr>
            @endforeach
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