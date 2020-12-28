@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"> Add SMS</h1>
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
          <div class="col-md-4 ">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Students</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <form action="" method="get" autocomplete="off">
                <div class="card-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-6">
                        <label>Class</label>
                        <select class="select2 form-control" name="school_class_id" required="" onchange="GetSections($(this));">
                          <option value="">Select Class</option>
                          @foreach($school_classes as $cls)
                          <option @if($cls->id==$school_class_id) selected @endif value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>                      
                      <div class="col-md-6">
                        <label>Section</label>
                        <select class="select2 form-control" name="section_id" id="sections_select" >
                          @if($section_id)
                          <option value="{{$section->id}}" selected="">{{$section->name}}</option>
                          @endif
                          <option value="">Select Section</option>
                        </select>
                      </div>                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-6">
                        <button type="submit" name="search_btn" value="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </div>
                  </div>
                </div>
                </form>
            </div>
          </div>
          @if($edit)
          <div class="col-md-8">
            <form action="" method="post">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Students</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                @csrf
                <div class="card-body" id="repeat-box">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th class="text-center"><input type="checkbox" class="cehckbox_all"></th>
                      <th>Code</th>
                      <th>Name</th>
                    </tr>
                    </thead>
                    <tbody>
                      @foreach($students as $d)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td class="text-center"><input type="checkbox" name="student[]" value="{{$d->id}}"></td>
                      <td>{{$d->code}}</td>
                      <td>{{$d->name}}</td>
                    </tr>
                      @endforeach
                      <tr>
                        <td colspan="4">
                          <textarea class="form-control" required="" onkeyup="countCharacters($(this))" onblur="countCharacters($(this))" name="content" id="textarea-msg" placeholder="Max 160 characters"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2"><div id="charNum"></div></td>
                        <td colspan="2">
                          <code>160 characters would be counted as 1 SMS </code>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer">
                  <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            </form>
          </div>
          @endif
        </div>
        
        </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection
@section('extra_footer')
<!-- bs-custom-file-input -->
<!-- Select2 -->
<script>
  var typingTimer;                //timer identifier
  var doneTypingInterval = 1000;  //time in ms (1 seconds)
  function countCharacters(obj){
    v=obj.val();
    clearTimeout(typingTimer);
    if(v){
      typingTimer = setTimeout(doneTyping, doneTypingInterval);
    }
    
  }
  function doneTyping () {
    v=$("#textarea-msg").val();
    $.get("/admin/ajax/count-chars/?str=" + v, function(data){
      $("#charNum").html(data + " Characters");
    });
  }
  function countChar(val) {
    var len = val.value.length;
    if (len > 160) {
      val.value = val.value.substring(0, 160  );
    } else {
      $('#charNum').text("Characters remaining: " + (160 - len));
    }
  };
  $(function () {
    $('.select2').select2()
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
</script>
@endsection