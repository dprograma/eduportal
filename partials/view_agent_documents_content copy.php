<div class="col-lg-9 col-md-8">
    <?php if (isset($_GET['error'])): ?>
        <div class="text-center alert alert-<?= $_GET['type'] ?>" role="alert"><?= $_GET['error'] ?></div>
    <?php endif; ?>

    <ul class="nav nav-tabs" id="documentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="past-questions-tab" data-bs-toggle="tab" data-bs-target="#past-questions" type="button" role="tab" aria-controls="past-questions" aria-selected="true">Past Questions</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ebooks-tab" data-bs-toggle="tab" data-bs-target="#ebooks" type="button" role="tab" aria-controls="ebooks" aria-selected="false">Ebooks</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="publications-tab" data-bs-toggle="tab" data-bs-target="#publications" type="button" role="tab" aria-controls="publications" aria-selected="false">Publication/Project material</button>
        </li>
    </ul>

    <div class="tab-content" id="documentTabsContent">
        <!-- Past Questions Tab -->
        <div class="tab-pane fade show active" id="past-questions" role="tabpanel" aria-labelledby="past-questions-tab">
            <!-- Existing Past Questions Table -->
            <h5 class="card-title">All Agent Past Questions</h5>
            <div class="table-responsive">
                <table id="pastQuestionsTable" class="table table-hover table-bordered">
                    <thead class="table-light fs-6">
                        <tr>
                            <th scope="col">ID</th>
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
                    <tbody>
                        <?php foreach ($questions as $question): ?>
                            <tr>
                                <th><?= ++$rows ?></th>
                                <td><?= $question->fullname ?></td>
                                <td><?= $question->sku ?></td>
                                <td><?= $question->exam_body ?></td>
                                <td><?= $question->subject ?></td>
                                <td><?= $question->year ?></td>
                                <td><?= $question->created_at ?></td>
                                <td><?= $question->published ? 'Published' : 'Unpublished' ?></td>
                                <td>
                                    <button onclick="confirmDelete(<?= $question['id'] ?>, 'past-questions')">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ebooks Tab -->
        <div class="tab-pane fade" id="ebooks" role="tabpanel" aria-labelledby="ebooks-tab">
            <h5 class="card-title">All Agent Ebooks</h5>
            <div class="table-responsive">
                <table id="ebooksTable" class="table table-hover table-bordered">
                    <thead class="table-light fs-6">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Title</th>
                            <th scope="col">Author</th>
                            <th scope="col">ISBN</th>
                            <th scope="col">SKU</th>
                            <th scope="col">Page Cover</th>
                            <th scope="col">Date</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $ebook): ?>
                            <tr>
                                <td><?= ++$rows?></td>
                                <td><?= $ebook->title ?></td>
                                <td><?= $ebook->author ?></td>
                                <td><?= $ebook->isbn ?></td>
                                <td><?= $ebook->sku ?></td>
                                <td><img src="data:image/jpeg;base64,<?= $ebook->page_cover ?>" alt="Page Cover" width="100"></td>
                                <td><?= $ebook->created_at ?></td>
                                <td><?= $ebook->published ? 'Published' : 'Unpublished' ?></td>
                                <td>
                                    <button onclick="confirmDelete(<?= $ebook['id'] ?>, 'ebooks')">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Publications Tab -->
        <div class="tab-pane fade" id="publications" role="tabpanel" aria-labelledby="publications-tab">
            <h5 class="card-title">All Agent Publications</h5>
            <div class="table-responsive">
                <table id="publicationsTable" class="table table-hover table-bordered">
                    <thead class="table-light fs-6">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Title</th>
                            <th scope="col">Author</th>
                            <th scope="col">SKU</th>
                            <th scope="col">Date</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $publication): ?>
                            <tr>
                                <td><?= ++$rows ?></td>
                                <td><?= $publication->title ?></td>
                                <td><?= $publication->author ?></td>
                                <td><?= $publication->sku ?></td>
                                <td><?= $publication->created_at ?></td>
                                <td><?= $publication->published ? 'Published' : 'Unpublished' ?></td>
                                <td>
                                    <button onclick="confirmDelete(<?= $publication['id'] ?>, 'publications')">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
