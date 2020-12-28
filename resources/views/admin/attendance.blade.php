@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Add Attendance</h1>
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
          <div class="col-md-12 ">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Attendance Add</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
                  <form action="" method="get" autocomplete="off">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-2">
                        <label>Academic Session</label>
                        <select class="select2 form-control" name="academic_session_id" required="">
                          @foreach($academic_sessions as $cls)
                          <option  value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>  
                      <div class="col-md-2">
                        <label>For Date</label>
                        <input type="text" name="for_date" id="datepicker" class="form-control" value="@if($search){{$for_date}}@endif" placeholder="Select date" required="">
                      </div>  
                      <div class="col-md-3">
                        <label>Class</label>
                        <select class="select2 form-control" name="school_class_id" required="" onchange="GetSections($(this));">
                          <option value="">Select Class</option>
                          @foreach($school_classes as $cls)
                          <option @if($search && $school_class_id==$cls->id) selected @endif   value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>                      
                      <div class="col-md-2">
                        <label>Section</label>
                        <select class="select2 form-control" name="section_id" id="sections_select" required="">
                          @if($search)
                          <option value="{{$section->id}}">{{$section->name}}</option>
                          @else
                          <option value="">Select Section</option>
                          @endif
                        </select>
                      </div>                 
                      <div class="col-md-2">
                        <label>&nbsp;<br></label><br>
                        <button class="btn btn-primary" name="search" value="Search">Search Students</button>
                      </div>
                    </div>
                  </div>
                  </form>
                </div>
            </div>
          </div>
         
        </div>
        <div class="row">
           @if($search)
          <div class="col-md-12">
            <form action="" method="post" autocomplete="off">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update attendance of students for {{$for_date}}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              
                @csrf
                <div class="card-body" id="repeat-box">
                  <div class="row">
                    <div class="col-md-3 form-group">
                        <select required="" class="form-control" name="status" onchange="ForHoliday($(this))">
                          <option value="">Mark As</option>
                          @foreach($attendance_statuses as $st)
                          <option value="{{$st->id}}">{{$st->name}}</option>
                          @endforeach
                        </select>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12"><input type="checkbox" class="cehckbox_all"> Select All</div>
                  </div>
                  <hr />
                  <div class="row">
                    @php $counter=1; @endphp
                        @foreach($students_without as $d)
                    <div class="col-md-3"><input type="checkbox" name="student[]" value="{{$d->id}}"> {{$d->code}} -  {{$d->name}}</div>
                        @endforeach
                        @foreach($students_with as $d)
                          @php
                            $attn=Helper::fetchAttendanceOnDate($d->id, $for_date);
                          @endphp
                          @if($attn)
                    <div class="col-md-3"><span class="badge badge-{{$attn->attendance_status->css}}">{{$attn->attendance_status->name}}</span> {{$d->code}} - {{$d->name}}</div>
                          @endif
                        @endforeach
                  </div>
                  
                </div>
                <div class="card-footer">
                  <div class="form-group">
                    <div class="row">
                      
                      <div class="col-md-3">
                        <button type="submit" name="submit" value="submit" class="btn btn-primary">Save</button>
                      </div>
                    </div>
                  </div>
                  
                </div>
            </div>
            </form>
          </div>
          @endif
        </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection
@section('extra_footer')
<!-- bs-custom-file-input -->
<!-- Select2 -->
<script>
  $(function () {
    $('.select2').select2();
  });
  $(document).ready(function(){
    $('.cehckbox_all').click(function(){
      ch=$(this).prop('checked');
      if(ch){
        $("input[type='checkbox']").prop('checked', true);
      }
      else{
       $("input[type='checkbox']").prop('checked', false); 
      }
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
  $('#datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
  });
  function ForHoliday(obj){
    console.log(obj.val());
    if(obj.val()==4){
      $("input[type='checkbox']").prop('checked', true);
    }
    else{
      $("input[type='checkbox']").prop('checked', false);
    }
  }
</script>
@endsection