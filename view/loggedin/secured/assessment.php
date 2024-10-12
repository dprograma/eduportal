<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"
        integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc"
        crossorigin="anonymous"></script>

    <style>
        @import "https://unpkg.com/open-props";

        /*------------------------------------*\
          #RESET
        \*------------------------------------*/
        :root {
            --clr-primary: var(--blue-8);

            --clr-neutral-500: #1c232b;
            --clr-neutral-400: #28323e;
            --clr-neutral-300: #384656;
            --clr-neutral-200: #51657b;
            --clr-neutral-100: #e6eaef;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body,
        h1,
        h2,
        h3,
        p {
            margin: 0;
        }

        body {
            display: grid;
            place-items: center;
            min-height: 100vh;

            background-image: url(assets/img/bg_patterned_white.png);
            color: var(--clr-neutral-100);
            accent-color: var(--clr-primary);

            font-family: system-ui, sans-serif;
            line-height: 1.5;
        }

        h1,
        h2,
        h3 {
            line-height: 1.1;
        }

        input,
        button,
        textarea,
        select {
            font: inherit;
        }

        /*------------------------------------*\
          #UTILITIES
        \*------------------------------------*/
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: 1px solid var(--clr-neutral-300);
            border-radius: var(--radius-2);
            padding: 0.6em 1.4em;

            background-color: transparent;
            color: var(--clr-neutral-100);

            font-weight: var(--font-weight-6);
            line-height: 1;
            text-transform: uppercase;
            letter-spacing: 0.05em;

            cursor: pointer;
        }

        .btn:is(:hover, :focus-visible) {
            background-color: var(--clr-neutral-400);
        }

        /*------------------------------------*\
          #QUIZ
        \*------------------------------------*/
        .quiz {
            display: grid;
            gap: var(--size-4);

            width: 100%;
            margin-block: 2rem;
            padding: var(--size-4);
            background-color: #fff;

        }

        /* Add your existing styles here */

        /* Additional styles for the modified quiz */
        .question-container {
            display: grid;
            gap: var(--size-4);
            width: min(100% - 1rem, 40rem);
            margin: auto;
            padding: var(--size-4);
            color: black;


        }

        .options {
            margin-bottom: 20px;
        }

        input[type="radio"] {
            display: none;
        }

        label {
            display: block;
            margin-bottom: 10px;

            color: #000;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
            position: relative;
        }

        label:hover {
            background-color: gray;
            color: white;
        }

        input[type="radio"]:checked+label {
            background-color: #2ecc71;
            font-weight: bold;
        }

        input[type="radio"]:checked+label::before {
            content: '\2713';
            font-size: 18px;
            color: #fff;
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
        }

        .correct-answer-btn {
            background-color: #2ecc71;
        }

        .bullet {
            font-size: 24px;
            margin-right: 10px;
        }

        .correct-answer {
            color: #2ecc71;
        }

        .btn-form {
            border-radius: 20px;
            background-color: black;
            color: white;
        }

        .btn-form:hover {
            border-radius: 20px;
            background-color: white;
            color: black;
        }

        .quiz__time {
            color: black;
            position: absolute;
            top: 35px;
            right: 95px;
            /* Adjust as needed */
        }

        @media (min-width: 738px) {
            .quiz__time {

                top: 65px;
                right: 175px;
                /* Adjust as needed */
            }

            .quiz {

                width: 80%;


            }

            #quiz-score {
                text-align: center;
                margin-top: 10px;
                font-weight: bold;
            }
        }
    </style>
</head>

