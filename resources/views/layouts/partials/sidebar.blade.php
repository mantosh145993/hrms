   <!-- Sidebar -->
   <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
       <!-- Sidebar - Brand -->
       <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{Auth::user()->role=='admin' ? route('admin.dashboard') : route('employee.dashboard')}}">
           <div class="sidebar-brand-icon rotate-n-15">
               <i class="fas fa-laugh-wink"></i>
           </div>
           <div class="sidebar-brand-text mx-3">HRMS Solutions <sup></sup></div>
       </a>
       <!-- Divider -->
       <hr class="sidebar-divider my-0">
       <!-- Nav Item - Dashboard -->
       <li class="nav-item active">
           <a class="nav-link" href="#">
               <i class="fas fa-fw fa-tachometer-alt"></i>
               <span>HRMS Panel</span></a>
       </li>
       <!-- Divider -->
       <hr class="sidebar-divider">
       <!-- Heading -->
       <div class="sidebar-heading">
           Interface
       </div>
       <!-- Nav Item - Pages Collapse Menu -->

        <!-- Admin Auth  -->
       @if(Auth::user()->role == 'admin')   
       <li class="nav-item">
           <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
               aria-expanded="true" aria-controls="collapseTwo">
               <i class="fas fa-fw fa-cog"></i>
               <span>Manage Employee</span>
           </a>
           <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
               <div class="bg-white py-2 collapse-inner rounded">
                   <h6 class="collapse-header">Manage Components:</h6>
                   <a href="{{ route('users.index') }}" class="collapse-item">Employees</a>
                   <a class="collapse-item" href="{{ route('tasks.index') }}">Assign Task</a>
                   <a href="{{ route('shifts.index') }}" class="collapse-item">Shifts</a>
               
                   <a href="{{ route('holidays.index') }}" class="collapse-item">Holidays</a>
                   <a href="{{route('leave.applied')}}" class="collapse-item" >Applied Leave</a>
           
                   <a href="{{ route('attendance.index') }}" class="collapse-item">Attendance Report</a>
                  <a href="{{ route('leave-types.index') }}" class="collapse-item">Leave Type</a>
               </div>
           </div>
           <li class="nav-item">
           <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
               aria-expanded="true" aria-controls="collapseUtilities">
               <i class="fas fa-fw fa-donate"></i>
               <span>Payrole Report</span>
           </a>
           <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
               data-parent="#accordionSidebar">
               <div class="bg-white py-2 collapse-inner rounded">
                   <h6 class="collapse-header">Payrole Utilities:</h6>
                   <a class="collapse-item" href="{{route('attendance.report')}}">Attendance Report</a>
                   <a class="collapse-item" href="#">Salary Slip</a>
               </div>
           </div>
       </li>
       </li>
       @endif
       <!-- End Admin Auth  -->

       <!-- Employee Auth  -->
       @if(Auth::user()->role == 'employee')
       <li class="nav-item">
           <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
               aria-expanded="true" aria-controls="collapseTwo">
               <i class="fas fa-fw fa-users"></i>
               <span>All Menu</span>
           </a>
           <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
               <div class="bg-white py-2 collapse-inner rounded">
                   <h6 class="collapse-header">Employee Components:</h6>
                   <a href="{{route('employee.assigned')}}" class="collapse-item">Task Panel</a>
                   <a href="{{route('employee.attendance')}}" class="collapse-item">Attendance Panel</a>
                   <a class="collapse-item" href="{{route('employee.holiday')}}">Holiday Panel</a>
                   <a href="{{route('leaves.index')}}" class="collapse-item"> Leave Panel</a>
               </div>
           </div>
       </li>
       <!-- Pay role section -->
       <!-- <li class="nav-item">
           <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
               aria-expanded="true" aria-controls="collapseUtilities">
               <i class="fas fa-fw fa-donate"></i>
               <span>Payrole Report</span>
           </a>
           <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
               data-parent="#accordionSidebar">
               <div class="bg-white py-2 collapse-inner rounded">
                   <h6 class="collapse-header">Payrole Utilities:</h6>
                   <a class="collapse-item" href="#">Attendance Report</a>
                   <a class="collapse-item" href="#">Salary Slip</a>
               </div>
           </div>
       </li> -->

       <li class="nav-item">
           <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#rais-concern"
               aria-expanded="true" aria-controls="rais-concern">
               <i class="fas fa-fw fa-portrait"></i>
               <span>Feedback Box</span>
           </a>
           <div id="rais-concern" class="collapse" aria-labelledby="headingUtilities"
               data-parent="#accordionSidebar">
               <div class="bg-white py-2 collapse-inner rounded">
                   <h6 class="collapse-header">Suggation Utilities:</h6>
                   <a class="collapse-item" href="#">Create Suggation</a>
               </div>
           </div>
       </li>

       <li class="nav-item">
           <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#month-star"
               aria-expanded="true" aria-controls="month-star">
               <i class="fas fa-fw fa-balance-scale-right"></i>
               <span>Bodmas Star</span>
           </a>
           <div id="month-star" class="collapse" aria-labelledby="headingUtilities"
               data-parent="#accordionSidebar">
               <div class="bg-white py-2 collapse-inner rounded">
                   <h6 class="collapse-header">Star Utilities:</h6>
                   <a class="collapse-item" href="#"> Employee</a>
               </div>
           </div>
       </li>
       @endif
       <!-- End Employee Auth-->

       <!-- Divider -->
       <hr class="sidebar-divider">

       <!-- Admin Auth  -->
       @if(Auth::user()->role == 'admin')
     
       <!-- <div class="sidebar-heading">
           Addons
       </div>
  
       <li class="nav-item">
           <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
               aria-expanded="true" aria-controls="collapsePages">
               <i class="fas fa-fw fa-folder"></i>
               <span>Pages</span>
           </a>
           <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
               <div class="bg-white py-2 collapse-inner rounded">
                   <h6 class="collapse-header">Login Screens:</h6>
                   <a class="collapse-item" href="#">Login</a>
                   <a class="collapse-item" href="#">Register</a>
                   <a class="collapse-item" href="#">Forgot Password</a>
                   <div class="collapse-divider"></div>
                   <h6 class="collapse-header">Other Pages:</h6>
                   <a class="collapse-item" href="#">404 Page</a>
                   <a class="collapse-item" href="#">Blank Page</a>
               </div>
           </div>
       </li>
     
       <li class="nav-item">
           <a class="nav-link" href="#">
               <i class="fas fa-fw fa-chart-area"></i>
               <span>Charts</span></a>
       </li>
    
       <li class="nav-item">
           <a class="nav-link" href="#">
               <i class="fas fa-fw fa-table"></i>
               <span>Tables</span></a>
       </li> -->
       @endif
       <!-- End Admin Auth  -->

       <!-- Divider -->
       <hr class="sidebar-divider d-none d-md-block">
       <!-- Sidebar Toggler (Sidebar) -->
       <div class="text-center d-none d-md-inline">
           <button class="rounded-circle border-0" id="sidebarToggle"></button>
       </div>
   </ul>
   <!-- End of Sidebar -->