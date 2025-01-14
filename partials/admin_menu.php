<div class="col-lg-3 col-md-4">
    <div class="d-flex align-items-center mb-4 justify-content-center justify-content-md-start">
    <img src="<?= file_exists($currentUser->profileimg)? $currentUser->profileimg : 'assets/images/avatar/fallback.jpg' ?>" alt="avatar"
    class="avatar avatar-sm rounded-circle" />
        <div class="ms-3">
            <h5 class="mb-0"><?= $currentUser->fullname; ?></h5>
            <small>Admin account</small>
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
            <a class="nav-link" href="admin-dashboard">
                <i class="align-bottom bx bx-home"></i>
                <span class="ms-2">Dashboard</span>
            </a>
        </li>
            <li class="nav-item">
                <a class="nav-link" href="upload-past-question" title="upload ebooks, past questions and publications.">
                    <i class="align-bottom bx bx-upload"></i>
                    <span class="ms-2 fs-6">Upload Document (pdf)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view-agent-past-questions" title="view agent uploaded past questions, ebooks and publications.">
                    <i class="align-bottom bx bx-file"></i>
                    <span class="ms-2 fs-6">View Agent Past Questions</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view-affiliate-past-questions" title="view affiliate uploaded past questions, ebooks and publications.">
                    <i class="align-bottom bx bx-file"></i>
                    <span class="ms-2 fs-6">View Affiliate Past Questions</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="create-past-question" title="create past questions.">
                    <i class="align-bottom bx bx-plus"></i>
                    <span class="ms-2 fs-6">Create CBT Questions</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view-past-questions" title="view past questions">
                    <i class="align-bottom bx bx-book"></i>
                    <span class="ms-2 fs-6">View CBT Questions</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view-withdrawal-requests" title="view affiliate uploaded past questions, ebooks and publications.">
                    <i class="align-bottom bx bx-file"></i>
                    <span class="ms-2 fs-6">View Withdrawal Requests</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="create-post" title="Create news for ebooks, past questions and publications.">
                    <i class="align-bottom bx bx-news"></i>
                    <span class="ms-2 fs-6">Create News</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="viewpost" title="view news for ebooks, past questions and publications.">
                    <i class="align-bottom bx bx-news"></i>
                    <span class="ms-2 fs-6">View News</span>
                </a>
            </li>
        </ul>
    </div>
</div>