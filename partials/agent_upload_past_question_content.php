<div class="col-lg-9 col-md-8">
<div class="container mt-4">
    <ul class="nav nav-tabs" id="uploadTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="past-question-tab" data-bs-toggle="tab" data-bs-target="#past-question"
                type="button" role="tab" aria-controls="past-question" aria-selected="true">Past Question</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ebook-tab" data-bs-toggle="tab" data-bs-target="#ebook" type="button"
                role="tab" aria-controls="ebook" aria-selected="false">Ebook</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="publication-tab" data-bs-toggle="tab" data-bs-target="#publication"
                type="button" role="tab" aria-controls="publication" aria-selected="false">publication/project material</button>
        </li>
    </ul>

    <div class="tab-content" id="uploadTabsContent">
        <!-- Past Question Form -->
        <div class="tab-pane fade show active" id="past-question" role="tabpanel" aria-labelledby="past-question-tab">
            <form method="POST" class="modal-content card px-3 bg-none" enctype="multipart/form-data">
            <?php if (isset($_GET['error'])): ?>
                  <?php
                     $alertClass = 'alert-' . ($_GET['type'] === 'success' ? 'success' : 'danger');
                     $message    = htmlspecialchars($_GET['error']);
                  ?>
                     <div class="alert<?php echo $alertClass ?> alert-dismissible fade show" role="alert">
                        <?php echo $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                  <?php endif; ?>
                <div class="form-group mb-3">
                    <label for="examBody">Exam Body</label>
                    <select class="form-control text-uppercase" id="examBody" name="examBody">
                        <option value="WAEC">WAEC</option>
                        <option value="NECO">NECO</option>
                        <option value="JAMB">JAMB</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control text-uppercase" id="subject" name="subject">
                </div>
                <div class="form-group mb-3">
                    <label for="examYear">Exam Year</label>
                    <select class="form-control text-uppercase" id="examYear" name="examYear">
                        <?php
                        $currentyear = date('Y');
                        for ($i = $currentyear; $i >= 1970; $i--) {
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="documentType" value="past-question">
                <div class="form-group mb-3">
                    <label for="fileToUpload">Upload PDF/Word Document (2mb max)</label>
                    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text">Price (₦)</span>
                    <input type="number" class="form-control" name="price" id="price" value="1000" readonly>
                </div>
                <button type="submit" class="btn btn-primary w-100" name="submit">Upload Past Question</button>
            </form>
        </div>

        <!-- Ebook Form -->
        <div class="tab-pane fade" id="ebook" role="tabpanel" aria-labelledby="ebook-tab">
            <form method="POST" class="modal-content card px-3 bg-none" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="title">Title</label>
                    <input type="text" class="form-control text-uppercase" id="title" name="title">
                </div>
                <div class="form-group mb-3">
                    <label for="author">Author</label>
                    <input type="text" class="form-control text-uppercase" id="author" name="author">
                </div>
                <div class="form-group mb-3">
                    <label for="author">ISBN</label>
                    <input type="text" class="form-control text-uppercase" id="isbn" name="isbn">
                </div>
                <div class="form-group mb-3">
                    <label for="page_cover">Page Cover</label>
                    <input type="file" class="form-control text-uppercase" id="page_cover" name="page_cover">
                </div>
                <div class="form-group mb-3">
                    <label for="author">ISBN</label>
                    <input type="text" class="form-control text-uppercase" id="isbn" name="isbn">
                </div>
                <div class="form-group mb-3">
                    <label for="examYear">Book Year</label>
                    <select class="form-control text-uppercase" id="examYear" name="examYear">
                        <?php
                        $currentyear = date('Y');
                        for ($i = $currentyear; $i >= 1970; $i--) {
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="documentType" value="ebook">
                <div class="form-group mb-3">
                    <label for="fileToUpload">Upload PDF/Word Document (2mb max)</label>
                    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text">Price (₦)</span>
                    <input type="number" class="form-control" name="price" id="price">
                </div>
                <button type="submit" class="btn btn-primary w-100" name="submit">Upload Ebook</button>
            </form>
        </div>

        <!-- Publication Form -->
        <div class="tab-pane fade" id="publication" role="tabpanel" aria-labelledby="publication-tab">
            <form method="POST" class="modal-content card px-3 bg-none" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="title">Title</label>
                    <input type="text" class="form-control text-uppercase" id="title" name="title">
                </div>
                <div class="form-group mb-3">
                    <label for="author">Author</label>
                    <input type="text" class="form-control text-uppercase" id="author" name="author">
                </div>
                <div class="form-group mb-3">
                    <label for="examYear">Publication Year</label>
                    <select class="form-control text-uppercase" id="examYear" name="examYear">
                        <?php
                        $currentyear = date('Y');
                        for ($i = $currentyear; $i >= 1970; $i--) {
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="documentType" value="publication">
                <div class="form-group mb-3">
                    <label for="fileToUpload">Upload PDF/Word Document (2mb max)</label>
                    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text">Price (₦)</span>
                    <input type="number" class="form-control" name="price" id="price">
                </div>
                <button type="submit" class="btn btn-primary w-100" name="submit">Upload Publication</button>
            </form>
        </div>
    </div>
</div>
</div>