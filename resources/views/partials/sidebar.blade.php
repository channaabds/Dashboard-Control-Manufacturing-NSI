<aside id="sidebar" class="sidebar">

    @if (Request::is('maintenance*'))
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('maintenance/dashboard-repair') ? 'collapsed' : ' ' }}" href="/maintenance/dashboard-repair">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard Mesin Rusak</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('maintenance/dashboard-finish') ? 'collapsed' : ' ' }}" href="/maintenance/dashboard-finish">
                    <i class="bi bi-menu-button-wide"></i><span>Data Mesin OK (Finish)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('maintenance/machines') ? 'collapsed' : ' ' }}" href="/maintenance/machines">
                    <i class="bi bi-journal-text"></i><span>Data Mesin</span>
                </a>
            </li>
            @if (auth()->user()->departement == 'it')
                <li class="nav-item">
                    <a class="nav-link {{ !Request::is('menu') ? 'collapsed' : ' ' }}" href="/menu">
                        <i class="bi bi-journal-text"></i><span>Menu</span>
                    </a>
                </li>
            @endif
        </ul>
    @endif

    @if (Request::is('quality*'))
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('quality/home') ? 'collapsed' : ' ' }}" href="/quality/home">
                    <i class="bi bi-grid"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('quality/dashboard-ipqc') ? 'collapsed' : ' ' }}" href="/quality/dashboard-ipqc">
                    <i class="bi bi-menu-button-wide"></i><span>Data IPQC</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('quality/dashboard-oqc') ? 'collapsed' : ' ' }}" href="/quality/dashboard-oqc">
                    <i class="bi bi-journal-text"></i><span>Data OQC</span>
                </a>
            </li>
            @if (auth()->user()->departement == 'it')
                <li class="nav-item">
                    <a class="nav-link {{ !Request::is('menu') ? 'collapsed' : ' ' }}" href="/menu">
                        <i class="bi bi-journal-text"></i><span>Menu</span>
                    </a>
                </li>
            @endif
        </ul>
    @endif

    @if (Request::is('purchasing*'))
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('purchasing/dashboard-waiting-sparepart') ? 'collapsed' : ' ' }}" href="/purchasing/dashboard-waiting-sparepart">
                    <i class="bi bi-journal-text"></i><span>Dashboard Mesin Waiting Repair</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('purchasing/dashboard-repair') ? 'collapsed' : ' ' }}" href="/purchasing/dashboard-repair">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard Mesin Rusak</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('purchasing/dashboard-finish') ? 'collapsed' : ' ' }}" href="/purchasing/dashboard-finish">
                    <i class="bi bi-menu-button-wide"></i><span>Data Mesin OK (Finish)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('purchasing/machines') ? 'collapsed' : ' ' }}" href="/purchasing/machines">
                    <i class="bi bi-menu-button-wide"></i><span>Data Mesin</span>
                </a>
            </li>
            @if (auth()->user()->departement == 'it')
                <li class="nav-item">
                    <a class="nav-link {{ !Request::is('menu') ? 'collapsed' : ' ' }}" href="/menu">
                        <i class="bi bi-journal-text"></i><span>Menu</span>
                    </a>
                </li>
            @endif
        </ul>
    @endif

    @if (Request::is('menu*'))
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('menu') ? 'collapsed' : ' ' }}" href="/menu">
                    <i class="bi bi-journal-text"></i><span>Menu</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('purchasing/dashboard-waiting-sparepart') ? 'collapsed' : ' ' }}" href="/maintenance">
                    <i class="bi bi-journal-text"></i><span>Maintenance</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('purchasing/dashboard-repair') ? 'collapsed' : ' ' }}" href="/purchasing">
                    <i class="bi bi-grid"></i>
                    <span>Purchasing</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ !Request::is('purchasing/dashboard-finish') ? 'collapsed' : ' ' }}" href="/quality">
                    <i class="bi bi-menu-button-wide"></i><span>Quality</span>
                </a>
            </li>
            @if (auth()->user()->role == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ !Request::is('register') ? 'collapsed' : ' ' }}" href="/menu/register">
                        <i class="bi bi-journal-text"></i><span>Tambah User Baru</span>
                    </a>
                </li>
            @endif
        </ul>
    @endif

</aside>
