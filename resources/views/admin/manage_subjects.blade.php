@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Manage Subjects</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div>
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
              <form action="" method="post" autocomplete="off">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-6">
                        <label>Name</label>
                        <input type="text"  name="name" class="form-control" value="@if($edit){{$subject->name}}@endif" placeholder="e.g Science">
                      </div>
                      <div class="col-md-6">
                        <label>Class</label>
                        <select class="select2 form-control" name="school_class_id[]" multiple="" required="" data-placeholder="Select Class">
                          @foreach($school_classes as $cls)
                          <option @if($edit) selected @endif value="{{$cls->id}}">{{$cls->name}}</option>
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
                <h3 class="card-title">Classes List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Teachers</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($subjects as $d)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$d->name}}</td>
                    <td> 
                      <a href="javascript:void(0)" data-toggle="modal" class="btn btn-info"  data-target="#modal-default{{$d->id}}">
                      {{$d->teachers->count()}}
                      </a>
                      <div class="modal fade" id="modal-default{{$d->id}}">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Teachers</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-3">
                                    <label>Teachers</label>
                                    <select required="" class="form-control" name="frequency[]" multiple="" required="" data-placeholder="Select Teachers">
                                      <option value="">Select Frequency</option>
                                      @foreach($frequency as $d)
                                      <option value="{{$d}}">{{$d}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                      </div>
                    </td>
                    <td>
                      <a onclick="return confirm('Are you sure, you want to delete this?');" href="/admin/subjects/?trash={{encrypt($d->id)}}"><span class="fa fa-trash"></span></a>
                      &nbsp;&nbsp;&nbsp;
                      <a href="/admin/subjects/{{encrypt($d->id)}}"><span class="fa fa-edit"></span></a>
                    </td>
                  </tr>
                    @endforeach
                  </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                    <th>Name</th>
                    <th>Teachers</th>
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