
<div class="bg-white p-8 rounded shadow-md w-full max-w-5xl m-5">
    <div class="flex justify-between">
     <h1 class="text-2xl font-bold mb-4">Test Name</h1>
     <div id="timer" class="text-red-500 text-xl mb-4">Time Left: <span id="timeLeft">00:00</span></div>
    </div>
     <div id="questionContainer" class="mb-4">
         <p id="questionText" class="text-lg">Question 1: What is your favorite color?</p>
         <div class="mt-4">
             <label class="block">
                 <input type="radio" name="answer" value="red" class="mr-2"> Red
             </label>
             <label class="block">
                 <input type="radio" name="answer" value="blue" class="mr-2"> Blue
             </label>
             <label class="block">
                 <input type="radio" name="answer" value="green" class="mr-2"> Green
             </label>
             <label class="block">
                 <input type="radio" name="answer" value="yellow" class="mr-2"> Yellow
             </label>
         </div>
     </div>
     <div class="flex justify-between">
         <button id="prevBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded" onclick="prevQuestion()" disabled>Previous</button>
         <button id="nextBtn" class="bg-blue-500 text-white px-4 py-2 rounded" onclick="nextQuestion()">Next</button>
     </div>
 </div>

 <script>
     const questions = [
         {
             text: "Question 1: What is your favorite color?",
             answers: ["Red", "Blue", "Green", "Yellow"]
         },
         {
             text: "Question 2: What is your favorite animal?",
             answers: ["Dog", "Cat", "Bird", "Fish"]
         },
         {
             text: "Question 3: What is your favorite season?",
             answers: ["Spring", "Summer", "Autumn", "Winter"]
         },
         {
             text: "Question 4: What is your favorite food?",
             answers: ["Pizza", "Sushi", "Burger", "Pasta"]
         }
     ];

     let currentQuestionIndex = 0;
     let timer;
     let timeLeft = 300; // Set time in seconds (e.g., 300 seconds = 5 minutes)

     function startTimer() {
         timer = setInterval(() => {
             if (timeLeft <= 0) {
                 clearInterval(timer);
                 alert("Time's up!");
                 // Optionally, you can submit the form here
             } else {
                 timeLeft--;
                 updateTimerDisplay();
             }
         }, 1000);
     }

     function updateTimerDisplay() {
         const minutes = Math.floor(timeLeft / 60);
         const seconds = timeLeft % 60;
         document.getElementById('timeLeft').innerText = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
     }

     function updateQuestion() {
         const currentQuestion = questions[currentQuestionIndex];
         document.getElementById('questionText').innerText = currentQuestion.text;
         const answersContainer = document.getElementById('questionContainer');
         answersContainer.querySelectorAll('label input').forEach((input, index) => {
             input.nextSibling.textContent = currentQuestion.answers[index];
         });

         document.getElementById('prevBtn').disabled = currentQuestionIndex === 0;
         document.getElementById('nextBtn').disabled = currentQuestionIndex === questions.length - 1;
     }

     function nextQuestion() {
         if (currentQuestionIndex < questions.length - 1) {
             currentQuestionIndex++;
             updateQuestion();
         }
     }

     function prevQuestion() {
         if (currentQuestionIndex > 0) {
             currentQuestionIndex--;
             updateQuestion();
         }
     }

     // Initialize the first question and start the timer
     updateQuestion();
     startTimer();
     updateTimerDisplay();
 </script>
