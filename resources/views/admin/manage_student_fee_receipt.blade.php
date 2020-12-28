@extends('layouts.admin-blank')

@section('extra_header')
<style type="text/css">
  table tr td, table tr th  { font-size: 14px; padding: 5px !important; }
</style>
@endsection

@section('content')

        @include('admin.message')
        <!-- /.row -->
        <div class="row">
          <div class="col-md-4">
          </div> 
          <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title text-center">Fee Receipt</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Particulars</th>
                    <th class="text-right">Amount</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>1.</td>
                    <td>Fee ( {{$fee->mode}} )</td>
                    <td class="text-right">        
                       {{Helper::formatMoney($fee->amount)}}
                    </td>  
                  </tr>
                  </tbody>
                </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
@endsection
@section('extra_footer')
<!-- Select2 -->
<script>
 window.print();
</script>
@endsection