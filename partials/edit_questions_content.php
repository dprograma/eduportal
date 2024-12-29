<div class="col-md-9 col-md-8">
    <section class="content bg-white p-4 rounded pb-9 mb-8">
        <div>


            <div class="text-center fw-bolder text-uppercase m-4">Edit question</div>
            <?php if (isset($_GET['error'])): ?>
                <div class="text-center alert alert-<?= $_GET['type'] ?>" role="alert"><?= $_GET['error'] ?><br />
                    <?php echo $_SESSION['referer'] ? '<p><a href="javascript:history.go(-1)"><< Back to Questions</a></p>' : ''; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['referer'])):
                unset($_SESSION['referer']);
                unset($_SESSION['initial_referer']); ?>
                <div class="text-center alert alert-success" role="alert">
                    <p class="text-center">Question Updated Successfully</p>
                    <p class="text-center"><a href="javascript:history.go(-1)">
                            << Back to Questions</a>
                    </p>
                </div>
            <?php endif; ?>
            <?php foreach ($questions as $question): ?>
                <div class="edit-form-container">

                    <form class=" card px-3 bg-none edit-form" id="editForm<?= $question->id ?>"
                        enctype="multipart/form-data" method="POST">

                        <div class="form-group mb-3 exam-div">
                            <label for="examBody">Exam Body</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($question->exam_body) ?>"
                                id="examBody" name="examBody">
                        </div>

                        <div class="form-group mb-3">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($question->subject) ?>"
                                id="subject" name="subject">
                        </div>

                        <div class="form-group mb-3">
                            <label for="examYear">Exam Year</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($question->exam_year) ?>"
                                id="examYear" name="examYear">
                        </div>

                        <div class="form-group mb-3">
                            <label for="question">Question</label>
                            <textarea class="form-control" id="question" rows="3"
                                name="question"><?= htmlspecialchars($question->question) ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="optionA">Option A</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($question->option_a) ?>"
                                id="optionA" name="optionA">
                        </div>

                        <div class="form-group mb-3">
                            <label for="optionB">Option B</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($question->option_b) ?>"
                                id="optionB" name="optionB">
                        </div>

                        <div class="form-group mb-3">
                            <label for="optionC">Option C</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($question->option_c) ?>"
                                id="optionC" name="optionC">
                        </div>

                        <div class="form-group mb-3">
                            <label for="optionD">Option D</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($question->option_d) ?>"
                                id="optionD" name="optionD">
                        </div>

                        <div class="form-group mb-3">
                            <label for="optionE">Option E</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($question->option_e) ?>"
                                id="optionE" name="optionE">
                        </div>

                        <div class="form-group mb-3">
                            <label for="correctAnswer">Correct Answer</label>
                            <input type="text" class="form-control"
                                value="<?= htmlspecialchars($question->correct_answer) ?>" id="correctAnswer"
                                name="correctAnswer">
                        </div>

                        <input type="hidden" id="editQuestionId" name="edit-question">
                        <div style=" padding:40px">
                            <button type="submit" class="w-100 text-center btn-primary p-7" style=" padding:10px">Save
                                Edited
                                Question</button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>

        </div>
</div>
</section>
</div>

<script>
    $(document).ready(function () {
        // Hide all edit forms initially
        $(".edit-form").hide();

        // Retrieve the question ID from the query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const questionId = urlParams.get('id');

        // Show the form with the matching ID
        if (questionId) {
            $("#editForm" + questionId).show();
        }
    });
</script>