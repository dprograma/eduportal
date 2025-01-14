<div class="container-fluid" style="position: static; margin-top: 100px;">
    <div class="row mx-auto justify-content-center align-content-center text-center" style="padding-bottom: 50px;">
        <h1 class="h2 mb-0 sub-section-header">E-BOOK SHOP</h1>
    </div>
</div>

<div class="container mb-3" id="search-form">
    <form method="get">
        <div class="row g-3 pe-1 ps-1">
            <div class="col-12 col-md-3">
                <input type="text" name="subject" class="form-control rounded-0 p-md-2" placeholder="Subject"
                    aria-label="Subject">
            </div>
            <div class="col-12 col-md-3">
                <input type="text" name="exam_body" class="form-control rounded-0 p-md-2" placeholder="Exam Body"
                    aria-label="Exam Body">
            </div>

            <div class="col-12 col-md-3">
                <select name="year" class="form-control rounded-0 p-md-2">
                    <option class="rounded-0" value="">Year</option>
                    <?php for ($i = date('Y'); $i >= 1970; $i--) { ?>
                        <option class="rounded-0" value="<?= $i ?>"><?= $i ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-primary p-md-2"
                    style="color: #fff; background-color: #347054; border-color: #347054; font-weight: 300; font-size: 16px;">Search</button>
            </div>
        </div>
    </form>

</div>

<div id="items-section" class="container text-center">
    <div class="row">
        <div class="col-md-12">
            <div class="list-group rounded-0">
                <?php

                // Retrieve search parameters from the form
                $cart_list = [];
                $subject_name = $_GET['subject'] ?? '';
                $exam_body = $_GET['exam_body'] ?? '';
                $subject_year = $_GET['year'] ?? '';
                // $carts = $_SESSION['cart'] ?? '';
                if (isset($_COOKIE['cart'])) {
                    // Unserialize the cart data from the cookie
                    $carts = unserialize($_COOKIE['cart']);
                    // print_r($carts);
                } else {
                    $carts = [];
                }
                foreach ($carts as $sku => $cart) {
                    $cart_list[] = $sku;
                }

                $add_style = "color: #fff; background-color: #781515; border-color: #781515;font-size: 12px; padding: 5px;";
                $added_style = "color: #fff; background-color: #347054; border-color: #347054;font-size: 12px; padding: 5px;";
                // Pagination
                $limit = 6; // Number of items per page
                $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
                $offset = ($page - 1) * $limit; // Offset for the query
                
                try {
                    $query = "SELECT * FROM document WHERE document_type='ebook' AND `published`= 1 LIMIT $limit OFFSET $offset";
                    $search = "SELECT count(*) as total FROM document WHERE document_type='ebook' AND `published`= 1 LIMIT $limit";
                    if (!empty($subject_name || $exam_body || $subject_year)) {
                        $query = "SELECT * FROM document WHERE document_type='ebook' AND `subject` LIKE '%$subject_name%' AND `exam_body` LIKE '%$exam_body%' AND `year` LIKE '%$subject_year%' AND `published` = 1  LIMIT $limit";
                        $search = "SELECT count(*) as total from document WHERE document_type='ebook' AND `subject` LIKE '%$subject_name%' AND `exam_body` LIKE '%$exam_body%' AND `year` LIKE '%$subject_year%' AND `published` = 1 LIMIT $limit";
                    }


                    $documents = $pdo->select($query)->fetchAll(PDO::FETCH_ASSOC);

                    // Loop through the documents and display them in cards
                    foreach ($documents as $document) {
                        ?>
                        <div class="list-group-item d-flex align-items-center p-2">
                            <!-- Thumbnail image on the left -->
                            <img src="<?= $document['coverpage']; ?>" class="img-thumbnail mr-3" alt="Thumbnail"
                                style="height: 50px; width: 50px;">
                            <div class="ms-4 text-start">
                                <!-- Past Question name -->
                                <h6 class="mb-1"><?= ucwords($document['subject']); ?> Past Question</h6>
                                <!-- Exam body and year -->
                                <p class="mb-1"><?= $document['exam_body']; ?> (<?= $document['year']; ?>)</p>
                                <!-- Price in bold -->
                                <h6 class="font-weight-bold text-success">₦<?= $document['price']; ?></h6>
                            </div>
                            <div class="ms-auto">
                                <!-- Buy button on the right -->
                                <form action="add-to-cart" method="post">
                                    <input type="hidden" name="sku" value="<?= $document['sku']; ?>">
                                    <input type="hidden" name="price" value="<?= $document['price']; ?>">
                                    <input type="hidden" name="subject" value="<?= $document['subject']; ?>">
                                    <input type="hidden" name="exam_body" value="<?= $document['exam_body']; ?>">
                                    <input type="hidden" name="year" value="<?= $document['year']; ?>">
                                    <input type="hidden" name="coverpage" value="<?= $document['coverpage']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="submit" class="btn btn-primary"
                                        style="<?php echo (in_array($document['sku'], $cart_list)) ? $added_style : $add_style; ?>"
                                        value="<?php echo (in_array($document['sku'], $cart_list)) ? 'Added To Cart' : 'Add To Cart'; ?>" />
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                } catch (\PDOException $e) {
                    $msg = "Error: " . $e->getMessage();
                    redirect('purchase-past-question.php', $msg);
                    exit;
                }
                ?>
            </div>
            <!-- Pagination links -->
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
</div>