<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class NotificationController extends Controller
{
    // public function actionPing()
    // {
    //     Yii::$app->response->format = Response::FORMAT_JSON;

    //     $notifier = Yii::$app->notifier;
    //     $count = $notifier->unreadCount();

    //     // Recent items dropdown HTML (reuse a partial)
    //     $itemsHtml = $this->renderPartial('@backend/views/notification/_dropdown', [
    //         'count'  => $count,
    //         'events' => $notifier->latest(),
    //     ]);

    //     return [
    //         'count'     => $count,
    //         'leftBadge' => $count,
    //         'itemsHtml' => $itemsHtml,
    //     ];
    // }

    // public function actionPing()
    // {
    //     Yii::$app->response->format = Response::FORMAT_JSON;

    //     $notifier = Yii::$app->notifier;

    //     // total unread (all categories your notifier tracks)
    //     $total = $notifier->unreadCount();

    //     // left menu per-category bubbles
    //     $assessUnread = \common\models\TimelineEvent::find()
    //         ->where(['category' => 'assessment', 'is_read' => 0])
    //         ->count();

    //     $newsUnread = \common\models\TimelineEvent::find()
    //         ->where(['category' => 'newsletter', 'is_read' => 0])
    //         ->count();

    //     $itemsHtml = $this->renderPartial('@backend/views/notification/_dropdown', [
    //         'count'  => $total,
    //         'events' => $notifier->latest(), // make sure latest() does NOT filter only assessments
    //     ]);

    //     return [
    //         'count'          => (int)$total,
    //         'assessLeft'     => (int)$assessUnread,
    //         'newsletterLeft' => (int)$newsUnread,
    //         'itemsHtml'      => $itemsHtml,
    //     ];
    // }

   public function actionPing()
    {
        $notifier = Yii::$app->notifier;

        $assessLeft = (int) \common\models\TimelineEvent::find()
            ->where(['category' => 'assessment', 'is_read' => 0])
            ->count();

        $newsletterLeft = (int) \common\models\TimelineEvent::find()
            ->where(['category' => 'newsletter', 'is_read' => 0])
            ->count(); 
        
        $contactLeft = (int) \common\models\TimelineEvent::find()
        ->where(['category' => 'contact', 'is_read' => 0])->count();


        return $this->asJson([
            'count'           => (int) $notifier->unreadCount(), // top bell
            'itemsHtml'       => $this->renderPartial('@backend/views/notification/_dropdown', [
                'count'  => (int) $notifier->unreadCount(),
                'events' => $notifier->latest(),
            ]),
            'assessLeft'      => $assessLeft,
            'newsletterLeft'  => $newsletterLeft,
            'contactLeft'     => $contactLeft, 
        ]);
    }




    
}
