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
                    <span>Total Verified Users</span>
                    <h3 class="mb-0 mt-4">1,245</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Unverified Users</span>
                    <h3 class="mb-0 mt-4">256</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Past Questions</span>
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
                                        Edit
                                    </button>
                                </td>
                                <td>
                                    <button type="button"
                                        class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white bg-danger text-white"
                                        title="Delete">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Add more rows as necessary -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>