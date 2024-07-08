<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonPlanController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\RazorpayPaymentController;
use App\Http\Controllers\HealtyMindController;
use App\Http\Controllers\ChatGptController;
use App\Http\Controllers\FeedBackController;
use App\Http\Controllers\{WebPage, Notification, ReportController, UserController};
use App\Http\Controllers\TestController;
use App\Http\Controllers\NcfAssessmentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\IdentifierConroller;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('testcheck', [TestController::class, 'test']);
Route::get('test-aimodule', [TestController::class, 'getAImodule']);


Route::get('lesson_plan', [TestController::class, 'getLessonPlan']);

Route::get('lesson_plan_view', [TestController::class, 'getLessonPlanView']);

Route::get('getUser', [TestController::class, 'testUser']);
Route::get('getStudent', [TestController::class, 'testStudent']);
Route::get('testlogin', [TestController::class, 'logintest']);
Route::get('macaddress', [TestController::class, 'macaddress']);
Route::get('pdftest', [TestController::class, 'generatePDF']);
Route::post('student-payment-insert', [StudentController::class, 'studentPaymentInsert'])->name('student-payment');
Route::post('payment_webhook_style_1', [SchoolController::class, 'payment_success']);
Route::get('razorpay-payment', [RazorpayPaymentController::class, 'store'])->name('razorpay.payment.store');
Route::get('sr-razorpay-payment', [RazorpayPaymentController::class, 'srStore'])->name('sr-razorpay-payment');
Route::get('sr-razorpay-payment-failed', [RazorpayPaymentController::class, 'srStore'])->name('sr-razorpay-payment-failed');
Route::get('student-payment-failed', [StudentController::class, 'studentPaymentFailed'])->name('student-payment-failed');
Route::middleware(['auth'])->controller(TestController::class)->group(function () {
    Route::get('session', [TestController::class, 'sessionData']);
});




//Route::post('payment-1', [TestController::class, 'payment_webhook_style_1']);
Route::middleware('isLogin')->get('/', function () {
    return redirect(route('login'));
});

/* Route::middleware(['auth', 'isAdmin'])->prefix('school')->controller(RazorpayPaymentController::class)->group(function () {
Route::get('payment', [RazorpayPaymentController::class, 'index'])->name('school-payment');
Route::post('payment', [RazorpayPaymentController::class, 'store'])->name('school-payment.store');
Route::get('school-payment', 'SchoolPayment')->name('school-payment');
    Route::post('school-payment', 'SchoolPaymentPay')->name('school-payment.store');

}); */

/* Route::get('school-payment', 'SchoolPayment')->name('school-payment');
    Route::post('school-payment', 'SchoolPaymentPay')->name('school-payment.store'); */

Route::get('creta-new', [StudentController::class, 'getdata']);
Route::middleware('isLogin')->controller(StudentController::class)->group(function () {
    Route::get('register', 'indexOne')->name('login-student');
    Route::post('login-student', 'authstudentotp')->name('student-otp');
    Route::post('login-student-user', 'authstudent')->name('login-students');
    Route::post('verify-otp', 'studentVerifyOtp')->name('verify-otp');
    Route::post('verify-otp-student', 'VerifyOtpLoginPage')->name('verify-otp-student');
    Route::post('otp-verify-at', 'OtpVerifyAtNullCase')->name('otp-verify-at');
    Route::get('dashboard-student', 'DashboardStudent')->name('student-dashboard');

    Route::post('access-code-login', 'AccessCodeLogin')->name('access-code-login');
    Route::post('student-login-with-otp', 'StudentLoginWithOtp')->name('student-login-with-otp');

    /* Registration  */
    Route::get('student-login', 'StudentIndex')->name('student-login');
    /*Start New */
    Route::post('student-login', 'StudentLoginAllProcess')->name('process-student-login');
    Route::get('phone-number', 'StudentPhoneVerify')->name('phone-number');
    Route::post('get-otp', 'StudentGetOtp')->name('get-otp');




    Route::get('otp-verify', 'StudentOtpVerify')->name('otp-verify');
    Route::post('otp-verify-login', 'LoginVerifyOtp')->name('otp-verify-login');

    Route::get('login-with-otp', 'StudentLoginWithOpt')->name('login-with-otp');
    Route::post('login-with-get-otp', 'StudentLoginWithGetOtp')->name('login-with-get-otp');

    Route::get('school-access-code', 'SchoolAccessCode')->name('school.access.code');
    Route::post('school-access-code', 'SchoolAccessCodeLogin')->name('school-access-code-update');


    /* Login */
    Route::post('student-login-verify-otp', 'StudentLoginWithVerifyOtp')->name('student-login-verify-otp');


    Route::get('no-access-code', 'studentNoAccessCode')->name('no-access-code');


    Route::get('set-password', 'StudentSetPassword')->name('set-password');
    Route::post('forgot-password-login', 'StudentForgotPassword')->name('forgot-password-login');

    Route::get('forgot-phone-number', 'ForgotPhoneVerify')->name('forgot-phone-number');
    Route::get('forgot-otp-verify', 'ForgotOtpVerify')->name('forgot-otp-verify');

    Route::post('reset-otp', 'StudentResetOTp')->name('reset-otp');

    /* End */








    Route::get('access-code-login', 'StudentIndexAccessCode')->name('student-login-access-code');
    Route::post('student', 'StudentLogin')->name('student.login');
    Route::post('login-with-access-code', 'StudentAccessCodeLogin')->name('login-with-access-code');
    Route::post('student-login-otp', 'StudentOtpLogin')->name('student-login-otp');

    Route::get('access-student-denied', 'StudentSessionLimit')->name('access-student-denied');


    Route::post('student-verify-otp', 'studentLoginVerifyOtp')->name('student-verify-otp');
    /*Forgot Password */
    Route::post('student-forgot-username', 'ForgotUsername')->name('student-forgot-username');
    Route::post('student-forgot-get-otp', 'StudentGetOtpForgot')->name('student-forgot-get-otp');
    Route::post('student-password-change', 'PasswordChange')->name('student-password-change');

    Route::get('forgot-password', 'StudentForgot')->name('student-forgot');

    Route::get('access-code', 'studentaccesscode')->name('access.code');
    Route::post('access-code-get', 'AccessCodeGet')->name('access-code-get');

    Route::post('forgot-verify-otp', 'StudentVerifiedOtpForgot')->name('forgot-verify-otp');
    Route::post('forgot-email-verify-otp', 'StudentEmailVerifiedOtpForgot')->name('forgot-email-verify-otp');
    Route::post('student-forgot-password', 'StudentForgotPassword')->name('forgot-password');
});

