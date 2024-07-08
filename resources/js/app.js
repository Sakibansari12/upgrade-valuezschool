import { createApp } from 'vue'

import Quiz from './components/Quiz.vue';

const app = createApp({});
// registers our HelloWorld component globally
app.component('quiz-page', Quiz);
// mount the app to the DOM
app.mount('#app');

require('./bootstrap');
