<aside class="main-sidebar sidebar-light-lightblue elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img width="200px" src="/images/happy-center-logo.png"
             alt="Ahas Logo">
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>

</aside>
