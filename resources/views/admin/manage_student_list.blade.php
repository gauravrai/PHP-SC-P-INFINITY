@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Student List</h1>
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
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Search</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="" method="get" autocomplete="off">
                <div class="card-body d-none1">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-2">
                        <label>Academic Session</label>
                        <select class="select2 form-control" name="academic_session_id" required="">
                          @foreach($academic_sessions as $cls)
                          <option @if($academic_session_id==$cls->id) selected @endif  value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2 d-none">
                        <label>Student Code</label>
                        <input type="text" name="code" class="form-control" value="" placeholder="2019010000009">
                      </div>
                      <div class="col-md-2 d-none">
                        <label>Name</label>
                        <input type="text" name="student_name" class="form-control" value="" placeholder="e.g Arun Khare" >
                      </div>
                      <div class="col-md-2">
                        <label>Class</label>
                        <select class="select2 form-control" name="school_class_id" onchange="GetSections($(this));">
                          <option value="">Select Class</option>
                          @foreach($school_classes as $cls)
                          <option @if($school_class_id==$cls->id) selected @endif value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label>Section</label>
                        <select class="select2 form-control" name="section_id" id="sections_select" >
                          <option value="">Select Section</option>
                          @foreach($sections as $s)
                          <option @if($section_id==$s->id) selected @endif value="{{$s->id}}">{{$s->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" name="submit" value="submit" class="btn form-control btn-primary">Search</button>
                      </div>
                    </div>
                  </div>
                </div>
                
              </form>
            </div>
          </div>
        </div>
        @if($search)
        <!-- /.row -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Student List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Class - Section</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($students as $d)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$d->code}}</td>
                    <td>{{$d->name}}</td>
                    <td>{{$d->admission->school_class->name}} - {{$d->admission->section->name}}</td>
                    <td>
                      @hasanyrole('Super Admin|Admin')
                      <a onclick="return confirm('Are you sure, you want to delete this?');" href="/admin/transport/routes/?trash={{encrypt($d->id)}}"><span class="fa fa-trash"></span></a>
                      &nbsp;&nbsp;&nbsp;
                      @endhasanyrole
                      <a href="/admin/student-fee/{{encrypt($d->id)}}"><i class="fas fa-rupee-sign"></i></a>
                      <a href="/admin/student-view/{{encrypt($d->id)}}" class="d-none"><span class="fa fa-eye"></span></a>
                      &nbsp;&nbsp;&nbsp;
                      <a href="/admin/manage-student/{{encrypt($d->id)}}"><span class="fa fa-edit"></span></a>
                    </td>
                  </tr>
                    @endforeach
                  </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Class - Section</th>
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
        @endif
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
  function GetSections(obj){
    $.get("/admin/ajax/sections/" + obj.val(), function(data){
      str='<option value="">Select Section</option>';
      for (var key in data) {
        if (data.hasOwnProperty(key)) {
            //console.log(key + " -> " + data[key]);
            str+='<option value="'+key+'">'+data[key]+'</option>';
        }
      }
      $("#sections_select").html(str);
    });
  }
</script>
@endsection