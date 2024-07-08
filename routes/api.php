<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebPage;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\MasterApi;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('send-workshop-email', [WebPage::class, 'workshopEmail']);
Route::post('send-investor-email', [WebPage::class, 'investorEmail']);
Route::post('send-cpd-email', [WebPage::class, 'cpdEmail']);
Route::post('send-package-email', [WebPage::class, 'packageEmail']);
Route::post('send-footer-email', [WebPage::class, 'footerEmail']);
Route::get('payment-get', [WebPage::class, 'paymentRequestGet']);
Route::get('subscription-request', [WebPage::class, 'paymentSubscriptionRequestGet']);
Route::get('quiz-list', [QuizController::class, 'getQuiz']);
// Quiz API
Route::get('get-qns-list', [MasterApi::class, 'quizlist'])->name('activityQuiz.data');
Route::post('submit-quiz-result', [MasterApi::class, 'QuizScore'])->name('store.quiz.score');
Route::get('quiz-result', [MasterApi::class, 'QuizScoreView'])->name('quiz.report');
