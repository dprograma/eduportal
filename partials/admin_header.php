<header>
    <nav class="navbar navbar-expand-lg navbar-light w-100">
        <div class="container px-3">
            <a class="navbar-brand" href="home">
                <h3><span class="text-primary">Edu</span><span>Portal</span></h3>
            </a>
            <button class="navbar-toggler offcanvas-nav-btn" type="button">
                <i class="bi bi-list"></i>
            </button>
            <div class="offcanvas offcanvas-start offcanvas-nav" style="width: 20rem">
                <div class="offcanvas-header">
                    <a href="home" class="text-inverse"><h3><span class="text-primary">Edu</span><span>Portal</span></h3></a>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body pt-0 float-end align-items-center ms-auto">
                    <div class="mt-3 mt-lg-0 d-flex align-items-center">
                        <!-- User Account Dropdown -->
                        <div class="dropdown ms-auto">
                            <a class="btn btn-light dropdown-toggle" href="#" role="button" id="userDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="<?= file_exists($currentUser->profileimg) ? $currentUser->profileimg : 'assets/images/avatar/fallback.jpg' ?>"
                                    alt="avatar" class="avatar avatar-sm rounded-circle" />
                                <?= $currentUser->fullname; ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile">Profile</a></li>
                                <li><a class="dropdown-item" href="settings">Settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>