<div class="col-md-9 col-md-8">
  <div class="container">
    <?php if (isset($_GET['error'])): ?>
      <div class="text-center alert alert-<?= $_GET['type'] ?>" role="alert"><?= $_GET['error'] ?></div>
    <?php endif; ?>
    <div class="container my-3 float-start" id="search-form">
      <form method="get">
        <div class="row g-3 pe-1 ps-1">
          <div class="col-12 col-md-3">
            <input type="text" name="subject" class="form-control form-control-sm rounded-0 p-md-2"
              placeholder="Subject" aria-label="subject">
          </div>
          <div class="col-12 col-md-3">
            <select name="exam_body" class="form-control form-control-sm">
              <option value="" selected>Exam Body</option>
              <option value="NECO">NECO</option>
              <option value="WAEC">WAEC</option>
              <option value="JAMB">JAMB</option>
            </select>
          </div>

          <div class="col-12 col-md-2">
            <select name="status" class="form-control form-control-sm">
              <option value="" selected>-- Status --</option>
              <option value="1">Published</option>
              <option value="0">Unpublished</option>
            </select>
          </div>

          <div class="col-12 col-md-2">
            <select name="year" class="form-control form-control-sm rounded-0">
              <option class="rounded-0" value="">Year</option>
              <?php for ($i = date('Y'); $i >= 1970; $i--) { ?>
                <option class="rounded-0" value="<?= $i ?>"><?= $i ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="col-12 col-md-2">
            <button type="submit" class="btn btn-primary btn-sm"
              style="color: #fff; background-color: #347054; border-color: #347054; font-weight: 300; font-size: 16px;">Search</button>
          </div>
        </div>
      </form>

    </div>
    <div>
      <table id="usersTable" class="table table-striped" style="font-size: 12px;">
        <thead>
          <tr>
            <th scope="col">id</th>
            <th scope="col">Exam Body</th>
            <th scope="col">Subject</th>
            <th scope="col">Exam Year</th>
            <th scope="col">Date</th>
            <th scope="col" class="text-center"></th>
            <th scope="col" class="text-center"></th>
            <th scope="col" class="text-center"></th>
            <th scope="col" class="text-center"></th>
          </tr>
        </thead>
        <?php $q = 0; ?>

        <!-- post body for past questions                -->
        <tbody class="post-body bg-white">
          <?php if ($currentUser->is_agent): ?>
            <?php if (empty($questions)): ?>
            <?php else: ?>
              <?php foreach ($questions as $ques => $question): ?>
                <tr data-id="<?= $question->id ?>" class="bg-white">
                  <th scope="row"><?= ++$rows ?></th>
                  <td class="text-uppercase"><?= $question->exam_body ?></td>
                  <td class="text-capitalize"><?= $question->subject ?></td>
                  <td class="text-capitalize"><?= $question->year ?></td>
                  <td class="text-capitalize"><?= $question->created_at ?></td>
                  <?php if (!$currentUser->is_agent): ?>
                    <td class="view-modal-trigger">
                      <button type="button" class="button btn btn-info btn-view"
                        data-modal-id="viewModal<?= $ques ?>">View</button>
                    </td>

                    <!-- Update your "EDIT" links with data attributes -->
                    <td class="view-modal-trigger">
                      <a href="edit-question?id=<?= $question->id ?>" class="button btn btn-warning btn-view edit-link"
                        data-question-id="<?= $question->id ?>">EDIT</a>
                    </td>
                  <?php else: ?>
                    <!-- Update your "EDIT" links with data attributes -->
                    <td class="view-modal-trigger">
                      <a href="edit-uploaded-past-question?id=<?= $question->id ?>"
                        class="button btn btn-warning btn-view edit-link" data-question-id="<?= $question->id ?>">EDIT</a>
                    </td>
                  <?php endif; ?>
                  <td class="text-center">
                    <?php if ($currentUser->is_agent): ?>
                      <button type="button"
                        class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white <?= $question->published == 1 ? 'bg-success' : 'bg-secondary text-white' ?>"
                        data-status="<?= $question->published ?>" style="cursor: text;">
                        <?= $question->published == 1 ? 'Published' : 'Unpublished' ?>
                      </button>
                    <?php else: ?>

                      <button disabled type="button"
                        class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white <?= $question->published == 1 ? 'bg-success' : 'bg-secondary text-white' ?>"
                        title="Publish" data-status="<?= $question->published ?>"
                        onclick="confirmPublish(<?= $question->id ?>, '<?= $url ?>', this)">
                        <?= $question->published == 1 ? 'Published' : 'Unpublished' ?>
                      </button>
                    <?php endif; ?>

                  </td>
                  <td class="text-center">
                    <button type="button"
                      class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white bg-danger text-white"
                      title="Delete" onclick="return confirmDelete(<?= $question->id ?>, 'view-past-questions')">
                      Delete
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>

          <?php else: ?>
            <?php foreach ($questions as $ques => $question): ?>
              <tr data-id="<?= $question->id ?>" class="bg-white">
                <th scope="row"><?= ++$rows ?></th>
                <td class="text-uppercase"><?= $question->exam_body ?></td>
                <td class="text-capitalize"><?= $question->subject ?></td>
                <td class="text-capitalize"><?= $question->exam_year ?></td>
                <td class="text-capitalize"><?= $question->created_at ?></td>

                <td class="view-modal-trigger">
                  <button type="button" class="button btn btn-info btn-view"
                    data-modal-id="viewModal<?= $ques ?>">View</button>
                </td>

                <!-- Update your "EDIT" links with data attributes -->
                <td class="view-modal-trigger">
                  <a href="edit-question?id=<?= $question->id ?>" class="button btn btn-warning btn-view edit-link"
                    data-question-id="<?= $question->id ?>">EDIT</a>
                </td>
                <td class="text-center">
                  <button type="button"
                    class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white <?= $question->publish == 1 ? 'bg-success' : 'bg-secondary text-white' ?>"
                    title="Publish" data-status="<?= $question->publish ?>"
                    onclick="confirmPublish(<?= $question->id ?>, '<?= $url ?>', this)">
                    <?= $question->publish == 1 ? 'Published' : 'Unpublished' ?>
                  </button>
                </td>
                <td class="text-center">
                  <button type="button"
                    class="btn btn-sm btn-rounded btn-pill text-uppercase ml-4 text-white bg-danger text-white"
                    title="Delete" onclick="return confirmDelete(<?= $question->id ?>, 'view-past-questions')">
                    Delete
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>

        <!-- end of post body for past questions -->


      </table>
    </div>
  </div>
  <nav aria-label="Page navigation" id="pagination-container">
    <ul class="pagination justify-content-center mt-5">
      <?php
      $stmt = $pdo->select($search);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $total_pages = isset($row['total']) ? ceil($row['total'] / $limit) : 1;
      $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
      $delta = 2;

      if ($total_pages >= 1) {
        // Previous page link
        if ($current_page > 1) {
          echo '<li class="page-item"><a class="page-link pagination-link" href="?page=' . ($current_page - 1) . '">Previous</a></li>';
        }

        // First page link
        if ($current_page > $delta + 1) {
          echo '<li class="page-item"><a class="page-link pagination-link" href="?page=1">1</a></li>';
          if ($current_page > $delta + 2) {
            echo '<li class="page-item"><span class="page-link">...</span></li>';
          }
        }

        // Page links around the current page
        for ($i = max(1, $current_page - $delta); $i <= min($total_pages, $current_page + $delta); $i++) {
          echo '<li class="page-item ' . ($current_page == $i ? 'active' : '') . '"><a class="page-link pagination-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }

        // Last page link
        if ($current_page < $total_pages - $delta) {
          if ($current_page < $total_pages - $delta - 1) {
            echo '<li class="page-item"><span class="page-link">...</span></li>';
          }
          echo '<li class="page-item"><a class="page-link pagination-link" href="?page=' . $total_pages . '">' . $total_pages . '</a></li>';
        }

        // Next page link
        if ($current_page < $total_pages) {
          echo '<li class="page-item"><a class="page-link pagination-link" href="?page=' . ($current_page + 1) . '">Next</a></li>';
        }
      }
      ?>
    </ul>
  </nav>

  <!-- view modal -->
  <div>
    <?php if ($currentUser->is_agent): ?>
      <?php if (empty($questions)): ?>
      <?php else: ?>
        <?php foreach ($questions as $ques => $question): ?>
          <div class="modal modal-view-div view-only-modal" id="viewModal<?= $ques ?>">
            <span onclick="document.getElementById('id03').style.display='none'" class="close" title="Close Modal">×</span>
            <form class="modal-content card px-3 col-md-8 form-view offset-md-2" method="post">

              <div class="form-group mb-3 exam-div">
                <label for="examBody">Exam Body</label>
                <input type="text" value="<?= htmlspecialchars($question->exam_body) ?>" class="form-control text-capitalize"
                  id="examBody" name="examBody" readonly>
              </div>

              <div class="form-group mb-3">
                <label for="subject">Subject</label>
                <input type="text" class="form-control text-capitalize" id="subject" name="subject"
                  value="<?= htmlspecialchars($question->subject) ?>" readonly>
              </div>

              <div class="form-group mb-3">
                <label for="examYear">Exam Year</label>
                <input type="text" class=" form-control text-capitalize" id="examYear" name="examYear"
                  value="<?= htmlspecialchars($question->exam_year) ?>" readonly>
              </div>

              <div class="form-group mb-3">
                <label for="question">Question</label>
                <textarea class="form-control" id="question" rows="3" name="question"
                  readonly><?= htmlspecialchars($question->question) ?></textarea>
              </div>
              <!-- <div class="form-group mb-3">
                <label for="optionA">Option A</label>
                <input type="text" class=" form-control text-capitalize" id="optionA" name="optionA"
                  value="<?= htmlspecialchars($question->option_a) ?>" readonly>
              </div> -->
              <!-- 
              <div class="form-group mb-3">
                <label for="optionB">Option B</label>
                <input type="text" class=" form-control text-capitalize" id="optionB" name="optionB"
                  value="<?= htmlspecialchars($question->option_b) ?>" readonly>
              </div> -->

              <!-- <div class="form-group mb-3">
                <label for="optionC">Option C</label>
                <input type="text" class=" form-control text-capitalize" id="optionC" name="optionC"
                  value="<?= htmlspecialchars($question->option_c) ?>" readonly>
              </div>

              <div class="form-group mb-3">
                <label for="optionD">Option D</label>
                <input type="text" class="form-control" id="optionD" name="optionD"
                  value="<?= htmlspecialchars($question->option_d) ?>" readonly>
              </div>

              <div class="form-group mb-3">
                <label for="optionE">Option E</label>
                <input type="text" class=" form-control text-capitalize" id="optionE" name="optionE"
                  value="<?= htmlspecialchars($question->option_e) ?>" readonly>
              </div> -->

              <!-- <div class="form-group mb-3">
                <label for="correctAnswer">Correct Answer</label>
                <input type="text" class=" form-control text-capitalize" id="correctAnswer" name="correctAnswer"
                  value="<?= htmlspecialchars($question->correct_answer) ?>" readonly>
              </div> -->


              <div class="clearfix d-flex justify-content-between " style="margin-bottom: 150px; margin-top:50px">
                <button type="button" class="cancelbtn btn btn-secondary text-white p-3" style="width:40%">close</button>
                <a href="edit-question?id=<?= $question->id ?>" class="text-white btn-view edit-link btn btn-primary p-3 "
                  style="width:40%" data-question-id="<?= $question->id ?>">EDIT</a>
              </div>
            </form>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    <?php else: ?>
      <?php foreach ($questions as $ques => $question): ?>
        <div class="modal modal-view-div view-only-modal" id="viewModal<?= $ques ?>">
          <span onclick="document.getElementById('id03').style.display='none'" class="close" title="Close Modal">×</span>
          <form class="modal-content card px-3 col-md-8 form-view offset-md-2" method="post">

            <div class="form-group mb-3 exam-div">
              <label for="examBody">Exam Body</label>
              <input type="text" value="<?= htmlspecialchars($question->exam_body) ?>" class="form-control text-capitalize"
                id="examBody" name="examBody" readonly>
            </div>

            <div class="form-group mb-3">
              <label for="subject">Subject</label>
              <input type="text" class="form-control text-capitalize" id="subject" name="subject"
                value="<?= htmlspecialchars($question->subject) ?>" readonly>
            </div>

            <div class="form-group mb-3">
              <label for="examYear">Exam Year</label>
              <input type="text" class=" form-control text-capitalize" id="examYear" name="examYear"
                value="<?= htmlspecialchars($question->exam_year) ?>" readonly>
            </div>

            <div class="form-group mb-3">
              <label for="question">Question</label>
              <textarea class="form-control" id="question" rows="3" name="question"
                readonly><?= htmlspecialchars($question->question) ?></textarea>
            </div>
            <div class="form-group mb-3">
              <label for="optionA">Option A</label>
              <input type="text" class=" form-control text-capitalize" id="optionA" name="optionA"
                value="<?= htmlspecialchars($question->option_a) ?>" readonly>
            </div>

            <div class="form-group mb-3">
              <label for="optionB">Option B</label>
              <input type="text" class=" form-control text-capitalize" id="optionB" name="optionB"
                value="<?= htmlspecialchars($question->option_b) ?>" readonly>
            </div>

            <div class="form-group mb-3">
              <label for="optionC">Option C</label>
              <input type="text" class=" form-control text-capitalize" id="optionC" name="optionC"
                value="<?= htmlspecialchars($question->option_c) ?>" readonly>
            </div>

            <div class="form-group mb-3">
              <label for="optionD">Option D</label>
              <input type="text" class="form-control" id="optionD" name="optionD"
                value="<?= htmlspecialchars($question->option_d) ?>" readonly>
            </div>

            <div class="form-group mb-3">
              <label for="optionE">Option E</label>
              <input type="text" class=" form-control text-capitalize" id="optionE" name="optionE"
                value="<?= htmlspecialchars($question->option_e) ?>" readonly>
            </div>

            <div class="form-group mb-3">
              <label for="correctAnswer">Correct Answer</label>
              <input type="text" class=" form-control text-capitalize" id="correctAnswer" name="correctAnswer"
                value="<?= htmlspecialchars($question->correct_answer) ?>" readonly>
            </div>


            <div class="clearfix d-flex justify-content-between " style="margin-bottom: 150px; margin-top:50px">
              <button type="button" class="cancelbtn btn btn-secondary text-white p-3" style="width:40%">close</button>
              <a href="edit-question?id=<?= $question->id ?>" class="text-white btn-view edit-link btn btn-primary p-3 "
                style="width:40%" data-question-id="<?= $question->id ?>">EDIT</a>
            </div>
          </form>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>