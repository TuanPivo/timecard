<div class="sidebar">
    <h4 class="text-center">Uruca K.K</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home')}}"><i class="fas fa-home"></i> Home</a>
        </li>
        @if (Auth::check() && Auth::user()->role === 0)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('account.index')}}"><i class="fas fa-users"></i> Manage User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('showRequest')}}"><i class="fas fa-bars"></i> Manage Request</a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" href="{{ route('password.change-password') }}"><i class="fas fa-key"></i> Change password</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout')}}"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
    </ul>
</div>
