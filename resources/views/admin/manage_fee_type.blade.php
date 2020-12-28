@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Manage Fee Type</h1>
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
          <div class="col-md-6 ">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Fee Structure Details</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
                  <div class="form-group">
                    <div class="row">                      
                      <div class="col-md-12">
                        <label>Fee Structure Name</label>
                        <input type="text"  name="name" class="form-control" value="@if($edit){{$fee->name}}@endif" placeholder="e.g Tution Fee"  required="">
                      </div> 
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12 form-check">
                          <label><input type="checkbox" @if($edit && $fee->has_concession) checked @endif name="has_concession" value="1"> Has Concession?</label>
                      </div>                     
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12 form-check">
                          <label class="text-danger"><i class="fas fa-info-circle"></i></label> <span class="text-danger">Do not add transport fee here. To manage trasnport fee, <a href="/admin/transport/transporters" target="_blank">click here</a></span>
                      </div>                     
                    </div>
                  </div>
                  <div class="card-footer">
                    @csrf
                    <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                    <a href="/admin/fee-type/" class="btn btn-warning">Add New</a>
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
                <h3 class="card-title">Fee Type</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Concession</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($records as $d)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$d->name}}</td>
                    <td>{{($d->has_concession)?"Yes":"No"}}</td>
                    <td>
                      @if($d->fee_type=='academic')
                      <a onclick="return confirm('Are you sure, you want to delete this?');" href="/admin/fee-type/?trash={{encrypt($d->id)}}"><span class="fa fa-trash"></span></a>
                      &nbsp;&nbsp;&nbsp;
                      <a href="/admin/fee-type/{{encrypt($d->id)}}"><span class="fa fa-edit"></span></a>
                      @else
                      <span class="text-danger">NA</span>
                      @endif
                    </td>
                  </tr>
                    @endforeach
                  </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Concession</th>
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
  $('#datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
  });
</script>
@endsection