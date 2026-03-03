<?php



namespace frontend\controllers;



use cheatsheet\Time;
use common\sitemap\UrlsIterator;
use frontend\models\ContactQueries;
use frontend\models\Newsletter;
use frontend\models\Userimported;
use backend\modules\UserManagement\models\UserProfile;
use webvimark\modules\UserManagement\models\User;
use frontend\models\ChangePasswordForm;
use Sitemaped\Element\Urlset\Urlset;
use Sitemaped\Sitemap;
use Yii;
use yii\filters\PageCache;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use common\commands\SendEmailCommand;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Article;
use yii\helpers\Json;  



/**

 * Site controller

 */

class SiteController extends Controller

{

    public $freeAccess = true;

    /**

     * @return array

     */

    public function behaviors()

    {

        return [

            [

                'class' => PageCache::class,

                'only' => ['sitemap'],

                'duration' => Time::SECONDS_IN_AN_HOUR,

            ]

        ];

    }



    /**

     * @inheritdoc

     */

    public function actions()

    {

        return [

            'error' => [

                'class' => 'yii\web\ErrorAction'

            ],

            'captcha' => [

                'class' => 'yii\captcha\CaptchaAction',

                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null

            ],

            'set-locale' => [

                'class' => 'common\actions\SetLocaleAction',

                'locales' => array_keys(Yii::$app->params['availableLocales'])

            ]

        ];

    }


 


 


// public function actionImportBatch(int $batchSize = 300)
//     {
//         // allow longer runs
//         set_time_limit(0);

//         // totals
//         $total     = UserImported::find()->count();
//         $done      = UserImported::find()->where(['processed'=>1])->count();
//         $remaining = $total - $done;

//         // next unprocessed chunk
//         $rows = UserImported::find()
//             ->where(['processed' => 0])
//             ->orderBy(['id'=>SORT_ASC])
//             ->limit($batchSize)
//             ->all();

//         if (empty($rows)) {
//             return $this->renderContent("<p><strong>All {$total} rows have been processed.</strong></p>");
//         }

//         $imported = $updated = $failed = [];

//         foreach ($rows as $r) {
//             // look for existing user by email
//             $u = User::findOne(['email' => $r->email]);

//             if ($u) {
//                 // update password
//                 $u->password_hash = Yii::$app->security->generatePasswordHash($r->password);
//                 if (!$u->save(false)) {
//                     $failed[$r->email] = $u->getErrors();
//                     continue;
//                 }
//                 // always ensure 'user' role
//                 User::assignRole($u->id, 'user');

//                 // profile: load or create
//                 $profile = UserProfile::findOne(['user_id'=>$u->id])
//                          ?: new UserProfile(['user_id'=>$u->id]);
//                 if (empty($profile->firstname)) {
//                     $profile->firstname = $r->fname;
//                 }
//                 if (empty($profile->lastname)) {
//                     $profile->lastname = $r->lname;
//                 }
//                 $profile->save(false);

//                 $updated[] = $r->email;
//             } else {
//                 // new user: username = email
//                 $u = new User([
//                     'username'        => $r->email,
//                     'email'           => $r->email,
//                     'address'         => mb_strimwidth($r->address,0,255,'','UTF-8'),
//                     'phone'           => $r->phone_number,
//                     'status'          => User::STATUS_ACTIVE,
//                     'email_confirmed' => 1,
//                     'auth_key'        => Yii::$app->security->generateRandomString(),
//                     'password_hash'   => Yii::$app->security->generatePasswordHash($r->password),
//                 ]);
//                 if (!$u->save(false)) {
//                     $failed[$r->email] = $u->getErrors();
//                     continue;
//                 }
//                 // assign role
//                 User::assignRole($u->id, 'user');

//                 // create profile
//                 $profile = new UserProfile([
//                     'user_id'   => $u->id,
//                     'firstname' => $r->fname,
//                     'lastname'  => $r->lname,
//                 ]);
//                 $profile->save(false);

//                 $imported[] = $r->email;
//             }

//             // mark as processed
//             $r->processed    = 1;
//             $r->processed_at = date('Y-m-d H:i:s');
//             $r->save(false);
//         }

//         // build HTML report
//         $html  = "<h3>Import batch (size: {$batchSize})</h3>";
//         $html .= "<p>Total: {$total} / Done: {$done} / Remaining: {$remaining}</p>";

//         if ($imported) {
//             $html .= '<h4>Newly Imported</h4><ul>';
//             foreach ($imported as $e) {
//                 $html .= '<li>' . Html::encode($e) . '</li>';
//             }
//             $html .= '</ul>';
//         }
//         if ($updated) {
//             $html .= '<h4>Updated Existing</h4><ul>';
//             foreach ($updated as $e) {
//                 $html .= '<li>' . Html::encode($e) . '</li>';
//             }
//             $html .= '</ul>';
//         }
//         if ($failed) {
//             $html .= '<h4>Failures</h4><ul>';
//             foreach ($failed as $e => $errs) {
//                 $html .= '<li>'
//                        . Html::encode($e)
//                        . ' – '
//                        . Html::encode(Json::encode($errs))
//                        . '</li>';
//             }
//             $html .= '</ul>';
//         }

//         // link to run next chunk
//         $html .= Html::a('Run next batch', ['import-batch','batchSize'=>$batchSize], ['class'=>'btn btn-primary']);

//         return $this->renderContent($html);
//     }






