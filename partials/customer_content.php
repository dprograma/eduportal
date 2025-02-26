<!-- Header Section with Affiliate Link -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4">
    <!-- Welcome Message -->
    <div>
        <h1 class="mb-0 h3">Welcome to EduPortal, <?= explode(" ", $currentUser->fullname)[0] ?>!</h1>
    </div>
    
    <!-- Affiliate Link Section - Only show for affiliates -->
    <?php if ($currentUser->is_affiliate): ?>
        <!-- Desktop View -->
        <div class="d-none d-md-block ms-3">
            <div class="card border-0 bg-light">
                <div class="card-body p-3">
                    <h6 class="card-title fs-6 mb-2">Your Affiliate Link</h6>
                    <div class="input-group input-group-sm">
                        <input type="text" 
                               class="form-control form-control-sm" 
                               id="affiliateLink" 
                               value="<?= APP_URL ?>/signup?ref=<?= $currentUser->affiliate_code ?>" 
                               readonly>
                        <button class="btn btn-sm btn-primary" 
                                onclick="copyAffiliateLink()" 
                                type="button">
                            <i class="bx bx-copy"></i>
                        </button>
                    </div>
                    <small class="text-muted mt-2 d-block">Share to earn commissions</small>
                </div>
            </div>
        </div>
        
        <!-- Mobile View -->
        <div class="d-md-none w-100 mt-3">
            <div class="card border-0 bg-light">
                <div class="card-body p-3">
                    <h6 class="card-title">Your Affiliate Link</h6>
                    <div class="input-group">
                        <input type="text" 
                               class="form-control form-control-sm" 
                               id="affiliateLinkMobile" 
                               value="<?= APP_URL ?>/signup?ref=<?= $currentUser->affiliate_code ?>" 
                               readonly>
                        <button class="btn btn-sm btn-primary" 
                                onclick="copyAffiliateLinkMobile()" 
                                type="button">
                            <i class="bx bx-copy"></i>
                        </button>
                    </div>
                    <small class="text-muted mt-2 d-block">Share this link to earn commissions</small>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Dashboard Title -->
<div class="mb-5">
    <h4 class="mb-1">My Dashboard</h4>
    <p class="mb-0 fs-6">Access your ebooks, publications, past questions, and CBT tests all in
        one place.</p>
</div>

<!-- Rest of the existing content -->
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

<!-- Add this script at the bottom of the file if not already present -->
<script>
function copyAffiliateLink() {
    var copyText = document.getElementById("affiliateLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
    
    navigator.clipboard.writeText(copyText.value).then(() => {
        // Show a temporary success message
        const btn = document.querySelector('[onclick="copyAffiliateLink()"]');
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="bx bx-check"></i>';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-primary');
        
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-primary');
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}

function copyAffiliateLinkMobile() {
    var copyText = document.getElementById("affiliateLinkMobile");
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
    
    navigator.clipboard.writeText(copyText.value).then(() => {
        // Show a temporary success message
        const btn = document.querySelector('[onclick="copyAffiliateLinkMobile()"]');
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="bx bx-check"></i>';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-primary');
        
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-primary');
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}
</script>