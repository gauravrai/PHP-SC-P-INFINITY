@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">@if($edit) Update @else Add @endif Student</h1>
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
        <form action="" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="row">
          <!-- left column -->
          <div class="col-md-5 ">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Student Details</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-6">
                        <label>Academic Session</label>
                        <select class="select2 form-control" name="academic_session_id" required="">
                          @foreach($academic_sessions as $cls)
                          <option  value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>  

                      <div class="col-md-6">
                        <label>Student Code</label>
                        <input type="text" readonly="" disabled="" class="form-control" value="@if($edit){{$student->code}} @else {{$code}} @endif" >
                      </div>                   
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Caste</label>
                        <select class="select2 form-control" name="caste_id" required="">
                          @foreach($castes as $c)
                          <option @if($edit && $c->id==$student->caste_id) selected @endif value="{{$c->id}}">{{$c->name}}</option>
                          @endforeach
                        </select>
                      </div>  
                      <div class="col-md-8">
                        <label>Name</label>
                        <input type="text" name="student_name" class="form-control" value="@if($edit){{$student->name}}@endif" placeholder="e.g Arun Khare" required="">
                      </div>                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-6">
                        <label>Class</label>
                        <select class="select2 form-control" name="school_class_id" required="" onchange="GetSections($(this));">
                          <option value="">Select Class</option>
                          @foreach($school_classes as $cls)
                          <option @if($edit && $cls->id==$student->admission->school_class_id) selected @endif value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>                      
                      <div class="col-md-6">
                        <label>Section</label>
                        <select class="select2 form-control" name="section_id" id="sections_select" required="">
                          @if($edit)
                          <option value="{{$student->admission->section_id}}" selected="">{{$student->admission->section->name}}</option>
                          @endif
                          <option value="">Select Section</option>
                        </select>
                      </div>                      
                    </div>
                  </div>

                  <div class="form-group" id="">
                    <div class="row">
                      <div class="col-md-12">
                        <label>Select Fee Structure</label>
                      </div>
                    </div>
                    @foreach($fees as $fee)
                    <div class="row">
                      <div class="col-md-12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="fee_id" @if($edit && $student->admission->fee_id==$fee->id) checked @endif class="form-check-input" id="fee_id{{$fee->id}}" value="{{$fee->id}}" required="">
                        <label class="form-check-label" for="fee_id{{$fee->id}}">{{$fee->name}}</label>
                      </div>
                    </div>
                    @endforeach
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-6">
                        <label>Aadhaar</label>
                        <input type="text" name="aadhar" class="form-control" value="@if($edit){{$student->aadhaar}}@endif" placeholder="98999999999" required="" maxlength="12" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode==46 ||event.charCode==127">
                      </div>                      
                      <div class="col-md-6">
                        <label>Board Roll Number</label>
                        <input type="text" name="board_code" class="form-control" value="@if($edit){{$student->board_code}}@endif" placeholder="2018002">
                      </div>                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-6">
                        <label>Profile Picture</label>
                        <input type="file" name="profile" class="form-control" >
                      </div> 
                      @if($edit)
                      <div class="col-sm-6"> 
                        <img src="{{Helper::getProfilePictureForForm($student->profile)}}" height="100" />
                      </div> 
                      @endif                 
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">
                        <label>DOB</label>
                        <input readonly="" type="text" name="dob" id="datepicker" class="form-control" value="@if($edit){{Helper::formatDate($student->dob)}}@endif" placeholder="Date of birth" required="">
                      </div>                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-6 form-check">&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="gender" @if($edit && $student->gender=='male') checked @endif class="form-check-input" id="gender_male" value="male" required="">
                          <label class="form-check-label" for="gender_male">Male</label>
                      </div>                      
                      <div class="col-md-6 form-check">
                          <input type="radio" name="gender" @if($edit && $student->gender=='female') checked @endif class="form-check-input" id="gender_female" value="female" required="">
                          <label class="form-check-label" for="gender_female">Female</label>
                      </div>                      
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-5 form-check">&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="checkbox" onclick="toggleTransportForm($(this));" name="checkbox" @if($edit && $student->conveyance->count()) checked @endif class="form-check-input" id="transport-check" value="1">
                          <label class="form-check-label" for="transport-check">Has Transport?</label>
                      </div>                      
                      <div class="col-md-7 form-check">
                          <div id="tranport-div" style="@if($edit && $student->conveyance->count()) display: block; @else display: none; @endif">
                            <div class="col-md-12">
                              <select class="form-control" name="conveyance_id">
                                <option value="">Select Route</option>
                                @if($routes->count())
                                @foreach($routes as $r)
                                  @foreach($r->conveyances as $c)
                                  <option @if($edit && $student->conveyance->count() && $student->conveyance[0]->id==$c->id)selected=""@endif value="{{$c->id}}">{{$r->name}} - {{$c->name}} @ {{Helper::formatMoney($c->fee)}}/mo</option>
                                  @endforeach
                                @endforeach
                                @endif
                              </select>
                            </div>
                          </div>
                      </div>                      
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-5 form-check">&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="checkbox" onclick="toggleConcessionForm($(this));" name="has_concession" @if($edit && $student->fee_concession) checked @endif class="form-check-input" id="concession-check" value="1">
                          <label class="form-check-label" for="concession-check">Has Concession?</label>
                      </div>                      
                      <div class="col-md-7 form-check">
                          <div id="concession-div" style="@if($edit && $student->fee_concession) display: block; @else display: none; @endif" class="row">
                            <div class="col-md-6">
                              <input  id="" type="number" value="@if($edit && $student->fee_concession){{$student->fee_concession->amount}}@endif" class="form-control" name="amount" max="20" min="0" >
                            </div>
                            <div class="col-md-2">%</div>
                          </div>
                      </div>                      
                    </div>
                  </div>
                </div>
            </div>
          </div>
          <div class="col-md-7">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Parents/Guardian Details</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                @csrf
                <div class="card-body" id="repeat-box">
                  @foreach($parent_relationships as $pr)
                    @php
                      if($edit){
                        $spa=Helper::fetchParent($student->id, $pr->id);
                      }
                    @endphp
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        <label>{{$pr->name}}'s Name</label>
                        <input type="text" name="name[]" class="form-control" value="@if($edit && $spa){{$spa->name}}@endif" placeholder="XYZ Khare" required="">
                      </div>
                      <div class="col-md-4">
                        <label>{{$pr->name}}'s Email</label>
                        <input type="email" name="email[]" class="form-control" value="@if($edit && $spa){{$spa->email}}@endif" placeholder="pqr@xyz.com">
                      </div>
                      <div class="col-md-4">
                        <label>{{$pr->name}}'s Phone</label>
                        <input type="text" name="phone[]" class="form-control" value="@if($edit && $spa){{$spa->phone}}@endif"  maxlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode==46 ||event.charCode==127" placeholder="9090909090" @if($pr->isrequired)required=""@endif  >
                        <input type="hidden" name="parent_relationship_id[]" class="form-control" required="" value="{{$pr->id}}">
                        @if($edit)
                        <input type="hidden" name="parent_id[]" class="form-control" required="" value="{{$spa->id}}">
                        @endif
                      </div>
                    </div>
                  </div>
                  @endforeach
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">
                        <label>Address Line 1</label>
                        <input type="text" name="address_line_1" class="form-control" value="@if($edit){{$student->address_line_1}}@endif" placeholder="102/369 Viram Khand" required="">
                      </div>                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">
                        <label>Address Line 2</label>
                        <input type="text" name="address_line_2" class="form-control" value="@if($edit){{$student->address_line_2}}@endif" placeholder="Gomtinagar, Lucknow" required="">
                      </div>                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">
                        <label>Address Line 3</label>
                        <input type="text" name="address_line_3" class="form-control" value="@if($edit){{$student->address_line_3}}@endif" placeholder="Uttarpradesh" required="">
                      </div>                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">
                        <label>Pin Code</label>
                        <input type="text" name="pin_code" class="form-control" value="@if($edit){{$student->pin_code}}@endif" placeholder="226010" required=""  maxlength="6" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode==46 ||event.charCode==127">
                      </div>                      
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  @if($fees->count())
                  <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                  @else
                  <span class="text-danger">
                    Unable to add student, first add fee structure.
                  </span>
                  @endif
                </div>
            </div>
          </div>
        </div>
        </form>
        </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection
@section('extra_footer')
<!-- bs-custom-file-input -->
<!-- Select2 -->
<script>
  $(function () {
    $('.select2').select2()
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
  });
  function toggleTransportForm(obj){
    if(obj.prop('checked')){
      $("#tranport-div").show();
    }
    else{
      $("#tranport-div").hide();
    }
  }
  function toggleConcessionForm(obj){
    if(obj.prop('checked')){
      $("#concession-div").show();
    }
    else{
      $("#concession-div").hide();
    }
  }
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