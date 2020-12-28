@extends('layouts.admin-blank')

@section('extra_header')
<style type="text/css">
  table tr td, table tr th  { font-size: 14px; padding: 5px !important; }
</style>
@endsection

@section('content')

        @include('admin.message')
        @if($search)
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Attendance for Class {{$school_class->name}} - {{$section->name}} for  {{date('M-Y', strtotime($date))}} </h3>
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
                        $date=$m . "-" . $month . "-" . $year;

                        $attn=Helper::fetchAttendanceOnDate($d->id, $date);
                      @endphp
                    <td>        
                       @if(isset($attn)  && $attn)
                      <span class="badge badge-{{$attn->attendance_status->css}}">{{$attn->attendance_status->initials}}</span>
                        @elseif(date('N', strtotime($date))==7)
                      <span class="badge badge-primary">H</span>
                        @else
                      <span class="badge badge-secondary1">NA</span>
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
@endsection
@section('extra_footer')
<!-- Select2 -->
<script>
 
</script>
@endsection