Route::middleware(['auth', 'isAdmin'])->prefix('student')->controller(StudentController::class)->group(function () {
    Route::get('manage-student', 'studentlist')->name('student.list');
    Route::get('add-student', 'addStudent')->name('student.add');
    Route::get('update-student/{schoolid?}', 'updateStudent')->name('student.edit');
    Route::post('create-student', 'CreateStudent')->name('student.store');
    //Route::delete('student-remove/{studentid?}', 'destroy')->name('student.remove');
    Route::put('student-edit/{studentid?}', 'editstudent')->name('student.update');
    Route::get('student-upload', 'uploadStudent')->name('student.bulkUploadForm');
    Route::post('process-bulk-upload', 'import')->name('student.import');
    Route::post('student-remove', 'destroy')->name('student.remove');
    Route::post('reset-password', 'resetPassword')->name('student.password');
    Route::get('download', 'download')->name('download');
    Route::get('student-filter-data', 'indexfilter')->name('student-filter-grade');
    Route::get('student-status-change', 'StudentStatusChange')->name('student.status.change');
    Route::post('student-status', 'change_student_status')->name('student-status');
    Route::get('student-logs-list/{userid}', 'StudentviewLogs')->name('student.logs.list');

    Route::post('student.verify-admin', 'VerifyadminPassword')->name('student.verify-admin');

    /* Forgot Password Module */
    /* Route::get('manage-forgot-password', 'ForgotPasswordList')->name('student.forgot.list');
    Route::get('update-forgot-student', 'getUpdateForgotStudent')->name('student-forgot.edit');
    Route::put('student-forgot-edit', 'UpdateForgotStudent')->name('student.update-forgot-password');
    Route::get('student-forgot-status-update', 'StatusUpdateForgot')->name('student-forgot.approved');
    Route::get('single-student', 'SingleStudent')->name('forgot-single-student'); */
    Route::get('school-student', 'allSchoolStudentList')->name('all.school.student.list');
    Route::post('student-update-status', 'allStudentStatusList')->name('student.update.status');
    Route::get('student-login-detail/{user_id}', 'StudentLoginHis')->name('student-login-history');
});

Route::middleware(['auth'])->prefix('student')->controller(StudentController::class)->group(function () {
    /* Student list in school admin */
    Route::get('student-list', 'studentlistSchooladmin')->name('student-list');
    Route::get('student-logs-history/{userid}', 'StudentviewLogs')->name('student-logs-history');
    Route::get('student-payment-history', 'StudentPaymentHistory')->name('student-payment-history');
});


Route::middleware('auth')->prefix('school')->controller(ExportController::class)->group(function () {
    Route::get('export-teacher', 'ExportUser')->name('teacher.export');
    Route::get('teacher-email-teacher', 'emailTeachers')->name('teacher.email-teacher');
    Route::get('export-school-payment', 'ExportSchoolPayment')->name('export-school-payment');
    Route::get('export-sr-school-payment', 'ExportSRSchoolPayment')->name('export-sr-school-payment');
    Route::get('export-subscription-request', 'ExportSubscriptionRequest')->name('export-subscription-request');
});

Route::middleware(['auth'])->prefix('school-payments-list')->controller(ExportController::class)->group(function () {
    Route::get('export-all-school-payment', 'ExportAllSchoolPayment')->name('export-all-school-payment');
});



Route::middleware('auth')->prefix('student')->controller(ExportController::class)->group(function () {
    Route::get('export-student', 'ExportStudent')->name('student.export');
});
Route::get('school-teacher', [ExportController::class, 'SchoolTeacher'])->name('school-teacher');
Route::middleware(['auth', 'isAdmin'])->prefix('forgpt-password')->controller(StudentController::class)->group(function () {
    /* Forgot Password Module */
    Route::get('manage-forgot-password', 'ForgotPasswordList')->name('student.forgot.list');
    Route::get('update-forgot-student', 'getUpdateForgotStudent')->name('student-forgot.edit');
    Route::put('student-forgot-edit', 'UpdateForgotStudent')->name('student.update-forgot-password');
    Route::get('student-forgot-status-update', 'StatusUpdateForgot')->name('student-forgot.approved');
    Route::get('single-student', 'SingleStudent')->name('forgot-single-student');
    Route::get('school-admin-list', 'ForgotPasswordSchoolAdmin')->name('school-admin-forgot-password-list');
    Route::get('school_admin_forgotPass_notify', 'forgotPasswordNotify')->name('school_admin_forgotPass_notify');
});
Route::middleware(['auth', 'isAdmin'])->prefix('register-student-list')->controller(StudentController::class)->group(function () {
    /* Forgot Password Module */
    Route::get('register-list', 'RegisterStudentList')->name('register-list');
    Route::get('register-student', 'getRegisterStudent')->name('register-student.edit');
    Route::put('register-student-update', 'UpdateRegisterStudent')->name('register-student.update');
    Route::get('register-student-removie', 'RegisterStudentRemove')->name('register-student-removie');
});

Route::middleware(['auth', 'isAdmin'])->prefix('package')->controller(StudentController::class)->group(function () {
    Route::get('package-list', 'packageList')->name('package-list');
    Route::get('add-package', 'addPackage')->name('package.add');
    Route::post('package-create', 'createPackage')->name('package.store');
    Route::post('package-remove', 'destroyPackage')->name('package.remove');
    Route::get('edit', 'editPackage')->name('package.edit');
    Route::get('view', 'ViewPackage')->name('package.view');
    Route::post('package-verify-admin', 'VerifyadminPasswordPackage')->name('package.verify-admin');
    Route::post('update-package', 'packageUpdate')->name('update-package');
});