<body>
    <form class="quiz hide" id="quiz-form">
        <div class="question-container" id="quiz-container"></div>
        <div class="quiz__info">
            <!--<p class="quiz__time" id="quiz-timer">00:00</p>-->
            <p id="quiz-score" style="text-align: center; margin-top: 10px; font-weight: bold;">Score: 0</p>
            <div class="button-group" style="display:flex; justify-content:space-around">
                <button class="quiz__reset btn-form" id="quiz-restart-btn" data-type="warning"
                    type="button">Restart</button>
                <button class="quiz__next btn btn-form" data-type="primary" type="submit">Next</button>
            </div>
        </div>
    </form>

    <!-- ... Your existing HTML code ... -->

    <script>
        const quizFormElem = document.getElementById("quiz-form");
        const quizContainerElem = document.getElementById("quiz-container");
        const quizTimerElem = document.getElementById("quiz-timer");
        const quizRestartBtn = document.getElementById("quiz-restart-btn");
        const quizScoreElem = document.getElementById("quiz-score");

        let quizIndex = 0;
        let quizScore = 0;
        let intervalId = null;

        const questions = <?php echo json_encode($questions); ?>;

        quizFormElem.addEventListener("submit", (e) => {
            e.preventDefault();

            const quizInputsElems = document.querySelectorAll("input[name='answer']");
            const userAnswer = findUserAnswer(quizInputsElems);
            const correctAnswer = findCorrectAnswer(quizInputsElems);

            // Display the correct answer
            displayCorrectAnswer(correctAnswer);

            // Evaluate user's score and display the result
            quizIndex++;
            console.log("Quiz index:", quizIndex);
            if (quizIndex >= questions.length) {
                clearInterval(intervalId);
                intervalId = null;

                quizContainerElem.innerHTML += evaluateScore(quizScore, questions.length);
            } else {
                renderQuiz(questions[quizIndex], quizIndex);
            }
        });

        quizRestartBtn.addEventListener("click", () => {
            quizIndex = 0;
            quizScore = 0;

            clearInterval(intervalId);
            intervalId = null;
            setTimer();

            // Update the UI with the current score
            updateScoreUI();

            renderQuiz(questions[quizIndex], quizIndex);
        });

        function renderQuiz(data, index) {
            if (!data) return;

            const output = `   <p style="text-align:center; margin-bottom:-20px; font-weight:bold; text-transform: uppercase; font-size:22px"><?= $examYear ?></p><br><h3 class="quiz__question"><span class="quiz__number">Question ${index + 1
                }<br><p style="margin-top:40px;color:#000; font-size:16px;   font-weight: 600;"></span> ${data.question}</p></h3>
          <div class="quiz__answers">
            ${renderQuizAnswers(data)}
          </div>`;

            quizContainerElem.innerHTML = output;
        }



        // displaying 5 question in colums


        // end od display of 5
        function renderQuizAnswers(question) {
            let output = "";
            let correctAnswerButtonAdded = false;

            const options = ['A', 'B', 'C', 'D', 'E'];

            options.forEach((option, i) => {
                const optionKey = `option_${option.toLowerCase()}`;

                // Check if the option exists in the question object and is not empty
                if (question.hasOwnProperty(optionKey) && question[optionKey].trim() !== "") {
                    output += `<div class="quiz__answer">
                <input type="radio" id="${optionKey}" name="answer" value="${question[optionKey]}" data-correct="${optionKey === question.correct_answer}" required />
                <label for="${optionKey}">
                    ${option}. ${question[optionKey]}
                </label>
            </div>`;


                    // Add the "View Correct Answer" button after rendering all options
                    if (!correctAnswerButtonAdded && (i === options.length - 1 || i === options.length - 2)) {
                        output += `<button class="correct-answer-btn"style="border:2px solid black; border-radius:10px;margin-top:30px; background-color: #165e34;padding:8px;cursor:pointer;
        color: #fff;" onclick="viewCorrectAnswer(this)" data-correct-answer="${question.correct_answer}"><i class="fa fa-eye"style="padding-right:5px" aria-hidden="true"></i>View Correct Answer</button> 
        
        <button class="correct-answer-btn fa-pull-right" style="border:2px solid black; border-radius:10px;margin-top:30px; background-color: #8e0808;padding:8px;cursor:pointer;
        color: #fff;" onclick="SubmitAnswer(this)" data-correct-answer="${question.correct_answer}">Submit Answer</button>`;
                        correctAnswerButtonAdded = true;
                    }
                    console.log("correct answer button added: ", correctAnswerButtonAdded);
                    console.log("I: ", i);
                    console.log("options length: ", options.length - 1);
                }
            });

            return output;
        }
        
        function viewCorrectAnswer(button) {
            const questionDiv = button.closest('.quiz__answers');
            const correctAnswer = button.dataset.correctAnswer;
            const userAnswer = findUserAnswer(questionDiv.querySelectorAll("input[name='answer']"));

            const options = questionDiv.querySelectorAll('label');

            options.forEach((option) => {
                if (option.innerText.includes(correctAnswer)) {
                    option.classList.add('correct-answer');
                }

                if (userAnswer && option.innerText.includes(userAnswer)) {
                    option.classList.add('user-answer');
                }
            });
            
            // Check if the user's answer is correct
            if (userAnswer === correctAnswer) {
                // Increment the quiz score
                quizScore++;
                // Update the UI with the current score
                updateScoreUI();
                alert("Your answer is correct! You earned a point.");
            } else {
                alert("Your answer is incorrect. The correct answer is: " + correctAnswer);
            }

            console.log("User Answer:", userAnswer);
            console.log("Correct Answer:", correctAnswer);
        }


        function SubmitAnswer(button) {
            const questionDiv = button.closest('.quiz__answers');
            const correctAnswer = button.dataset.correctAnswer;
            const userAnswer = findUserAnswer(questionDiv.querySelectorAll("input[name='answer']"));

            const options = questionDiv.querySelectorAll('label');

            options.forEach((option) => {
                if (option.innerText.includes(correctAnswer)) {
                    option.classList.add('correct-answer');
                }

                if (userAnswer && option.innerText.includes(userAnswer)) {
                    option.classList.add('user-answer');
                }
            });
            
            // Check if the user's answer is correct
            if (userAnswer === correctAnswer) {
                // Increment the quiz score
                quizScore++;
                // Update the UI with the current score
                updateScoreUI();
                // alert("Your answer is correct! You earned a point.");
            } 
            // else {
            //     alert("Your answer is incorrect. The correct answer is: " + correctAnswer);
            // }

            console.log("User Answer:", userAnswer);
            console.log("Correct Answer:", correctAnswer);
        }



        function findUserAnswer(quizInputs) {
            for (let i = 0, l = quizInputs.length; i < l; i++) {
                if (quizInputs[i].checked) {
                    // Retrieve the value of the selected radio button
                    return quizInputs[i].value;
                }
            }
            return null;
        }


        function findCorrectAnswer(quizInputs) {
            for (let i = 0, l = quizInputs.length; i < l; i++) {
                if (quizInputs[i].dataset.correct === "true") {
                    return quizInputs[i].id;
                }
            }
            return null;
        }

        function displayCorrectAnswer(correctAnswer) {
            const correctAnswerElem = document.createElement("p");
            correctAnswerElem.classList.add("correct-answer");
            correctAnswerElem.innerHTML = `Correct Answer: ${correctAnswer}`;

            // Append the correct answer element to the quiz container
            quizContainerElem.appendChild(correctAnswerElem);
        }

        function evaluateScore(correctAnswers, totalQuestions) {
            const scoreInPercent = Math.floor((correctAnswers / totalQuestions) * 100);

            let message = '';
            let grade = '';

            if (scoreInPercent >= 80) {
                grade = 'A';
                message = 'Excellent!';
            } else if (scoreInPercent >= 60) {
                grade = 'B';
                message = 'Good job!';
            } else if (scoreInPercent >= 40) {
                grade = 'C';
                message = 'Well done!';
            } else {
                grade = 'D';
                message = 'You can do better.';
            }

            return `<h3>${message}</h3>
              <p>Your grade is ${grade}. You got ${correctAnswers} out of ${totalQuestions} correct (${scoreInPercent}%)</p>`;
        }

        function setTimer() {
            const formatTime = (num, str = "0") => num.toString().padStart(2, str);

            let seconds = 0;
            let minutes = 0;

            if (!intervalId) {
                intervalId = setInterval(() => {
                    if (seconds == 60) {
                        seconds = 0;
                        minutes++;
                    }

                    quizTimerElem.innerHTML = `${formatTime(minutes)}:${formatTime(seconds)}`;
                    seconds++;
                }, 1000); // 1000ms = 1s
            }
        }

        function updateScoreUI() {
            if (quizScoreElem) {
                quizScoreElem.innerText = `Score: ${quizScore}`;
            }
        }

        // Display the first question on page load
        renderQuiz(questions[quizIndex], quizIndex);
    </script>


    <!-- ... Remaining HTML code ... -->

</body>

</html>