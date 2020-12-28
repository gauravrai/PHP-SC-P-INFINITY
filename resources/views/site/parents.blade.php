@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(!$search)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Parents Login</h3>
                </div>

                <div class="card-body">
                    
                    <form method="get" action="">
                        @csrf                
                        @include('admin.message')
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Student Code</label>
                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control " name="code" required value="">
                            </div>
                        </div>

                        
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" name="search" value="search" class="btn btn-primary">
                                    Search
                                </button>

                               
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            @else
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Homework  for {{$student->name}}</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered table-hover">
                      <thead>
                      <tr>
                        <th>#</th>
                        <th>Homework</th>
                        <th>Date</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($homework as $d)
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$d->description}}</td>
                        <td>
                          {{Helper::formatDate($d->for_date)}}
                        </td>   
                      </tr>
                        @endforeach
                      </tbody>
                    <tfoot>
                    <tr>
                      <th>#</th>
                      <th>Homework</th>
                      <th>Date</th>
                    </tr>
                    </tfoot>
                    </table>
                  </div>
                    <!-- /.card-body -->
                    <div class="row">
                      <div class="col-12 text-center">
                        <a href="/" class="btn btn-primary">
                            Back
                        </a>
                      </div>
                    </div>
                </div>
              <!-- /.card -->
              </div>
            </div>
            
            @endif
        </div>
    </div>
</div>
@endsection
