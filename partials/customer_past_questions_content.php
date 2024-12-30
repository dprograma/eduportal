<h3 class="mb-5">My Past Questions</h3>
<div class="table-responsive">
    <table id="publicationsTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Exam</th>
                <th>Publish Date</th>
                <th>Category</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            <!-- Add PHP to dynamically fetch the ebooks from your database -->
            <?php foreach($pastquestions as $pastquestion): ?>
                <tr>
                    <td><?= $pastquestion['item']; ?></td>
                    <td><?= $pastquestion['exambody']; ?></td>
                    <td><?= $pastquestion['year']; ?></td>
                    <td><?= $pastquestion['document_type']; ?></td>
                    <td><a href="downloadfile?sku=<?=$pastquestion['sku']?>" class="btn btn-primary btn-sm"><i class="bx bx-download"></i></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
