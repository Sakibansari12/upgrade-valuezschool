<template>
  <div class="quiz-container">
    <div class="score-headding">
        <h2>{{ quiz.title }}</h2>
    </div>
    
    <div v-for="(question, index) in quiz.questions" :key="index" class="question">
      <h3>{{ index + 1 }} {{ question.text }}</h3>
      <div v-for="(option, oIndex) in question.options" :key="oIndex" class="option">
         <p> {{ option.text }}</p>
      </div>
      <!-- <ul class="options">
        <li v-for="(option, oIndex) in question.options" :key="oIndex" class="option">
          <label>
            <input type="radio" :name="'question_' + index" :value="option.value" v-model="selectedAnswers[index]" class="radio-input">
            {{ option.text }}
          </label>
        </li>
      </ul> -->
    </div>
    <!-- <button @click="submitAnswers" class="submit-btn">Submit</button> -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      quiz: {},
      selectedAnswers: []
    };
  },
  mounted() {
    // Fetch quiz data from the server
    this.fetchQuizData();
  },
  methods: {
    fetchQuizData() {
      this.quiz = {
        title: 'View Answers',
        questions: [
          {
            text: 'Which of the following states is not located in the North?',
            options: [
              { text: 'Jharkhand', value: '3' },
            ]
          },
           {
            text: 'What is the state flower of Haryana?',
            options: [
              { text: 'Golden Shower', value: '3' },
            ]
          },
          {
            text: 'What are the major languages spoken in Andhra Pradesh?',
            options: [
              { text: 'All of the above languagess', value: '3' },
            ]
          },
        ]
      };
    },
    submitAnswers() {
      // Handle submission of answers
      // Send selectedAnswers to the server for evaluation
    }
  }
};
</script>

<style scoped>
.quiz-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  background-color: #ffcc66;
  border-radius: 40px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.question {
  margin-bottom: 20px;
}

.options {
  list-style-type: none;
  padding: 0;
}

.option {
  margin-bottom: 10px;
}

.radio-input {
  margin-right: 5px;
}
</style>
