<nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top" style="margin-bottom: 30px;">
    <a class="navbar-brand" href="{{ route('dashboard') }}">m8s.com</a>
    <ul class="navbar-nav navbar-right">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                Dropdown link
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('account') }}">Account</a>
                <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
            </div>
        </li>
    </ul>
</nav>