<?php

namespace app\components;

class BaseController extends \yii\web\Controller
{
    public function init()
    {
        parent::init();
    }
    
    public function behaviors()
    {
        return [
            'ghost-access'=> [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
        ];
    }
    
    /**
	 * @inheritdoc
	 */
	public function beforeAction($action)
	{
		if ( parent::beforeAction($action) )
		{
//                    print "jawwad"; die;
                    // get the cookie collection (yii\web\CookieCollection) from the "request" component
                    $cookies = Yii::$app->request->cookies;
                    if (($cookie = $cookies->get('__company_id__')) !== null) {
                        $companyid = $cookie->value;
                    }
                    if(!Yii::$app->user->isGuest && !is_null(Yii::$app->user->company->id) && $companyid != Yii::$app->user->company->id) {

                        $cookie = new Cookie([
				'name' => '__company_id__',
				'value' => Yii::$app->user->company->id,
				'expire' => time() + 86400 * 365, // 1 year
			]);
                        
                        Yii::$app->response->cookies->add($cookie);
                        
                        $cookie = new Cookie([
				'name' => '__company_name__',
				'value' => Yii::$app->user->company->title,
				'expire' => time() + 86400 * 365, // 1 year
			]);
                        Yii::$app->response->cookies->add($cookie);
                        
                    }
                     $cookies = Yii::$app->request->cookies;
  
//print ">>".Yii::$app->user->profile->company->id ;
//print "from cook";
//print $cookies->getValue('_grid_page_size');
//print $cookies->getValue('__company_id__');
//print $cookies->getValue('__company_name__');
//                    die;
                }
        }
}
