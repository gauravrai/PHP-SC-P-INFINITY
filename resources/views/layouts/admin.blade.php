@php
if(Auth::guard('student')->user())
  $user=Auth::guard('student')->user();
else
  $user=$user=Auth::user();

if(!isset($side_main))
  $side_main='';

if(!isset($side_sub))
  $side_sub='';
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Laravel') }} Admin Panel</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <style type="text/css">
    .nav-sidebar .nav-treeview > .nav-item > .nav-link > .nav-icon{ font-size: 12px; }
    .navbar{ height: 30px; }
  </style>
  @yield('extra_header')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>

    <!-- SEARCH FORM -->
    

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown d-none">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ asset('dist/img/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ asset('dist/img/user8-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ asset('dist/img/user3-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown  d-none">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item d-none">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/admin" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">ICP</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset(Helper::getProfilePicture()) }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ $user->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="/admin" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard 
              </p>
            </a>
          </li>
          @if($user->hasAnyRole(['Super Admin', 'Admin', 'Admission Team', 'Accounts']))
          <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link @if(Helper::isSidebar($side_main, 'student')) menu-open active @endif">
              <i class="nav-icon fas fa-child"></i>
              <p>
                Student Management <i class="right fas @if(Helper::isSidebar($side_main, 'student')) fa-angle-down @else fa-angle-left @endif"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" @if(Helper::isSidebar($side_main, 'student')) style="display: block;" @endif>
              <li class="nav-item">
                <a href="/admin/student-list" class="nav-link @if(Helper::isSidebar($side_sub, 'student-list')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
              @if($user->hasPermissionTo('Student Add'))
              <li class="nav-item">
                <a href="/admin/manage-student" class="nav-link @if(Helper::isSidebar($side_sub, 'student')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/student-action" class="nav-link @if(Helper::isSidebar($side_sub, 'student')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Action</p>
                </a>
              </li>
              @endif
              
            </ul>
          </li>
          @endif
          @if($user->hasAnyRole(['Super Admin', 'Admin', 'Teacher']))
          <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link @if(Helper::isSidebar($side_main, 'attendance')) menu-open active @endif">
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Attendance <i class="right fas @if(Helper::isSidebar($side_main, 'attendance')) fa-angle-down @else fa-angle-left @endif"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" @if(Helper::isSidebar($side_main, 'attendance')) style="display: block;" @endif>
              <li class="nav-item">
                <a href="/admin/attendance" class="nav-link @if(Helper::isSidebar($side_sub, 'attendance')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/attendance-list" class="nav-link @if(Helper::isSidebar($side_sub, 'attendance-list')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link @if(Helper::isSidebar($side_main, 'homework')) menu-open active @endif">
              <i class="nav-icon far fa-address-book"></i>
              <p>
                Homework <i class="right fas @if(Helper::isSidebar($side_main, 'homework')) fa-angle-down @else fa-angle-left @endif"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" @if(Helper::isSidebar($side_main, 'homework')) style="display: block;" @endif>
              <li class="nav-item">
                <a href="/admin/homework" class="nav-link @if(Helper::isSidebar($side_sub, 'attendance')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/homework-list" class="nav-link @if(Helper::isSidebar($side_sub, 'homework-list')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          @if($user->hasAnyRole(['Super Admin', 'Admin']))
          <li class="nav-item @if(Helper::isSidebar($side_main, 'transactional') || Helper::isSidebar($side_main, 'promotional')) menu-open active @endif">
            <a href="javascript:void(0);" class="nav-link @if(Helper::isSidebar($side_main, 'transactional') || Helper::isSidebar($side_main, 'promotional')) menu-open active @endif">
              <i class="nav-icon fas fa-sms"></i>
              <p>
                SMS <i class="right fas @if(Helper::isSidebar($side_main, 'transactional') || Helper::isSidebar($side_main, 'promotional')) fa-angle-down @else fa-angle-left @endif"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-dot-circle nav-icon"></i>
                  <p>Transactional <i class="right fas  fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview" @if(Helper::isSidebar($side_main, 'transactional')) style="display: block;" @endif>
                  <li class="nav-item">
                    <a href="/admin/sms" class="nav-link @if(Helper::isSidebar($side_sub, 'transactional-add')) active @endif">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Add</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/admin/sms-list" class="nav-link @if(Helper::isSidebar($side_sub, 'transactional-list')) active @endif">
                      <i class="far fa-circle nav-icon"></i>
                      <p>List</p>
                    </a>
                  </li>
                </ul>
              </li>
              @if($user->id==1 || $user->id==2)
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-dot-circle nav-icon"></i>
                  <p>Promotional <i class="right fas  fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview" @if(Helper::isSidebar($side_main, 'promotional')) style="display: block;" @endif>
                  <li class="nav-item">
                    <a href="/admin/sms-promotional-add" class="nav-link @if(Helper::isSidebar($side_sub, 'promotional-add')) active @endif">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Add</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/admin/sms-promotional-list" class="nav-link @if(Helper::isSidebar($side_sub, 'promotional-list')) active @endif">
                      <i class="far fa-circle nav-icon"></i>
                      <p>List</p>
                    </a>
                  </li>
                </ul>
              </li>
              @endif
            </ul>

            
          </li>
          @endif
          @if($user->hasRole('Parents'))
          <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link @if(Helper::isSidebar($side_main, 'parents')) menu-open active @endif">
              <i class="nav-icon fas fa-child"></i>
              <p>
                Student <i class="right fas @if(Helper::isSidebar($side_main, 'parents')) fa-angle-down @else fa-angle-left @endif"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" @if(Helper::isSidebar($side_main, 'parents')) style="display: block;" @endif>
              
              <li class="nav-item">
                <a href="/admin/parents/homework" class="nav-link @if(Helper::isSidebar($side_sub, 'homework')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Homework</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/parents/attendance" class="nav-link @if(Helper::isSidebar($side_sub, 'student-list')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Attendance</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          @if($user->hasRole('Super Admin'))
          <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link @if(Helper::isSidebar($side_main, 'settings')) menu-open active @endif">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Settings <i class="right fas @if(Helper::isSidebar($side_main, 'settings')) fa-angle-down @else fa-angle-left @endif"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" @if(Helper::isSidebar($side_main, 'settings')) style="display: block;" @endif>
              <li class="nav-item">
                <a href="/admin/classes" class="nav-link @if(Helper::isSidebar($side_sub, 'classes')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Classes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/sections" class="nav-link @if(Helper::isSidebar($side_sub, 'sections')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Sections</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/transport/transporters" class="nav-link @if(Helper::isSidebar($side_sub, 'transporters')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Transporters</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/transport/routes" class="nav-link @if(Helper::isSidebar($side_sub, 'routes')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Transport Routes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/designation" class="nav-link @if(Helper::isSidebar($side_sub, 'designation')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Designation</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/department" class="nav-link @if(Helper::isSidebar($side_sub, 'department')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Department</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/staff" class="nav-link @if(Helper::isSidebar($side_sub, 'staff')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Staff</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/fee-type" class="nav-link @if(Helper::isSidebar($side_sub, 'fee-type')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Fee Type</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/fee-structure" class="nav-link @if(Helper::isSidebar($side_sub, 'fee-structure')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Fee Structure</p>
                </a>
              </li>
              <li class="nav-item d-none">
                <a href="/admin/subjects" class="nav-link @if(Helper::isSidebar($side_sub, 'subjects')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Subjects</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          <li class="nav-item">
            @if(Auth::guard('web')->check())
            <a href="#" class="nav-link" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
              <i class="nav-icon far fa-circle text-danger"></i>
              <p>
                    {{ __('Logout') }}
                </p>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </a>
            @elseif(Auth::guard('student')->check())
            <a href="/student/logout" class="nav-link">
              <i class="nav-icon far fa-circle text-danger"></i>
              <p>Logout</p>
            </a>
            @endif
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @yield('content')   
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2020 <a href="/">{{ config('app.name', 'Laravel') }}</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<script src="{{ asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js') }}"></script>
@yield('extra_footer')
</body>
</html>
