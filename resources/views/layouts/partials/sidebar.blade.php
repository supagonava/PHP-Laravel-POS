<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('images/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->getAvatar() }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->getFullname() }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                {{-- <li class="nav-item has-treeview">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>สรุปข้อมูล</p>
                    </a>
                </li> --}}
                <li class="nav-item has-treeview">
                    <a href="{{ route('cart.index') }}" class="nav-link {{ activeSegment('cart') }}">
                        <i class="nav-icon fas fa-cart-plus"></i>
                        <p>หน้าร้าน</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('orders.index') }}" class="nav-link {{ activeSegment('orders') }}">
                        <i class="nav-icon fas fa-cart-plus"></i>
                        <p>ออเดอร์</p>
                    </a>
                </li>
                @if (Auth::user()->role == 'admin')
                    <li class="nav-item has-treeview">
                        <a href="{{ route('products.index') }}" class="nav-link {{ activeSegment('products') }}">
                            <i class="nav-icon fas fa-th-large"></i>
                            <p>สินค้า</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="{{ route('users.index') }}" class="nav-link {{ activeSegment('users') }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>ยูสเซอร์</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="{{ route('settings.index') }}" class="nav-link {{ activeSegment('settings') }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>ตั้งค่า</p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit()">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>ออกจากระบบ</p>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                        </form>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
