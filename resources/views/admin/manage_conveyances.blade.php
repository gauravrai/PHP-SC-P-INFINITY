@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Manage Conveyances</h1>
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
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Form</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="" method="post">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-5">
                        <label>Name</label>
                        <input type="text"  name="name" class="form-control" value="@if($edit){{$conveyance->name}}@endif" placeholder="e.g Bus">
                      </div>
                      <div class="col-md-3">
                        <label>Fee per month</label>
                        <input type="number"  name="fee" class="form-control" value="@if($edit){{$conveyance->fee}}@endif" placeholder="e.g 200">
                      </div>
                      <div class="col-md-4">
                        <label>Routes</label>
                        <select required="" class="select2 form-control" multiple="multiple" data-placeholder="Select routes" name="routes[]">
                          @foreach($routes as $route)
                          <option @if($edit && in_array($route->id, $conveyance->routes->pluck('id')->toArray())) selected @endif value="{{$route->id}}">{{$route->name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Conveyances List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Fee</th>
                    <th>Routes</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($conveyances as $d)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$d->name}}</td>
                    <td>{{$d->fee}}</td>
                    <td>@if($d->routes->count()){{implode(",", $d->routes->pluck('name')->toArray())}}@endif</td>
                    <td>
                      <a onclick="return confirm('Are you sure, you want to delete this?');" href="/admin/transport/conveyances/?trash={{encrypt($d->id)}}"><span class="fa fa-trash"></span></a>
                      &nbsp;&nbsp;&nbsp;
                      <a href="/admin/transport/conveyances/{{encrypt($d->transport_id)}}?id={{encrypt($d->id)}}"><span class="fa fa-edit"></span></a>
                    </td>
                  </tr>
                    @endforeach
                  </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                   <th>Name</th>
                    <th>Fee</th>
                    <th>Routes</th>
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