    /**

     * @return string

     */

    public function actionIndex()

    {
        if (!Yii::$app->user->isGuest) {
            return Yii::$app->response->redirect(['student/current']);
        }
         
         $articles = Article::find()
         ->where(['status' => 1]) 
         ->orderBy(['created_at' => SORT_DESC])
         ->limit(3) 
         ->all();

         return $this->render('index', [
            'articles' => $articles,
        ]);
    }

    public function actionAbout()

    {
        if (!Yii::$app->user->isGuest) {
            return Yii::$app->response->redirect(['student/current']);
        }

        return $this->render('about');

    }

    public function actionFaqs()

    {
        if (!Yii::$app->user->isGuest) {
            return Yii::$app->response->redirect(['student/current']);
        }
        return $this->render('faqs');

    }



     public function actionLogin()

    {

        return $this->render('login');

    }

  

      public function actionSelectProfile()

    {

        return $this->render('select-profile');

    }





      public function actionEmail()
     {
        return $this->render('email');
     }

    // public function actionEmail()
    // {
    //     Yii::$app->mailer->compose()
    //         ->setFrom('umairgilani64@gmail.com')
    //         ->setTo('umairgilani2018@gmail.com')
    //         ->setSubject('Test Email from Yii2')
    //         ->setTextBody('Hello, this is a test email.')
    //         ->send();

    //     return 'Email sent.';
    // }


    //     public function beforeAction($action)
    // {
    //     if ($action->id === 'send') {
    //         $this->enableCsrfValidation = false;
    //     }
    //     return parent::beforeAction($action);
    // }



    // public function actionSend()
    // {
    //     Yii::$app->response->format = Response::FORMAT_JSON;
        
    //     if (Yii::$app->request->isAjax) {
    //         $recipient = Yii::$app->request->post('recipient');
    //         $subject = Yii::$app->request->post('subject');
    //         $body = Yii::$app->request->post('body');
            
    //         if (empty($recipient) || empty($subject) || empty($body)) {
    //             return [
    //                 'success' => false,
    //                 'message' => 'Please fill all required fields'
    //             ];
    //         }
            
    //         try {
    //             $sent = Yii::$app->mailer->compose()
    //                 ->setFrom(['reports@aplustudents.com' => 'APluStudents Reports'])
    //                 ->setTo($recipient)
    //                 ->setSubject($subject)
    //                 ->setHtmlBody($body)
    //                 ->send();
                
    //             if ($sent) {
    //                 return [
    //                     'success' => true,
    //                     'message' => 'Email sent successfully!'
    //                 ];
    //             } else {
    //                 return [
    //                     'success' => false,
    //                     'message' => 'Failed to send email. Please try again.'
    //                 ];
    //             }
    //         } catch (\Exception $e) {
    //             return [
    //                 'success' => false,
    //                 'message' => 'Error: ' . $e->getMessage()
    //             ];
    //         }
    //     }
        
    //     return [
    //         'success' => false,
    //         'message' => 'Invalid request'
    //     ];
    // }





    /**

     * @return string|Response

     */
    
