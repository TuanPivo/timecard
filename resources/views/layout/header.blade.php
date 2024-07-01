 <div class="main-header">
     <div class="main-header-logo">
         <!-- Logo Header -->
         <div class="logo-header" data-background-color="dark">
             <a href="index.html" class="logo">
                 <img src="{{ asset('assets/image/logo.png') }}" alt="" class="navbar-brand" />
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
     <!-- Navbar Header -->
     <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
         style="min-height: 60px">
         <div class="container-fluid">
             <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                 <li class="nav-item topbar-user dropdown hidden-caret">
                     <div>
                         <div class="clock" id="clock" style="font-size: 1.5rem; padding-right: 15px"></div>
                     </div>
                 </li>
                 @if (!Auth::check())
                     <li class="nav-item topbar-user dropdown hidden-caret">
                         <a href="{{ route('login') }}" class="btn btn-danger me-2">Login</a>
                     </li>
                 @endif
                 <li class="nav-item topbar-user dropdown hidden-caret" style="background-color:#f5f7fd">
                     <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                         aria-expanded="false">
                         <span class="profile-username">
                             <span class="op-7">Hi,</span>
                             {{-- <span class="fw-bold">{{ isset($user) ? $user->name : 'Guest' }}</span> --}}
                             <span class="fw-bold">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
                         </span>
                     </a>
                     @if (Auth::check())
                         <ul class="dropdown-menu dropdown-user animated fadeIn">
                             <div class="dropdown-user-scroll scrollbar-outer">
                                 <li>
                                     <div class="user-box">
                                         <div class="u-text">
                                             {{-- <h4>{{ $user->name }}</h4>
                                             <p class="text-muted">{{ $user->email }}</p> --}}
                                             @if (Auth::check())
                                                 <h4>{{ Auth::user()->name }}</h4>
                                                 <p class="text-muted">{{ Auth::user()->email }}</p>
                                             @else
                                                 <h4>Guest</h4>
                                                 <p class="text-muted">No email available</p>
                                             @endif
                                             <a href="{{ route('home') }}" class="btn btn-xs btn-danger btn-sm">View
                                                 Calender</a>
                                         </div>
                                     </div>
                                 </li>
                                 <li>
                                     <div class="dropdown-divider"></div>
                                     <a class="dropdown-item" href="{{ route('password.change-password') }}">Change
                                         passwword</a>
                                 </li>
                                 <li>
                                     <div class="dropdown-divider"></div>
                                     <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                                 </li>
                             </div>
                         </ul>
                     @endif
                 </li>
             </ul>
         </div>
     </nav>
     <!-- End Navbar -->
 </div>
