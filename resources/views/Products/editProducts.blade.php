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
                                    <h4>Edit Product Details</h4>
                                </div>
                                <div class="tab-inn">
                                    <form action="{{ route('product.editProduct') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <input type="hidden" name="Product_id" value="{{ $product->id }}">
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input id="name" name="name" type="text" class="validate"
                                                    value="{{ $product->name }}">
                                                <label for="name">products Name</label>
                                                @error('name')
                                                <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="input-field col s6">
                                                <input id="sku" name="sku" type="text" class="validate"
                                                    value="{{ $product->sku }}" readonly>
                                                <label for="phone">sku</label>
                                                @error('phone')
                                                <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s3">
                                                <input id="description" name="description" type="text" class="validate"
                                                    value="{{ $product->description }}">
                                                <label for="description">Description</label>
                                                @error('city')
                                                <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="input-field col s3">
                                                <input id="low_stock" name="description" type="text" class="validate"
                                                    value="{{ $product->low_stock }}">
                                                <label for="description">Low Stock</label>
                                                @error('city')
                                                <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                          
                                          <div class="input-field col s3">
    <select name="department">
        <option value="">Department</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}" 
                {{ (int)$brand->id === (int)$product->category_id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="input-field col s3">
    <select name="subdepartment">
        <option value="">Sub Department</option>
        @foreach($departments as $department)
            <option value="{{ $department->id }}" 
                {{ (int)$department->id === (int)$product->department_id ? 'selected' : '' }}>
                {{ $department->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="input-field col s3">
    <select name="category">
        <option value="">Category</option>
        @foreach($ctegories as $category)
            <option value="{{ $category->id }}" 
                {{ (int)$category->id === (int)$product->real_category_id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>  


<div class="input-field col s3">
    <select name="subCategory">
        <option value="">Sub Category</option>
        @foreach($bikes as $bike)
            <option value="{{ $bike->id }}" 
                {{ (int)$bike->id === (int)$product->bikes_id ? 'selected' : '' }}>
                {{ $bike->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="input-field col s3">
    <select name="rack_name">
        <option value="">Rack</option>
      
        @foreach($rackDetail as $rack)
            <option value="{{ $rack->id }}" 
                {{ (int)$rack->id === (int)$product->rack_name ? 'selected' : '' }}>
                {{ $rack->rack_name  }}
            </option>
        @endforeach
    </select>
</div>


                                        </div>
                                        

                                        <div class="row">
                                            <div class="input-field col s6">
                                                <label for="photo">Product Image</label><br>

                                                <!-- Display Existing Image -->
                                                @if($product->photo)
                                                <div style="margin-bottom: 10px;">
                                                    <img id="photo-preview" name="photo"
                                                        <img src="{{ asset('storage/'. $product->photo) }}" alt="Product Image"
                                                        style="max-width: 150px; max-height: 150px;">
                                                       

                                                    <!-- Hidden Field to Pass Existing Image -->
                                                    <input type="hidden" typ="file" name="photo"
                                                        value="{{ $product->photo }}">
                                                </div>
                                                @else
                                                <p>No image uploaded</p>
                                                @endif

                                                <!-- Input Field for New Image Upload -->
                                                <input id="photo" name="photo" type="file" class="validate"
                                                    onchange="previewImage(event)">

                                                <!-- Validation Error -->
                                                @error('photo')
                                                <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <button type="submit"
                                                    class="waves-effect waves-light btn-large">Submit</button>
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

    @include('includes.js')
    <script>
    // Preview the New Image Before Upload
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('photo-preview');
            output.src = reader.result; // Update the preview with the new image
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    </script>
</body>

</html>