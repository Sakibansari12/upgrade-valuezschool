
// 1. Initialization
const buttons = document.querySelectorAll(".question-button");
const contentButton = document.getElementById("content");
const prevButton = document.getElementById("prevButton");
const nextButton = document.getElementById("nextButton");
const questions = document.querySelectorAll(".question-container");
const btn1 = document.querySelectorAll('.btn1');
const submitButton = document.getElementById("submitButton");
let currentQuestionIndex = 0;
const totalQuestions = questions.length;
let answers = [];

// Hide all questions initially and show the first one
questions.forEach(q => q.style.display = "none");
//questions[0].style.display = "block";
updateContentButton(currentQuestionIndex);
updateButtons();

// 2. Event listeners for the question number buttons
buttons.forEach((button, index) => {
    button.addEventListener("click", function() {
        changeQuestion(index);
    });
});

// 3. Event listeners for the navigation buttons (Next, Previous)
prevButton.addEventListener("click", function() {
    changeQuestion(currentQuestionIndex - 1);
});
nextButton.addEventListener("click", function() {
    changeQuestion(currentQuestionIndex + 1);
});

// 4. Event listeners for the answer buttons
btn1.forEach(button => {
    button.addEventListener('click', () => {
        // Save the selected answer
        answers[currentQuestionIndex] = {
            question: questions[currentQuestionIndex].querySelector('h3').textContent.trim(),
            answer: button.getAttribute('data-value')
        };

        // If the button is already selected, deselect it
        if (button.classList.contains('selected2')) {
            button.classList.remove('selected2');
        } else {
            // Remove the 'selected2' class from all buttons of this question
            questions[currentQuestionIndex].querySelectorAll('.btn1').forEach(b => b.classList.remove('selected2'));
            // Add the 'selected2' class to the clicked button
            button.classList.add('selected2');
        }
        let correspondingButton = document.getElementById(`button${currentQuestionIndex + 1}`);
        if (correspondingButton) {
            correspondingButton.classList.add('answered');
        }
    });
});

// 5. Event listener for the submit button
// Event listener for the main submit button
submitButton.addEventListener("click", function() {
    displayAllAnswers();
    location.reload();
});

// Function to display all answers in an alert box
function displayAllAnswers() {
    let allAnswers = "";

    // Iterate over the button answers
    answers.forEach((answer, index) => {
        allAnswers += "Question " + (index + 1) + ": " + answer.answer + "\n";  // Adjusted this line to extract the answer
    });

    // Add the answer from question 6
    let feedback = input6.value.trim();
    if (feedback) {
        allAnswers += "Question 6: " + feedback + "\n";
    } else {
        allAnswers += "Question 6: No feedback provided.\n";
    }

    alert(allAnswers);
    
}



// 6. Helper functions
function updateContentButton(index) {
    contentButton.textContent = `${index + 1}`;
    buttons.forEach((button, i) => {
        button.classList.toggle("selected", i === index);
    });
}

function changeQuestion(index) {
    if (index >= 0 && index < totalQuestions) {
        // Hide the current question
        questions[currentQuestionIndex].style.display = "none";
        // Update the current question index
        currentQuestionIndex = index;
        // Show the new current question
        questions[currentQuestionIndex].style.display = "block";
        // Update the content and navigation buttons
        updateContentButton(index);
        updateButtons();
    }
}

function updateButtons() {
    prevButton.disabled = currentQuestionIndex === 0;
    nextButton.disabled = currentQuestionIndex === totalQuestions - 1;
    if (currentQuestionIndex === totalQuestions - 1) {
        submitButton.style.display = 'block';
    } else {
        submitButton.style.display = 'none';
    }
}








// // Get reference to the new button and the output paragraph
// const apiButton = document.getElementById("apiButton");
// const apiOutput = document.getElementById("apiOutput");

// // Add click event listener to the button
// apiButton.addEventListener("click", function() {
//     // Define the API endpoint. Replace with your API URL
//     const apiEndpoint = "https://employeedetails.free.beeceptor.com/my/api/path";

//     // Use fetch to make the API call
//     fetch(apiEndpoint)
//     .then(response => {
//         if (!response.ok) {
//             throw new Error('Network response was not ok');
//         }
//         return response.text(); // first get the response as text
//     })
//     .then(text => {
//         try {
//             const data = JSON.parse(text); // try parsing it as JSON
//             apiOutput.textContent = JSON.stringify(data, null, 2);
//         } catch (error) {
//             // if parsing fails, treat it as plain text
//             apiOutput.textContent = text;
//         }
//     })
//     .catch(error => {
//         console.log("There was a problem with the fetch operation:", error.message);
//         apiOutput.textContent = "Failed to fetch data from the API.";
//     });

// });