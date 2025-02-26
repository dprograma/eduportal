<?php
// Pagination setup
$postsPerPage = 6;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalPosts = count($posts);
$totalPages = ceil($totalPosts / $postsPerPage);

// Calculate the offset
$offset = ($currentPage - 1) * $postsPerPage;

// Fetch the posts for the current page
$paginatedPosts = array_slice($posts, $offset, $postsPerPage);
?>


<div class="container my-5" id="newsHub">
    <!-- Header Section -->
    <section class="text-center py-5">
        <h2 class="fw-bold h3 mt-9">Welcome to <?= SITE_TITLE ?> News Hub!</h2>
        <p class="lead text-muted mt-3">
            Weekly published articles, news, blogs, and much more. Our blog extends the weekend's message into
            actionable next steps, enabling practical implementation.
        </p>
    </section>
<div id="postsContainer" class="row gy-4">
    <?php foreach ($paginatedPosts as $post): ?>
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm h-100">
                <img src="<?= htmlspecialchars($post['img']); ?>" class="card-img-top" alt="<?= htmlspecialchars($post['title']); ?>" />
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-uppercase fw-bold text-primary">
                        <?= htmlspecialchars($post['title']); ?>
                    </h5>
                    <p class="text-muted mb-2">
                        <span class="fw-bold">Category:</span> <?= htmlspecialchars($post['category']); ?>
                    </p>
                    <p class="card-text text-muted">
                        <?= htmlspecialchars(substr($post['body'], 0, 150)); ?>...
                    </p>
                    <small class="text-muted">
                        <?= date('F j, Y', strtotime($post['date_created'])); ?>
                    </small>
                    <div class="mt-auto">
                        <a href="blogdetails?title=<?= urlencode(str_replace(' ', '_', $post['title'])); ?>" 
                           class="btn btn-primary btn-sm mt-3">
                            Learn More <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</div>

<!-- Pagination Controls -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center" id="paginationControls">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $currentPage ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
