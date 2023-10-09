<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ !Request::is('dashboard') ? 'collapsed' : ' ' }}" href="/dashboard">
                <i class="bi bi-grid"></i>
                <span>Dashboard Mesin Rusak</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ !Request::is('mesin-ok') ? 'collapsed' : ' ' }}" href="/mesin-finish">
                <i class="bi bi-menu-button-wide"></i><span>Data Mesin OK (Finish)</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ !Request::is('data-mesin') ? 'collapsed' : ' ' }}" href="data-mesin">
                <i class="bi bi-journal-text"></i><span>Data Mesin</span>
            </a>
        </li>

    </ul>

</aside><!-- End Sidebar-->
