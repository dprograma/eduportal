<div class="col-lg-9 col-md-8">
    <form method="POST" class="modal-content card px-3 bg-none" enctype="multipart/form-data">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-<?= $_GET['type'] ?>" role="alert"><?= $_GET['error'] ?></div>
        <?php endif; ?>
        <div class="form-group mb-3 exam-div">
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
                    echo "<option value='$i'>" . $i . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="documentType">Document Type</label>
            <select class="form-control text-uppercase" id="documentType" name="documentType">
                <option value="Past Question">Past Question</option>
                <option value="Publication">Publication</option>
                <option value="Ebook">Ebook</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="examBody">Upload PDF/Word Document <span><small><i>(2mb max)</i></small></span></label>
            <input type="file" class="form-control text-uppercase" name="fileToUpload" id="fileToUpload">
        </div>

        <div class="input-group mb-4">
            <span class="input-group-text" id="basic-addon1">Price (₦)</span>
            <input type="number" class="form-control text-uppercase" name="price" id="price" value="1000" readonly>
        </div>


        <div class="form-group mb-3">
            <input type="submit" class="w-100 text-center btn-primary p-2" value="Upload Document" name="submit">
        </div>
    </form>
</div>