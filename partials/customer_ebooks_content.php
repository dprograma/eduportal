<h3 class="mb-5">My Ebooks</h3>
<div class="table-responsive">
    <table id="ebooksTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Publish Date</th>
                <th>Category</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            <!-- Add PHP to dynamically fetch the ebooks from your database -->
            <?php foreach($ebooks as $ebook): ?>
                <tr>
                    <td><?= $ebook['title']; ?></td>
                    <td><?= $ebook['author']; ?></td>
                    <td><?= $ebook['created_at']; ?></td>
                    <td><?= $ebook['document_type']; ?></td>
                    <td><a href="#" class="btn btn-primary btn-sm"><i class="bx bx-download"></i></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
