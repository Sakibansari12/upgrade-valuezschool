<template>
    <section class="widget-card  questionBox">
        <div class="row align-items-center justify-content-center">
            <div v-if="quizItem.length > 0" class="col-md-12 col-xl-10 mt-3">

                <div style="border-radius: 1.25rem!important;" class="card box-quiz mb-4"
                    v-if="quizItem.length > questionIndex">
                    <!--progress-->

                    <step-progress :length="quizItem.length" :current="questionIndex + 1"></step-progress>

                    <!--/progress-->
                    <div class="card-body">
                        <!-- <div class="progress1 mb-3">
                            <div class="progress-bar bg-success" role="progressbar"
                                :style="{ 'width': (questionIndex / quizItem.length) * 100 + '%' }"
                                :aria-valuenow="(questionIndex / quizItem.length) * 100" aria-valuemin="0"
                                aria-valuemax="100">
                                {{ (questionIndex / quizItem.length) * 100 }}%</div>
                        </div> -->


                        <h5 class="my-2 text-center" v-if="question.question_text">{{ question.question_text }}</h5>
                        <div class="quiz-title-info mb-3">
                            <h4 class="card-title" v-html="'Q' + (questionIndex + 1) + '. ' + question.question_title">
                            </h4>
                        </div>

                        <a href="#" v-if="userData.usertype != 'student'"
                            class="waves-effect waves-light btn btn-sm btn-rounded btn-primary pull-right"
                            @click="selectHint(question.crct_answer)">Hint</a>

                        <div class="quiz-box-img mb-4">
                            <div class="d-flex align-items-center my-3" v-if="question.question_image">
                                <img class="mb-2" :src="baseUrl + 'uploads/quiz/' + question.question_image"
                                    :alt="question.question_title" />
                            </div>

                            <!-- 16:9 aspect ratio -->
                            <div v-if="question.question_url" class="embed-responsive embed-responsive-16by9">
                                <iframe width="100%" height="315" style="margin: auto;display: block;"
                                    :src="question.question_url" frameborder="0" allow="autoplay; encrypted-media"
                                    allowfullscreen></iframe>
                            </div>

                            <div class="row mt-4">
                                <div v-if="question.question_mcq_opt"
                                    v-for="(option, index) in JSON.parse(question.question_mcq_opt)"
                                    class="col-md-6 col-lg-3">
                                    <a href="javascript:void(0)" class="btn pushable mb-3 font-weight-600"
                                        @click="selectOption(index)"
                                        :class="{ 'btn-success': userResponses[questionIndex] == index, 'btn-success btn-hint': hintResponses[questionIndex] == index }"
                                        :key="index">
                                        <span class="front">{{ option }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>


                        <!--pagination-->

                        <div class="box-footer d-flex justify-content-center">
                            <!-- back button -->
                            <button type="button" class="btn btn-danger me-5" v-if="questionIndex > 0" @click="prev();"
                                :disabled="questionIndex < 1">
                                < Previous </button>

                                    <!-- next button -->
                                    <button v-if="(userResponses[questionIndex] != null)" type="button"
                                        class="btn btn-warning ms-5"
                                        :class="(userResponses[questionIndex] == null) ? '' : 'is-active'"
                                        @click="next();" :disabled="questionIndex >= quizItem.length">
                                        {{ (userResponses[questionIndex] == null) ? 'Skip >' : 'Next >' }}
                                    </button>
                        </div>
                        <a href="#" v-if="(userResponses[questionIndex] == null)" @click="next();"
                            class="pull-right">Skip ></a>
                        <!--/pagination-->
                    </div>
                </div>

                <!--quizCompletedResult-->
                <div v-if="questionIndex >= quizItem.length" :key="questionIndex"
                    class="quizCompleted has-text-centered">

                    <!-- quizCompletedIcon: Achievement Icon -->
                    <div class="card card-icon-big card-profile-1 mb-4" v-if="viewAns == false">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Score Card</h5>
                        </div>
                        <div class="card-body text-center">
                            <!-- quizCompletedIcon: Achievement Icon -->
                            <div class="row">

                                <div class="col-md-6">
                                    <i class="my-3"
                                        :class="score() > 1 ? 'fa fa-medal text-success' : 'fa fa-face-rolling-eyes text-danger'"
                                        style="font-size:60px;"></i>
                                </div>

                                <div class="col-md-6">
                                    <ul class="list-group my-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">Total Questions:</div>

                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ quizItem.length }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">Attempted Questions:</div>

                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ userResponses ?
                                                userResponses.filter(function (e) {
                                                    return e
                                                }).length : 0 }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">Correct Answers:</div>

                                            </div>
                                            <span class="badge bg-success rounded-pill">{{ score() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">Incorrect Answers:</div>

                                            </div>
                                            <span class="badge bg-danger rounded-pill">{{ wrngAns() }}</span>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                            <!--resultTitleBlock-->
                            <div class="card-footer">
                                <button class="btn btn-primary btn-rounded me-2" @click="viewAnswer(true)">View
                                    Answers</button>
                                <button class="btn btn-primary btn-rounded" @click="restart()">Restart <i
                                        class="fa fa-refresh"></i></button>
                            </div>
                            <!--/resultTitleBlock-->

                        </div>
                    </div>

                    <div v-if="viewAns == true">
                        <button class="btn btn-primary btn-rounded me-2" @click="viewAnswer(false)">Back</button>
                        <button class="btn btn-primary btn-rounded" @click="restart()">Restart <i
                                class="fa fa-refresh"></i></button>

                        <div class="card" v-for="(qitem, qk) in quizItem">
                            <div class="card-body">
                                <!-- <p class="my-2 text-24 " v-if="qitem.question_text">{{ qitem.question_text }}</p> -->
                                <h4 class="card-title" v-html="'Q' + (qk + 1) + '. ' + qitem.question_title"></h4>
                                <div class="d-flex align-items-center quiz-box-img my-3">
                                    <img class="mb-2" :src="baseUrl + 'uploads/quiz/' + qitem.question_image" />
                                </div>
                                <p>Answer : {{ JSON.parse(qitem.question_mcq_opt)[qitem.crct_answer] }}</p>
                                <p v-if="qitem.crct_feedback">Correct Feedback : {{ qitem.crct_feedback }}</p>
                            </div>
                        </div>
                    </div>

                </div>
                <!--/quizCompetedResult-->
            </div>

            <div class="col-md-12 col-xl-8 col-lg-10 mt-3" v-else>
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="spinner-bubble spinner-bubble-primary m-5"></div>
                        <div class="card-title mb-4">Loading...</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>


<script>
import axios from "axios";
import stepProgress from './stepProgessSlider'

export default {
    data() {
        return {
            quizItem: [],
            questionIndex: 0,
            question: [],
            userResponses: [],
            hintResponses: [],
            userQuizRes: [],
            viewAns: false,
            quizstart: start_time,
            userData: window.auth,
            baseUrl: baseUrl + '/',
        }
    },
    components: { stepProgress },
    methods: {
        loadQuizData() {
            let config_chapter = {
                headers: { accept: "application/json" },
                params: {
                    grade: 0,
                },
            }
            axios.get(route('activityQuiz.data'), config_chapter).then((res) => {
                this.quizItem = res.data.recordList;
                this.question = this.quizItem[0];
            });
        },
        restart: function () {
            this.questionIndex = 0;
            this.userResponses = [];
            this.hintResponses = [];
            this.userQuizRes = [];
            // this.userResponses = Array(this.quizItem.length).fill(null);
            this.question = this.quizItem[this.questionIndex];
        },
        next: function () {
            if (this.questionIndex < this.quizItem.length) {
                this.questionIndex++;
                this.question = this.quizItem[this.questionIndex];
            }

            if (this.questionIndex >= this.quizItem.length) {
                this.submitScore();
            }
        },

        prev: function () {
            if (this.quizItem.length > 0) {
                this.questionIndex--;
                this.question = this.quizItem[this.questionIndex];
            }
        },
        selectOption: function (index) {
            if (this.questionIndex < this.quizItem.length) {
                this.userResponses[this.questionIndex] = index;
                this.userQuizRes[this.quizItem[this.questionIndex].id] = index;
            }
            // console.log(this.userResponses);
        },
        selectHint: function (index) {
            if (this.questionIndex < this.quizItem.length) {
                // this.userResponses[this.questionIndex] = index;
                this.hintResponses[this.questionIndex] = index;
                this.userQuizRes[this.quizItem[this.questionIndex].id] = index;
            }
            console.log(this.hintResponses);
        },
        score: function () {
            var score = 0;
            for (let i = 0; i < this.userResponses.length; i++) {
                // console.log(JSON.parse(this.quizItem[i].question_mcq_opt), this.quizItem[i].crct_answer);
                if (JSON.parse(this.quizItem[i].question_mcq_opt) !== "undefined" && this.userResponses[i] == this.quizItem[i].crct_answer) {
                    score = score + 1;
                }
            }
            return score;
        },
        wrngAns: function () {
            var userRes = this.userResponses.filter(function (e) { return e }).length;
            var rightAns = this.score();
            return (userRes - rightAns);
        },
        viewAnswer: function (vA) {
            this.viewAns = (vA == true) ? true : false;
        },
        submitScore: function () {
            axios.post(route('store.quiz.score', {
                uid: this.userData.id,
                quiz_title_id: 1,
                total_attempt: this.userResponses ? this.userResponses.filter(function (e) { return e }).length : 0,
                wrng_attempt: this.wrngAns(),
                right_attempt: this.score(),
                start_time: this.quizstart,
                quiz_summary: this.userQuizRes,
            })).then((res) => {
                console.log(res);
            });

        }
    },
    watch: {
        quizItem() {
            this.quizItem = this.quizItem;
        }
    },
    created() {
        this.loadQuizData();
        console.log('Play Quiz Component mounted.')
    }
}
</script>

<!-- Include Font Awesome CSS -->
<style>
a.pushable {
    text-decoration: none !important;
    width: 100%;
}

.btn-success>.front {
    background: hsl(216.36deg 51.56% 25.1%);
    color: #ffffff;
}

.pushable {
    background: hsl(61.46deg 17.3% 53.53%);
    border: none;
    border-radius: 12px;
    padding: 0;
    cursor: pointer;
}

.front {
    display: block;
    padding: 12px 42px;
    border-radius: 12px;
    font-size: 1.25rem;
    background: hsl(60deg 16.67% 90.59%);
    color: #4f4f4f;
    transform: translateY(-4px);
}

.pushable:focus:not(:focus-visible) {
    outline: none;
}

.front {
    will-change: transform;
    transition: transform 250ms;
}

.pushable:hover .front {
    transform: translateY(-6px);
}

.pushable:active .front {
    transform: translateY(-2px);
}

.quiz-title-info {
    border-radius: 1.25rem !important;
    padding: 15px;
    background-color: #00205c !important;
    color: #FFF;
}

.quiz-box-img img {
    max-width: 400px;
    height: auto;
    margin: 0 auto;
}

.box-quiz {
    background-color: #fee865 !important;
}

.btn-hint>.front {
    background: #499b8e;
    color: #ffffff;
}
</style>