     public function actionContact()
     {
         $model = new ContactQueries();
        
         if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
             if ($model->save()) {
                    // 1) backend detail link (param ya fallback)
                // $backendBase = Yii::$app->params['backendBaseUrl'] ?? 'https://alpha.aplustudents.com';
                $backendBase = 'https://alpha.aplustudents.com';
                $backendLink = rtrim($backendBase, '/') . '/contact-queries/view?id=' . $model->id;

                // 2) TimelineEvent create
                $evt = new \common\models\TimelineEvent();
                $evt->application = 'backend';
                $evt->category    = 'contact'; 
                $evt->event       = 'new';
                $evt->is_read     = 0; 
                $evt->data        = json_encode([
                    'contact_id' => (int)$model->id,
                    'name'       => (string)$model->name,
                    'email'      => (string)$model->email,
                    'subject'    => (string)($model->subject ?? ''),
                    'link'       => $backendLink,
                ], JSON_UNESCAPED_UNICODE);
                $evt->save(false);

                
                 Yii::$app->session->setFlash('success', 'Your message has been sent successfully.');
                 $model->clearAttributes(); // Reset attributes
             } else {
                 Yii::$app->session->setFlash('error', 'There was an error while processing your request.');
             }
         }
 
         return $this->render('contact', [
             'model' => $model,
         ]);
     }


    //  public function actionSubscribe()
    //  {
    //      $model = new Newsletter(); 
         
    //      if ($model->load(Yii::$app->request->post()) && $model->save()) {
             
    //          Yii::$app->session->setFlash('success', 'Thank you for subscribing!');
    //          return $this->goHome(); 
    //      }
    //      Yii::$app->session->setFlash('error', 'There was an issue with your subscription.');
    //      return $this->goHome();
    //  }

    //  public function actionSubscribe()
    // {
    //     $post = Yii::$app->request->post();
    //     $email = $post['Newsletter']['email'] ?? null;

    //     if (!$email) {
    //         Yii::$app->session->setFlash('error', 'Please enter an email.');
    //         return $this->goHome();
    //     }

    //     // pehle se maujood?
    //     $model = Newsletter::findOne(['email' => $email]);
    //     if ($model) {
    //         Yii::$app->session->setFlash('success', 'You are already subscribed.');
    //         return $this->goHome();
    //     }

    //     $model = new Newsletter();
    //     $model->email = $email;

    //     if ($model->save()) {
    //         Yii::$app->session->setFlash('success', 'Thank you for subscribing!');
    //     } else {
    //         Yii::$app->session->setFlash('error', 'There was an issue with your subscription.');
    //     }
    //     return $this->goHome();
    // }


    public function actionSubscribe()
{
    $post  = Yii::$app->request->post();
    $email = strtolower(trim($post['Newsletter']['email'] ?? ''));
    $hp    = $post['hp'] ?? '';
    $t0    = (int)($post['t0'] ?? 0);

    // honeypot hit or too-fast submit (< 2s)
    if ($hp !== '' || (time() - $t0) < 2) {
        Yii::$app->session->setFlash('error', 'Invalid submission.');
        return $this->goHome();
    }

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        Yii::$app->session->setFlash('error', 'Please enter a valid email.');
        return $this->goHome();
    }

    // (optional) lightweight domain check
    $domain = substr(strrchr($email, "@"), 1);
    if ($domain && !checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
        Yii::$app->session->setFlash('error', 'Email domain seems invalid.');
        return $this->goHome();
    }

    // already subscribed?
    if (Newsletter::find()->where(['email' => $email])->exists()) {
        Yii::$app->session->setFlash('success', 'You are already subscribed.');
        return $this->goHome();
    }

    $model = new Newsletter();
    $model->email = $email;

    if ($model->save()) {
        Yii::$app->session->setFlash('success', 'Thank you for subscribing!');
    } else {
        Yii::$app->session->setFlash('error', 'There was an issue with your subscription.');
    }
    return $this->goHome();
}



    

    

	

	public function actionTestEmail($target){

		if ($target == 786){

			print Yii::$app->params['robotEmail'];

			Yii::$app->commandBus->handle(new SendEmailCommand([

						'mailer' =>'mailer_student_portal',

						'subject' => Yii::t('frontend', 'MIHE Admission Application Submitted'),

						'body' => 'This email will be sent to Jawwad Ahmed',

						'to' => ['jawwad.software@gmail.com'=> 'Jawwad Ahmed'],

						'from' => [Yii::$app->params['robotEmail'] => 'MIHE' ],

						'params' => [

							'url' => Url::to(['/studentms/application-detail/view/', 'id' => 'someid', 'ten'=>54], true),

							//'model' => $model

						]

					]));

		}		

	}



    /**

     * @param string $format

     * @param bool $gzip

     * @return string

     * @throws BadRequestHttpException

     */

