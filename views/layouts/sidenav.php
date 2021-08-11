

<?php
\Yii::$app->language = 'uk-UK';

use kartik\sidenav\SideNav;

$this->beginContent('@app/views/layouts/main.php'); ?>


<div class="row" id="nav-main">
    <div class="col-md-2">
        <div class="pg-sidebar tt">


            <?php
            if (!Yii::$app->user->isGuest) {
                /*
                //элементы навбара общие для всех
                $items =[

                        [
                            'url' => ['site/index'],
                            'label' => 'Профіль',
                            'icon' => 'glyphicon glyphicon-user'
                        ],


                ];
*/
                //if (Yii::$app->user->can('system')) {
                    $items[] = [
                        'label' => 'Мій кабінет',
                        'icon' => 'glyphicon glyphicon-home',
                        'items' => [
                            ['label' => 'Профіль', 'icon' => 'glyphicon glyphicon-user', 'url' => ['site/index']],
                            ['label' => 'Календарний графік', 'icon' => 'glyphicon glyphicon-calendar', 'url' => ['site/calendar']],
                            ['label' => 'Повідомлення', 'icon' => 'glyphicon glyphicon-alert', 'url' => ['site/news']],

                        ],
                    ];

               // }

                if (Yii::$app->user->can('system')) {
                    $items[] = [
                        'label' => 'Табельщик',
                        'icon' => 'glyphicon glyphicon-calendar',
                        'items' => [
                            ['label' => 'Календарний графік НВК', 'icon' => 'glyphicon glyphicon-calendar', 'url' => ['site/calendar-set']],
                            ['label' => 'Табелі', 'icon' => 'glyphicon glyphicon-calendar', 'url' => ['site/calendar-report-all']],
                            ['label' => 'Список на неділю', 'icon' => 'glyphicon glyphicon-calendar', 'url' => ['site/week-report']],
                            ['label' => 'Абривіатура для табелю', 'icon' => 'glyphicon glyphicon-text-height', 'url' => ['time-table/index']],

                        ],
                    ];

                }


                if (Yii::$app->user->can('setCalendar')){// || Yii::$app->user->can('taskForSector')) {
                    $items[] = [
                        'label' => 'Робочий час',
                        'icon' => 'glyphicon glyphicon-calendar',
                        'items' => [
                            ['label' => 'Календарний графік робітника', 'icon' => 'glyphicon glyphicon-calendar', 'url' => ['/personal-calendar/index']],
                            ['label' => 'Шаблони', 'icon' => 'glyphicon glyphicon-calendar', 'url' => ['/calendar-pattern-name/index']],
                            //['label' => 'Табель', 'icon' => 'glyphicon glyphicon-calendar', 'url' => ['site/calendar-report']],
                            ['label' => 'Табелі', 'icon' => 'glyphicon glyphicon-calendar', 'url' => ['site/calendar-report-all']],

                        ],
                    ];

                }
/*
                if (Yii::$app->user->can('system')) {
                    $items[] = [
                        'url' => ['norm-name/index','id'=>112],
                        'label' => 'Розрахзунок норм ',
                        'icon' => 'glyphicon glyphicon-sunglasses'
                    ];
                }
*/
                if(Yii::$app->user->can('system')){
                    $url = ['/workers/index'];
                }else $url = ['/workers/index'];

                if (Yii::$app->user->can('planning') || Yii::$app->user->can('viewPlanning')) {
                   // if(Yii::$app->user->id == 1 || Yii::$app->user->id == 119) {
                        $items[] = [
                            'label' => 'Планування',
                            'icon' => 'glyphicon glyphicon-education',
                            'items' => [
                                ['label' => 'План-графік', 'icon' => 'glyphicon glyphicon-education', 'url' => ['/planning/index']],
                                ['label' => 'Загрузка штату', 'icon' => 'glyphicon glyphicon-signal', 'url' => $url],
                                //  ['label' => 'Роботи по секторам', 'icon' => 'glyphicon glyphicon-time', 'url' => ['/planning/sector']],
                                //  ['label' => 'Test1', 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['site/test1']],
                                ['label' => 'Табелі', 'icon' => 'glyphicon glyphicon-calendar', 'url' => ['site/calendar-report-all']],

                            ],
                        ];
                  //  }

                }



                if (Yii::$app->user->can('updateTerms')) {
                    $items[] = [
                        // 'url' => ['plan/index'],
                        'label' => 'Індивідуальний план',
                        'icon' => 'glyphicon glyphicon-bell', //glyphicon-briefcase'
                        'items' => [
                            ['label' => 'Поточний місяць ' . date('m. Y'), 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['personal-plan/index', 'm'=>'current']],
                            ['label' => 'Наступний місяць ', 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['personal-plan/index', 'm'=>'next']],
                            ['label' => 'Минулий місяць', 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['personal-plan/index', 'm'=>'last']],
                            ['label' => 'Всі', 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['personal-plan/index', 'm' => 'all']],

                        ],
                    ];

                }


                if (Yii::$app->user->can('editNorms')) {
                    $items[] = [
                        // 'url' => ['plan/index'],
                        'label' => 'Нормування',
                        'icon' => 'glyphicon glyphicon-time', //glyphicon-briefcase'
                        'items' => [
                            ['label' => 'Норми', 'icon' => 'glyphicon glyphicon-time', 'url' => ['norm-name-input/index']],
                            ['label' => 'Одиниці нормування', 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['normunit/index']],

                        ],
                    ];

                }

                /*
                if (Yii::$app->user->can('uploadFiles')) {
                    $items[] = [

                        'label' => 'Мої розробки',
                        'icon' => 'glyphicon glyphicon-briefcase',
                        'items' => [
                            ['label' => 'Креслення', 'icon' => 'glyphicon glyphicon-cog', 'url' => ['driver/index']],
                        ],
                    ];

                }
*/
/*
                if (Yii::$app->user->can('editNorms')) {
                    $items[] = [

                        'label' => 'Нормування',
                        'icon' => 'glyphicon glyphicon-stats',
                        'items' => [
                            ['label' => 'Норми', 'icon' => 'glyphicon glyphicon-hand-right', 'url' => ['norms/index']],
                            ['label' => 'Одиниці нормування', 'icon' => 'glyphicon glyphicon-hand-right', 'url' => ['normunit/index']],
                        ],
                    ];

                }
*/
                if (Yii::$app->user->can('editTask')) {
                    $items[] = [

                        'label' => 'Зміст робіт сектора',
                        'icon' => 'glyphicon glyphicon-list',
                        'items' => [
                            ['label' => 'Drivers', 'icon' => 'glyphicon glyphicon-hand-right', 'url' => ['driver/index']],
                        ],
                    ];

                }


                if (Yii::$app->user->can('viewFact')) {
                    $items[] = [

                        'label' => 'План-звіт',
                        'icon' => 'glyphicon glyphicon-list',
                        'items' => [
                           // ['label' => 'План-Графік', 'icon' => 'glyphicon glyphicon-hand-right', 'url' => ['driver/index']],
                           // ['label' => ' План-Звіт', 'icon' => 'glyphicon glyphicon-hand-left', 'url' => ['personal-plan-main/index']],
                            ['label' => 'Поточний місяць '. date('m. Y'), 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['personal-plan-main/index', 'm'=>'current']],
                            ['label' => 'Наступний місяць', 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['personal-plan-main/index', 'm'=>'next']],
                            ['label' => 'Минулий місяць', 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['personal-plan-main/index', 'm'=>'last']],
                            ['label' => 'Всі', 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['personal-plan-main/index','m'=>'all']],

                        ],
                    ];

                }


                //if (Yii::$app->user->can('system')) {
                    $items[] = [

                        'label' => 'Дерево виробу',
                        'icon' => 'glyphicon glyphicon-tree-conifer',
                        'items' => [
                             ['label' => 'Дерево по темам', 'icon' => 'glyphicon glyphicon-tree-conifer', 'url' => ['main-tree/index']],
                            // ['label' => ' План-Звіт', 'icon' => 'glyphicon glyphicon-hand-left', 'url' => ['personal-plan-main/index']],

                        ],
                    ];

                //}


//Темы
                if (Yii::$app->user->can('editThemes')) {
                    $items[] = [
                        'url' => ['theme/index'],
                        'label' => 'Теми',
                        'icon' => 'glyphicon glyphicon-th-list'
                    ];
                }

//Структура

                if (Yii::$app->user->can('editStructs')) {
                    $items[] = [
                        'url' => ['structs/index'],
                        'label' => 'Структури',
                        'icon' => 'glyphicon glyphicon-th-large'
                    ];
                }

                //Підрозділи
                if (Yii::$app->user->can('editDivs')) {
                    $items[] = [
                        'url' => ['div/index'],
                        'label' => 'Відділи',
                        'icon' => 'glyphicon glyphicon-th'
                    ];
                }

                //Сектори
                if (Yii::$app->user->can('editSectors')) {
                    $items[] = [
                        'url' => ['sectors/index'],
                        'label' => 'Сектори',
                        'icon' => 'glyphicon glyphicon-list-alt'
                    ];
                }

                //Персоны
                if (Yii::$app->user->can('viewResidents')) {
                    $items[] = [
                        'url' => ['resident/index'],
                        'label' => 'Штат',
                        'icon' => 'glyphicon glyphicon-sunglasses'
                    ];
                }
//
                if (Yii::$app->user->can('viewSector')) {
                    $items[] = [
                        'url' => ['resident/index'],
                        'label' => 'Штат сектору',
                        'icon' => 'glyphicon glyphicon-sunglasses'
                    ];
                }

/*
                //Посади
                if (Yii::$app->user->can('assignRole')) {
                    $items[] = [
                        'url' => ['posada/index'],
                        'label' => 'Посади',
                        'icon' => 'glyphicon glyphicon-hand-right'
                    ];
                }


                //просмотр користувачів
                if (Yii::$app->user->can('viewUsers')) {
                    $items[] = [
                        'url' => ['userumbrella/index'],
                        'label' => 'Користувачі',
                        'icon' => 'glyphicon glyphicon-user'
                    ];
                }
*/


// Нач. сектора
                if (Yii::$app->user->can('taskForSector')) {
                    $items[] = [

                        'label' => 'Зміст робіт сектору',
                        'icon' => 'glyphicon glyphicon-inbox',
                        'items' => [
                            ['label' => 'Поточний місяць '. date('m. Y'), 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['personal-plan-sector/index', 'm'=>'current']],
                            ['label' => 'Наступний місяць', 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['personal-plan-sector/index', 'm'=>'next']],
                            ['label' => 'Минулий місяць', 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['personal-plan-sector/index', 'm'=>'last']],
                            ['label' => 'Всі', 'icon' => 'glyphicon glyphicon-th-list', 'url' => ['personal-plan-sector/index','m'=>'all']],

                        ],
                    ];

                }



                echo SideNav::widget([
                    'type' => SideNav::TYPE_DEFAULT,
                    'heading' => 'Панель користувача ' . Yii::$app->user->identity->username,//'SPAMOTRON panel',
                    'items' =>

                        $items,

                ]);
            }
            ?>

            <?= $this->blocks['toolbar']; ?>
        </div>
    </div>
    <div class="col-md-10">
        <?= $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>