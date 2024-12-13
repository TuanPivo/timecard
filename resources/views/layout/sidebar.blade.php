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
                </a>
            </li>
            @if (Auth::check())
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#myrequest">
                        <i class="fas fa-calendar-alt"></i>
                        <p>My Request</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="myrequest">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('showRequestUser') }}">
                                    <span class="sub-item">Check-in/Check-out</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('leave_requests.index') }}">
                                    <span class="sub-item">Calendar Leave</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('leave_requests.list') }}">
                                    <span class="sub-item">Leaves Request</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if (Auth::check() && Auth::user()->role === 0)
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#base">
                            <i class="fas fa-users-cog"></i>
                            <p>Manage User</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="base">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('account.index') }}">
                                        <span class="sub-item">Lists User</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('account.create') }}">
                                        <span class="sub-item">Create User</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#manage">
                            <i class="far fa-calendar-check"></i>
                            <p>Manages Request</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="manage">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('showRequest') }}">
                                        <span class="sub-item">Check-in/Check-out</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin_leave_requests.index') }}">
                                        <span class="sub-item">Leaves Request</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#holiday">
                            <i class="far fa-calendar-alt"></i>
                            <p>Manages Holiday</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="holiday">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('holiday.index') }}">
                                        <span class="sub-item">Lists Holiday</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
            @endif
        </ul>
    </div>
</div>
</div>
<!-- End Sidebar -->