Route::middleware(['auth', 'isAdmin'])->prefix('school-payments-list')->controller(SchoolController::class)->group(function () {
    Route::get('payment-list', 'SchoolPaymentList')->name('payment-list');
    Route::get('payment-edit', 'getsinglePayment')->name('payment-edit');
    Route::post('school-payment-update', 'UpdatePayment')->name('school-payment-update');
    Route::get('school-payment-removie', 'SchoolPaymentRemove')->name('school-payment-removie');
    //  Route::post('payment-1', 'payment_webhook_style_1');
});

Route::get('home', [AuthController::class, 'dashboard']);
Route::get('signout', [AuthController::class, 'signout'])->name('signout');
Route::get('student-signout', [AuthController::class, 'StudentSignout'])->name('student-signout');

Route::middleware('isLogin')->controller(AuthController::class)->group(function () {
    Route::get('login', 'index')->name('login');
    Route::post('process-login', 'authuser')->name('login.process');
    Route::get('forgot_password', 'schoolAdminForgot')->name('school-admin-forgot');
    Route::post('forgot-password', 'schoolAdminForgotPasswor')->name('school-admin-forgot-password');
});
Route::post('remove-ipaddress', [AuthController::class, 'IpaddressRemove'])->name('remove.ipaddress');

Route::middleware(['auth', 'isAdmin'])->get('master-dashboard', [AuthController::class, 'AdminDash'])->name('admin-dashboard');
Route::middleware('auth')->get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

Route::middleware(['auth', 'isAdmin'])->prefix('school')->controller(SchoolController::class)->group(function () {
    Route::get('manage-school', 'index')->name('school.list');
    Route::get('add-new-school', 'addschool')->name('school.add');
    Route::get('update-school', 'editschool')->name('school.edit');
    Route::post('remove-school', 'destroy')->name('school.remove');
    Route::post('preview-school', 'previewSchool')->name('school.preview');
    Route::post('school-add', 'store')->name('school.store');
    Route::post('school-edit', 'edit')->name('school.update');
    Route::post('school-status', 'change_status')->name('school.status');
    Route::post('school-demo-status-update', 'change_school_demo_status')->name('school.demo.status');
    Route::post('fetch-cities', 'CityList')->name('city.json');
    Route::get('school-payment', 'SchoolPayment')->name('school-payment');
    Route::post('school-payment', 'SchoolPaymentPay')->name('school-payment.store');
    Route::post('upload-invoice', 'UploadInvoice')->name('upload-invoice');
    Route::get('downloadInvoice', 'PaymentInvoice')->name('download-invoice');
    Route::post('sent-email-payment-link', 'SentEmailPaymentLink')->name('sent-email-payment-link');
    Route::post('payment-removie', 'CreatePagePaymentRemove')->name('payment-removie');
    Route::post('deactivate-payment-link', 'DeactivatePaymentLink')->name('deactivate-payment-link');
    Route::post('single-school-payment-list', 'singleSchoolPayment')->name('single-school-payment-list');
    Route::post('single-student-payment-list', 'singleStudentPayment')->name('single-student-payment-list');
    Route::post('student-payment-faild-list', 'studentPaymentfailedpopup')->name('student-payment-faild-list');
    Route::post('school.verify-admin', 'VerifyadminPassword')->name('school.verify-admin');
    Route::post('payment.verify-admin', 'VerifyadminPasswordPayment')->name('payment.verify-admin');
    Route::get('student-payment-list', 'StudentPaymentList')->name('student-payment-list');
    Route::get('student-generate-invoice', 'generateStudentInvoice')->name('student-generate-invoice');
    Route::post('student-view-status', 'change_student_view_status')->name('student.view.status');
    Route::post('student-payment-verify-admin', 'VerifyadminPasswordStudentPayment')->name('student-payment-verify-admin');
    Route::post('student-payment-removie', 'StudentPaymentRemove')->name('student-payment-removie');
});

Route::middleware(['auth', 'isAdmin'])->prefix('course')->controller(CourseController::class)->group(function () {
    Route::get('manage-course', 'index')->name('course.list');
    Route::get('add-course', 'addcourse')->name('course.add');
    Route::get('update-course', 'editcourse')->name('course.edit');
    Route::post('remove-course', 'destroy')->name('course.remove');
    Route::post('course-add', 'store')->name('course.store');
    Route::post('course-edit', 'edit')->name('course.update');
    Route::post('course-status', 'change_status')->name('course.status');

    Route::post('course.verify-admin', 'VerifyadminPassword')->name('course.verify-admin');
});

Route::middleware(['auth', 'isAdmin'])->prefix('lesson-plan')->controller(LessonPlanController::class)->group(function () {
    Route::get('manage-lesson-plan', 'index')->name('lesson.plan.list');
    Route::get('add-lesson-plan', 'addlessonplan')->name('lesson.plan.add');
    Route::get('update-lesson-plan', 'editlessonplan')->name('lesson.plan.edit');
    Route::post('remove-lesson-plan', 'destroy')->name('lesson.plan.remove');
    Route::post('lesson-plan-add', 'store')->name('lesson.plan.store');
    Route::post('lesson-plan-edit', 'edit')->name('lesson.plan.update');

    Route::post('instruction-module.verify-admin', 'VerifyadminPassword')->name('instruction-module.verify-admin');

    Route::get('sort-instruction-module', 'sortLessonPlan')->name('lesson.plan.sorting');
    Route::post('sort-update-lesson-module', 'updateSortingNumber')->name('lesson.plan.sorting.update');
    Route::post('lesson-plan-demo-status', 'change_demo_status')->name('lesson.demo.status');
    Route::post('lesson-status', 'change_status')->name('lesson.status');
});

