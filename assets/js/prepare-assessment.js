import {
    alertSimpleError,
    enableAddBackgroundToMenuBarOnScroll,
    hideElement,
    initializeBootstrapSelect,
    makeAndPostForm,
    showElement,
    showSimpleInfoAlert,
    triggerShowAppAdvert
} from "./modules/uiutil.js";
import {areValidStrs, isValidStr} from "./modules/util.js";
import {EXAM_MODE_REAL} from "./constants.js";

$(document).ready(function () {
    initializePrepareAssessmentPage();
});

function initializePrepareAssessmentPage() {
    enableAddBackgroundToMenuBarOnScroll();
    initializeBootstrapSelect();
    addListenerToAssessmentBoardChange();
    addListenerToSubjectChange();

    addListenerToAddAssessmentBtn();
    addListenerToResetSelectionBtn();
    addListenerToSubmitButton();

    addListenerToTooltipIconClick();

    onChangeExamBoard();

    triggerShowAppAdvert()
}

function addListenerToAssessmentBoardChange() {
    $("#examination-board-name-list").unbind("change").on("change", function () {
        onChangeExamBoard();
    });
}

function onChangeExamBoard() {

    // Get selected examination board option
    const selectedExamBoardOption = $("#examination-board-name-list").children("option:selected");

    if (!isValidStr(selectedExamBoardOption.val())) {
        return;
    }

    const examinationBoard = selectedExamBoardOption.val();

    // Clear additional assessment area
    clearAdditionalAssessmentSpace();
    clearAdditionalAssessmentSpace();
    clearAdditionalAssessmentSpace(); // Need to be called twice. Reason unknown

    let assessmentSubjectDropDown = $("#prepare-assessment-subject-name-1");
    let assessmentTitleDropDown = $("#prepare-assessment-subject-assessment-list-1");

    if (examinationBoard === "JAMB") {
        // Show add/reset assessment Button
        showElement("additional-assessment-control");
    } else {
        hideElement("additional-assessment-control");
        $("#holder").data("number-of-assessments", '1');
    }
    
    assessmentSubjectDropDown.val('').selectpicker("refresh");
    assessmentTitleDropDown.val('').selectpicker("refresh");

    assessmentSubjectDropDown.prop("selected", false);
    assessmentTitleDropDown.empty();

    addListenerToSubjectChange();

    $(".selectpicker").selectpicker('refresh');
}

function addListenerToSubjectChange() {
    $("select.prepare-assessment-subject-name").unbind("change").on("change", function (e) {
        e.stopImmediatePropagation();

        // Get selected assessment board
        const selectedBoardOption = $("#examination-board-name-list").children("option:selected");
        if (!isValidStr(selectedBoardOption.val())) {
            alertSimpleError("Whooops!", "Please select an examination board e.g. JAMB, WAEC or NECO")
        }

        const examinationBoard = selectedBoardOption.val();
        const examinationBoardLowerCase = examinationBoard.toLowerCase();

        // Get selected subject option
        const selectedSubjectOption = $(this).children("option:selected");

        // Extract assessments for selected subject
        const assessmentTitlesStr = selectedSubjectOption.data(examinationBoardLowerCase + "-assessment-titles");
        const assessmentIdsStr = selectedSubjectOption.data(examinationBoardLowerCase + "-assessment-ids");
        const assessmentIndex = $(this).data("assessment-index");

        if (!areValidStrs(assessmentTitlesStr, assessmentIdsStr)) {
            let assessmentTitleDropDown = $("#prepare-assessment-subject-assessment-list-" + assessmentIndex);
            assessmentTitleDropDown.empty();
            let unavailableMessage = "No past question available for selected subject. Please choose another subject."
            assessmentTitleDropDown.append(
                $('<option disabled></option>').val("unavailable").html(unavailableMessage)
            );

            $(".assessment-select").selectpicker('refresh');
            $(".prepare-assessment-subject-name").selectpicker('refresh');
            return;
        }

        const assessmentTitles = assessmentTitlesStr.split(";");
        const assessmentIds = assessmentIdsStr.split(";");

        // Update assessment drop down
        let assessmentTitleDropDown = $("#prepare-assessment-subject-assessment-list-" + assessmentIndex);
        assessmentTitleDropDown.empty();

        for (let i = 0; i < assessmentIds.length; i++) {
            assessmentTitleDropDown.append(
                $('<option></option>').val(assessmentIds[i]).html(assessmentTitles[i])
            );
        }

        $(".selectpicker").selectpicker('refresh');
    })
}

