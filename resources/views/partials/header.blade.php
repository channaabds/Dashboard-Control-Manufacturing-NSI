<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        @if (Request::is('maintenance*'))
            <a href="/maintenance/dashboard-repair" class="logo d-flex align-items-center">
                <img src="{{ asset('./img/logo/logo-nsi.jpg') }}" alt="">
                <span class="d-none d-lg-block">Dashboard Control Manufacturing</span>
            </a>
        @endif
        @if (Request::is('quality*'))
            <a href="/quality/home" class="logo d-flex align-items-center">
                <img src="{{ asset('./img/logo/logo-nsi.jpg') }}" alt="">
                <span class="d-none d-lg-block">Dashboard Control Manufacturing</span>
            </a>
        @endif
        @if (Request::is('purchasing*'))
            <a href="/purchasing/dashboard-waiting-sparepart" class="logo d-flex align-items-center">
                <img src="{{ asset('./img/logo/logo-nsi.jpg') }}" alt="">
                <span class="d-none d-lg-block">Dashboard Control Manufacturing</span>
            </a>
        @endif
        @if (Request::is('menu*'))
            <a href="/menu" class="logo d-flex align-items-center">
                <img src="{{ asset('./img/logo/logo-nsi.jpg') }}" alt="">
                <span class="d-none d-lg-block">Dashboard Control Manufacturing</span>
            </a>
        @endif
        @if (Request::is('target*'))
            <a href="/target" class="logo d-flex align-items-center">
                <img src="{{ asset('./img/logo/logo-nsi.jpg') }}" alt="">
                <span class="d-none d-lg-block">Dashboard Control Manufacturing</span>
            </a>
        @endif
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{ asset('./img/users/profile-user.png') }}" alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->username }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ auth()->user()->username }}</h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="/login" method="post">
                            @csrf
                            @method('delete')
                            <button class="dropdown-item d-flex align-items-center" type="submit">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
