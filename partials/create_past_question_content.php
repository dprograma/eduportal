<civ class="col-lg-9 col-md">
    <section class="content bg-white p-4 rounded pb-9 mb-8">
        <div class="text-center fw-bolder text-uppercase m-4">Create question</div>
        <?php if (isset($_GET['error'])): ?>
            <div class="text-center alert alert-<?= $_GET['type'] ?>" role="alert"><?= $_GET['error'] ?></div>
        <?php endif; ?>
        <form class="modal-content card px-3 bg-none" enctype="multipart/form-data" method="POST">


            <div class="form-group mb-3 exam-div">
                <label for="examBody">Exam Body</label>
                <input type="text" class="form-control text-uppercase" id="examBody" name="examBody">
            </div>

            <div class="form-group mb-3">
                <label for="subject">Subject</label>
                <input type="text" class="form-control text-uppercase" id="subject" name="subject">
            </div>

            <div class="form-group mb-3">
                <label for="examYear">Exam Year</label>
                <input type="text" class="form-control text-uppercase" id="examYear" name="examYear">
            </div>

            <!--<div class="form-group mb-3">-->
            <!--  <label for="question">Question</label>-->
            <!--  <textarea class="form-control text-capitalize" id="question" rows="3" name="question" ></textarea>-->
            <!--</div>-->
            <div class="form-group mb-3">
                <label for="question">Question</label>
                <textarea id="question" class="form-control text-capitalize" name="question"></textarea>
            </div>
            <script>
                CKEDITOR.replace('question');
            </script>




            <div class="form-group mb-3">
                <label for="optionA">Option A</label>
                <input type="text" class="form-control text-capitalize" id="optionA" name="optionA">
            </div>

            <div class="form-group mb-3">
                <label for="optionB">Option B</label>
                <input type="text" class="form-control text-capitalize" id="optionB" name="optionB">
            </div>

            <div class="form-group mb-3">
                <label for="optionC">Option C</label>
                <input type="text" class="form-control text-capitalize" id="optionC" name="optionC">
            </div>

            <div class="form-group mb-3">
                <label for="optionD">Option D</label>
                <input type="text" class="form-control text-capitalize" id="optionD" name="optionD">
            </div>

            <div class="form-group mb-3">
                <label for="optionE">Option E</label>
                <input type="text" class="form-control text-capitalize" id="optionE" name="optionE">
            </div>

            <div class="form-group mb-3">
                <label for="correctAnswer">Correct Answer</label>
                <input type="text" class="form-control text-capitalize" id="correctAnswer" name="correctAnswer">
            </div>

            <input type="hidden" name="create-question">
            <div style=" padding:40px">
                <button type="submit" class="w-100 text-center btn-primary p-7" style=" padding:10px">Create
                    Question</button>
            </div>
        </form>

    </section>
</civ>