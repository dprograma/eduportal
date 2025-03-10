<div class="col-lg-9 col-md-8">
    <div class="mb-4">
        <h1 class="mb-0 h3">Welcome to Your Dashboard, <?= explode(' ', $currentUser->fullname)[0] ?>!</h1>
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
                    <h3 class="mb-0 mt-4"><?= $totalVerifiedUsers ? formatNumber($totalVerifiedUsers, 0) : 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Unverified Users</span>
                    <h3 class="mb-0 mt-4"><?= $totalUnverifiedUsers ? formatNumber($totalUnverifiedUsers, 0) : 0; ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Past Questions</span>
                    <h3 class="mb-0 mt-4"><?= $totalQuestionsUploaded ? formatNumber($totalQuestionsUploaded, 0) : 0; ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Revenue<br />&nbsp;</span>
                    <h3 class="mb-0 mt-4">₦<?= $totalAmount ? formatNumber($totalAmount, 2) : formatNumber(0, 2); ?>
                    </h3>
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
                                        class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white bg-primary view-user-btn"
                                        data-id="<?= $userdata['id'] ?>" data-bs-toggle="modal"
                                        data-bs-target="#userDetailsModal" title="View User Details">
                                        View
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white bg-warning edit-user-btn"
                                        data-id="<?= $userdata['id'] ?>" data-bs-toggle="modal"
                                        data-bs-target="#editUserModal" title="Edit User Details">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- User Details Modal -->
        <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col-md-4"><strong><img id="userImage" class="img-thumbnail" src=""
                                            alt="" /></strong></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Full Name:</strong></div>
                                <div class="col-md-8" id="userFullName"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Email:</strong></div>
                                <div class="col-md-8" id="userEmail"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Access Level:</strong></div>
                                <div class="col-md-8" id="userAccessLevel"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Year Joined:</strong></div>
                                <div class="col-md-8" id="userYear"></div>
                            </div>
                            <!-- Add more fields as needed -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm">
                            <input type="hidden" id="editUserId" name="id">
                            <div class="mb-3">
                                <label for="editUserFullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="editUserFullName" name="fullname" required>
                            </div>
                            <div class="mb-3">
                                <label for="editUserEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editUserEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="editUserAccessLevel" class="form-label">Access Level</label>
                                <select class="form-control" id="editUserAccessLevel" name="access">
                                    <option value="guest">Guest</option>
                                    <option value="secured">Secured</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.view-user-btn').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                // Fetch user details via AJAX
                fetch(`user-details?id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate modal with user details
                            document.getElementById('userImage').src = data.user.profileimg;
                            document.getElementById('userFullName').textContent = data.user.fullname;
                            document.getElementById('userEmail').textContent = data.user.email;
                            document.getElementById('userAccessLevel').textContent = data.user.access;
                            document.getElementById('userYear').textContent = data.user.created_date;
                        } else {
                            Swal.fire('Error!', `Error fetching user details.`, 'error');

                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-user-btn').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                // Fetch user details via AJAX
                fetch(`user-details?id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate the edit form with user details
                            document.getElementById('editUserId').value = userId;
                            document.getElementById('editUserFullName').value = data.user.fullname;
                            document.getElementById('editUserEmail').value = data.user.email;
                            document.getElementById('editUserAccessLevel').value = data.user.access;
                        } else {
                            Swal.fire('Error!', `Error fetching user details.`, 'error');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        document.getElementById('editUserForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            // Update user details via AJAX
            fetch('update-user', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log("user data: ", data.success);
                    if (data.success) {
                        Swal.fire('Updated!', `User details updated successfully.`, 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error!', `Error fetching user details.`, 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>