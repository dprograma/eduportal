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