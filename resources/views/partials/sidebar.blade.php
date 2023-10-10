<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ !Request::is('dashboard-repair') ? 'collapsed' : ' ' }}" href="/dashboard-repair">
                <i class="bi bi-grid"></i>
                <span>Dashboard Mesin Rusak</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ !Request::is('dashboard-finish') ? 'collapsed' : ' ' }}" href="/dashboard-finish">
                <i class="bi bi-menu-button-wide"></i><span>Data Mesin OK (Finish)</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ !Request::is('machines') ? 'collapsed' : ' ' }}" href="/machines">
                <i class="bi bi-journal-text"></i><span>Data Mesin</span>
            </a>
        </li>
    </ul>
</aside>
