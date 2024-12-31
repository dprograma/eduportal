<div class="col-lg-9 col-md-8">
    <div class="mb-4">
        <h1 class="mb-0 h3">Welcome to Your Dashboard, <?=explode(' ', $currentUser->fullname)[0]?>!</h1>
    </div>
    <div class="mb-5">
        <h4 class="mb-1">Dashboard Overview</h4>
        <p class="mb-0 fs-6">Manage your past questions, users, and track overall statistics.</p>
    </div>
    <div class="row mb-5 g-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Verified Users</span>
                    <h3 class="mb-0 mt-4"><?=$totalVerifiedUsers ? formatNumber($totalVerifiedUsers, 0): 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Unverified Users</span>
                    <h3 class="mb-0 mt-4"><?=$totalUnverifiedUsers ? formatNumber($totalUnverifiedUsers, 0): 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Past Questions</span>
                    <h3 class="mb-0 mt-4"><?=$totalQuestionsUploaded ? formatNumber($totalQuestionsUploaded, 0): 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Revenue<br/>&nbsp;</span>
                    <h3 class="mb-0 mt-4">₦<?=$totalAmount ? formatNumber($totalAmount, 2): formatNumber(0, 2); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table for Users -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body">
            <h5 class="card-title">All Users</h5>
            <div class="table-responsive">
                <table id="usersTable" class="table table-hover table-bordered">
                    <thead class="table-light fs-6">
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Access Level</th>
                            <th>Year</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="fs-6">
                        <!-- Example Row -->
                        <?php foreach ($usersData as $userdata): ?>
                            <tr data-id="<?= $userdata['id'] ?>" class="bg-white">
                                <th scope="row"><?= ++$rows ?></th>
                                <td class="text-uppercase"><?= $userdata['fullname'] ?></td>
                                <td class="text-uppercase"><?= $userdata['email'] ?></td>
                                <td class="text-uppercase"><?= $userdata['access'] ?></td>
                                <td class="text-capitalize"><?= $userdata['created_date'] ?></td>
                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white bg-primary"
                                        title="Publish">
                                        View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <nav aria-label="Page navigation" id="pagination-container">
                <ul class="pagination justify-content-center mt-5">
                    <?php
                    $stmt = $pdo->select($search);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total_pages = isset($row['total']) ? ceil($row['total'] / $limit) : 1;
                    $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                    $delta = 2; // Number of pages to show around the current page
                    
                    if ($total_pages >= 1) {
                        // Previous page link
                        if ($current_page > 1) {
                            echo '<li class="page-item"><a class="page-link pagination-link" href="?page=' . ($current_page - 1) . '">Previous</a></li>';
                        }

                        // First page link
                        if ($current_page > $delta + 1) {
                            echo '<li class="page-item"><a class="page-link pagination-link" href="?page=1">1</a></li>';
                            if ($current_page > $delta + 2) {
                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                            }
                        }

                        // Page links around the current page
                        for ($i = max(1, $current_page - $delta); $i <= min($total_pages, $current_page + $delta); $i++) {
                            echo '<li class="page-item ' . ($current_page == $i ? 'active' : '') . '"><a class="page-link pagination-link" href="?page=' . $i . '">' . $i . '</a></li>';
                        }

                        // Last page link
                        if ($current_page < $total_pages - $delta) {
                            if ($current_page < $total_pages - $delta - 1) {
                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link pagination-link" href="?page=' . $total_pages . '">' . $total_pages . '</a></li>';
                        }

                        // Next page link
                        if ($current_page < $total_pages) {
                            echo '<li class="page-item"><a class="page-link pagination-link" href="?page=' . ($current_page + 1) . '">Next</a></li>';
                        }
                    }
                    ?>
                </ul>
            </nav>
</div>