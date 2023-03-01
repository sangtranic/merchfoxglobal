<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
        <a href="{{route('home')}}" title="Home" class="navbar-brand">
            <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminSELLER Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">AdminSELLER</span>
        </a>

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a href="{{route('home')}}" title="Home" class="nav-link">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarUsersDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Users
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarUsersDropdown">
                        <?php if(Auth::user()->role == "admin"){ ?>
                            <a href="{{route('users.index')}}" class="dropdown-item">Danh sách người dùng</a>
                            <a href="{{route('roles.index')}}" class="dropdown-item">Danh sách quyền</a>
                        <?php } ?>
                        <a href="{{route('seller.index')}}" class="dropdown-item">Danh sách Seller</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('users.edit', [Auth::user()->id]) }}">Thông tin tài khoản</a>
                        <a class="dropdown-item" href="{{ route('users.changepassword', [Auth::user()->id]) }}">Đổi mật khẩu</a>
                    </div>
                </li>
                <?php if(Auth::user()->role == "admin"){ ?>
                <li class="nav-item">
                    <a href="{{route('vps.index')}}" class="nav-link">Vps</a>
                </li>
                <?php } ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarProductsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sản phẩm
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarUsersDropdown">
                        <a href="{{route('product-cates.index')}}" class="dropdown-item">Chuyên mục sản phẩm</a>
                        <a href="{{route('products.index')}}" class="dropdown-item">Danh sách sản phẩm</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarOrdersDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Orders
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarOrdersDropdown">
                        <a href="{{route('orders.index')}}" class="dropdown-item">Orders</a>
                        @foreach ($productCates as $category)
                            <a href="{{route('orders.search', ['productCate' => $category->id])}}" class="dropdown-item">{{$category->name}}</a>
                        @endforeach
                    </div>
                </li>
            </ul>
        </div>

        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
            <!-- Messages Dropdown Menu -->
            <li class="nav-item">
                <a href="{{ route('users.edit', [Auth::user()->id]) }}" class="nav-link">
                    @if (Auth::check())
                        <p>Hello, {{ Auth::user()->fullName }}</p>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Logout" href="{{route('logout')}}">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>
