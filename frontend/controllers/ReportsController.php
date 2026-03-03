<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\LessonTestAttempt;
use frontend\models\LessonContent;
use frontend\models\Student;
use frontend\models\Subject;
use yii\data\Pagination;

class ReportsController extends Controller
{
    public function actionProgressReport()
    {
        ini_set('memory_limit', '2048M');

        $currentUserId       = Yii::$app->user->id;
        $students            = Student::find()->where(['parent_id' => $currentUserId])->all();
        $subjects            = Subject::find()->where(['status' => 1])->all();

        $selectedStudent     = Yii::$app->request->get('student_id');
        $selectedSubject     = Yii::$app->request->get('subject_id');
        $selectedPerformance = Yii::$app->request->get('performance');
        $selectedDateRange   = Yii::$app->request->get('date');

        // // --- English alias ids (Language Arts + English)
        // $englishIds = [2, 5];

        // // Date range
        // $startDate = $endDate = null;
        // if ($selectedDateRange) {
        //     if (strpos($selectedDateRange, ' to ') !== false) {
        //         [$s, $e] = explode(' to ', $selectedDateRange);
        //         $startDate = date('Y-m-d 00:00:00', strtotime($s));
        //         $endDate   = date('Y-m-d 23:59:59', strtotime($e));
        //     } else {
        //         $startDate = date('Y-m-d 00:00:00', strtotime($selectedDateRange));
        //         $endDate   = date('Y-m-d 23:59:59', strtotime($selectedDateRange));
        //     }
        // }

        
        // --- English alias ids (Language Arts + English)
        // $englishIds = [2, 5];

        // // Date range
        // $startDate = $endDate = null;
        // $selectedDateRange = Yii::$app->request->get('date');

        // // ✅ Default to current month when no date is selected
        // if (!$selectedDateRange || trim($selectedDateRange) === '') {
        //     $firstDay = date('Y-m-01');           // e.g. 2025-09-01
        //     $lastDay  = date('Y-m-t');            // e.g. 2025-09-30
        //     $startDate = $firstDay . ' 00:00:00';
        //     $endDate   = $lastDay  . ' 23:59:59';
        //     // pre-fill input so user ko UI me bhi current month dikhe
        //     $selectedDateRange = $firstDay . ' to ' . $lastDay;
        // } else {
        //     if (strpos($selectedDateRange, ' to ') !== false) {
        //         [$s, $e] = explode(' to ', $selectedDateRange);
        //         $startDate = date('Y-m-d 00:00:00', strtotime($s));
        //         $endDate   = date('Y-m-d 23:59:59', strtotime($e));
        //     } else {
        //         $startDate = date('Y-m-d 00:00:00', strtotime($selectedDateRange));
        //         $endDate   = date('Y-m-d 23:59:59', strtotime($selectedDateRange));
        //     }
        // }


        // --- English alias ids (Language Arts + English)
        $englishIds = [2, 5];

         
         // --- Date / Quick handling (Toronto-aware) ------------------------------
            $selectedDateRange = Yii::$app->request->get('date');
            $isFirstVisit      = empty(Yii::$app->request->queryParams);
            $quick             = Yii::$app->request->get('quick'); // 'all' | 'day' | 'week' | 'month' | null

            // Display timezone = your reporting TZ (formatter->timeZone)
            $displayTzId = env('APP_TIMEZONE', 'America/Toronto');
            $displayTz   = new \DateTimeZone($displayTzId);
            $utcTz       = new \DateTimeZone('UTC');

            $startTs = $endTs = null;

            if ($quick) {
                switch ($quick) {
                    case 'all':
                        $selectedDateRange = '';
                        // no date filter
                        break;

                    case 'day': {
                        $start = new \DateTime('today 00:00:00', $displayTz);
                        $end   = new \DateTime('today 23:59:59', $displayTz);
                        $selectedDateRange = $start->format('Y-m-d') . ' to ' . $end->format('Y-m-d');
                        $startTs = (clone $start)->setTimezone($utcTz)->getTimestamp();
                        $endTs   = (clone $end)->setTimezone($utcTz)->getTimestamp();
                        break;
                    }

                    case 'week': {
                        $start = new \DateTime('monday this week 00:00:00', $displayTz);
                        $end   = new \DateTime('sunday this week 23:59:59',  $displayTz);
                        $selectedDateRange = $start->format('Y-m-d') . ' to ' . $end->format('Y-m-d');
                        $startTs = (clone $start)->setTimezone($utcTz)->getTimestamp();
                        $endTs   = (clone $end)->setTimezone($utcTz)->getTimestamp();
                        break;
                    }

                    case 'month': {
                        $start = new \DateTime('first day of this month 00:00:00', $displayTz);
                        $end   = new \DateTime('last day of this month 23:59:59',  $displayTz);
                        $selectedDateRange = $start->format('Y-m-d') . ' to ' . $end->format('Y-m-d');
                        $startTs = (clone $start)->setTimezone($utcTz)->getTimestamp();
                        $endTs   = (clone $end)->setTimezone($utcTz)->getTimestamp();
                        break;
                    }
                }
            }

            // Agar abhi tak quick ne set nahi kia to manual date ya first-visit default
            if (!$startTs && !$endTs) {
                if ($selectedDateRange && trim($selectedDateRange) !== '') {
                    if (strpos($selectedDateRange, ' to ') !== false) {
                        [$s, $e] = explode(' to ', $selectedDateRange);
                    } else {
                        $s = $e = $selectedDateRange;
                    }

                    // Manual range ko displayTz me interpret kar ke UTC epoch bana do
                    $start = new \DateTime($s . ' 00:00:00', $displayTz);
                    $end   = new \DateTime($e . ' 23:59:59', $displayTz);
                    $startTs = $start->setTimezone($utcTz)->getTimestamp();
                    $endTs   = $end->setTimezone($utcTz)->getTimestamp();

                } elseif ($isFirstVisit) {
                    // First visit: current month (display TZ)
                    $start = new \DateTime('first day of this month 00:00:00', $displayTz);
                    $end   = new \DateTime('last day of this month 23:59:59',  $displayTz);
                    $selectedDateRange = $start->format('Y-m-d') . ' to ' . $end->format('Y-m-d');
                    $startTs = (clone $start)->setTimezone($utcTz)->getTimestamp();
                    $endTs   = (clone $end)->setTimezone($utcTz)->getTimestamp();
                }
            }
            // ------------------------------------------------------------------------





        // Base query
        $query = LessonTestAttempt::find()->alias('lta')
            ->joinWith(['lesson l', 'lesson.subject s', 'lesson.chapter c'])
            ->orderBy(['lta.created_at' => SORT_DESC]);

        //  Derived subject (chapter > lesson > attempt)
        $effectiveSubjectExpr = new \yii\db\Expression('COALESCE(c.subject_id, l.subject_id, lta.subject_id)');
         $query->select(['lta.*']); // keep base
        $query->addSelect(['effective_subject_id' => $effectiveSubjectExpr]); // add derived



        if ($selectedStudent) {
            $query->andWhere(['lta.student_id' => $selectedStudent]);
        } else {
            $studentIds = Student::find()->select('id')->where(['parent_id' => $currentUserId]);
            $query->andWhere(['lta.student_id' => $studentIds]);
        }

       if ($selectedSubject) {
            $wantIds = in_array((int)$selectedSubject, $englishIds, true) ? $englishIds : [(int)$selectedSubject];
            // filter on derived subject (no ORs)
            $query->andWhere(['IN', $effectiveSubjectExpr, $wantIds]);
        }



        // if ($startDate && $endDate) {
        //     $query->andWhere(['between', 'FROM_UNIXTIME(lta.created_at)', $startDate, $endDate]);
        // }

         
        if ($startTs && $endTs) {
            $query->andWhere(['between', 'lta.created_at', $startTs, $endTs]);
        }

       
        $timeQuery = clone $query;

        $attempts = $query->all();

      

 

        // Subject map (for normal titles)
        $subjectsById = Subject::find()->indexBy('id')->all();

        /**
         * Normalize subject IDs/titles:
         * - 4 => Math
         * - 2 ya 5 => English (canonical id 5 prefer)
         * - warna DB title ya 'N/A'
         */
        $normalizeSubject = function (?int $sid) use ($subjectsById, $englishIds): array {
            if (!$sid) return [null, 'N/A'];

            // Hard map for Math
            if ((int)$sid === 4) {
                return [4, 'Math'];
            }

            // English aliases (2/5) ko 'English' banao
            if (in_array((int)$sid, [2, 5], true)) {
                // canonical id choose (prefer 5 if present)
                $canonicalId = isset($subjectsById[5]) ? 5 : (isset($subjectsById[2]) ? 2 : (int)$sid);
                return [$canonicalId, 'English'];
            }

            // Fallback: DB title if available
            $title = $subjectsById[$sid]->title ?? 'N/A';
            return [(int)$sid, $title];
        };



        // ---------------- STATS + SCOPE (APPLIES TO EVERYTHING) ----------------
        $performanceCounts  = ['Excellent' => 0, 'Average' => 0, 'Requires Attention' => 0];
        $correctAnswers     = 0;
        $wrongAnswers       = 0;
        $allMonths          = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $monthData          = ['labels' => $allMonths, 'correct' => array_fill(0, 12, 0)];
        $chapterData        = [];

        // Preload lesson total points (for percentage calc)
        $lessonIds = array_values(array_unique(array_map(fn($a) => (int)$a->lesson_test_id, $attempts)));
        $totalPointsMap = [];
        if ($lessonIds) {
            $rows = LessonContent::find()
                ->select(['lesson_id', 'SUM(points) AS total'])
                ->where(['lesson_id' => $lessonIds])
                ->groupBy('lesson_id')
                ->asArray()->all();
            foreach ($rows as $r) {
                $totalPointsMap[(int)$r['lesson_id']] = (int)$r['total'];
            }
        }

        // Helper: return bucket label
        $bucketOf = function(float $pct): string {
            if ($pct >= 80) return 'Excellent';
            if ($pct >= 51) return 'Average';
            return 'Requires Attention';
        };

        
        $attemptsScope = [];
        foreach ($attempts as $a) {
            $lid   = (int)$a->lesson_test_id;
            $total = $totalPointsMap[$lid] ?? 0;
            $pct   = $total > 0 ? ($a->score / $total) * 100 : 0.0;
            $bucket = $bucketOf($pct);

            if (!$selectedPerformance || $selectedPerformance === $bucket) {
                $attemptsScope[] = $a;
            }
        }

        // 1.1) Ab performanceCounts sirf filtered scope par nikalo
        $performanceCounts = ['Excellent' => 0, 'Average' => 0, 'Requires Attention' => 0];
        foreach ($attemptsScope as $a) {
            $lid   = (int)$a->lesson_test_id;
            $total = $totalPointsMap[$lid] ?? 0;
            $pct   = $total > 0 ? ($a->score / $total) * 100 : 0.0;
            $bucket = $bucketOf($pct);
            $performanceCounts[$bucket]++;
        }



        // 2) All stats/charts must use attemptsScope
        foreach ($attemptsScope as $a) {
            $lid   = (int)$a->lesson_test_id;
            $total = $totalPointsMap[$lid] ?? 0;

            // right/wrong
            $correctAnswers += (int)$a->score;
            $wrongAnswers   += max(0, $total - (int)$a->score);

            // chapter counts
            if ($a->lesson && $a->lesson->chapter) {
                $cid = $a->lesson->chapter->id;
                $ct  = $a->lesson->chapter->title;
                if (!isset($chapterData[$cid])) $chapterData[$cid] = ['title' => $ct, 'count' => 0];
                $chapterData[$cid]['count']++;
            }

            // monthly points (bars)
            $m = date('M', (int)$a->created_at);
            $i = array_search($m, $allMonths, true);
            if ($i !== false) $monthData['correct'][$i] += (int)($a->points_earned ?? 0);
        }

        // 3) Group by lesson for the table (use attemptsScope)
        $grouped = [];
        foreach ($attemptsScope as $a) {
            if (!$a->lesson) continue;
            $lid = (int)$a->lesson->id;

            // Subject derive (same robust chain)
            $sidRaw = null;
            if (isset($a->effective_subject_id) && $a->effective_subject_id !== null) {
                $sidRaw = (int)$a->effective_subject_id;
            } elseif ($a->lesson && $a->lesson->chapter && isset($a->lesson->chapter->subject_id)) {
                $sidRaw = (int)$a->lesson->chapter->subject_id;
            } elseif ($a->lesson && isset($a->lesson->subject_id)) {
                $sidRaw = (int)$a->lesson->subject_id;
            } elseif (isset($a->subject_id)) {
                $sidRaw = (int)$a->subject_id;
            }

            [$normSid, $normTitle] = $normalizeSubject($sidRaw);

            if (!isset($grouped[$lid])) {
                $grouped[$lid] = [
                    'lesson'         => $a->lesson,
                    'subject'        => $a->lesson->subject ?? null,
                    'chapter'        => $a->lesson->chapter ?? null,
                    'attempts'       => 0,
                    'latest_attempt' => $a,
                    'subjectId'      => $normSid,
                    'subjectTitle'   => $normTitle,
                ];
            }
            $grouped[$lid]['attempts']++;
        }

        $testAttempts = array_values($grouped);

        // 4) Pagination after grouping
        $pagination = new Pagination([
            'totalCount'    => count($testAttempts),
            'pageSize'      => 20,
            'pageParam'     => 'page',
            'pageSizeParam' => false,
        ]);
        $pagination->params = Yii::$app->request->getQueryParams();
        $testAttempts = array_slice($testAttempts, $pagination->offset, $pagination->limit);

        // 5) Time spent (respect performance filter)
        $totalTimeSpentSec = 0;
        foreach ($attemptsScope as $a) {
            $totalTimeSpentSec += (int)($a->time_spent ?? 0);
        }
        $hours = intdiv($totalTimeSpentSec, 3600);
        $mins  = intdiv($totalTimeSpentSec % 3600, 60);
        $totalTimeSpentFormatted = sprintf('%02dH: %02dM', $hours, $mins);

        // Optional: if you show “totalQuestions”, use scope count
        $totalQuestions = count($attemptsScope);


        // --- TZ DEBUG (only when ?tzdebug=1 is present) ---
        $tzDebug = null;
        if (Yii::$app->request->get('tzdebug')) {
            $tzDebug = [
                'appTimeZone'        => Yii::$app->timeZone,
                'formatterTimeZone'  => Yii::$app->formatter->timeZone,
                'formatterDefaultTZ' => Yii::$app->formatter->defaultTimeZone,
                'phpDefaultTZ'       => date_default_timezone_get(),
                'serverNow_epoch'    => time(),
                'serverNow_iso'      => (new \DateTime('now', new \DateTimeZone(date_default_timezone_get())))->format('c'),
            ];

             
            if (!empty($attempts)) {
                /** @var \frontend\models\LessonTestAttempt $any */
                $any = $attempts[0];
                $tzDebug['sampleAttempt_epoch'] = (int)$any->created_at;
                $tzDebug['sampleAttempt_asDate_displayTZ'] = Yii::$app->formatter->asDatetime($any->created_at, 'php:c'); // uses formatter->timeZone
            }
        }

    $totalQuestionsCount = count($filteredAttempts ?? []);


        $this->layout = '@frontend/views/layouts/_minimal.php';

        return $this->render('index', [
            'students'            => $students,
            'subjects'            => $subjects,
            'testAttempts'        => $testAttempts,
            'selectedStudent'     => $selectedStudent,
            'selectedSubject'     => $selectedSubject,
            'selectedPerformance' => $selectedPerformance,
            'selectedDateRange'   => $selectedDateRange,
            'pagination'          => $pagination,
            
            'totalQuestions' => $totalQuestionsCount,
            'performanceCounts'   => $performanceCounts,
            'correctAnswers'      => $correctAnswers,
            'wrongAnswers'        => $wrongAnswers,
            'chapterData'         => $chapterData,
            'dayData'             => $monthData,
            'totalTimeSpent'      => $totalTimeSpentFormatted,
            'tzDebug' => $tzDebug,

        ]);
    }

    
 public function actionFilteredResults($performance, $student_id = null, $subject_id = null, $date = null)
    {
        ini_set('memory_limit', '2048M');

        $query = LessonTestAttempt::find()
            ->alias('lta')
            ->joinWith(['lesson l', 'lesson.subject s', 'lesson.chapter c'])
            ->orderBy(['lta.created_at' => SORT_DESC]);

        // Derived subject again
        $effectiveSubjectExpr = new \yii\db\Expression('COALESCE(c.subject_id, l.subject_id, lta.subject_id)');
        $query->select(['lta.*', 'effective_subject_id' => $effectiveSubjectExpr]);


                $query->andFilterWhere(['lta.student_id' => $student_id]);

        
            $wantIds = ($subject_id == 2 || $subject_id == 5) ? [2,5] : (strlen((string)$subject_id) ? [(int)$subject_id] : []);
        if ($wantIds) {
            // filter on derived subject only
            $query->andWhere(['IN', $effectiveSubjectExpr, $wantIds]);
        }



        if ($date) {
            if (strpos($date, ' to ') !== false) {
                [$startDate, $endDate] = explode(' to ', $date);
                $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                $endDate   = date('Y-m-d 23:59:59', strtotime($endDate));
            } else {
                $startDate = date('Y-m-d 00:00:00', strtotime($date));
                $endDate   = date('Y-m-d 23:59:59', strtotime($date));
            }
            // $query->andWhere(['between', 'FROM_UNIXTIME(lta.created_at)', $startDate, $endDate]);
            
            $startTs = $endTs = null;
            if (!empty($date)) {
                if (strpos($date, ' to ') !== false) {
                    [$s, $e] = explode(' to ', $date);
                    $startTs = strtotime(date('Y-m-d 00:00:00', strtotime($s)));
                    $endTs   = strtotime(date('Y-m-d 23:59:59', strtotime($e)));
                } else {
                    $startTs = strtotime(date('Y-m-d 00:00:00', strtotime($date)));
                    $endTs   = strtotime(date('Y-m-d 23:59:59', strtotime($date)));
                }
                $query->andWhere(['between', 'lta.created_at', $startTs, $endTs]);
            }



        }

        // Preload totals
        $lessonIds = (clone $query)->select('lta.lesson_test_id')->distinct()->column();
        $totalPointsMap = LessonContent::find()
            ->select(['lesson_id', 'SUM(points) AS total'])
            ->where(['lesson_id' => $lessonIds])
            ->groupBy('lesson_id')
            ->indexBy('lesson_id')
            ->asArray()
            ->all();

        $rawAttempts = [];
        foreach ($query->batch(100) as $batch) {
            foreach ($batch as $attempt) {
                $lessonId = $attempt->lesson_test_id ?? $attempt->lesson->id ?? null;
                $totalPoints = $totalPointsMap[$lessonId]['total'] ?? 0;
                $pct = $totalPoints > 0 ? ($attempt->score / $totalPoints) * 100 : 0;

                if (
                    ($performance == 'Excellent' && $pct >= 80) ||
                    ($performance == 'Average' && $pct >= 51 && $pct < 80) ||
                    ($performance == 'Requires Attention' && $pct < 51)
                ) {
                    $rawAttempts[] = $attempt;
                }
            }
        }

        $filteredAttempts = [];
        foreach ($rawAttempts as $attempt) {
            if (!$attempt->lesson || !$attempt->lesson->id) continue;
            $lid = $attempt->lesson->id;
            if (!isset($filteredAttempts[$lid])) {
                $filteredAttempts[$lid] = [
                    'lesson'        => $attempt->lesson,
                    'latest_attempt'=> $attempt,
                    'attempts'      => 0,
                    'total_points'  => $totalPointsMap[$lid]['total'] ?? 0,
                ];
            }
            $filteredAttempts[$lid]['attempts']++;
        }

        $filteredAttempts = array_values($filteredAttempts);

        $this->layout = '@frontend/views/layouts/_minimal.php';
        return $this->render('filtered-results', [
            'filteredAttempts' => $filteredAttempts,
            'performance'      => $performance,
        ]);
    }
}