Route::middleware(['auth', 'isAdmin'])->prefix('healty-mind')->controller(HealtyMindController::class)->group(function () {
    Route::get('healty-mind-list', 'indexHealtyMind')->name('healty-mind-list');
    Route::get('healty-mind-add', 'addHealtyMind')->name('healty-mind-add');
    Route::post('healty-mind-store', 'store')->name('healty-mind-store');
    Route::get('edit-healty-mind', 'editHealtyMind')->name('edit-healty-mind');
    Route::post('healty-mind-update', 'updateHealtyMind')->name('healty-mind-update');
    Route::post('healty-mind-verify-admin', 'HMVerifyadminPassword')->name('healty-mind-verify-admin');
    Route::post('healty-mind-remove', 'destroy')->name('healty-mind-remove');
    Route::post('healty-mind-status', 'change_status')->name('healty-mind-status');
});
Route::middleware(['auth', 'isAdmin'])->prefix('ncf-assessment')->controller(NcfAssessmentController::class)->group(function () {
    Route::get('ncf-assessment-list', 'indexAssessment')->name('ncf-assessment-list');
    Route::get('ncf-assessment-add', 'addNcfAssessment')->name('ncf-assessment-add');
    Route::post('ncf-assessment-store', 'store')->name('ncf-assessment-store');
    Route::get('edit-ncf-assessment', 'editNcfAssessment')->name('edit-ncf-assessment');
    Route::post('ncf-assessment-update', 'updateNcfAssessment')->name('ncf-assessment-update');
    Route::post('ncf-assessment-verify-admin', 'ncfAssessmentVerify')->name('ncf-assessment-verify-admin');
    Route::post('ncf-assessment-remove', 'destroy')->name('ncf-assessment-remove');
    Route::post('ncf-assessment-status', 'changeStatus')->name('ncf-assessment-status');

    Route::get('assessment-list', 'getNcfSorting')->name('assessment-list');
    Route::post('ncf-sorting-update', 'updateNcfSortingNumber')->name('ncf.sorting.update');
});
Route::middleware(['auth', 'isAdmin'])->prefix('quiz')->controller(QuizController::class)->group(function () {
    Route::get('quiz-list', 'indexQuiz')->name('quiz-list');
    Route::get('quiz-add', 'addQuiz')->name('quiz-add');
    Route::post('quiz-store', 'store')->name('quiz-store');
    Route::post('quiz-status', 'changeStatus')->name('quiz-status');
    Route::post('quiz-verify', 'quizVerify')->name('quiz-verify');
    Route::post('quiz-remove', 'destroy')->name('quiz-remove');
    Route::get('quiz-edit', 'editQuiz')->name('quiz-edit');
    Route::get('quiz-details', 'detailsQuiz')->name('quiz-details');
    Route::post('quiz-update', 'updateQuiz')->name('quiz-update');
});
Route::middleware(['auth', 'isAdmin'])->prefix('avatar')->controller(AvatarController::class)->group(function () {
    Route::get('avatar-list', 'indexAvatar')->name('avatar-list');
    Route::get('avatar-add', 'addAvatar')->name('avatar-add');
    Route::post('avatar-store', 'store')->name('avatar-store');
    Route::post('avatar-verify', 'AvatarVerify')->name('avatar-verify');
    Route::post('avatar-remove', 'destroyAvatar')->name('avatar-remove');
    // Route::post('quiz-status', 'changeStatus')->name('quiz-status');
    //  Route::post('quiz-verify', 'quizVerify')->name('quiz-verify');
    //  Route::post('quiz-remove', 'destroy')->name('quiz-remove');
    //   Route::get('quiz-edit', 'editQuiz')->name('quiz-edit');
    //   Route::get('quiz-details', 'detailsQuiz')->name('quiz-details');
    //   Route::post('quiz-update', 'updateQuiz')->name('quiz-update');

});
Route::middleware(['auth', 'isAdmin'])->prefix('quiz-category')->controller(QuizController::class)->group(function () {
    Route::get('quiz-category-list', 'indexCategoryList')->name('quiz-category-list');
    Route::get('quiz-category-add', 'addQuizCategory')->name('quiz-category-add');
    Route::post('quiz-category-store', 'storeQuizCategory')->name('quiz-category-store');
    Route::get('quiz-category-edit', 'editQuizCategory')->name('quiz-category-edit');
    Route::post('quiz-category-update', 'updateQuizCategory')->name('quiz-category-update');
    Route::post('quiz-category-verify', 'quizCategoryVerify')->name('quiz-category-verify');
    Route::post('quiz-category-remove', 'destroyQuizCategory')->name('quiz-category-remove');
});
Route::middleware(['auth', 'isAdmin'])->prefix('quiz-title')->controller(QuizController::class)->group(function () {
    Route::get('list', 'quizTitleList')->name('quiz-title-list');
    Route::get('quiz-title-add', 'addQuizTitle')->name('quiz-title-add');
    Route::post('quiz-title-store', 'storeQuizTitle')->name('quiz-title-store');
    Route::get('quiz-title-edit', 'editQuizTitle')->name('quiz-title-edit');
    Route::post('quiz-title-update', 'updateQuizTitle')->name('quiz-title-update');
    Route::post('quiz-title-status', 'changeStatusQuizTitle')->name('quiz-title-status');
    Route::post('quiz-title-verify', 'quizTitleVerify')->name('quiz-title-verify');
    Route::post('quiz-title-remove', 'destroyQuizTitle')->name('quiz-title-remove');
});

Route::middleware(['auth', 'isTeacher'])->prefix('ncf-assessment')->controller(NcfAssessmentController::class)->group(function () {
    Route::get('ncf-assessments-list', 'teacherNcfAssessments')->name('ncf-assessments-list');
});

Route::middleware(['auth', 'isAdmin'])->prefix('download')->controller(HealtyMindController::class)->group(function () {
    Route::get('download-list', 'indexDownload')->name('download-list');
    Route::get('download-add', 'addDownload')->name('download-add');
    Route::post('download-store', 'addstoreDownload')->name('download-store');
    Route::get('edit-download', 'editdownload')->name('edit-download');
    Route::post('download-update', 'updateDownload')->name('download-update');
    Route::post('download-verify-admin', 'downloadadminPassword')->name('download-verify-admin');
    Route::post('download-remove', 'downloadDestroy')->name('download-remove');
    Route::post('download-status', 'changeDownloadstatus')->name('download-status');
});
Route::middleware('auth')->prefix('download')->controller(HealtyMindController::class)->group(function () {
    Route::get('download-lists', 'schoolAdminDownload')->name('download-lists');
});

