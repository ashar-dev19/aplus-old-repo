<?php
/**
 * @author Eugine Terentev <eugine@terentev.net>
 * @author Victor Gonzalez <victor@vgr.cl>
 * @var yii\web\View $this
 * @var string $content
 * checking to see if we have a layout file
 */

use backend\assets\BackendAsset;
use backend\modules\system\models\SystemLog;
use backend\widgets\MainSidebarMenu;
use common\models\TimelineEvent;
use yii\bootstrap4\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\log\Logger;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Html;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FAS;
use common\components\keyStorage\FormModel;
use common\components\keyStorage\FormWidget;

 

if (!function_exists('canAnyRoute')) {
    /**
     * Check kare ke current user ke paas diye gaye routes me se
     * kisi ek ka bhi permission hai ya nahi (RBAC se).
     */
    function canAnyRoute(array $routes): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        foreach ($routes as $route) {
            if (Yii::$app->user->can($route)) {
                return true;
            }
        }

        return false;
    }
}

$bundle = BackendAsset::register($this);

Yii::info(Yii::$app->components["i18n"]["translations"]['*']['class'], 'test');

$keyStorage = Yii::$app->keyStorage;

$logEntries = [
    [
        'label' => Yii::t('backend', 'You have {num} log items', ['num' => SystemLog::find()->count()]),
        'linkOptions' => ['class' => ['dropdown-item', 'dropdown-header']]
    ],
    '<div class="dropdown-divider"></div>',
];
foreach (SystemLog::find()->orderBy(['log_time' => SORT_DESC])->limit(5)->all() as $logEntry) {
    $logEntries[] = [
        'label' => FAS::icon('exclamation-triangle', ['class' => [$logEntry->level === Logger::LEVEL_ERROR ? 'text-red' : 'text-yellow']]). ' '. $logEntry->category,
        'url' => ['/system/log/view', 'id' => $logEntry->id]
    ];
    $logEntries[] = '<div class="dropdown-divider"></div>';
}

$logEntries[] = [
    'label' => Yii::t('backend', 'View all'),
    'url' => ['/system/log/index'],
    'linkOptions' => ['class' => ['dropdown-item', 'dropdown-footer']]
];




/** @var \backend\components\Notifier $notifier */
$notifier = Yii::$app->notifier;
$count = $notifier->unreadCount();
$events = $notifier->latest();



 
 
// layout me upar:
$timelineUnread = TimelineEvent::find()
    ->where(['is_read' => 0])
    ->count();

$assessUnread = TimelineEvent::find()
    ->where(['category' => 'assessment', 'is_read' => 0])
    ->count();

$newsUnread = TimelineEvent::find()
    ->where(['category' => 'newsletter', 'is_read' => 0])
    ->count();

$contactUnread = \common\models\TimelineEvent::find()
    ->where(['category' => 'contact', 'is_read' => 0])
    ->count();

    


$isSA = Yii::$app->user->isSuperadmin;





?>

