<nav class="d-flex flex-column flex-shrink-0 bg-dark text-white p-3" style="width: 260px; min-height: 100vh;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4 fw-bold"><i class='bx bxs-component'></i> FinanceApp</span>
    </a>
    <hr class="border-secondary">

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="#" class="nav-link text-white d-flex align-items-center gap-2 py-2 px-3">
                <i class='bx bxs-dashboard fs-5'></i> 
                <span class="fw-semibold">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('chart-of-accounts.index') }}" 
            class="nav-link {{ request()->routeIs('chart-of-accounts.*') ? 'active' : 'text-white' }} d-flex align-items-center gap-2 py-2 px-3">
                <i class='bx bxs-book-content fs-5'></i> 
                <span class="fw-semibold">Chart of Accounts</span>
            </a>
        </li>
        <li>
            <a href="{{ route('journals.index') }}" 
            class="nav-link {{ request()->routeIs('journals.*') ? 'active' : 'text-white' }} d-flex align-items-center gap-2 py-2 px-3">
                <i class='bx bxs-notepad fs-5'></i> 
                <span class="fw-semibold">Journals</span>
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white d-flex align-items-center gap-2 py-2 px-3">
                <i class='bx bxs-receipt fs-5'></i> 
                <span class="fw-semibold">Invoices</span>
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white d-flex align-items-center gap-2 py-2 px-3">
                <i class='bx bxs-wallet fs-5'></i> 
                <span class="fw-semibold">Payments</span>
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white d-flex align-items-center gap-2 py-2 px-3">
                <i class='bx bxs-report fs-5'></i> 
                <span class="fw-semibold">Trial Balance</span>
            </a>
        </li>
    </ul>

    <hr class="border-secondary">
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
            <i class='bx bxs-user-circle fs-4 me-2'></i>
            <strong>Admin</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul>
    </div>
</nav>