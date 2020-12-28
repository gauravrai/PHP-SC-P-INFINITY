@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Manage Fee Structure</h1>
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
        <form action="" method="post" autocomplete="off">
        <div class="row">
          <!-- left column -->
          <div class="col-md-4 ">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Fee Structure Details</h3>
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
                        <label>With effect from</label>
                        <input type="text" name="wef" id="datepicker" class="form-control" value="@if($edit){{$fee->wef}}@endif" placeholder="Effective date" required="" readonly>
                      </div>                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">
                        <label>Class</label>
                        <select class="select2 form-control" name="school_class_id[]" multiple="" required="" data-placeholder="Select classes">
                          @foreach($school_classes as $cls)
                          <option @if($edit && in_array($cls->id, $fee->school_classes->pluck('id')->toArray())) selected @endif value="{{$cls->id}}">{{$cls->name}}</option>
                          @endforeach
                        </select>
                      </div>                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">                      
                      <div class="col-md-12">
                        <label>Fee Structure Name</label>
                        <input type="text"  name="name" class="form-control" value="@if($edit){{$fee->name}}@endif" placeholder="e.g Fee Structure For Class VI" required="">
                      </div> 
                    </div>
                  </div>
                </div>
            </div>
          </div>
          <div class="col-md-8">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Structure Break-up</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                @csrf
                <div class="card-body" id="repeat-box">
                  @if($edit)
                  @foreach($fee->fee_strucures as $f)
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        @if($loop->iteration ==1)
                        <label>Name</label>
                        @endif

                        <select required="" name="fee_type_id[]" class="form-control" >
                          <option value="">Select Fee</option>
                          @foreach($fee_types as $ft)
                          <option @if($edit && $ft->id==$f->fee_type_id) selected  @endif value="{{$ft->id}}">{{$ft->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2">
                        @if($loop->iteration ==1)
                        <label>Amount</label>
                        @endif
                        <input type="number" name="amount[]" class="form-control text-right" value="@if($edit){{$f->amount}}@endif" placeholder="00.00" required=""  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode==46 ||event.charCode==127">
                      </div>
                      <div class="col-md-3">
                        @if($loop->iteration ==1)
                        <label>Frequency</label>
                        @endif
                        <select required="" class="form-control" name="frequency[]" required="">
                          <option value="">Select Frequency</option>
                          @foreach($frequency as $d)
                          <option @if($edit && $f->frequency==$d) selected @endif value="{{$d}}">{{$d}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2">
                        @if($loop->iteration ==1)
                        <label>Sort Order</label>
                        @endif
                        <input type="text"  name="sort_order[]" class="form-control" value="@if($edit){{$f->sort_order}}@endif" required="" placeholder="e.g 1 or 0 or 2" maxlength="2"  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode==46 ||event.charCode==127">
                      </div>
                      
                      <div class="col-md-1 text-center">
                        @if($loop->iteration ==1)
                        <label>Action</label>
                        @endif
                        <a onclick="removeThis($(this))" href="javascript:void(0)"><span class="fa fa-trash"></span></a>
                      </div>
                    </div>
                  </div>
                  @endforeach
                  @else
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Name</label>
                        <select required="" name="fee_type_id[]" class="form-control" >
                          <option value="">Select Fee</option>
                          @foreach($fee_types as $ft)
                          <option value="{{$ft->id}}">{{$ft->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label>Amount</label>
                        <input type="number" name="amount[]" class="form-control text-right" placeholder="00.00" required="" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode==46 ||event.charCode==127">
                      </div>
                      <div class="col-md-3">
                        <label>Frequency</label>
                        <select required="" class="form-control" name="frequency[]" required="">
                          <option value="">Select Frequency</option>
                          @foreach($frequency as $d)
                          <option value="{{$d}}">{{$d}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label>Sort Order</label>
                        <input type="text"  name="sort_order[]" class="form-control" required="" placeholder="e.g 1 or 0 or 2" maxlength="2"  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode==46 ||event.charCode==127">
                      </div>
                      <div class="col-md-1">
                      </div>
                    </div>
                  </div>
                  @endif
                </div>
                <div class="card-footer">
                  <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                  <a href="javascript:void(0)" onclick="addMoreRows()" class="btn btn-default">
                    Add More Rows
                  </a>
                  @if($edit)
                  <a href="/admin/fee-structure/" class="btn btn-warning">Add New Fee Structure</a>
                  @endif
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
                <h3 class="card-title">Fee Structures</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($records as $d)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$d->name}}</td>
                    <td>
                      
                      <a onclick="return confirm('Are you sure, you want to delete this?');" href="/admin/fee-structure/?trash={{encrypt($d->id)}}"><span class="fa fa-trash"></span></a>
                      &nbsp;&nbsp;&nbsp;
                      <a href="/admin/fee-structure/{{encrypt($d->id)}}"><span class="fa fa-edit"></span></a>
                    </td>
                  </tr>
                    @endforeach
                  </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Name</th>
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
<div class="d-none" id="clone-div">
  <div class="form-group">
    <div class="row">
      <div class="col-md-4">
        <select required="" name="fee_type_id[]" class="form-control" >
          <option value="">Select Fee</option>
          @foreach($fee_types as $ft)
          <option value="{{$ft->id}}">{{$ft->name}}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <input type="number" name="amount[]" class="form-control text-right" placeholder="00.00" required="" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode==46 ||event.charCode==127">
      </div>
      <div class="col-md-3">
        <select required="" class="form-control" name="frequency[]" required="">
          <option value="">Select Frequency</option>
          @foreach($frequency as $d)
          <option value="{{$d}}">{{$d}}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <input type="text"  name="sort_order[]" class="form-control" value"" required="" placeholder="e.g 1 or 0 or 2" maxlength="2"  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode==46 ||event.charCode==127">
      </div>
      <div class="col-md-1">
        <a onclick="removeThis($(this))" href="javascript:void(0)"><span class="fa fa-trash"></span></a>
      </div>
    </div>
  </div>
</div>
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
  function addMoreRows(){
    str=$('#clone-div').html();
    $("#repeat-box").append(str);
  }
  function removeThis(obj){
    obj.parents('.form-group').remove();
  }
  $('#datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
  });
</script>
@endsection