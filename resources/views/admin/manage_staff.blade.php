@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Manage Staff</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @include('admin.message')
        <form action="" method="post">
        <div class="row">
          <!-- left column -->
          <div class="col-md-8">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Staff Detail</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Name</label>
                        <input type="text"  name="name" class="form-control" value="@if($edit){{$staff->user->name}}@endif" placeholder="e.g Manoj Singh" required="">
                      </div>
                      <div class="col-md-4">
                        <label>Email</label>
                        <input type="text"  name="email" class="form-control" value="@if($edit){{$staff->user->email}}@endif" required="" placeholder="e.g abc@xyz.com">
                      </div>
                      <div class="col-md-4">
                        
                        <label>Role</label>
                        <select required="" class="select2 form-control" name="role_id" required="">
                          <option value="">Select Role</option>
                          @foreach($roles as $d)
                          <option @if($edit && in_array($d->id, $role_ids)) selected @endif value="{{$d->name}}">{{$d->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Phone</label>
                        <input type="tet" maxlength="10"  name="phone" class="form-control" value="@if($edit){{$staff->phone}}@endif" placeholder="e.g 9876543210" required=""  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode==46 ||event.charCode==127">
                      </div>
                      <div class="col-md-4">
                        <label>Department</label>
                        <select required="" class="select2 form-control" name="department_id" required="">
                          <option value="">Select Department</option>
                          @foreach($departments as $d)
                          <option @if($edit && $staff->department_id==$d->id) selected @endif value="{{$d->id}}">{{$d->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      
                      <div class="col-md-4">
                        <label>Designation</label>
                        <select required="" class="select2 form-control"  name="designation_id" required="">
                          <option value="">Select designation</option>
                          @foreach($designations as $d)
                          <option @if($edit && $staff->designation_id==$d->id) selected @endif value="{{$d->id}}">{{$d->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Password</label>
                        <input type="text"   name="password" class="form-control" value="" placeholder="*****" @if(!$edit) required="" @endif>
                        <input type="hidden"   name="old_password" class="form-control" value="@if($edit){{$staff->user->password}}@endif">
                      </div>                      
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
          </div>
          <div class="col-md-4 d-none1">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Is Teacher?</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-6">
                        <label>Select Class</label>
                        <select class="select2 form-control" multiple="multiple" data-placeholder="Select sections" name="school_classes_id">
                          @foreach($school_classes as $cls)
                          <option  value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      
                      <div class="col-md-6">
                        <label>Select Section</label>
                        <select class="select2 form-control" multiple="multiple" data-placeholder="Select sections" name="sections[]">
                          @foreach($school_classes as $section)
                          <option  value="{{$section->id}}">{{$section->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
        </form>
        <!-- /.row -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Staff List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($records as $d)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$d->user->name}}</td>
                    <td>{{$d->user->email}}</td>
                    <td>{{$d->phone}}</td>
                    <td>@if($d->department){{$d->department->name}}@endif</td>
                    <td>@if($d->designation){{$d->designation->name}}@endif</td>
                    <td>
                      
                      <a onclick="return confirm('Are you sure, you want to delete this?');" href="/admin/staff/?trash={{encrypt($d->user->id)}}"><span class="fa fa-trash"></span></a>
                      &nbsp;&nbsp;&nbsp;
                      <a href="/admin/staff/{{encrypt($d->user->id)}}"><span class="fa fa-edit"></span></a>
                    </td>
                  </tr>
                    @endforeach
                  </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Action</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection
@section('extra_footer')
<!-- Select2 -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });
</script>
@endsection