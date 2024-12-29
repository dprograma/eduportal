<div class="col-md-9 col-md-8">
    <section class="content bg-white p-4 rounded pb-9 mb-8">
        <div>
            <div class="text-center fw-bolder text-uppercase m-4">Edit Post</div>
            <?php if (isset($_GET['error'])): ?>
                <div class="text-center alert alert-<?= $_GET['type'] ?>" role="alert"><?= $_GET['error'] ?></div>
            <?php endif; ?>

            <div class="edit-post-container">
                <form class="card px-3 bg-none edit-post-form" id="" enctype="multipart/form-data" method="POST">
                    <div class="form-group mb-3 exam-div">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" value="" id="title" name="title">
                    </div>

                    <div class="form-group mb-3">
                        <label for="category">Category</label>
                        <input type="text" class="form-control" value="" id="category" name="category">
                    </div>

                    <div class="form-group mb-3">
                        <label for="question">Post Body</label>
                        <textarea class="form-control" id="body" rows="3" name="body"></textarea>
                    </div>
                    <div class="form-group mb-3 text-center">
                        <label class="custom-file">
                            <img src="" class="img-fluid text-center" style="width:100px" alt="...">
                            <input type="file" name="upload" id="" placeholder="" class="custom-file-input"
                                aria-describedby="fileHelpId">
                            <span class="custom-file-control"></span>
                        </label>
                        <small id="fileHelpId"
                            class="form-text text-white w-25 m-auto btn-primary cursor-pointer">Select
                            image</small>

                    </div>

                    <input type="hidden" id="editPostId" name="create-post">
                    <div style="padding: 40px">
                        <button type="submit" class="w-100 text-center btn-primary p-7" style="padding: 10px">Create
                            News</button>
                    </div>
                </form>
            </div>
        </div>
</div>
</section>
</div>