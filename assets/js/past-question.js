document.addEventListener("DOMContentLoaded", function () {
    const examBodySelect = document.getElementById("examination-board-name-list");
    const subjectSelect = document.getElementById("prepare-assessment-subject-name-1");
    const examYearSelect = document.getElementById("prepare-assessment-subject-assessment-list-1");

    examBodySelect.addEventListener("change", function () {
        const selectedExamBody = examBodySelect.value;

        if (selectedExamBody === "SomeValueToHideSubjectsAndYears") {
            // Hide the "Subject" and "Exam Year" selects
            subjectSelect.style.display = "none";
            examYearSelect.style.display = "none";
        } else {
            // Show the "Subject" and "Exam Year" selects
            subjectSelect.style.display = "block";
            examYearSelect.style.display = "block";
        }
    });
});
