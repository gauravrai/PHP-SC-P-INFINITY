@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Add Homework</h1>
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
          <div class="col-md-10 ">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Homework Add</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
                  <form action="" method="post" autocomplete="off">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <label>Academic Session</label>
                        <select class="select2 form-control" name="academic_session_id" required="">
                          @foreach($academic_sessions as $cls)
                          <option @if($edit && $work->academic_session_id==$cls->id) selected @endif  value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>  
                      <div class="col-md-3">
                        @csrf
                        <label>For Date</label>
                        <input type="text" name="for_date" id="datepicker" class="form-control" value="@if($edit){{$for_date}}@endif" placeholder="Select date" required="">
                      </div>  
                      <div class="col-md-3">
                        <label>Class</label>
                        <select class="select2 form-control" name="school_class_id" required="" onchange="GetSections($(this));">
                          <option value="">Select Class</option>
                          @foreach($school_classes as $cls)
                          <option @if($edit && $work->school_class_id==$cls->id) selected @endif   value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>                      
                      <div class="col-md-3">
                        <label>Section</label>
                        <select class="select2 form-control" name="section_id" id="sections_select" required="">
                          @if($edit)
                          <option value="{{$section->id}}">{{$section->name}}</option>
                          @else
                          <option value="">Select Section</option>
                          @endif
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">
                        <label>Homework</label>
                        <textarea class="form-control" name="description" required="">@if($edit){{$work->description}}@endif</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">
                        @if($edit)
                        <input type="hidden" value="1" name="update">
                        <button class="btn btn-primary" name="search" value="Search">Update Homework</button>
                        @else
                        <input type="hidden" value="1" name="submit">
                        <button class="btn btn-primary" name="search" value="Search">Add Homework</button>
                        @endif
                        
                        
                      </div>
                    </div>
                  </div>
                  </form>
                </div>
            </div>
          </div>
         
        </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection
@section('extra_footer')
<!-- Select2 -->
<script>
  $(function () {
    $('.select2').select2();
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
</script>
@endsection