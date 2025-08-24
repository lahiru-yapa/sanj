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
                        <li class="active-bre"><a href="#">Rack</a>
                        </li>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title">
                                    <h4>Add Rack</h4>
                                </div>
                                <div class="tab-inn">
                                    <form action="{{ route('rack.store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="input-field col s4">
                                                <input name="name" type="text" class="validate">
                                                <label for="name">Name</label>
                                            </div>
                                            <div class="input-field col s4">
                                                <input name="rackCode" type="text" class="validate">
                                                <label for="name">Rack Code</label>
                                            </div>
                                            <div class="input-field col s4">
                                                <select name="warehouse_id" id="department_id" class="browser-default" required>
                                                    <option value="">-- Select Warehouse --</option>
                                                    @foreach($warehouse as $dept)
                                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('department_id')
                                                    <span class="red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="input-field col s6">
                                                <input name="row_number" type="text" class="validate">
                                                <label for="name">row_number(optional)</label>
                                            </div>
                                            <div class="input-field col s6">
                                                <input name="column_number" type="text" class="validate">
                                                <label for="name">column_number(optional)</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
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

    <!--== BOTTOM FLOAT ICON ==-->


    @include('includes.js')
</body>

</html>