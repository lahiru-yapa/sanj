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
                <!--== USER INFO ==-->
                <div class="sb2-12">
                    <ul>
                        <li><img src="images/placeholder.jpg" alt="">
                        </li>
                        <li>
                            <h5>Sanjeewa Motors <span> Godakawela</span></h5>
                        </li>
                        <li></li>
                    </ul>
                </div>
                <!--== LEFT MENU ==-->
                @include('includes.sidebar')
            </div>
            <div class="sb2-2">
                <div class="sb2-2-2">
                    <ul>
                        <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                        </li>
                        <li class="active-bre"><a href="#"> Ui Form</a>
                        </li>
                    </ul>
                </div>

                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title">
                                    <h4>View Supplier Details</h4>
                                </div>
                                <div class="tab-inn">
                                <form action="{{ route('product.editProduct') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <input type="hidden" name="Product_id" value="{{ $product->id }}">
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input id="name" name="name" type="text" class="validate"
                                                    value="{{ $product->name }}" readonly>
                                                <label for="name">products Name</label>
                                                @error('name')
                                                <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="input-field col s6">
                                                <input id="category" name="category" type="text" class="validate" 
       value="{{ $product->category->name ?? 'N/A' }}" readonly>

                                                <label for="phone">sku</label>
                                                @error('phone')
                                                <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>  
                                        <div class="row">
                                            <div class="input-field col s6">
                                               <input id="category" name="category" type="text" class="validate" 
       value="{{ $product->categorys->name ?? 'N/A' }}" >

                                                <label for="description">Description</label>
                                                @error('city')
                                                <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
 <div class="input-field col s6">
                                                <input id="category" name="category" type="text" class="validate"
                                                    value="{{ $product->category->name }}" readonly>
                                                <label for="category">Category</label>
                                                @error('category')
                                                <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                           
                                        </div>
                                     
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <label for="photo">Product Image</label><br>

                                                <!-- Display Existing Image -->
                                                @if($product->photo)
                                                <div style="margin-bottom: 10px;">
                                                    <img id="photo-preview" name="photo"
                                                        src="{{ asset('storage/' . $product->photo) }}"
                                                        alt="Product Image"
                                                        style="max-width: 150px; max-height: 150px;">
                                                    <!-- Hidden Field to Pass Existing Image -->
                                                   
                                                </div>
                                                @else
                                                <p>No image uploaded</p>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="row">
                                           
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

    @include('includes.js')
</body>

</html>