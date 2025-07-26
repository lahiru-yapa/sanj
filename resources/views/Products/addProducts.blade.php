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
                        <li class="active-bre"><a href="#">Product</a>
                        </li>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title">
                                    <h4>Add New Product </h4>
                                </div>
                                <div class="tab-inn">
                                    <form action="{{ route('product.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="row">
                                            <div class="input-field col s4">
                                                <input id="phone" name="name" type="text" class="validate"
                                                    value="{{ old('name') }}">
                                                <label for="phone">Name</label>
                                                @error('phone')
                                                <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="input-field col s4">
                                            <input id="phone" name="description" type="text" class="validate"
                                                    value="{{ old('description') }}">
                                                <label for="phone">Description</label>
                                                @error('phone')
                                                <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                           <div class="input-field col s4">
                                            <input id="code" name="code" type="text" class="validate"
                                                    value="{{ old('code') }}">
                                                <label for="phone">Product Code</label>
                                                @error('phone')
                                                <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s4">
                                                <div class="file-field">
                                                    <div class="btn">
                                                        <span>File</span>
                                                        <input type="file" name="photo" accept="image/*" required>
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text"
                                                            placeholder="Upload Blog Banner">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-field col s3">
                                                <select name="brand">
                                                <option value="" disabled selected>Select Parts</option>
                                                    @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                             <div class="input-field col s3">
                                                <select name="real_brand">
                                                <option value="" disabled selected>Select Brand</option>
                                                    @foreach ($ctegories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                             <div class="input-field col s3">
                                                <select name="bike">
                                                <option value="" disabled selected>Select Bike</option>
                                                    @foreach ($bikes as $bike)
                                                    <option value="{{ $bike->id }}">{{ $bike->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <button type="submit"
                                                    class="waves-effect waves-light btn-large">Submit</button>
                                            </div>
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


    @include('includes.js')
</body>

</html>