<div class="col-lg-3 col-md-4">
    <div class="d-flex align-items-center mb-4 justify-content-center justify-content-md-start">
    <img src="<?= file_exists($currentUser->profileimg)? $currentUser->profileimg : 'assets/images/avatar/fallback.jpg' ?>" alt="avatar"
    class="avatar avatar-sm rounded-circle" />
        <div class="ms-3">
            <h5 class="mb-0"><?= $currentUser->fullname; ?></h5>
            <small>Agent account</small>
        </div>
    </div>
    <div class="d-md-none text-center d-grid">
        <button class="btn btn-light mb-3 d-flex align-items-center justify-content-between" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapseAccountMenu" aria-expanded="false"
            aria-controls="collapseAccountMenu">
            Account Menu
            <i class="bi bi-chevron-down ms-2"></i>
        </button>
    </div>
    <div class="collapse d-md-block" id="collapseAccountMenu">
        <ul class="nav flex-column nav-account">
        <li class="nav-item">
            <a class="nav-link" href="agent-dashboard">
                <i class="align-bottom bx bx-home"></i>
                <span class="ms-2">Dashboard</span>
            </a>
        </li>
            <li class="nav-item">
                <a class="nav-link" href="upload-past-question">
                    <i class="align-bottom bx bx-upload"></i>
                    <span class="ms-2 fs-6">Upload Documents (pdf)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view-current-agent-past-questions">
                    <i class="align-bottom bx bx-file"></i>
                    <span class="ms-2 fs-6">View Documents</span>
                </a>
            </li>
        </ul>
    </div>
</div>