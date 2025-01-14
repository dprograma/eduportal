<div class="container my-5" id="newsHub">
    <!-- Header Section -->
    <section class="text-center py-5">
        <h2 class="fw-bold h3 mt-9">Welcome to <?= SITE_TITLE ?> News Hub!</h2>
        <p class="lead text-muted mt-3">
            Weekly published articles, news, blogs, and much more. Our blog extends the weekend's message into
            actionable next steps, enabling practical implementation.
        </p>
    </section>

    <!-- Blog Posts Section -->
    <div id="postsContainer" class="row gy-4">
        <!-- Posts will be dynamically added here -->
    </div>

    <!-- Pagination Controls -->
    <div class="d-flex justify-content-center my-4">
        <nav>
            <ul class="pagination" id="paginationControls">
                <!-- Pagination buttons will be dynamically generated here -->
            </ul>
        </nav>
    </div>
</div>