<!doctype html>
<html lang="en">
<?php $title = "EduPortal | Admin Dashboard"; ?>
<?php include "partials/head.php"; ?>

<body>
    <!-- Navbar -->
<?php include "partials/admin_header.php"; ?>

    <main>
        <!--Dashboard home start-->
        <section class="py-lg-7 py-5 bg-light-subtle">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <div class="d-flex align-items-center mb-4 justify-content-center justify-content-md-start">
                            <img src="assets/images/avatar/avatar-1.jpg" alt="avatar"
                                class="avatar avatar-lg rounded-circle" />
                            <div class="ms-3">
                                <h5 class="mb-0">John Doe</h5>
                                <small>Admin account</small>
                            </div>
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
                                    <a class="nav-link" href="#">
                                        <i class="align-bottom bx bx-upload"></i>
                                        <span class="ms-2 fs-6">Upload Documents</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="align-bottom bx bx-file"></i>
                                        <span class="ms-2 fs-6">View Documents</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="mb-4">
                            <h1 class="mb-0 h3">Welcome to Your Dashboard, John!</h1>
                        </div>
                        <div class="mb-5">
                            <h4 class="mb-1">Dashboard Overview</h4>
                            <p class="mb-0 fs-6">Manage your past questions, users, and track overall statistics.</p>
                        </div>
                        <div class="row mb-5 g-4">
                            <div class="col-lg-3 col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <span>Total Documents Uploaded</span>
                                        <h3 class="mb-0 mt-4">1,245</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <span>Total Documents Approved</span>
                                        <h3 class="mb-0 mt-4">256</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <span>Total Documents Pending</span>
                                        <h3 class="mb-0 mt-4">1,015</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <span>Total Revenue</span>
                                        <h3 class="mb-0 mt-4">$10,540.00</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!--Dashboard home end-->
    </main>

    <?php include 'partials/footer.php'; ?>
    <!-- Scroll top -->
    <?php include 'partials/scroll_top.php'; ?>
    <!-- Libs JS -->
    <?php include 'partials/scripts.php'; ?>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function () {
            $('#usersTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "retrieve": true
            });
        });
    </script>
</body>

</html>
