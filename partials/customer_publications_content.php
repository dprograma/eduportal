<h3 class="mb-5">My Publications</h3>
<div class="table-responsive">
    <table id="publicationsTable" class="table table-striped table-bordered">
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
            <?php foreach($publications as $publication): ?>
                <tr>
                    <td><?= $publication['title']; ?></td>
                    <td><?= $publication['author']; ?></td>
                    <td><?= $publication['created_at']; ?></td>
                    <td><?= $publication['document_type']; ?></td>
                    <td><a href="downloadfile?sku=<?=$publication['sku']?>" class="btn btn-primary btn-sm"><i class="bx bx-download"></i></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
