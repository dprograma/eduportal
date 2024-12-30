    <div class="col-lg-9 col-md-8">
        <?php if (isset($_GET['error'])): ?>
            <div class="text-center alert alert-<?= $_GET['type'] ?>" role="alert"><?= $_GET['error'] ?></div>
        <?php endif; ?>
        <div class="container my-3 float-start" id="search-form">
            <form method="get">
                <div class="row g-3 pe-1 ps-1">
                    <div class="col-12 col-md-3">
                        <input type="text" name="fullname" class="form-control form-control-sm rounded-0 p-md-2"
                            placeholder="Full name" aria-label="Fullname">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="text" name="email" class="form-control form-control-sm rounded-0 p-md-2"
                            placeholder="Email" aria-label="Email">
                    </div>
                    <div class="col-12 col-md-2">
                        <select name="status" class="form-control form-control-sm">
                            <option value="" selected>-- Status --</option>
                            <option value="1">Published</option>
                            <option value="0">Unpublished</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <select name="year" class="form-control form-control-sm rounded-0 p-md-2">
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
        
        <div class="table-responsive">
            <table id="usersTable" class="table table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">Name</th>
                        <th scope="col">SKU</th>
                        <th scope="col">Exam Body</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Exam Year</th>
                        <th scope="col">Date</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center"></th>
                    </tr>
                </thead>
                <?php $q = 0; ?>

                <!-- post body for past questions                -->
                <tbody class="post-body bg-white">
                    <?php foreach ($questions as $question): ?>
                        <tr data-id="<?= $question->id ?>" class="bg-white">
                            <th scope="row"><?= ++$rows ?></th>
                            <td class="text-uppercase"><?= $question->fullname ?></td>
                            <td class="text-uppercase"><?= $question->sku ?></td>
                            <td class="text-uppercase"><?= $question->exam_body ?></td>
                            <td class="text-capitalize"><?= $question->subject ?></td>
                            <td class="text-capitalize"><?= $question->year ?></td>
                            <td class="text-capitalize"><?= $question->created_at ?></td>
                            <td class="text-center">
                                <button type="button"
                                    class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white <?= $question->published == 1 ? 'bg-success' : 'bg-secondary text-white' ?>"
                                    title="Publish" data-status="<?= $question->published ?>"
                                    onclick="confirmPublish(<?= $question->id ?>, '<?= $url ?>', this)">
                                    <?= $question->published == 1 ? 'Published' : 'Unpublished' ?>
                                </button>
                            </td>
                            <td class="text-center">
                                <button type="button"
                                    class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white bg-danger text-white"
                                    title="Delete"
                                    onclick="return confirmDelete(<?= $question->id ?>, 'view-past-questions')">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

                <!-- end of post body for past questions -->


            </table>
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
    </div>