Route::middleware(['auth', 'isTeacher'])->prefix('healty-mind')->controller(HealtyMindController::class)->group(function () {
    Route::get('healty-minds-list', 'teacherHealtyMind')->name('healty-minds-list');
});



Route::middleware(['auth', 'isAdmin'])->prefix('grade')->controller(ProgramController::class)->group(function () {
    Route::get('manage-grade', 'index')->name('program.list');
    Route::get('add-grade', 'addprogram')->name('program.add');
    Route::get('update-grade', 'editprogram')->name('program.edit');
    Route::post('remove-grade', 'destroy')->name('program.remove');
    Route::post('grade-add', 'store')->name('program.store');
    Route::post('grade-edit', 'edit')->name('program.update');
    Route::post('grade-status', 'change_status')->name('grade.status');

    Route::post('grade.verify-admin', 'VerifyadminPassword')->name('grade.verify-admin');
});

Route::middleware('auth')->prefix('school')->controller(AuthController::class)->group(function () {
    Route::get('manage-teacher', 'userlist')->name('teacher.list');
    Route::get('add-teacher', 'addUser')->name('teacher.add');
    Route::get('update-teacher', 'updateUser')->name('teacher.edit');
    Route::post('teacher-remove', 'destroy')->name('teacher.remove');
    Route::post('teacher-add', 'createuser')->name('teacher.store');
    Route::post('create-teacher', 'createTeacher')->name('create-teacher');

    Route::post('teacher-edit', 'edituser')->name('teacher.update');
    Route::post('teacher-update', 'editTeacher')->name('teacher-update');
    Route::get('teacher-list', 'teacherList')->name('school.teacher.list');
    Route::get('ip-address-history', 'ipaddressList')->name('ip-address-history');
    Route::post('reset-password', 'resetPassword')->name('user.password');
    Route::get('manage-school-admin', 'SchoolAdmin')->name('school.admin');
    Route::get('add-school-admin/{schoolid}', 'addAdminUser')->name('school.admin.add');
    Route::get('update-school-admin/{userid}', 'updateAdminUser')->name('school.admin.edit');
    Route::post('admint.status', 'change_admin_status')->name('admint.status');

    Route::post('teacher.verify-admin', 'VerifyadminPassword')->name('teacher.verify-admin');

    Route::post('verify-school-admin', 'VerifyschooladminPassword')->name('verify-school-admin');
    Route::post('school-admin-remove', 'destroySchoolAdmin')->name('school-admin-remove');

    Route::get('school-admin-forgot-edit', 'getAdminUser')->name('school-admin-forgot-edit');
    Route::post('school-forgot-password-update', 'UpdateSchoolAdmin')->name('school-forgot-password-update');

    Route::post('forgot-pass-verify-admin', 'VerifySAFPPassword')->name('forgot-pass-verify-admin');
    Route::post('forgot-pass-sa-remove', 'destroyFPSchoolAdmin')->name('forgot-pass-sa-remove');
});

Route::middleware(['auth'])->prefix('student')->controller(SchoolController::class)->group(function () {
    Route::get('student_payment_list', 'StudentPaymentListSchoolAdmin')->name('student_payment_list');
});

Route::middleware('auth')->prefix('support')->controller(AuthController::class)->group(function () {
    Route::get('support', 'supporthistory')->name('support');
    Route::get('support-list', 'getSupportlist')->name('support-list');
    Route::post('contact-support', 'contactSupport')->name('contact-support');
    Route::get('support-reply-add', 'supportReply')->name('support-reply-add');
    Route::post('support-reply', 'supportCreateReply')->name('support-reply');
    Route::get('support-count-noty', 'supportNotify')->name('support-count-noty');
    Route::post('verify-admin-suport', 'VerifySupport')->name('verify-admin-suport');
    Route::post('support-remove', 'destroySupport')->name('support-remove');
    Route::post('support-status', 'supportchangestatus')->name('support-status');
});
Route::middleware('auth')->prefix('subscription')->controller(AuthController::class)->group(function () {
    Route::get('by-subscription', 'subscriptionCreate')->name('by-subscription');
    Route::get('subscription.edit', 'subscriptionEdit')->name('subscription.edit');
    Route::get('create-classroom', 'classroomEdit')->name('create-classroom');
    Route::post('createclassroom', 'classroomCreate')->name('createclassroom');
    Route::post('subscription-create', 'createSubscription')->name('subscription.store');
    Route::post('subscription-update', 'updateSubscription')->name('subscription-update');
    Route::post('school-licence-update', 'schoolLicenceUpdate')->name('school-licence-update');
    Route::get('subscription-list', 'getSubscription')->name('subscription-list');
    Route::get('subscription-request-list', 'getSubscriptionRequest')->name('subscription-request-list');
    Route::get('subscription-manage-list', 'getSubscriptionManage')->name('subscription-manage-list');

    Route::get('subscription-request-payment', 'subscriptionRequestPayment')->name('subscription-request-payment');
    Route::post('subscription-request-payment', 'SubscriptionPaymentPay')->name('subscription-request-payment-store');
    Route::post('sr-payment-list', 'srPaymentfailed')->name('sr-payment-list');

    Route::post('subscription-request-status-update', 'changeSubscriptionRequestStatus')->name('subscription-request-status-update');
    Route::post('sr-sent-email-payment-link', 'SentEmailPaymentLinkSR')->name('sr-sent-email-payment-link');
    Route::post('sr-payment-removie', 'SRPaymentRemove')->name('sr-payment-removie');
    Route::post('sr-upload-invoice', 'UploadInvoiceSR')->name('sr-upload-invoice');
    Route::post('sr-verify-admin', 'VerifySRPasswordPackage')->name('sr-verify-admin');
    Route::post('sr-remove', 'SRdestroyPackage')->name('sr-remove');
    // Route::post('contact-support', 'contactSupport')->name('contact-support');
});
Route::middleware('auth')->prefix('feedback')->controller(FeedBackController::class)->group(function () {
    Route::get('feedback', 'getfeedback')->name('teacher.feedback');
    Route::post('feedback-teacher', 'feedbackTeacher')->name('feedback-teacher');
    Route::get('feedback-teacher', 'feedbackGet')->name('feedback.teacher');
    Route::post('verify-admin-feedback', 'VerifyadminPassword')->name('verify-admin-feedback');
    Route::post('remove-feedback', 'destroy')->name('feedback.remove');
    Route::get('feedback-reply-add', 'feedbackReply')->name('feedback-reply-add');
    Route::post('feedback-reply', 'feedbackReplyCreate')->name('feedback-reply');
    Route::get('feedback_reply_notify', 'feedbackReplyNotify')->name('feedback_reply_notify');
});
Route::middleware('auth')->prefix('testing')->controller(FeedBackController::class)->group(function () {
    Route::get('testing', 'getTesting')->name('testing');
    Route::post('verify-admin-testing', 'VerifyadminTestingPassword')->name('verify-admin-testing');
    Route::post('testing-remove', 'destroyTesting')->name('testing.remove');
    Route::get('test-add', 'addTesting')->name('test.add');
    Route::post('test-otp-add', 'otpTesting')->name('test-otp-add');
    Route::post('test-email-add', 'emailTesting')->name('test-email-add');
    Route::post('test-payment-add', 'paymentTesting')->name('test-payment-add');
    Route::post('test-chat-gpt-add', 'chatGptTesting')->name('test-chat-gpt-add');
    Route::post('test-dally-add', 'dallyTesting')->name('test-dally-add');
});
Route::middleware(['auth', 'isAdmin'])->prefix('whats-new')->controller(Notification::class)->group(function () {
    Route::get('manage-notification', 'index')->name('notify.list');
    Route::get('notification-detail', 'detailNotification')->name('notify.detail');
    Route::get('add-notification', 'addnewNotification')->name('notify.add');
    Route::post('add-update-notify', 'addUpdateNotify')->name('notify.store');
    Route::post('remove-notify', 'destroy')->name('notify.remove');
    Route::get('school-view-whats-new', 'viewNotify')->name('notify.schoolview')->withoutMiddleware('isAdmin');
    Route::get('teacher-view-whats-new', 'viewteacherNotify')->name('notify.teacherview')->withoutMiddleware('isAdmin');
    Route::get('student-view-whats-new', 'viewstudentNotify')->name('notify.studentview')->withoutMiddleware('isAdmin');

    Route::post('notify.verify-admin', 'VerifyadminPassword')->name('notify.verify-admin');
});