function addListenerToTooltipIconClick() {
    $("#practice-mode-tooltip-icon").click(function (event) {
        let element = $('#practice-mode-radio-div');
        element.tooltip("show")
    });

    $("#real-mode-tooltip-icon").click(function (event) {
        let element = $('#real-mode-radio-div');
        element.tooltip("show")
    });
}

function addListenerToResetSelectionBtn() {
    $("#reset-assessment-selection-button").unbind("click").on("click", function (event) {
        event.preventDefault();

        clearAdditionalAssessmentSpace();
        clearAdditionalAssessmentSpace(); // For unknown reasons does not complete clearing once
        $("#examination-board-name-list").val('').selectpicker("refresh");
        $("#prepare-assessment-subject-name-1").val('').selectpicker("refresh");
        $("#prepare-assessment-subject-assessment-list-1").val('').selectpicker("refresh");
        $("#holder").data("number-of-assessments", '1');
    });
}

function clearAdditionalAssessmentSpace() {
    let paperGroups = document.getElementsByClassName("assessment-paper-group");

    if (paperGroups == null || typeof paperGroups === "undefined") {
        return;
    }

    for (let i = 0; i < paperGroups.length; i++) {
        let id = paperGroups[i].id;

        if (id !== "assessment-paper-group-1") {
            document.getElementById(id).remove();
        }
    }
}

function addListenerToAddAssessmentBtn() {
    $("#add-assessment-button").unbind("click").on("click", function (event) {
        event.preventDefault();

        // Get current number of assessments
        const holder = $("#holder");
        const currentNumberOfAssessments = parseInt(holder.data("number-of-assessments"));

        if (currentNumberOfAssessments >= 4) {
            showSimpleInfoAlert("Let's Take One Step Back",
                "Only a maximum of 4 subjects (standard JAMB format) is allowed", "OK")
            return;
        }

        const nextAssessmentIndex = currentNumberOfAssessments + 1;
        const subjectTranslation = holder.data("translation-subject");
        const selectSubjectTranslation = holder.data("translation-select-subject");
        const assessmentTranslation = holder.data("translation-assessment");
        const selectAssessmentTranslation = holder.data("translation-select-assessment");
        let allSubjectsDivContent = document.getElementById("all-subjects-div-1").innerHTML;

        const paperBlock = `
        <div class="mb-3 assessment-paper-group assessment-paper-group-${nextAssessmentIndex}"
                     id="assessment-paper-group-${nextAssessmentIndex}">
            <div class="input-group mt-3 mb-3">
                <span id="prepare-assessment-subject-name-label"
                          class="input-group-text"> ${subjectTranslation} ${nextAssessmentIndex}
                </span>
                <select class="form-control form-control-lg selectpicker prepare-assessment-subject-name dropdown"
                        id="prepare-assessment-subject-name-${nextAssessmentIndex}"
                        title="${selectSubjectTranslation}"
                        data-live-search="true"
                        data-dropup-auto="false"
                        data-assessment-index="${nextAssessmentIndex}"
                        required>
                         ${allSubjectsDivContent}
                </select>
            </div>

            <div class="input-group mt-3 mb-3">
                <span id="prepare-assessment-subject-assessment-list-label-${nextAssessmentIndex}"
                          class="input-group-text">${assessmentTranslation} ${nextAssessmentIndex}
                </span>
                <select class="form-control form-control-lg selectpicker assessment-select"
                    id="prepare-assessment-subject-assessment-list-${nextAssessmentIndex}"
                    title="${selectAssessmentTranslation}"
                    data-assessment-index="${nextAssessmentIndex}"
                    data-live-search="true"
                    required>
                </select>
            </div>
        </div>

        <div class="paper-group-empty-space mb-3" id="paper-group-empty-space">

        </div>
`;

        // Append newly created option to form
        document.getElementById("paper-group-empty-space").outerHTML = paperBlock;

        addListenerToSubjectChange();

        $(".selectpicker").selectpicker('refresh');

        holder.data("number-of-assessments", nextAssessmentIndex);
    });
}

