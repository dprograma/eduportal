<header>
        <nav class="navbar navbar-expand-lg navbar-light w-100">
            <div class="container px-3">
            <a class="navbar-brand" href="home"><h3><span class="text-primary">Edu</span><span>Portal</span></h3></a>
                <button class="navbar-toggler offcanvas-nav-btn" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <div class="offcanvas offcanvas-start offcanvas-nav" style="width: 20rem">
                    <div class="offcanvas-header">
                        <a href="home" class="text-inverse"><img src="assets/images/logo/logo.svg"
                                alt="EduPortal Logo" /></a>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body pt-0 align-items-center">
                        <ul class="navbar-nav mx-auto align-items-lg-center">
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"></a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"></a>
                            </li>
                            <li class="nav-item dropdown dropdown-fullwidth">
                                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"></a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"></a>
                            </li>
                        </ul>
                        <div class="mt-3 mt-lg-0 d-flex align-items-center">
                            <!-- User Account Dropdown -->
                            <div class="dropdown">
                                <a class="btn btn-light dropdown-toggle" href="#" role="button" id="userDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="<?= file_exists($currentUser->profileimg)? $currentUser->profileimg : 'assets/images/avatar/fallback.jpg' ?>" alt="avatar"
                                        class="avatar avatar-sm rounded-circle" />
                                    <?= $currentUser->fullname ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="account-profile.html">Profile</a></li>
                                    <li><a class="dropdown-item" href="account-settings.html">Settings</a></li>
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