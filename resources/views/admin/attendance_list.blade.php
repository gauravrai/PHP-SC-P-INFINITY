@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Attendance</h1>
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
              <form action="/admin/attendance-insight" method="get" autocomplete="off" target="_blank">
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
                      <div class="col-md-2">
                        <label>Year</label>
                        <select class="select2 form-control" name="year" required="">
                          <option value="">Select Year</option>
                          @for($y=2019; $y<=date('Y'); $y++)
                          <option @if($year==$y) selected @endif value="{{$y}}">{{$y}}</option>
                          @endfor
                        </select>
                      </div>
                      <div class="col-md-2 ">
                        <label>Month</label>
                        <select class="select2 form-control" name="month" required="">
                          <option value="">Select Month</option>
                          @for($m=1; $m<=12; $m++)
                          <option @if($month==$m) selected @endif value="{{$m}}">{{$m}}</option>
                          @endfor
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label>Class</label>
                        <select class="select2 form-control" name="school_class_id" onchange="GetSections($(this));" required="">
                          <option value="">Select Class</option>
                          @foreach($school_classes as $cls)
                          <option @if($school_class_id==$cls->id) selected @endif value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label>Section</label>
                        <select class="select2 form-control" name="section_id" id="sections_select" required="" >
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
                <h3 class="card-title">Attendance Details</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Code - Name</th>
                    @for($m=1;$m<=date('t', strtotime($date)); $m++)
                    <th>{{$m}}</th>
                    @endfor
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($students as $d)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$d->name}} - {{$d->code}}</td>
                    @for($m=1;$m<=date('t', strtotime($date)); $m++)
                      @php
                        $attn=Helper::fetchAttendanceOnDate($d->id, $m . "-" . $month . "-" . $year);
                      @endphp
                    <td>
                       @if(isset($attn)  && $attn)
                      <span class="badge badge-{{$attn->attendance_status->css}}">{{$attn->attendance_status->initials}}</span>
                        @endif
                    </td>  
                    @endfor                  
                  </tr>
                    @endforeach
                  </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                    <th>Code - Name</th>
                    @for($m=1;$m<=date('t', strtotime($date)); $m++)
                    <th>{{$m}}</th>
                    @endfor
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