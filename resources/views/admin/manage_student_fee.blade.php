@extends('layouts.admin')

@section('extra_header')

@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{$student->name}} [ {{$student->code}} ]</h1>
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
          <div class="col-md-12 text-right">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-lg">
              Add Fee
            </button>
          </div>
          <div class="modal fade" id="modal-lg">
            <form action="" method="post">
            <div class="modal-dialog">
              <div class="modal-content bg-default">
                <div class="modal-header">
                  <h4 class="modal-title">Add Fee for {{$student->name}}</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group row">
                        <label for="" class="col-sm-6 col-form-label">Code</label>
                        <div class="col-sm-6">
                          <input type="text" disabled="" class="form-control" name="" value="{{$student->code}}">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group row">
                        <label for="" class="col-sm-6 col-form-label">Amount</label>
                        <div class="col-sm-6">
                          <input type="number" required="" class="form-control text-right" name="amount" placeholder="00.00">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group row">
                        <label for="" class="col-sm-6 col-form-label">Mode</label>
                        <div class="col-sm-6">
                          <select required="" class="select2 form-control" name="mode">
                            <option value="">Select Mode</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="dd">DD</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  @csrf
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" name="btn" value="Submit" class="btn btn-success">Save changes</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            </form>
            <!-- /.modal-dialog -->
          </div>
        </div>
        <br clear="all">
        <div class="row">
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Fee Details</h3>
              </div>  
              <div class="card-body">
                <table class="table table-bordered table-stripped table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Frequency</th>
                    <th>Year-Month</th>
                    <th class="text-right">Amount</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php
                      $balance=0;
                    @endphp
                  @if($student->fee_balances->count())
                    @foreach($student->fee_balances as $fb)
                      @php $balance+=$fb->amount; @endphp
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                      {{$fb->name}}
                    </td>
                    <td>
                      {{$fb->frequency}}
                    </td>
                    <td>
                      {{date("Y-m", strtotime($fb->for_month))}}
                    </td>
                    <td class="text-right">
                      {{Helper::formatMoney($fb->amount)}}
                    </td>
                  </tr>
                    @endforeach
                  @endif
                  <tr>
                    <td colspan="4">Total</td>
                    <td colspan="" class="text-right">{{Helper::formatMoney($balance)}}</td>
                  </tr>

                  </tbody>
                <tfoot>
                
                </tfoot>
              </table>
              </div>              
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Fee Paid</h3>
              </div>  
              <div class="card-body">
                <table class="table table-bordered table-stripped table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Paid On</th>
                    <th>Mode</th>
                    <th class="text-right">Amount</th>
                    <th class="text-center">Print</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php
                      $paid=0;
                    @endphp
                @if($student->fee_paid->count())
                    @foreach($student->fee_paid as $fp)
                      @php $paid+=$fp->amount; @endphp
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                      {{Helper::formatDate($fp->created_at)}}
                    </td>
                    <td>{{strtoupper($fp->mode)}}</td>
                    <td class="text-right">
                      {{Helper::formatMoney($fp->amount)}}
                    </td>
                    <td class="text-center">
                      <a href="/admin/student-fee-receipt/{{encrypt($fp->id)}}" target="_blank">
                        <i class="fas fa-receipt"></i>
                      </a>
                    </td>
                  </tr>
                    @endforeach
                  @endif
                    <tr>
                      <td colspan="3">Total</td>
                      <td colspan="" class="text-right">{{Helper::formatMoney($paid)}}</td>
                    </tr>
                  </tbody>
              </table>
              </div>              
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 offset-md-4">
            <table class="table table-bordered table-stripped table-hover">
              <tr>
                <td class="text-bold">
                  Total Fee
                </td>
                <td class="text-right">
                  {{Helper::formatMoney($balance)}}
                </td>
              </tr>
              <tr>
                <td class="text-bold">Fee Paid</td>
                <td class="text-right"> {{Helper::formatMoney($paid)}}</td>
              </tr>
              <tr>
                <td class="text-bold">Balance Fee</td>
                <td class="text-right">{{Helper::formatMoney($balance-$paid)}}</td>
              </tr>
            </table>
          </div>
          
        </div>
      </div>
    </section>
@endsection
@section('extra_footer')

<script>

</script>
@endsection