Route::middleware(['auth'])->prefix('reminder')->controller(ReminderController::class)->group(function () {
    Route::get('list', 'index')->name('reminder.list');
    Route::get('lists', 'indexLists')->name('reminder.lists');
    Route::get('reminder-detail', 'reminderDetail')->name('reminder.detail');
    Route::get('add-reminder', 'addReminder')->name('reminder.add');
    Route::post('create', 'reminderCreate')->name('reminder.store');
    Route::get('edit', 'EditReminder')->name('reminder.edit');
    Route::post('update', 'updateReminder')->name('reminder.update');
    Route::post('reminder-verify-admin', 'VerifyReminder')->name('reminder-verify-admin');
    Route::post('remove-reminder', 'destroyReminder')->name('reminder.remove');

    //Route::get('reminder-detail', 'reminderDetail')->name('reminder.detail');
});


Route::middleware(['auth'])->prefix('invoice')->controller(InvoiceController::class)->group(function () {
    Route::get('list', 'indexInvoice')->name('invoice.list');
   // Route::get('lists', 'indexLists')->name('reminder.lists');
  //  Route::get('reminder-detail', 'reminderDetail')->name('reminder.detail');
    Route::get('add-invoice', 'addInvoice')->name('invoice.add');
    Route::post('create', 'invoiceCreate')->name('invoice.store');
    Route::get('edit', 'EditInvoice')->name('invoice.edit');
    Route::post('update', 'updateInvoice')->name('invoice.update');
    Route::post('invoice-verify-admin', 'VerifyInvoice')->name('invoice-verify-admin');
    Route::post('remove-invoice', 'destroyInvoice')->name('invoice.remove');
});


Route::middleware(['auth'])->prefix('terms-privacy')->controller(Notification::class)->group(function () {
    Route::get('list', 'termsIndex')->name('terms-privacy.list');
    Route::get('create', 'addTermsPrivacy')->name('terms-privacy.add');
    Route::post('create', 'createTermsPrivacy')->name('terms-privacy.store');
    Route::get('edit', 'EditTermsPrivacy')->name('terms-privacy.edit');
    Route::post('update', 'updateTermsPrivacy')->name('terms-privacy.update');
    Route::post('terms.verify-admin', 'VerifyTermsPrivacy')->name('terms.verify-admin');
    Route::post('remove-terms', 'destroyTermsPrivacy')->name('terms.remove');
});
Route::get('terms-conditions', [Notification::class, 'getTermsConditions'])->name('terms-conditions');
Route::get('privacy-policy', [Notification::class, 'getPrivacyPolicy'])->name('privacy-policy');


Route::middleware(['auth'])->prefix('whats-new')->controller(Notification::class)->group(function () {
    Route::get('notification-detail', 'detailNotification')->name('notify.detail');
});
Route::middleware(['auth', 'isAdmin'])->prefix('users')->controller(UserController::class)->group(function () {
    Route::get('manage-users', 'index')->name('users.admin.list');
    Route::get('add-master-user', 'AddAdminUser')->name('users.admin.add');
    Route::get('update-master-user', 'editAdminUser')->name('users.admin.edit');
    Route::post('store-master-user', 'AddUpdateAdminUser')->name('users.admin.store');
    Route::post('users_type_contentadmin.remove', 'destroy')->name('users_type_contentadmin.remove');
    Route::post('users_type_contentadmin.verify-admin', 'VerifyadminPassword')->name('users_type_contentadmin.verify-admin');
});

