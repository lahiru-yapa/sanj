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
                        <li class="active-bre"><a href="#"> User</a>
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
    <h4 class="mb-0">User Details</h4>
    <a href="{{ route('adduser') }}" class="btn btn-primary">Add New User</a>
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
                                                    <th>name</th>
                                                    <th>address</th>
                                                    <th>Email</th>
                                                    <th>role</th>
                                                    <th>credit_limit</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($users as $user)
                                                <tr>
                                                    <td>{{ $user->name }}
                                                    </td>
                                                    <td>{{ $user->address }}
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->role }}</td>
                                                    <td>{{ $user->credit_limit }}</td>
        
                                                    <td>
                                                    <a href="{{ route('users.edit', $user->id) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('users.delete',$user->id) }}"><i class="fas fa-trash"></i>
</a>
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