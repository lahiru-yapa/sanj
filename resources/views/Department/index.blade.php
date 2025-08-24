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
                        <li class="active-bre"><a href="#">Sub Department</a>
                        </li>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title">

                                </div>

                               <div class="inn-title grid-container">
    <h4 class="mb-0">Sub Department Details</h4>
    <a href="{{ route('sub-department.create') }}" class="btn btn-primary">Add Sub Department</a>
</div>

                                <style>
                                .grid-container {
                                    display: grid;
                                    grid-template-columns: auto max-content;
                                    /* Title takes available space, button adjusts */
                                    align-items: center;
                                    /* Vertically aligns elements */
                                    gap: 10px;
                                    /* Adds spacing between elements */
                                    padding: 10px;
                                    border: 1px solid #ddd;
                                    background-color: #f8f9fa;
                                    margin-bottom: 10px;
                                    /* Adds spacing between sections */
                                }
                                </style>
                                <div class="tab-inn">
                                    <div class="table-responsive table-desi">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>name</th>
                                                    <th>Department</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($department as $department)
                                                <tr>
                                                    <td>{{ $department->name }}
                                                    <td>{{ $department->category->name ?? 'No Category' }}</td>
                                                    </td>
<td>
    <form action="{{ route('sub-department.destroy', $department->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" style="border: none; background: none; color: red;">
            <i class="fa fa-trash" aria-hidden="true"></i>
        </button>
    </form>
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