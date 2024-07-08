<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Quiz, QuizReport};


class MasterApi extends Controller
{
    public function quizlist(Request $request)
    {
        $quizTbl = Quiz::leftJoin('quiz_titles', 'quiz_titles.id', 'quizlist.quiz_title_id')
            ->leftJoin('quiz_categories', 'quiz_titles.quiz_category_id', 'quiz_categories.id')
            ->where('quizlist.status', 1)
            ->selectRaw('quizlist.*,quiz_categories.title as quiz_category_title,quiz_titles.quiz_title,quiz_titles.quiz_title_grade as quiz_grade')->get();

        $quizTbl = collect($quizTbl)->map(function ($item) {
            $mca_opt = array_filter(json_decode($item->question_mcq_opt));
            $item['question_mcq_opt'] = !empty($mca_opt) ? json_encode($mca_opt) : [];
            $item['question_url'] = !empty($item->question_url) ? \App\Http\Controllers\WebPage::getVideoUrl($item->question_url) : '';
            return $item;
        })->all();
        return response()->json(['status' => true, 'recordList' => $quizTbl]);
    }

    public function QuizScore(Request $request)
    {
        $storeData = [
            'user_id' => $request->uid,
            'quiz_title_id' => $request->quiz_title_id,
            'total_attempt' => $request->total_attempt,
            'wrng_attempt' => $request->wrng_attempt,
            'right_attempt' => $request->right_attempt,
            'start_time' =>  $request->start_time,
            'quiz_summary' => json_encode($request->quiz_summary),
            'status' => '1',
        ];
        // dd($storeData);
        QuizReport::insert($storeData);
    }

    public function QuizScoreView(Request $request)
    {
        $quiz_report = QuizReport::with('getuser', 'getquiz')->get();
        dd($quiz_report);
    }
}
