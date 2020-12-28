@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Student Action</h1>
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
        <form action="/admin/student-action-save" method="post" autocomplete="off" onsubmit="return confirm('Are you sure you want to save it?');">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"> List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th><input type="checkbox" name="" class="cehckbox_all"></th>
                    <th>Name</th>
                    <th>Class - Section</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($students as $d)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td><input type="checkbox" name="student_id[]" value="{{$d->id}}"></td>
                    <td>{{$d->code}}</td>
                    <td>{{$d->name}}</td>
                    <td>{{$d->admission->school_class->name}} - {{$d->admission->section->name}}</td>
                  </tr>
                    @endforeach
                  </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                    <th><input type="checkbox" name="" class="cehckbox_all"></th>
                    <th>Name</th>
                    <th>Class - Section</th>
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
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Move students</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              
                <div class="card-body d-none1">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <label>Select Action</label>
                        <select class="select2 form-control" required="" name="action">
                          <option value="promote">Promote</option>
                          <option value="repeat">Repeat</option>
                          <option value="tc">TC</option>
                          <option value="terminate">Terminate</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label>Academic Session</label>
                        <select class="select2 form-control" name="academic_session_id" required="">
                          @foreach($academic_sessions as $cls)
                          <option value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label>Class</label>
                        <select class="select2 form-control" name="school_class_id" onchange="GetSections($(this), 2);">
                          <option value="">Select Class</option>
                          @foreach($school_classes as $cls)
                          <option value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label>Section</label>
                        <select class="select2 form-control" name="section_id" id="sections_select2" >
                          <option value="">Select Section</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" name="take_action" value="submit" class="btn form-control btn-primary">Submit</button>
                        @csrf
                      </div>
                    </div>
                  </div>
                </div>
                
              
            </div>
          </div>
        </div>
        <br clear="all">
        </form>
        @endif
        
      </div><!-- /.container-fluid -->
    </section>
@endsection
@section('extra_footer')
<!-- Select2 -->
<script>
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
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2();
  });
  function GetSections(obj, no){
    if(no)
      var resp='#sections_select'+no;
    else
      var resp='#sections_select';
    $.get("/admin/ajax/sections/" + obj.val(), function(data){
      str='<option value="">Select Section</option>';
      for (var key in data) {
        if (data.hasOwnProperty(key)) {
            //console.log(key + " -> " + data[key]);
            str+='<option value="'+key+'">'+data[key]+'</option>';
        }
      }
      $(resp).html(str);
    });
  }
</script>
@endsection