<?php $this->beginContent('@backend/views/layouts/base.php'); ?>
<div class="wrapper custom-wrapper beta_site">
    <!-- navbar -->
    <?php NavBar::begin([
        'renderInnerContainer' => false,
        'options' => [
            'class' => [
                'main-header',
                'navbar',
                'navbar-expand',
                'navbar-dark',
                $keyStorage->get('adminlte.navbar-no-border') ? 'border-bottom-0' : null,
                $keyStorage->get('adminlte.navbar-small-text') ? 'text-sm' : null,
            ],
        ],
    ]); ?>

        <!-- left navbar links -->
        <?php echo Nav::widget([
            'options' => ['class' => ['navbar-nav']],
            'encodeLabels' => false,
            'items' => [
                [
                    // sidebar menu toggler
                    'label' => FAS::icon('bars'),
                    'url' => '#',
                    'options' => [
                        'data' => ['widget' => 'pushmenu'],
                        'role' => 'button',
                    ]
                ],
            ]
        ]); ?>
        <!-- /left navbar links -->

        <!-- right navbar links -->
        <?php echo Nav::widget([
            'options' => ['class' => ['navbar-nav', 'ml-auto']],
            'encodeLabels' => false,
            'items' => [
                // [
                //     // timeline events
                //     'label' => FAR::icon('bell').' <span class="badge badge-success navbar-badge test11">'.TimelineEvent::find()->today()->count().'</span>',
                //     'url'  => ['/timeline-event/index']
                // ],

                [
                    'label' => FAR::icon('bell') .
                                ' <span class="badge badge-success navbar-badge" id="notif-count">' . $count . '</span>',
                    'url' => '#',
                    'linkOptions' => ['class' => 'no-caret', 'data-toggle' => 'dropdown'],
                    'dropdownOptions' => ['class' => ['dropdown-menu','dropdown-menu-lg','dropdown-menu-right','p-0']],
                    'items' => [[
                        // pure HTML body (encodeLabels=false set hai Nav par)
                        'label' => $this->render('@backend/views/notification/_dropdown', [
                            'count'  => $count,
                            'events' => $events,
                        ]),
                        'linkOptions' => ['class' => 'dropdown-item p-0'],
                        'url' => null,
                    ]],
                    ],

                
                [
                    // log events
                    'label' => FAS::icon('clipboard-list').' <span class="badge badge-warning navbar-badge">'.SystemLog::find()->count().'</span>',
                    'url' => '#',
                    'linkOptions' => ['class' => ['no-caret']],
                    'dropdownOptions' => [
                        'class' => ['dropdown-menu', 'dropdown-menu-lg', 'dropdown-menu-right'],
                    ],
                    'items' => $logEntries,
                ],
                '<li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        
                        '.Html::tag('span', Yii::$app->user->identity->username, ['class' => ['d-none', 'd-md-inline']]).'
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-primary">
                            '.Html::img("/img/anonymous.png", ['class' => ['img-circle', 'elevation-2', 'bg-white'], 'alt' => 'User image']).'
                            
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="float-left">
                                '.Html::a(Yii::t('backend', 'Profile'), ['/sign-in/profile'], ['class' => 'btn btn-default btn-flat']).'
                            </div>
                            <div class="float-left">
                                '.Html::a(Yii::t('backend', 'Account'), ['/sign-in/account'], ['class' => 'btn btn-default btn-flat']).'
                            </div>
                            <div class="float-right">
                                '.Html::a(Yii::t('backend', 'Logout'), ['/sign-in/logout'], ['class' => 'btn btn-default btn-flat', 'data-method' => 'post']).'
                            </div>
                        </li>
                    </ul>
                </li>
                ',
                [
                    // control sidebar button
                    'label' => FAS::icon('th-large'),
                    'url'  => '#',
                    'linkOptions' => [
                        'data' => ['widget' => 'control-sidebar', 'slide' => 'true'],
                        'role' => 'button'
                    ],
                    'visible' => Yii::$app->user->can('administrator'),
                ],
            ]
        ]);
        //'.Html::img(Yii::$app->user->identity->userProfile->getAvatar('/img/anonymous.png'), ['class' => ['img-circle', 'elevation-2', 'bg-white', 'user-image'], 'alt' => 'User image']).'
        //Yii::$app->user->identity->userProfile->getAvatar('/img/anonymous.png')
        ?>
        <!-- /right navbar links -->

    <?php NavBar::end(); ?>
    <!-- /navbar -->



    <!-- main sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4<?php echo $keyStorage->get('adminlte.sidebar-no-expand') ? 'sidebar-no-expand' : null ?>">
        <!-- brand logo -->
        <a href="/" class="brand-link text-center <?php echo $keyStorage->get('adminlte.brand-text-small') ? 'text-sm' : null ?>">
            <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                style="opacity: .8"> -->
            <span class="brand-text font-weight-bold"><?php echo Yii::$app->name ?></span>
        </a>
        <!-- /brand logo -->

        <!-- sidebar -->
        <div class="sidebar">
            <!-- sidebar user panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <?php echo Html::img(
//                        Yii::$app->user->identity->userProfile->getAvatar('/img/anonymous.png'),
                            "/img/anonymous.png",
                        ['class' => ['img-circle', 'elevation-2', 'bg-white'], 'alt' => 'User image']
                    ) ?>
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?php echo Yii::$app->user->identity->username ?></a>
                </div>
            </div>
            <!-- /sidebar user panel -->

            <!-- sidebar menu -->
             <nav class="mt-2">
                <?php echo MainSidebarMenu::widget([
                    'options' => [
                        'class' => [
                            'nav',
                            'nav-pills',
                            'nav-sidebar',
                            'flex-column',
                            $keyStorage->get('adminlte.sidebar-small-text') ? 'text-sm' : null,
                            $keyStorage->get('adminlte.sidebar-flat') ? 'nav-flat' : null,
                            $keyStorage->get('adminlte.sidebar-legacy') ? 'nav-legacy' : null,
                            $keyStorage->get('adminlte.sidebar-compact') ? 'nav-compact' : null,
                            $keyStorage->get('adminlte.sidebar-child-indent') ? 'nav-child-indent' : null,
                        ],
                        'data' => [
                            'widget' => 'treeview',
                            'accordion' => 'false'
                        ],
                        'role' => 'menu',
                    ],
                    'items' => [
                        [
                            'label' => Yii::t('backend', 'Main Navigation'),
                            'options' => ['class' => 'nav-header'],
                        ],

                        [
                            'label' => Yii::t('backend', 'Timeline'),
                            'icon' => FAS::icon('stream', ['class' => ['nav-icon']]),
                            'url' => ['/timeline-event/index'],
                            'badge' => $timelineUnread,
                            'badgeBgClass' => 'badge-success',
                        ],

                        [
                            'label' => Yii::t('backend', 'Dashboard'),
                            'icon' => FAS::icon('stream', ['class' => ['nav-icon']]),
                            'url' => ['/dashboard'],
                            'badgeBgClass' => 'badge-success',
                        ],
                        [
                            'label' => Yii::t('backend', 'Main'),
                            'options' => ['class' => 'nav-header'],
                        ],

                        // USER MANAGEMENT GROUP
                        [
                            'label' => Yii::t('backend', 'User Management'),
                            'url' => '#',
                            'icon' => FAS::icon('user', ['class' => ['nav-icon']]),
                            'options' => ['class' => 'nav-item has-treeview'],
                            'active' => Yii::$app->controller->module->id === 'user-management'
                                || ('teacher-reports' === Yii::$app->controller->id),
                            'visible' => canAnyRoute([
                                '/user-management/user/*',
                                '/user-management/user/index',
                                '/user-management/user/sales-persons',
                            ]),
                            'items' => [
                                [
                                    'label' => Yii::t('backend', 'User List'),
                                    'url' => ['/user-management/user/index'],
                                    'icon' => FAS::icon('user', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'user',
                                    'visible' => canAnyRoute([
                                        '/user-management/user/*',
                                        '/user-management/user/index',
                                    ]),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Teachers'),
                                    'url' => 'javascript:void(0);',
                                    'icon' => FAR::icon('file', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'category',
                                    'visible' => false, // abhi koi permission nahi
                                ],
                                [
                                    'label' => Yii::t('backend', 'Sales People'),
                                    'url' => ['/user-management/user/sales-persons'],
                                    'icon' => FAR::icon('file', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'category',
                                    'visible' => canAnyRoute([
                                        '/user-management/user/sales-persons',
                                    ]),
                                ],
                            ],
                        ],

                        // STUDENT MANAGEMENT GROUP
                        [
                            'label' => Yii::t('backend', 'Student Management'),
                            'url' => '#',
                            'icon' => FAS::icon('graduation-cap', ['class' => ['nav-icon']]),
                            'options' => ['class' => 'nav-item has-treeview'],
                            'active' => Yii::$app->controller->module->id === 'student-management' ||
                                ('notification' === Yii::$app->controller->id
                                    || 'student' === Yii::$app->controller->id
                                    || 'notes' === Yii::$app->controller->id
                                    || 'points' === Yii::$app->controller->id),
                            'visible' => canAnyRoute([
                                '/student/index',
                                '/student/*',
                                '/student-management/notification/index',
                                '/student-management/notes/index',
                            ]),
                            'items' => [
                                [
                                    'label' => Yii::t('backend', 'Student List'),
                                    'url' => ['/student/index'],
                                    'icon' => FAR::icon('user', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'student',
                                    'visible' => canAnyRoute(['/student/index', '/student/*']),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Send Notifications'),
                                    'url' => ['/student-management/notification/index'],
                                    'icon' => FAR::icon('envelope', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'notification',
                                    'visible' => canAnyRoute(['/student-management/notification/index']),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Student Notes'),
                                    'url' => ['/student-management/notes/index'],
                                    'icon' => FAR::icon('comment', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'notes',
                                    'visible' => canAnyRoute(['/student-management/notes/index']),
                                ],
                            ],
                        ],

                         
                        [
                            'label' => Yii::t('backend', 'Content Management'),
                            'url' => '#',
                            'icon' => FAS::icon('thumbtack', ['class' => ['nav-icon']]),
                            'active' => Yii::$app->controller->id === 'subject'
                                || Yii::$app->controller->id === 'chapter'
                                || Yii::$app->controller->id === 'lesson'
                                || Yii::$app->controller->id === 'lesson-content'
                                || Yii::$app->controller->id === 'grade',
                             
                            'visible' => canAnyRoute([
                                '/elearning/subject/*',
                                '/elearning/chapter/*',
                                '/elearning/lesson/*',
                                '/elearning/grade/*',
                            ]),
                            'items' => [
                                [
                                    'label' => Yii::t('backend', 'Subject'),
                                    'url' => ['/elearning/subject/index'],
                                    'icon' => FAS::icon('calculator', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'subject',
                                    'visible' => canAnyRoute([
                                        '/elearning/subject/index',
                                        '/elearning/subject/*',
                                    ]),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Chapter'),
                                    'url' => ['/elearning/chapter/index'],
                                    'icon' => FAR::icon('book', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'chapter',
                                    'visible' => canAnyRoute([
                                        '/elearning/chapter/index',
                                        '/elearning/chapter/*',
                                    ]),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Lesson'),
                                    'url' => ['/elearning/lesson/index'],
                                    'icon' => FAR::icon('book', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'lesson',
                                    'visible' => canAnyRoute([
                                        '/elearning/lesson/index',
                                        '/elearning/lesson/*',
                                    ]),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Grade'),
                                    'url' => ['/elearning/grade/index'],
                                    'icon' => FAR::icon('book', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'grade',
                                    'visible' => canAnyRoute([
                                        '/elearning/grade/index',
                                        '/elearning/grade/*',
                                    ]),
                                ],
                            ],
                        ],


                        // TEST MANAGEMENT GROUP
                        [
                            'label' => Yii::t('backend', 'Test Management'),
                            'url' => ['/elearning/lesson-test/'],
                            'icon' => FAS::icon('thumbtack', ['class' => ['nav-icon']]),
                            'active' => Yii::$app->controller->id === 'lesson-test' ||
                                Yii::$app->controller->id === 'lesson-test-options' ||
                                Yii::$app->controller->id === 'lesson-test-attempt' ||
                                Yii::$app->controller->id === 'lesson-content',
                            'visible' => canAnyRoute([
                                '/elearning/lesson-test/*',
                                '/elearning/lesson-content/*',
                                '/elearning/lesson-test-options/*',
                            ]),
                            'items' => [
                                [
                                    'label' => Yii::t('backend', 'Create Test Question'),
                                    'url' => ['/elearning/lesson-content/create'],
                                    'icon' => FAS::icon('plus', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->action->id === 'create',
                                    'visible' => canAnyRoute(['/elearning/lesson-content/create']),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Test Questions'),
                                    'url' => ['/elearning/lesson-content/index'],
                                    'icon' => FAS::icon('highlighter', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'lesson-content',
                                    'visible' => canAnyRoute(['/elearning/lesson-content/index', '/elearning/lesson-content/*']),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Test Question Options'),
                                    'url' => ['/elearning/lesson-test-options/index'],
                                    'icon' => FAS::icon('list', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'lesson-test-options',
                                    'visible' => canAnyRoute(['/elearning/lesson-test-options/index', '/elearning/lesson-test-options/*']),
                                ],
                            ],
                        ],

                        // ASSESSMENT SINGLE
                        [
                            'label' => Yii::t('backend', 'Assessment'),
                            'url' => ['/assessment/index'],
                            'icon' => FAR::icon('clipboard', ['class' => ['nav-icon']]),
                            'badge' => $assessUnread,
                            'badgeBgClass' => 'badge-info',
                            'linkTemplate' => '<a href="{url}">{icon}<p>{label} <span class="right badge badge-info" id="left-assess-badge">' . $assessUnread . '</span></p></a>',
                            'active' => Yii::$app->controller->id === 'assessment',
                            'visible' => canAnyRoute([
                                '/assessment/*',
                                '/assessment/index',
                            ]),
                        ],

                        // FORM SUBMISSIONS
                        [
                            'label' => Yii::t('backend', 'Form Submissions'),
                            'options' => ['class' => 'nav-header'],
                            'visible' => canAnyRoute([
                                '/contact-queries/index',
                                '/newsletter/index',
                            ]),
                        ],
                        // [
                        //     'label' => Yii::t('backend', 'Contact Queries'),
                        //     'url' => ['/contact-queries/index'],
                        //     'icon' => FAR::icon('clipboard', ['class' => ['nav-icon']]),
                        //     'active' => Yii::$app->controller->id === 'contact-queries',
                        //     'visible' => canAnyRoute(['/contact-queries/index', '/contact-queries/*']),
                        // ],
                         

                        [
                            'label' => Yii::t('backend', 'Contact Queries'),
                            'url'   => ['/contact-queries/index'],
                            'icon'  => \rmrevin\yii\fontawesome\FAR::icon('clipboard', ['class' => ['nav-icon']]),
                            'active'  => Yii::$app->controller->id === 'contact-queries',
                            'visible' => canAnyRoute(['/contact-queries/index', '/contact-queries/*']),
                            'linkTemplate' =>
                                '<a href="{url}">{icon}<p>{label} '.
                                '<span class="right badge badge-info" id="left-contact-badge">' .
                                (int)$contactUnread .
                                '</span></p></a>',
                        ],
                        


                        [
                            'label' => Yii::t('backend', 'Newsletter'),
                            'url' => ['/newsletter/index'],
                            'icon' => FAR::icon('clipboard', ['class' => ['nav-icon']]),
                            'badge' => $newsUnread,
                            'badgeBgClass' => 'badge-info',
                            'linkTemplate' =>
                                '<a href="{url}">{icon}<p>{label} <span class="right badge badge-info" id="left-news-badge">' . $newsUnread . '</span></p></a>',
                            'active' => Yii::$app->controller->id === 'newsletter',
                            'visible' => canAnyRoute(['/newsletter/index', '/newsletter/*']),
                        ],

                        // CONTENT HEADER
                        
                        
                        
                        [
                            'label' => Yii::t('backend', 'Articles'),
                            'url'   => '#',
                            'icon'  => FAS::icon('newspaper', ['class' => ['nav-icon']]),
                            'options' => ['class' => 'nav-item has-treeview'],
                            'active' => 'content' === Yii::$app->controller->module->id &&
                                (Yii::$app->controller->id === 'article' || Yii::$app->controller->id === 'category'),
                            'visible' => $isSA || canAnyRoute([
                                '/content/article/index',
                                '/content/article/*',
                            ]),
                            'items' => [
                                [
                                    'label' => Yii::t('backend', 'Articles'),
                                    'url'   => ['/content/article/index'],
                                    'icon'  => FAR::icon('circle', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'article',
                                    'visible' => $isSA || canAnyRoute([
                                        '/content/article/index',
                                        '/content/article/*',
                                    ]),
                                ],
                            ],
                        ],

                        [
                            'label' => Yii::t('backend', 'Widgets'),
                            'url'   => '#',
                            'icon'  => FAS::icon('puzzle-piece', ['class' => ['nav-icon']]),
                            'options' => ['class' => 'nav-item has-treeview'],
                            'active' => Yii::$app->controller->module->id === 'widget',
                            'visible' => $isSA || canAnyRoute([
                                '/widget/text/*',
                                '/widget/menu/*',
                                '/widget/carousel/*',
                            ]),
                            'items' => [
                                [
                                    'label' => Yii::t('backend', 'Text Blocks'),
                                    'url'   => ['/widget/text/index'],
                                    'icon'  => FAR::icon('circle', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'text',
                                    'visible' => $isSA || canAnyRoute(['/widget/text/index', '/widget/text/*']),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Menu'),
                                    'url'   => ['/widget/menu/index'],
                                    'icon'  => FAR::icon('circle', ['class' => ['nav-icon']]),
                                    'active' => Yii::$app->controller->id === 'menu',
                                    'visible' => $isSA || canAnyRoute(['/widget/menu/index', '/widget/menu/*']),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Carousel'),
                                    'url'   => ['/widget/carousel/index'],
                                    'icon'  => FAR::icon('circle', ['class' => ['nav-icon']]),
                                    'active' => in_array(Yii::$app->controller->id, ['carousel', 'carousel-item']),
                                    'visible' => $isSA || canAnyRoute(['/widget/carousel/index', '/widget/carousel/*']),
                                ],
                            ],
                        ],


                       [
                            'label'   => Yii::t('backend', 'Translation'),
                            'options' => ['class' => 'nav-header'],
                            'visible' => $isSA &&
                                Yii::$app->components["i18n"]["translations"]['*']['class'] === \yii\i18n\DbMessageSource::class,
                        ],
                         
                        [
                            'label' => Yii::t('backend', 'Translation'),
                            'url'   => ['/translation/default/index'],
                            'icon'  => FAS::icon('language', ['class' => ['nav-icon']]),
                            'active' => (Yii::$app->controller->module->id == 'translation'),
                            'visible' => $isSA &&
                                Yii::$app->components["i18n"]["translations"]['*']['class'] === \yii\i18n\DbMessageSource::class,
                        ],
 
                        [
                            'label'   => Yii::t('backend', 'System'),
                            'options' => ['class' => 'nav-header'],
                            'visible' => $isSA || canAnyRoute([
                                '/file/storage/*',
                                '/file/manager/*',
                                '/system/key-storage/*',
                                '/system/cache/*',
                                '/system/log/*',
                            ]),
                        ],
                         
                        [
                            'label' => Yii::t('backend', 'Files'),
                            'url'   => '#',
                            'icon'  => FAS::icon('folder-open', ['class' => ['nav-icon']]),
                            'options' => ['class' => 'nav-item has-treeview'],
                            'active'  => (Yii::$app->controller->module->id == 'file'),
                            'visible' => $isSA || canAnyRoute([
                                '/file/storage/*',
                                '/file/manager/*',
                            ]),
                            'items' => [
                                [
                                    'label' => Yii::t('backend', 'Storage'),
                                    'url'   => ['/file/storage/index'],
                                    'icon'  => FAS::icon('database', ['class' => ['nav-icon']]),
                                    'active' => (Yii::$app->controller->id == 'storage'),
                                    'visible' => $isSA || canAnyRoute(['/file/storage/index', '/file/storage/*']),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Manager'),
                                    'url'   => ['/file/manager/index'],
                                    'icon'  => FAS::icon('archive', ['class' => ['nav-icon']]),
                                    'active' => (Yii::$app->controller->id == 'manager'),
                                    'visible' => $isSA || canAnyRoute(['/file/manager/index', '/file/manager/*']),
                                ],
                            ],
                        ],
                       [
                            'label' => Yii::t('backend', 'Key-Value Storage'),
                            'url'   => ['/system/key-storage/index'],
                            'icon'  => FAS::icon('exchange-alt', ['class' => ['nav-icon']]),
                            'active' => (Yii::$app->controller->id == 'key-storage'),
                            'visible' => $isSA || canAnyRoute(['/system/key-storage/index', '/system/key-storage/*']),
                        ],
                       [
                            'label' => Yii::t('backend', 'Cache'),
                            'url'   => ['/system/cache/index'],
                            'icon'  => FAS::icon('sync', ['class' => ['nav-icon']]),
                            'visible' => $isSA || canAnyRoute(['/system/cache/index', '/system/cache/*']),
                        ],
                      [
                            'label' => Yii::t('backend', 'Logs'),
                            'url'   => ['/system/log/index'],
                            'icon'  => FAS::icon('clipboard-list', ['class' => ['nav-icon']]),
                            'badge' => SystemLog::find()->count(),
                            'badgeBgClass' => 'badge-danger',
                            'visible' => $isSA || canAnyRoute(['/system/log/index', '/system/log/*']),
                        ],


                       [
                        'label'   => Yii::t('backend', 'User Management'),
                        'options' => ['class' => 'nav-header'],
                        'visible' => $isSA || canAnyRoute([
                            '/user-management/user/*',
                            '/user-management/role/*',
                            '/user-management/permission/*',
                            '/user-management/auth-item-group/*',
                        ]),
                    ],

                      [
                            'label' => Yii::t('backend', 'User'),
                            'url'   => ['/user-management/user/index'],
                            'icon'  => FAS::icon('user', ['class' => ['nav-icon']]),
                            'active'  => Yii::$app->controller->id === 'user',
                            'visible' => $isSA || canAnyRoute(['/user-management/user/index', '/user-management/user/*']),
                        ],
                        [
                            'label' => Yii::t('backend', 'RBAC'),
                            'url'   => '#',
                            'icon'  => FAS::icon('newspaper', ['class' => ['nav-icon']]),
                            'options' => ['class' => 'nav-item has-treeview'],
                            'active' => Yii::$app->controller->module->id === 'user-management' &&
                                in_array(Yii::$app->controller->id, ['role', 'permission', 'auth-item-group']),
                            'visible' => $isSA || canAnyRoute([
                                '/user-management/role/*',
                                '/user-management/permission/*',
                                '/user-management/auth-item-group/*',
                            ]),
                            'items' => [
                                [
                                    'label' => Yii::t('backend', 'Role'),
                                    'url'   => ['/user-management/role/index'],
                                    'icon'  => FAR::icon('circle', ['class' => ['nav-icon']]),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Permissions'),
                                    'url'   => ['/user-management/permission/index'],
                                    'icon'  => FAR::icon('circle', ['class' => ['nav-icon']]),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Permission group'),
                                    'url'   => ['/user-management/auth-item-group/index'],
                                    'icon'  => FAR::icon('circle', ['class' => ['nav-icon']]),
                                ],
                            ],
                        ],

                    ],
                ]) ?>
            </nav>
            <!-- /sidebar menu -->
        </div>
        <!-- /sidebar -->
    </aside>
    <!-- /main sidebar -->

    <!-- content wrapper -->
    <div class="content-wrapper" style="min-height: 402px;">
        <!-- content header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><?php echo Html::encode($this->title) ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <?php echo Breadcrumbs::widget([
                            'tag' => 'ol',
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            'options' => ['class' => ['breadcrumb', 'float-sm-right']]
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- /content header -->

        <!-- main content -->
        <section class="content">
            <div class="container-fluid">
                <?php if (Yii::$app->session->hasFlash('alert')) : ?>
                    <?php echo Alert::widget([
                        'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
                        'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
                    ]) ?>
                <?php endif; ?>
                <?php echo $content ?>
            </div>
        </section>
        <!-- /main content -->

        <?php echo Html::a(FAS::icon('chevron-up'), '#', [
            'class' => ['btn', 'btn-primary', 'back-to-top'],
            'role' => 'button',
            'aria-label' => 'Scroll to top',
        ]) ?>
    </div>
    <!-- /content wrapper -->

    <!-- footer -->
    <footer class="main-footer <?php echo $keyStorage->get('adminlte.footer-small-text') ? 'text-sm' : null ?>">
        <strong>&copy; A+ Students, All rights reserved <?php echo date('Y') ?></strong>
        <div class="float-right d-none d-sm-inline-block"><?php echo Yii::powered() ?></div>
    </footer>
    <!-- /footer -->

    <?php //if (Yii::$app->user->can('administrator')) : ?>
    <!-- control sidebar -->
    <div class="control-sidebar control-sidebar-dark overflow-auto">
        <div class="control-sidebar-content p-3">
            <?php echo FormWidget::widget([
                'model' => new FormModel([
                    'keys' => [
                        'frontend.options' => [
                            'type' => FormModel::TYPE_HEADER,
                            'content' => 'Frontend Options'
                        ],
                        'frontend.maintenance' => [
                            'label' => Yii::t('backend', 'Maintenance mode'),
                            'type' => FormModel::TYPE_DROPDOWN,
                            'items' => [
                                'disabled' => Yii::t('backend', 'Disabled'),
                                'enabled' => Yii::t('backend', 'Enabled'),
                            ],
                        ],
                        'backend.options' => [
                            'type' => FormModel::TYPE_HEADER,
                            'content' => 'Backend Options'
                        ],
                        'adminlte.body-small-text' => [
                            'label' => Yii::t('backend', 'Body small text'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.no-navbar-border' => [
                            'label' => Yii::t('backend', 'No navbar border'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.navbar-small-text' => [
                            'label' => Yii::t('backend', 'Navbar small text'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.navbar-fixed' => [
                            'label' => Yii::t('backend', 'Fixed navbar'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.footer-small-text' => [
                            'label' => Yii::t('backend', 'Footer small text'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.footer-fixed' => [
                            'label' => Yii::t('backend', 'Fixed footer'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-small-text' => [
                            'label' => Yii::t('backend', 'Sidebar small text'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-flat' => [
                            'label' => Yii::t('backend', 'Sidebar flat style'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-legacy' => [
                            'label' => Yii::t('backend', 'Sidebar legacy style'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-compact' => [
                            'label' => Yii::t('backend', 'Sidebar compact style'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-fixed' => [
                            'label' => Yii::t('backend', 'Fixed sidebar'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-collapsed' => [
                            'label' => Yii::t('backend', 'Collapsed sidebar'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-mini' => [
                            'label' => Yii::t('backend', 'Mini sidebar'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-child-indent' => [
                            'label' => Yii::t('backend', 'Indent sidebar child menu items'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-no-expand' => [
                            'label' => Yii::t('backend', 'Disable sidebar hover/focus auto expand'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.brand-small-text' => [
                            'label' => Yii::t('backend', 'Brand small text'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                    ],
                ]),
                'submitText' => FAS::icon('save').' '.Yii::t('backend', 'Save'),
                'submitOptions' => ['class' => 'btn btn-primary'],
                'formOptions' => [
                    'action' => ['/system/settings/index'],
                    'method' => 'post'
                ],
            ]) ?>
        </div>
    </div>
    <!-- /control sidebar -->
    <?php //endif; ?>
     <link href="/css/custom.css" rel="stylesheet">
</div>
<?php $this->endContent(); ?>




<?php
$pingUrl = \yii\helpers\Url::to(['/notification/ping']);
$js = <<<JS
function refreshNotif(){
  $.getJSON('{$pingUrl}', function(r){
    if (!r) return;
    // top bell
    $('#notif-count').text(r.count);

    // dropdown body
    if (r.itemsHtml) {
      $('#notif-count').closest('li.nav-item').find('.dropdown-menu').html(r.itemsHtml);
    }

    // left menu bubbles
    if (typeof r.assessLeft !== 'undefined') {
      $('#left-assess-badge').text(r.assessLeft);
    }
    if (typeof r.newsletterLeft !== 'undefined') {
      $('#left-news-badge').text(r.newsletterLeft);
    }
    if (typeof r.contactLeft !== 'undefined') {
    $('#left-contact-badge').text(r.contactLeft);
    }



  });
}
setInterval(refreshNotif, 30000);
JS;
$this->registerJs($js);


?>

 