function addListenerToSubmitButton() {
    $("#start-assessment-button").unbind("click").on("click", function (event) {
        event.preventDefault();

        const examMode = $("input[name='assessment-mode']:checked").val();
        if (!isValidStr(examMode)) {
            alertSimpleError("Invalid Exam Mode", "Please select an exam mode");
            return;
        }

        const examBoard = getSelection("examination-board-name-list");
        if (!isValidStr(examBoard)) {
            alertSimpleError("Invalid Exam Board", "Please select an exam board e.g JAMB, NECO or WAEC");
            return;
        }

        let inputsAreOk = true;
        let subjectIds = [];
        $("select.prepare-assessment-subject-name").each(function () {
            const subjectId = getSelection($(this).attr("id"));
            if (!isValidStr(subjectId)) {
                alertSimpleError("Invalid Subject",
                    "Please select subject for chosen assessment(s)");
                inputsAreOk = false;
                return;
            }

            subjectIds.push(subjectId);
        });

        if (!inputsAreOk) {
            return;
        }

        let assessmentIds = [];
        $("select.assessment-select").each(function () {
            const selectedAssessmentId = getSelection($(this).attr("id"));
            if (!isValidStr(selectedAssessmentId)) {
                const assessmentIndex = $(this).data("assessment-index");
                let subjectNameList = document.getElementById(
                    "prepare-assessment-subject-name-" + assessmentIndex);
                const subjectName = subjectNameList.options[subjectNameList.selectedIndex].text;
                alertSimpleError("Invalid Assessment",
                    "Please select an assessment for " + subjectName);
                inputsAreOk = false;
                return;
            }

            assessmentIds.push(selectedAssessmentId);
        });

        if (!inputsAreOk) {
            return;
        }

        // Prevent same assessment or same subject
        if ((new Set(subjectIds)).size !== subjectIds.length) {
            alertSimpleError("Invalid Selection",
                "Please select different subjects. " +
                "Duplicate subject selection is not allowed");
            return;
        }

        if ((new Set(assessmentIds)).size !== assessmentIds.length) {
            alertSimpleError("Invalid Selection",
                "Please select different assessments. " +
                "Duplicate assessment selection is not allowed");
            return;
        }

        let requestParam = {};
        requestParam["assessmentMode"] = examMode;
        requestParam["assessmentBoard"] = examBoard;

        if (examMode === EXAM_MODE_REAL) {
            if (assessmentIds.length > 1) {
                requestParam["assessmentIds"] = assessmentIds.join(',');
                makeAndPostForm("/cbt/assessment/instructions", requestParam);
            } else {
                requestParam["assessmentId"] = assessmentIds[0];
                makeAndPostForm("/cbt/assessment/instruction", requestParam);
            }
        } else {
            if (assessmentIds.length > 1) {
                requestParam["assessmentIds"] = assessmentIds.join(',');
                makeAndPostForm("/cbt/assessment/take/multiple", requestParam);
            } else {
                requestParam["assessmentId"] = assessmentIds[0];
                makeAndPostForm("/cbt/assessment/take", requestParam);
            }

        }
    });
}

function getSelection(id) {
    let element = document.getElementById(id);
    return element.options[element.selectedIndex].value;
}