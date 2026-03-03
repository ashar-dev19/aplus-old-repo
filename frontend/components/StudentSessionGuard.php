<?php
namespace frontend\components;

use Yii;
use yii\base\ActionFilter;

class StudentSessionGuard extends ActionFilter
{
    public $only   = [];   // from config
    public $except = [];

    public function beforeAction($action)
    {
        // 0) Guests ko chhor do; unka apna login flow hai
        if (Yii::$app->user->isGuest) return true;

        $route = $action->uniqueId; // e.g. lesson-content/test
        $req   = Yii::$app->request;

        // 1) static assets / health / ajax debugging etc. ko chhor do
        $path = $req->url; // fast check
        if (strpos($path, '/assets/') === 0 || strpos($path, '/favicon') === 0) {
            return true;
        }

        // 2) Agar "only" diya hua hai to route allow nahi hai to seedha pass
        if (!empty($this->only) && !$this->matchesList($route, $this->only)) {
            return true;
        }

        // 3) except list
        if ($this->matchesList($route, $this->except)) {
            return true;
        }

        // 4) ultra-cheap session read (1 lookup)
        $sid = null;
        $cs  = Yii::$app->session->get('current_student');
        if (is_array($cs)) $sid = $cs['id'] ?? null;
        elseif (is_scalar($cs)) $sid = $cs;

        if (!$sid) {
            Yii::$app->response->redirect(['/student/current'])->send();
            return false;
        }

        return true;
    }

    private function matchesList(string $route, array $list): bool
    {
        // simple prefix or exact match (supports pattern like 'foo/*')
        foreach ($list as $p) {
            $p = (string)$p;
            if ($p === $route) return true;
            // 'foo/*' => 'foo/'
            if (substr($p, -2) === '/*') {
                $prefix = substr($p, 0, -1); // keep trailing slash
                if (strpos($route, rtrim($prefix, '/')) === 0) return true;
            }
        }
        return false;
    }
}
