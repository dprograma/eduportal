<div class="container-fluid">
    <div class="row mx-auto justify-content-center align-content-center text-center" style="padding-bottom: 50px;">
        <h4 class="">PAST QUESTION HUB</h4>
    </div>
    <div class="row mx-auto text-center justify-content-center align-items-center">
        <form id="assessment-entry-form" class="prep-form" method="get">
            <div class="assessment-paper-group assessment-paper-group-1 mb-3" id="assessment-paper-group-1">
                <div class="input-group mt-3 mb-3 assessment">
                    <span id="examination-board-name-list-label" class="input-group-text text-uppercase">Exam
                        Body</span>
                    <select class="form-control form-control-lg selectpicker text-uppercase" id="exambody"
                        title="Select Exam Body" name="examBody" required>
                        <?php
                        $examBodyQuestion = [];
                        $examBodyOptions = [];
                        foreach ($questions as $question) {
                            $examBody = $question['exam_body'];
                            if (!isset($examBodyQuestion[$examBody])) {
                                $examBodyQuestion[$examBody] = true;
                                $examBodyOptions[] = $examBody;
                            }
                        }
                        $examBodyOptions = array_values(array_unique($examBodyOptions));
                        sort($examBodyOptions);

                        foreach ($examBodyOptions as $examBody) {
                            ?>
                            <option value="<?= $examBody ?>"><?= $examBody ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group mt-3 mb-3 assessment">

                    <span id="prepare-assessment-subject-name-label-1"
                        class="input-group-text text-uppercase">Subject</span>
                    <select class="form-control form-control-lg selectpicker prepare-assessment-subject-name"
                        id="subject" title="Select Subject" name="subject" required data-live-search="true">
                        <?php
                        $subjectQuestion = [];
                        $subjectOptions = [];
                        foreach ($questions as $question) {
                            $subject = $question['subject'];
                            if (!isset($subjectQuestion[$subject])) {
                                $subjectQuestion[$subject] = true;
                                $subjectOptions[] = $subject;
                            }
                        }
                        sort($subjectOptions);

                        foreach ($subjectOptions as $subject) {
                            ?>
                            <option value="<?= $subject ?>"><?= $subject ?></option>
                            <?php
                        }

                        ?>
                    </select>
                </div>
                <div class="input-group mt-3 mb-3 assessment">
                    <span id="prepare-assessment-subject-assessment-list-label-1"
                        class="input-group-text text-uppercase">Exam Year</span>
                    <select class="form-control form-control-lg selectpicker assessment-select text-uppercase"
                        id="examyear" title="Select Exam Year" name="examYear" required data-live-search="true">
                        <?php
                        $examYearQuestion = [];
                        $examYearOptions = [];
                        foreach ($questions as $question) {
                            $examYear = $question['exam_year'];
                            if (!isset($examYearQuestion[$examYear])) {
                                $examYearQuestion[$examYear] = true;
                                $examYearOptions[] = $examYear;
                            }
                        }
                        sort($examYearOptions);
                        foreach ($examYearOptions as $examYear) {
                            ?>
                            <option value="<?= $examYear ?>"><?= $examYear ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group mt-3">
                    <div class="d-grid w-100">
                        <input type="hidden" name="questionsDetails">
                        <button type="submit" class="btn btn-lg btn-primary start_btn" id="start-quiz">Start</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>