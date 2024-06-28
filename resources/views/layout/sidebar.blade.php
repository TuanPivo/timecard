 <!-- Sidebar -->
 <div class="sidebar" data-background-color="light">
     <div class="sidebar-logo">
         <!-- Logo Header -->
         <div class="logo-header" data-background-color="light">
             <a href="{{ route('home') }}" class="logo">
                 <img src="{{ asset('assets/image/logo.png') }}" alt="navbar brand" class="navbar-brand"
                     style="height: 100px" />
             </a>
             <div class="nav-toggle">
                 <button class="btn btn-toggle toggle-sidebar">
                     <i class="gg-menu-right"></i>
                 </button>
                 <button class="btn btn-toggle sidenav-toggler">
                     <i class="gg-menu-left"></i>
                 </button>
             </div>
             <button class="topbar-toggler more">
                 <i class="gg-more-vertical-alt"></i>
             </button>
         </div>
         <!-- End Logo Header -->
     </div>
     <div class="sidebar-wrapper scrollbar scrollbar-inner">
         <div class="sidebar-content">
             <ul class="nav nav-secondary">
                 <li class="nav-item">
                     <a href="{{ route('home') }}" class="collapsed" aria-expanded="false">
                         <i class="fas fa-home"></i>
                         <p>Home</p>
                         <span class="caret"></span>
                     </a>
                 </li>
                 @if (Auth::check() && Auth::user()->role === 1)
                     <li class="nav-item">
                         <a href="{{ route('showRequestUser') }}">
                             <i class="fa fa-list-alt"></i>
                             <p>Request user</p>
                             <span class="caret"></span>
                         </a>
                     </li>
                 @endif
                 @if (Auth::check() && Auth::user()->role === 0)
                     <li class="nav-item">
                         <a href="{{ route('account.index') }}">
                             <i class="fas fa-user"></i>
                             <p>Manage User</p>
                             <span class="caret"></span>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a href="{{ route('showRequest') }}">
                             <i class="fa fa-list-alt"></i>
                             <p>Request user</p>
                             <span class="caret"></span>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a href="{{ route('holiday.index') }}">
                             <i class="fa fa-list-alt"></i>
                             <p>List Holiday</p>
                             <span class="caret"></span>
                         </a>
                     </li>
                 @endif
             </ul>
         </div>
     </div>
 </div>
 <!-- End Sidebar -->