public function actionLogout()
{
    Yii::$app->user->logout();
    return $this->goHome();  // Redirect to homepage after logout
}

public function actionChangePassword()
{
    $model = new ChangePasswordForm();

    if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
        Yii::$app->session->setFlash('success', 'Password changed successfully.');
        return $this->goHome();
    }
    $this->layout = '@frontend/views/layouts/_header-before.php';
    return $this->render('change-password', ['model' => $model]);
}




    public function actionSitemap($format = Sitemap::FORMAT_XML, $gzip = false)

    {

        $links = new UrlsIterator();

        $sitemap = new Sitemap(new Urlset($links));



        Yii::$app->response->format = Response::FORMAT_RAW;



        if ($gzip === true) {

            Yii::$app->response->headers->add('Content-Encoding', 'gzip');

        }



        if ($format === Sitemap::FORMAT_XML) {

            Yii::$app->response->headers->add('Content-Type', 'application/xml');

            $content = $sitemap->toXmlString($gzip);

        } else if ($format === Sitemap::FORMAT_TXT) {

            Yii::$app->response->headers->add('Content-Type', 'text/plain');

            $content = $sitemap->toTxtString($gzip);

        } else {

            throw new BadRequestHttpException('Unknown format');

        }



        $linksCount = $sitemap->getCount();

        if ($linksCount > 50000) {

            Yii::warning(\sprintf('Sitemap links count is %d'), $linksCount);

        }



        return $content;

    }


    public function actionHashPasswords($lastId = 0, $batch = 200, $done = 0, $n = 1)
{
    ini_set('memory_limit', '512M');
    set_time_limit(0);

    $db    = Yii::$app->db;
    $table = \webvimark\modules\UserManagement\models\User::tableName();
    $re    = '^(\\$2[ayb]\\$|\\$argon2(id|i)\\$)'; // bcrypt/argon2

    // rows jinke password_hash abhi hash nahi hai
    $rows = (new \yii\db\Query())
        ->from($table)
        ->select(['id','password','password_hash'])
        ->where(['>', 'id', (int)$lastId])
        ->andWhere(new \yii\db\Expression("(password IS NOT NULL AND password <> '')"))
        ->andWhere([
            'or',
            ['password_hash' => null],
            ['password_hash' => ''],
            new \yii\db\Expression("password_hash NOT REGEXP :re", [':re' => $re]),
        ])
        ->orderBy(['id' => SORT_ASC])
        ->limit((int)$batch)
        ->all();

    if (!$rows) {
        return $this->renderContent("<h3>All done.</h3><p>Total updated: {$done}</p>");
    }

    $updated = 0; $maxId = $lastId;

    foreach ($rows as $u) {
        $maxId = max($maxId, (int)$u['id']);
        $raw   = (string)$u['password'];

        // if password column already contains a hash, keep it
        $hash = preg_match('/^\$2[ayb]\$|\$argon2(id|i)\$/', $raw)
            ? $raw
            : Yii::$app->security->generatePasswordHash($raw);

        $db->createCommand()
           ->update($table, ['password_hash' => $hash], ['id' => $u['id']])
           ->execute();

        $updated++;
    }

    $done += $updated;

    $nextUrl = \yii\helpers\Url::to(['site/hash-passwords',
        'lastId' => $maxId, 'batch' => $batch, 'done' => $done, 'n' => $n + 1
    ], true);

    $html = <<<HTML
<div>Batch #{$n}: updated={$updated}, lastId={$maxId} | Total so far: {$done}</div>
<script>
  console.log("Batch #{$n}: updated={$updated}, lastId={$maxId}, done={$done}");
  setTimeout(function(){ location.href = "{$nextUrl}"; }, 400);
</script>
HTML;

    return $this->renderContent($html);
}




}

