<div class="col-lg-9 col-md-8">
    <!-- Data Table for Users -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header">
            <h5 class="card-title">Withdrawal Requests</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-hover table-bordered">
                    <thead class="table-light fs-6">
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Agent Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="fs-6">
                        <!-- Example Row -->
                        <?php foreach ($requests as $request): ?>
                            <tr data-id="<?= $request->id ?>" class="bg-white">
                                <th scope="row"><?= ++$rows ?></th>
                                <td class="text-uppercase"><?= $request->fullname ?></td>
                                <td class="text-uppercase"><?= $request->email ?></td>
                                <td class="text-uppercase"><?= $request->is_agent == 1 ? 'Agent' : 'Affiliate' ?></td>
                                <td class="text-capitalize"><?= $request->amount ?></td>
                                <td class="text-capitalize"><?= $request->status ?></td>
                                <td class="text-capitalize"><?= $request->created_at ?></td>
                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white <?=$request->status == 'Approved' ? 'bg-success': 'bg-primary'; ?> approve-request-btn"
                                        data-id="<?= $request->id ?>">
                                        <?=$request->status == 'Approved' ? 'Approved': 'Approve'; ?>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white bg-danger decline-request-btn"
                                        data-id="<?= $request->id ?>">
                                        Disapprove
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Approval withdrawal request
        document.querySelectorAll('.approve-request-btn').forEach(button => {
            button.addEventListener('click', function () {
                const withdrawalId = this.getAttribute('data-id');
                const buttonElement = this; 
                // Fetch user details via AJAX
                const approveWithdrawal = async (withdrawalId) => {
                    try {
                        const response = await fetch(`approve-withdrawal?id=${withdrawalId}`);
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire({
                                title: 'Withdrawal Request',
                                text: `${data.message}`,
                                icon: 'success',
                            });

                            // Update the button text and class
                            buttonElement.textContent = 'Approved';
                            buttonElement.classList.remove('bg-primary');
                            buttonElement.classList.add('btn-success');
                            buttonElement.disabled = true; 
                        } else {
                            Swal.fire({
                                title: 'Withdrawal Request',
                                text: `${data.message}`,
                                icon: 'error',
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            title: 'Withdrawal Request',
                            text: 'An error occurred while processing your request. Please try again.',
                            icon: 'error',
                        });
                    }
                };

                // Confirm withdrawal approval
                Swal.fire({
                    title: `Confirmation Withdrawal`,
                    text: `Do you want to approve this withdrawal request?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: `Yes, approve it!`
                }).then((result) => {
                    if (result.isConfirmed) {
                        approveWithdrawal(withdrawalId);
                    }
                });
            });
        });

        // Decline withdrawal request
        document.querySelectorAll('.decline-request-btn').forEach(button => {
            button.addEventListener('click', function () {
                const declinelId = this.getAttribute('data-id');
                const buttonElement = document.querySelector('.approve-request-btn'); 

                // Fetch user details via AJAX
                const declineWithdrawal = async (declineId) => {
                    try {
                        const response = await fetch(`decline-withdrawal?id=${declineId}`);
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire({
                                title: 'Decline Withdrawal Request',
                                text: `${data.message}`,
                                icon: 'success',
                            });

                            // Update the button text and class
                            buttonElement.disabled = false; 
                            buttonElement.textContent = 'Approve';
                            buttonElement.classList.remove('btn-success');
                            buttonElement.classList.add('bg-primary');
                        } else {
                            Swal.fire({
                                title: 'Decline Withdrawal Request',
                                text: `${data.message}`,
                                icon: 'error',
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            title: 'Decline Withdrawal Request',
                            text: 'An error occurred while processing your request. Please try again.',
                            icon: 'error',
                        });
                    }
                };

                // Confirm withdrawal approval
                Swal.fire({
                    title: `Decline Withdrawal`,
                    text: `Do you want to disapprove this withdrawal request?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: `Yes, disapprove it!`
                }).then((result) => {
                    if (result.isConfirmed) {
                        declineWithdrawal(declinelId);
                    }
                });
            });
        });

    });

</script>