Route::middleware(['auth'])->prefix('users')->controller(UserController::class)->group(function () {
    Route::post('logout-user-all-device', 'logoutUserOtherDevices')->name('logout.user.device');
    Route::post('logout-student-all-device', 'logoutStudentOtherDevices')->name('logout.student.device');
});
/** ============= SCHOOL ROUTES ============= */

/** ============= TEACHER ROUTES ============= */
Route::middleware(['auth', 'isTeacher'])->prefix('teacher')->group(function () {
    Route::get('class-list', [ProgramController::class, 'TeacherClasslist'])->name('teacher.class.list');
    Route::get('course-list/{class}', [WebPage::class, 'courselist'])->name('teacher.course.list');
    Route::get('lesson-plan-list/{classid}/{course}', [WebPage::class, 'lessonPlan'])->name('teacher.lesson.list');
    Route::get('teacher-instruction-module', [WebPage::class, 'TeacherLoginInstructionModule'])->name('teacher-instruction-module');
    Route::post('save-plan-report', [WebPage::class, 'setUserReport'])->name('report.save.plan');

    Route::get('teacher-search.lesson.list', [WebPage::class, 'SearchlessonPlan'])->name('teacher-search.lesson.list');
    Route::post('video-play-report-store', [ReportController::class, 'storePlay'])->name('video-play-report-store');
});

Route::middleware(['auth', 'isStudent'])->prefix('student')->group(function () {
    Route::get('grade-list', [ProgramController::class, 'StudentGradelist'])->name('student.grade.list');
    //Route::get('student-details', [StudentController::class, 'StudentDetails'])->name('student-details');
    Route::get('student-course-list/{class}', [WebPage::class, 'studentcourselist'])->name('student.course.list');
    Route::get('student-lesson-plan-list/{classid}/{course}', [WebPage::class, 'StudentlessonPlan'])->name('student.lesson.list');
    Route::post('student-save-plan-report', [WebPage::class, 'StudentsetUserReport'])->name('student.report.save.plan');
    Route::post('student-login-reset-password', [StudentController::class, 'StudentResetPassword'])->name('student-login-reset-password');
    Route::post('student-upload-image', [StudentController::class, 'UploadStudentImage'])->name('student-upload-image');
    Route::post('avatar-image-changes', [StudentController::class, 'UploadAvatar'])->name('avatar-image-changes');
});

Route::middleware(['auth', 'isStudent'])->prefix('my-profile')->group(function () {
    Route::get('student-details', [StudentController::class, 'StudentDetails'])->name('student-details');
    Route::get('student-login-edit', [StudentController::class, 'studentLoginEdit'])->name('student-login-edit');
    Route::post('student-detail-login-otp', [StudentController::class, 'studentDetailLoginOtp'])->name('student-detail-login-otp');
    Route::post('verify-st-login-detail-otp', [StudentController::class, 'studentLoginUpdateVerifyOtp'])->name('verify-st-login-detail-otp');
    Route::post('student-update', [StudentController::class, 'studentLoginUpdate'])->name('student-update');
    Route::get('student-pdf-download', [StudentController::class, 'generateStudentPDF'])->name('student-pdf-download');
});
Route::middleware(['auth', 'isStudent'])->prefix('billing')->group(function () {
    Route::get('student-billing', [StudentController::class, 'studentBilling'])->name('student-billing');
    //  Route::get('student-pdf-download',[StudentController::class, 'generateStudentPDF'])->name('student-pdf-download');

});

Route::middleware(['auth'])->prefix('reports')->controller(ReportController::class)->group(function () {
    Route::get('view-engagement', 'index')->name('report.school.view');
    Route::get('view-history', 'viewTeacherSummary')->name('teacher.class.history');
    Route::get('student-view-history', 'viewStudentSummary')->name('student-view-history');
    Route::post('view-grade-wise-history', 'viewTeacherGradeSummary')->name('teacher.grade.course.history');
    Route::post('student-view-grade-wise-history', 'viewStudentGradeSummary')->name('student.grade.course.history');
    Route::get('module-access-report-list', 'moduleAccessReport')->name('module-access-report-list');
});

Route::middleware(['auth'])->controller(ChatGptController::class)->group(function () {
    Route::get('ai-modules', 'chatgptView')->name('chat-gpt');
    Route::get('aimodules', 'chatgptModuleView')->name('chat-gpt-modules');
    Route::post('chat-gpt-create', 'chatgptCreate')->name('chat-gpt-create');
    Route::post('search-chatgpt', 'chatgptSearch')->name('search-chatgpt');
    Route::post('search-dalle', 'dalleSearch')->name('search-dalle');
    Route::post('generate-speech', 'generateSpeech')->name('search-text-to-speech');
    Route::post('own-prompts', 'ownpromptsSpeech')->name('search-own-prompts');

    Route::post('generate-music', 'generateMusic')->name('search-text-to-music');
    Route::post('output-music', 'generateMusicFile')->name('output-music-to-music');



    Route::post('generate-text', 'generateText')->name('search-speech-to-text');
    Route::post('speech-to-text', 'generateSpeechText')->name('speech-to-text-own-prompts');
    Route::post('second-own-prompts-speech-text', 'secondSpeechText')->name('search-own-prompts-speech-text');

    Route::post('generate-avatar', 'generateAvatar')->name('generate-avatar');
    Route::post('output-avatar', 'OutputAvatar')->name('output-avatar');

    Route::post('generate-photo-video', 'generatePhototVideo')->name('generate-photo-video');
    Route::post('output-photo-video', 'OutputPhototVideo')->name('output-photo-video');

    Route::post('prompts-generate-photo-video', 'generatePromptPhotoVideo')->name('prompts-generate-photo-video');
    // Route::post('output-photo-video', 'OutputPhototVideo')->name('output-photo-video');

    Route::post('bird-classifier', 'generateBirdClassifier')->name('bird-classifier');
    Route::post('bird-classifier-second', 'generateBirdClassifierSecond')->name('bird-classifier-second');
    Route::post('plant-recognizer', 'generatePlantRecognizer')->name('plant-recognizer');
    Route::post('prompts-plant-recognizer', 'generatePromptPlantRecognizer')->name('prompts-plant-recognizer');

    Route::post('image_narration', 'analyzeImage')->name('image_narration');


   /// Route::get('ChatGptTest', [ChatGptController::class, 'analyzeImage']);
});

