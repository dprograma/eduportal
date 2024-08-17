<!doctype html>
<html lang="en">
<?php $title = "EduPortal | Dashboard"; ?>
<?php include "partials/head.php"; ?>

<body>
    <!-- Navbar -->
<?php include "partials/customer_header.php"; ?>

    <main>
        <!--Account home start-->
        <section class="py-lg-7 py-5 bg-light-subtle">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <div class="d-flex align-items-center mb-4 justify-content-center justify-content-md-start">
                            <img src="assets/images/avatar/avatar-1.jpg" alt="avatar"
                                class="avatar avatar-lg rounded-circle" />
                            <div class="ms-3">
                                <h5 class="mb-0">John Doe</h5>
                                <small>Personal account</small>
                            </div>
                        </div>
                        <!-- Navbar -->
                        <div class="mb-4 text-center text-md-start">
                            <a href="#" class="text-reset">
                                <span>
                                    <span>View Profile</span>
                                </span>
                                <span class="ms-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                        class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z" />
                                        <path fill-rule="evenodd"
                                            d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                        <div class="d-md-none text-center d-grid">
                            <button class="btn btn-light mb-3 d-flex align-items-center justify-content-between"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseAccountMenu"
                                aria-expanded="false" aria-controls="collapseAccountMenu">
                                Account Menu
                                <i class="bi bi-chevron-down ms-2"></i>
                            </button>
                        </div>
                        <div class="collapse d-md-block" id="collapseAccountMenu">
                            <ul class="nav flex-column nav-account">
                                <li class="nav-item">
                                    <a class="nav-link" href="account-home.html">
                                        <i class="align-bottom bx bx-home"></i>
                                        <span class="ms-2">Dashboard</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="account-profile.html">
                                        <i class="align-bottom bx bx-user"></i>
                                        <span class="ms-2">Profile</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="account-security.html">
                                        <i class="align-bottom bx bx-lock-alt"></i>
                                        <span class="ms-2">Security</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="account-library.html">
                                        <i class="align-bottom bx bx-book"></i>
                                        <span class="ms-2">My Library</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="account-cbt.html">
                                        <i class="align-bottom bx bx-clipboard"></i>
                                        <span class="ms-2">CBT Tests</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="account-notifications.html">
                                        <i class="align-bottom bx bx-bell"></i>
                                        <span class="ms-2">Notifications</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="account-appearance.html">
                                        <i class="align-bottom bx bx-palette"></i>
                                        <span class="ms-2">Appearance</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.html">
                                        <i class="align-bottom bx bx-log-out"></i>
                                        <span class="ms-2">Sign Out</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="mb-4">
                            <h1 class="mb-0 h3">Welcome to EduPortal, John!</h1>
                        </div>
                        <div class="mb-5">
                            <h4 class="mb-1">My Dashboard</h4>
                            <p class="mb-0 fs-6">Access your ebooks, publications, past questions, and CBT tests all in
                                one place.</p>
                        </div>
                        <div class="row mb-5 g-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <span>My Library</span>
                                        <h3 class="mb-0 mt-4">12 Items</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <span>Total Purchases</span>
                                        <h3 class="mb-0 mt-4">$25.00</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <span>CBT Test Results</span>
                                        <h3 class="mb-0 mt-4">87%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5>Upcoming CBT Tests</h5>
                                        <p class="mb-0">Don't miss your scheduled tests. Review your materials and ace
                                            your exams.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5>Recently Added Publications</h5>
                                        <p class="mb-0">Check out the latest publications added to our collection.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Account home end-->
    </main>

    <?php include 'partials/footer.php'; ?>
    <!-- Scroll top -->
    <?php include 'partials/scroll_top.php'; ?>
    <!-- Libs JS -->
    <?php include 'partials/scripts.php'; ?>
</body>

</html>