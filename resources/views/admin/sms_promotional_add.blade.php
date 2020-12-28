@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"> Add Promotional SMS</h1>
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
            <form action="" method="post">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">SMS</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                @csrf
                <div class="card-body" id="repeat-box">
                  <table id="example2" class="table table-bordered table-hover">
                      <tr>
                        <td width="30%">
                            Contact Numbers
                        </td>
                        <td>
                          <textarea class="form-control" required="" name="contact_numbers" placeholder="Numbers with enter or new line"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          Content
                        </td>
                        <td>
                          <textarea class="form-control" required="" onkeyup="countCharacters($(this))" onblur="countCharacters($(this))" name="content" id="textarea-msg" placeholder="Max 160 characters"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td><div id="charNum"></div></td>
                        <td>
                          <code>160 characters would be counted as 1 SMS </code>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer">
                  <button type="submit" name="submit" onclick="return confirm('Are you sure you want to send this SMS');" value="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            </form>
          </div>
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
 
  
</script>
@endsection