Route::middleware(['auth', 'isAdmin'])->prefix('master/aimodules')->controller(ChatGptController::class)->group(function () {
    Route::get('list', 'getAimodules')->name('aimodules.list');
    Route::get('create', 'createAimodules')->name('create.add');
    Route::post('add-module', 'AIModulesCreate')->name('add-module');
    Route::get('feedback', 'feedbackPage')->name('feedback');
    Route::get('edit', 'editaimodule')->name('aimodule.edit');
    Route::post('update-aimodule', 'aiModulesUpdate')->name('update-aimodule');
    Route::post('aimodule.verify-admin', 'VerifyadminPassword')->name('aimodule.verify-admin');
    Route::post('aimodule.remove', 'destroy')->name('aimodule.remove');
    Route::post('aimodules-status', 'change_aimodules_status')->name('change.aimodules.status');

    Route::get('ai-create', 'createModule')->name('identifier.aicreate');
    Route::post('create', 'AIModulesSave')->name('create-aimodule');
    Route::get('ai-list', 'AimoduleList')->name('list-aimodule');
    Route::get('ai-update', 'editModule')->name('aimodule-update');
    Route::post('ai-update', 'UpdateModule')->name('module-update');
    //  Route::post('course-add', 'store')->name('course.store');
    //Route::post('course-edit', 'edit')->name('course.update');
    //   Route::post('course-status', 'change_status')->name('course.status');

    //  Route::post('course.verify-admin', 'VerifyadminPassword')->name('course.verify-admin');
});

Route::middleware(['auth', 'isAdmin'])->prefix('identifier')->controller(IdentifierConroller::class)->group(function () {
    Route::get('list', 'getIdentifier')->name('identifier.list');
    Route::get('create', 'CreateIdentifier')->name('create.identifier');
    Route::post('create', 'IdentifierCreate')->name('create-add');
    Route::get('update', 'editIdentifier')->name('identifier.edit');
    Route::post('update', 'IdentifierUpdate')->name('update-identifier');
    Route::post('identifier-remove', 'destroy')->name('identifier.remove');
});

Route::middleware(['auth'])->controller(ChatGptController::class)->group(function () {
    Route::get('ai/chatgpt', 'staticChatgpt')->name('static-chatgpt');
    Route::get('ai/dalle', 'staticDalle')->name('static-dalle');
});
/* Route::middleware(['auth'])->prefix('chatgpt')->controller(ChatGptController::class)->group(function () {
    Route::get('chat-gpt', 'chatgptView')->name('chat-gpt');
    Route::get('chat-gpt-modules', 'chatgptModuleView')->name('chat-gpt-modules');
    Route::post('chat-gpt-create', 'chatgptCreate')->name('chat-gpt-create');
    Route::post('search-chatgpt', 'chatgptSearch')->name('search-chatgpt');
}); */

/** ============= COMMON ROUTES ============= */
Route::middleware(['auth'])->prefix('school')->controller(SchoolController::class)->group(function () {
    Route::post('teacher-status', 'change_user_status')->name('teacher.status');
    Route::get('users-logs-list/{userid}', 'viewLogs')->name('user.logs.list');
});

Route::middleware('auth')->get('ajax-notify-list', [Notification::class, 'viewNotifyList'])->name('ajax.notify.list');
Route::middleware('auth')->get('icon-notify-list', [Notification::class, 'viewNotifyIcon'])->name('ajax.notify.icon');
Route::middleware('auth')->get('icon-reminder-list', [ReminderController::class, 'viewReminderNotifyIcon'])->name('ajax.reminder.icon');
Route::middleware('auth')->get('subscription-request-notify', [AuthController::class, 'subscriptionrequestNotify'])->name('subscription-request-notify');
Route::middleware('auth')->get('show-student-subcrition', [Notification::class, 'viewNotifyStudentSubcription'])->name('show-student-subcrition');

Route::middleware(['auth'])->get('/access-denied', function () {
    return view('auth.deny');
})->name('access.denied');

Route::get('get-demo', [WebPage::class, 'getDemo'])->name('get.demo.page');
Route::get('send-mail', function () {
    $details = [
        'view' => 'emails.test',
        'subject' => 'Test Mail form LMS',
        'title' => 'Mail from Valuez School',
        'body' => 'This is for testing email using smtp'
    ];
    \Mail::to('itrahul.com@gmail.com')->send(new \App\Mail\TestMail($details));
    dd("Email is Sent.");
});


Route::middleware(['auth'])->get('/play-quiz', function () {
    return view('quiz');
})->name('play.quiz');

Route::get('quiz-list', [QuizController::class, 'getQuiz']);
Route::get('quiz-answer-list', [QuizController::class, 'getAnswerList']);
Route::post('quiz-insert', [QuizController::class, 'storeResult']);
Route::middleware(['auth'])->prefix('play-quiz')->group(function () {
    Route::get('quiz-category', [QuizController::class, 'classroomLoginQuizCategory'])->name('classroom.quiz-category');
    Route::get('quiz-thumbnail', [QuizController::class, 'classroomLoginQuizTitle'])->name('classroom.quiz-thumbnail');
});  
Route::middleware(['auth', 'isStudent'])->prefix('quiz')->group(function () {
    Route::get('student-quiz', [QuizController::class, 'quizStudent'])->name('student-quiz');
    Route::get('student-quiz-category', [QuizController::class, 'studentLoginQuizCategory'])->name('student.quiz.category');
    Route::get('student-quiz-list', [QuizController::class, 'studentLoginQuizTitle'])->name('student.quiz.list');
    Route::get('student-quiz-question-list', [QuizController::class, 'studentQuizQuestion'])->name('student.quiz.question.list');
    Route::get('student-quiz-report', [QuizController::class, 'studentQuizReport'])->name('student.quiz.report');
});
