<div class="mb-4">
    <h1 class="mb-0 h3">Welcome to EduPortal, <?= explode(" ", $currentUser->fullname)[0] ?>!</h1>
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
                <h3 class="mb-0 mt-4"><?= $totallibrarycount; ?> Items</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <span>Total Purchases</span>
                <h3 class="mb-0 mt-4">₦ <?= $totaltransactionamount; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <span>CBT Test Results</span>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h3 class="mb-0"><?= $score == 0 ? 'N/A' : $score . '%'; ?></h3>
                    <span style="font-size: 12px;"><?= isset($cbtsubject) ? $cbtsubject : ''; ?></span>
                </div>
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