<div class="col-lg-9 col-md-8">
    <?php if (isset($_GET['error'])): ?>
        <div class="text-center alert alert-<?= $_GET['type'] ?>" role="alert"><?= $_GET['error'] ?></div>
    <?php endif; ?>
    <div class="container my-3 float-start" id="search-form">
        <form method="get">
            <div class="row g-3 pe-1 ps-1">
                <div class="col-12 col-md-3">
                    <input type="text" name="title" class="form-control form-control-sm rounded-0 p-md-2"
                        placeholder="Title" aria-label="Title">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" name="category" class="form-control form-control-sm rounded-0 p-md-2"
                        placeholder="Category" aria-label="Category">
                </div>

                <div class="col-12 col-md-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="" selected>-- Status --</option>
                        <option value="1">Published</option>
                        <option value="0">Unpublished</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <select name="year" class="form-control form-control-sm rounded-0">
                        <option class="rounded-0" value="">Year</option>
                        <?php for ($i = date('Y'); $i >= 1970; $i--) { ?>
                            <option class="rounded-0" value="<?= $i ?>"><?= $i ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm"
                        style="color: #fff; background-color: #347054; border-color: #347054; font-weight: 300; font-size: 16px;">Search</button>
                </div>
            </div>
        </form>

    </div>
    <div class="row my-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">All Posts</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table id="usersTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Title</th>
                            <th scope="col">Category</th>
                            <th scope="col">Date</th>
                            <th scope="col" class="text-center"></th>
                            <th scope="col" class="text-center"></th>
                            <th scope="col" class="text-center"></th>
                        </tr>
                    </thead>
                    <?php $q = 1; ?>
                    <tbody class="post-body bg-white">
                        <?php foreach ($posts as $pos => $post): ?>
                            <tr data-id="<?= $post->id ?>" class="bg-white">
                                <th scope="row"><?= $pos + 1 ?></th>
                                <td class="text-uppercase"><?= $post->title ?></td>
                                <td class="text-capitalize"><?= $post->category ?></td>
                                <td class="text-capitalize"><?= $post->date_created ?></td>




                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white <?= $post->publish == 1 ? 'bg-success' : 'bg-secondary text-white' ?>"
                                        title="Publish"
                                        onclick="confirmPostPublish(<?= $post->id ?>, <?= $post->publish ?>, this)">
                                        <?= $post->publish == 1 ? 'Published' : 'Unpublished' ?>
                                    </button>
                                </td>A

                                <td class="view-modal-trigger">
                                    <a href="edit-post?id=<?= $post->id ?>" class="btn btn-warning btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white edit-link" data-question-id="<?= $post->id ?>">Edit</a>
                                </td>

                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white bg-danger text-white"
                                        title="Delete" onclick="return confirmDelete(<?= $post->id ?>, 'viewpost')">
                                        Delete
                                    </button>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>

                </table>
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
            $delta = 2;

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