// document.addEventListener("DOMContentLoaded", function () {
//     const questionsContainer = document.getElementById("questions-container");

//     function displayQuiz(questions) {
//         questionsContainer.innerHTML = ""; // Clear previous questions

//         questions.forEach((question, index) => {
//             const questionElement = document.createElement("div");
//             questionElement.classList.add("question");
//             questionElement.innerHTML = `
//                 <h3>Question ${index + 1}</h3>
//                 <p>${question.question}</p>
//                 <div class="options">
//                     ${Object.keys(question.options).map((key) => `
//                         <div class="form-check">
//                             <input class="form-check-input" type="radio" name="question-${index}" id="question-${index}-option-${key}" value="${key}">
//                             <label class="form-check-label" for="question-${index}-option-${key}">
//                                 ${question.options[key]}
//                             </label>
//                         </div>
//                     `).join('')}
//                 </div>
//             `;
//             questionsContainer.appendChild(questionElement);
//         });
//     }

//     // Example fetch call (adjust URL accordingly)
//     fetch("path/to/questions")
//         .then(response => response.json())
//         .then(data => displayQuiz(data))
//         .catch(error => console.error('Error fetching questions:', error));
// });


document.addEventListener("DOMContentLoaded", function () {
    const questionsContainer = document.getElementById("questions-container");

    // Helper function to decode HTML entities
    function decodeHTMLEntities(text) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(text, "text/html");
        return doc.documentElement.textContent;
    }

    function displayQuiz(questions) {
        questionsContainer.innerHTML = ""; // Clear previous questions

        questions.forEach((question, index) => {
            const questionElement = document.createElement("div");
            questionElement.classList.add("question");

            // Decode HTML tags in the question text before inserting
            const decodedQuestion = decodeHTMLEntities(question.question);

            questionElement.innerHTML = `
                <h3>Question ${index + 1}</h3>
                <p>${decodedQuestion}</p> 
                <div class="options">
                    ${Object.keys(question.options).map((key) => `
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question-${index}" id="question-${index}-option-${key}" value="${key}">
                            <label class="form-check-label" for="question-${index}-option-${key}">
                                ${question.options[key]}
                            </label>
                        </div>
                    `).join('')}
                </div>
            `;
            questionsContainer.appendChild(questionElement);
        });
    }

    // Example fetch call (adjust URL accordingly)
    fetch("path/to/questions")
        .then(response => response.json())
        .then(data => displayQuiz(data))
        .catch(error => console.error('Error fetching questions:', error));
});
