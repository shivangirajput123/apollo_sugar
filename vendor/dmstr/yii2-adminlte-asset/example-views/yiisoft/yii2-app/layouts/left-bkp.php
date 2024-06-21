<?php
use yii\helpers\Url;
$baseurl = Url::base();


?>


<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo $baseurl.'/logos/apollo-logo.png' ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>  <?php echo Yii::$app->user->identity->username;?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                   
                    
						[
                		'label' => 'Dashboard',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'user',
                		'url' => ['/site/index'],
                		
                		],
							[
                		'label' => 'Banners',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'user',
                		'url' => ['/banners/mobilebanners'],
                		
                		],
						[
                		'label' => 'Notifications',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'user',
                		'url' => '#',
						'items' => [
                                    ['label' => 'Notification Type', 'icon' => 'plus-circle', 'url' => ['/notifications/notificationtypes'],
                                   ],
								   [
                		'label' => 'Notifications',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'user',
                		'url' => ['/notifications/notifications'],
                		
                		],
                		
								   ],
                                    
                		
                		],
						
						[
                                                'label' => 'Customer',
                                                //'class' => 'fa fa-user-md',
                                                'icon' => 'user',
                                                'url' => ['/users/userprofile'],
                                                
                                                ],
						[
                            'label' => 'Food Items',
                            //'class' => 'fa fa-user-md',
                            'icon' => 'user',
                            'url' => ['/common/fooditems'],                            
                        ],
                		
					[
                		'label' => 'Webinars',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'user',
                		'url' => ['/webinar/webinars'],
                		
                		],
                   
                ],
            ]
        ) ?>

    </section>

</aside>
