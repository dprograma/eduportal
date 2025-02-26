<!DOCTYPE html>
<html lang="en">
<?php $title = "EduPortal | Assessment"; ?>
<?php include "partials/head.php"; ?>

<body>
    <!-- Include necessary styles -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .quiz-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .question-container {
            margin-bottom: 20px;
        }

        .quiz__info {
            text-align: center;
            margin-top: 20px;
        }

        .quiz-score {
            font-size: 20px;
            font-weight: bold;
            color: #165e34;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            color: white;
        }

        button:hover {
            opacity: 0.9;
        }

        .btn-warning {
            background-color: #f0ad4e;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .correct-answer-btn {
            background-color: #28a745;
        }

        .submit-answer-btn {
            background-color: #8e0808;
        }

        /* Styles for highlighting answers */
        .correct-answer {
            background-color: #28a745 !important;
            color: white !important;
        }

        .user-answer {
            background-color: #dc3545 !important;
            color: white !important;
        }

        /* Increase size of radio buttons */
        input[type="radio"] {
            transform: scale(1.5);
            margin-right: 10px;
        }

        /* Change option text color */
        label {
            color: white;
            font-size: 18px;
            /* Increase font size */
        }
    </style>

    <div class="quiz-container">
        <form class="quiz" id="quiz-form">
            <div id="alert-container"></div>
            <div class="question-container" id="quiz-container"></div>
            <div class="quiz__info">
                <p id="quiz-score" class="quiz-score">Score: 0</p>
                <div class="button-group">
                    <button class="quiz__reset btn btn-warning" id="quiz-restart-btn" type="button">Restart</button>
                    <button class="quiz__next btn btn-primary" type="submit">Next</button>
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript for quiz functionality -->
    <script>
        const quizFormElem = document.getElementById("quiz-form");
        const quizContainerElem = document.getElementById("quiz-container");
        const quizRestartBtn = document.getElementById("quiz-restart-btn");
        const quizScoreElem = document.getElementById("quiz-score");

        let quizIndex = 0;
        let quizScore = 0;

        // The questions data passed from PHP
        const questions = <?php echo json_encode($questions); ?>;


        function renderQuiz(data, index) {
            if (!data) return;

            const output = `
                <h3 class="quiz__question" style="background-color: #007bff; padding: 20px; border-radius: 10px;">
                    <span class="quiz__number">Question ${index + 1}</span><br>
                    <p style="margin-top:20px;color:#fff; font-size:24px; font-weight: 600;">
                        ${stripHTML(data.question)}
                    </p>
                </h3>
                <div class="quiz__answers">
                    ${renderQuizAnswers(data)}
                </div>
                <div class="button-container" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; width: 100%;">
                    <button class="submit-answer-btn" style="flex: 1;" type="button" onclick="submitAnswer()">
                        Submit Answer
                    </button>
                </div>
            `;

            quizContainerElem.innerHTML = output;
        }

        function stripHTML(html) {
            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = html;

            // Use regex to strip out remaining HTML tags, just in case
            const plainText = tempDiv.textContent || tempDiv.innerText || "";
            return removeHTMLTags(plainText);
        }

        function removeHTMLTags(htmlString) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlString, 'text/html');
            const textContent = doc.body.textContent || "";
            return textContent.trim();
        }


        function showAlert(message, type) {
            const alertContainer = document.getElementById("alert-container");

            // Create a new Bootstrap alert element
            const alert = document.createElement("div");
            alert.className = `alert alert-${type} alert-dismissible fade show`;  // Adds Bootstrap alert classes
            alert.role = "alert";
            alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

            // Append the alert to the container
            alertContainer.appendChild(alert);
        }


        function renderQuizAnswers(question) {
            let output = "";
            const options = ['A', 'B', 'C', 'D', 'E'];

            options.forEach((option) => {
                const optionKey = `option_${option.toLowerCase()}`;

                if (question.hasOwnProperty(optionKey) && question[optionKey].trim() !== "") {
                    output += `
                        <div class="quiz__answer" style="margin-bottom: 10px;">
                            <input type="radio" id="${option}" name="answer" value="${option}" required />
                            <label for="${option}">
                                ${option}. ${question[optionKey]}
                            </label>
                        </div>
                    `;
                }
            });

            return output;
        }

        function submitAnswer() {
            const selectedOption = document.querySelector('input[name="answer"]:checked');
            if (!selectedOption) {
                alert("Please select an answer before submitting.");
                return;
            }

            // Get selected answer text (label text)
            const userAnswer = selectedOption.value;  // e.g., "A", "B"
            console.log("Selected answer text: " + userAnswer)
            const correctAnswerText = questions[quizIndex].correct_answer.trim();  // Full text in the database
            correctAnswer = correctAnswerText.substring(0, 1).toUpperCase();  // First letter of the answer
            console.log("Correct answer text: " + correctAnswerText)
            // Check if the user's answer is correct
            if (userAnswer === correctAnswer) {
                quizScore++;
                showAlert("Your answer is correct! You earned a point.", "success");
            } else {
                showAlert("Your answer is incorrect. The correct answer is: " + correctAnswerText, "danger");
            }

            updateScoreUI();

            // Move to the next question
            quizIndex++;
            if (quizIndex >= questions.length) {
                // Quiz is over
                displayFinalScore();
            } else {
                renderQuiz(questions[quizIndex], quizIndex);
            }
        }


        function viewCorrectAnswer() {
            const correctAnswerText = questions[quizIndex].correct_answer.trim();
            const options = document.querySelectorAll('input[name="answer"]');

            options.forEach((option) => {
                const label = document.querySelector(`label[for="${option.id}"]`);
                const labelText = label.innerText.trim();

                if (labelText === correctAnswerText) {
                    label.classList.add('correct-answer');
                } else {
                    label.classList.remove('correct-answer');
                }
            });
        }

        function updateScoreUI() {
            quizScoreElem.innerText = `Score: ${quizScore}`;
        }

        function displayFinalScore() {
            const totalQuestions = questions.length;
            const percentage = (quizScore / totalQuestions) * 100;
            let grade = '';
            let message = '';

            if (percentage >= 80) {
                grade = 'A';
                message = 'Excellent!';
            } else if (percentage >= 70) {
                grade = 'B';
                message = 'Good job!';
            } else if (percentage >= 60) {
                grade = 'C';
                message = 'Well done!';
            } else if (percentage >= 50) {
                grade = 'D';
                message = 'You passed!';
            } else {
                grade = 'F';
                message = 'You can do better. Try again!';
            }

            // Quiz is over, change the button to "Complete Test"
            const nextBtn = document.querySelector(".quiz__next");
            nextBtn.innerText = "Complete Test";

            // Log button status
            console.log("Button updated, now should say 'Complete Test'");
            console.log(nextBtn); // Check the button element in console

            nextBtn.addEventListener('click', completeTest);

            quizContainerElem.innerHTML = `
                <h3>${message}</h3>
                <p>Your grade is ${grade}. You scored ${quizScore} out of ${totalQuestions} (${percentage.toFixed(2)}%)</p>
            `;
        }

        function completeTest(e) {
            e.preventDefault();
            const userId = <?php echo json_encode($currentUser->id); ?>;
            const subject = <?php echo json_encode($subject); ?>;
            const totalQuestions = questions.length;
            const percentage = (quizScore / totalQuestions) * 100;
            console.log("complete test button clicked!")
            // Send the score to the server
            fetch('https://eduportal.prepr.com.ng/core/controller/SaveCBTScore.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId,
                    score: percentage,
                    subject: subject
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert("Your test score has been saved successfully.", "success");
                        // Redirect the user to a result page
                        setTimeout(() => {
                            window.location.href = "cbt-test";
                        }, 2000);
                    } else {
                        showAlert("There was an error saving your score. Please try again.", "danger");
                    }
                })
                .catch(error => {
                    console.log("Error: ", error.message)
                    showAlert("Error: " + error.message, "danger");
                });
        }

        quizRestartBtn.addEventListener("click", () => {
            quizIndex = 0;
            quizScore = 0;
            updateScoreUI();
            renderQuiz(questions[quizIndex], quizIndex);
        });

        // Initialize the quiz
        renderQuiz(questions[quizIndex], quizIndex);
    </script>
</body>

</html>