<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                    data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>

    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="{{ (request()->is('dashboard*')) ? 'mm-active' : '' }}">
                    <a href="{{ url("dashboard") }}" style="padding-left: 0px">
                        Dashboard
                    </a>
                </li>

                @if (auth()->user()->role_id == 1)
                <li class="app-sidebar__heading">User Management</li>
                <li class="{{ (request()->is('roles*')) ? 'mm-active' : '' }}">
                    <a href="{{ url("roles") }}">
                        <i class="metismenu-icon pe-7s-shield"></i>
                        Roles
                    </a>
                </li>
                <li class="{{ (request()->is('users*')) ? 'mm-active' : '' }}">
                    <a href="{{ url("users") }}">
                        <i class="metismenu-icon pe-7s-users"></i>
                        Users
                    </a>
                </li>
                @endif

                <li class="app-sidebar__heading">Expense Management</li>
                @if (auth()->user()->role_id == 1)
                <li class="{{ (request()->is('categories*')) ? 'mm-active' : '' }}">
                    <a href="{{ url("categories") }}">
                        <i class="metismenu-icon pe-7s-ticket"></i>
                        Expense Categories
                    </a>
                </li>
                @endif
                <li class="{{ (request()->is('expenses*')) ? 'mm-active' : '' }}">
                    <a href="{{ url("expenses") }}">
                        <i class="metismenu-icon pe-7s-cash"></i>
                        Expenses
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>