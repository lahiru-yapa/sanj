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
                        <li class="active-bre"><a href="#">All-Brands</a>
                        </li>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title grid-container">
                                <h4>Shop</h4>
    <a href="{{ route('add-brand') }}" class="btn btn-primary">Add New Brand</a>
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
    margin-bottom: 10px; /* Adds spacing between sections */
}

</style>
                                <div class="tab-inn">
                                    <div class="table-responsive table-desi">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Code</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($brands as $brand)
                                                <tr>
                                                   
                                                <td>{{$brand->name}}</td>
                                                    <td>{{$brand->description}}</td>
                                                    <td>{{$brand->sku}}</td>
                                                  
                                                    <td>
                                                     <a href="{{ route('brand.delete',$brand->id) }}"><i class="fas fa-trash"></i>
                                                    </td>
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