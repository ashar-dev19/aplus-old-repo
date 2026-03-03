<?php
namespace backend\components;

use Yii;
use yii\base\Component;
use common\models\TimelineEvent;

class Notifier extends Component
{
    public $recent = 10;

     public function unreadCount(): int
    {
        return (int) TimelineEvent::find()
            ->where(['is_read' => 0])        
            ->count();
    }

    public function latest(int $limit = 5): array
    {
        return TimelineEvent::find()
            ->where(['is_read' => 0]) 
            ->orderBy(['created_at' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    public function markAssessmentRead($assessmentId): void
    {
        TimelineEvent::updateAll(
            ['is_read' => 1],
            ['category' => 'assessment', 'is_read' => 0]
            + ['LIKE', 'data', '"assessment_id":'.$assessmentId] 
        );
    }

  public function markContactRead($contactId): void
    {
        TimelineEvent::updateAll(
            ['is_read' => 1],
            ['and',
                ['category' => 'contact', 'is_read' => 0],
                ['like', 'data', '"contact_id":'.$contactId]
            ]
        );
    }


    
}
