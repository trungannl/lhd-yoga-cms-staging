<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('dashboard') }}" class="nav-link @if (isset($page) && $page == 'dashboard') active  @endif">
        <i class="nav-icon fas"><img src="/images/icon/dashboard.svg" alt="icon_dashboard"/></i>
        <p>Dashboard</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('users.index') }}" class="nav-link @if (isset($page) && $page == 'users') active  @endif">
        <i class="nav-icon fas"><img src="/images/icon/user.svg" alt="icon_user" /></i>
        <p>
            Users
        </p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('staffs.index') }}" class="nav-link @if (isset($page) && $page == 'staffs') active  @endif">
        <i class="nav-icon fas"><img src="/images/icon/staff.svg" alt="icon_staff" /></i>
        <p>Staffs</p>
    </a>
</li>

<li class="nav-item @if (isset($page) && $page == 'studios') menu-is-opening menu-open  @endif">
    <a href="#" class="nav-link">
        <i class="nav-icon fas"><img src="/images/icon/house-fill.svg" alt="icon_studio" /></i>
        <p>
            Studio
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('studios.index') }}" class="nav-link @if (isset($page) && $page == 'studios') active  @endif">
                <i class="far fa-circle nav-icon"></i>
                <p>Studio</p>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="{{ route('setting') }}" class="nav-link @if (isset($page) && $page == 'settings') active  @endif">
        <i class="nav-icon fas fa-cog"></i>
        <p>Settings</p>
    </a>
</li>

