<?php
use yii\helpers\Url;
$baseurl = Url::base();


?>


<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="https://apollosugar.com/wp-content/uploads/2020/09/Apollo-Sugar-New-Logo-1-e1598960920160.jpg" class="img-circle" style="max-width: none !important;" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>  <?php echo Yii::$app->user->identity->username;?></p>

                </div>
        </div>

      
      <!-- /.search form -->
	<?php if(Yii::$app->user->identity->roleId == 4){?>
			<?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [					
                       [
                            'label' => 'Patients',
                            //'class' => 'fa fa-user-md',
                            'icon' => 'user',
                            'url' => ['/users/userprofile'],
                        ],
						
                   
                ],
            ]
	) ?>
	<?php }
	elseif(Yii::$app->user->identity->roleId == 5){?>
		
			<?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [					
                       [
                            'label' => 'Patients',
                            //'class' => 'fa fa-user-md',
                            'icon' => 'user',
                            'url' => ['/users/userprofile'],
                        ],
						
                   
                ],
            ]
        ) ?>
	
	<?php }elseif(Yii::$app->user->identity->roleId == 6){ ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                   [
                		'label' => 'Dashboard',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'dashboard',
                		'url' => ['/site/index'],
                		
                		],
                      
						
						
						[
                		'label' => 'New Patients',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'adn',
                		'url' => ['/clinics/clinics/planapproval'],
                		
                		],
						[
                		'label' => 'Patients',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'adn',
                		'url' => ['/callcentre/callcentre/patients'],
                		
                		],
                          [
                            'label' => 'Users',
                            //'class' => 'fa fa-user-md',
                            'icon' => 'user-plus',
                            'url' => '#',
                            'items' => [
                                    
                                    [
                                        'label' => 'Doctors',
                                        //'class' => 'fa fa-user-md',
                                        'icon' => 'user-md',
                                        'url' => ['/users/doctors'],
                                        
                                        ],
                                        [
                                            'label' => 'Dietician',
                                            //'class' => 'fa fa-user-md',
                                            'icon' => 'user-md',
                                            'url' => ['/users/dietician'],
                                            
                                            ],
                                         /*   [
                                                'label' => 'Coach',
                                                //'class' => 'fa fa-user-md',
                                                'icon' => 'user-md',
                                                'url' => ['/users/coach'],
                                                
                                                ],*/
												
                                     
                            ],
                        ],
						
						
						
						[
                                        'label' => 'Services',
                                        //'class' => 'fa fa-user-md',
                                        'icon' => 'briefcase',
                                        'url' => ['/packages/packages'],
                                       
                                        ],
						[
                            'label' => 'Sugar Programs',
                            //'class' => 'fa fa-user-md',
                            'icon' => 'fort-awesome',
                            'url' => ['/packages/plans'],                           
                                       
                                     
                            
                        ],
						
					[
                		'label' => 'Webinars',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'youtube-square',
                		'url' => ['/webinar/webinars'],
                		
                		],
                   
                ],
            ]
        ) ?>
	
	<?php }elseif(Yii::$app->user->identity->roleId == 7){ ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                   [
                		'label' => 'Dashboard',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'dashboard',
                		'url' => ['/site/index'],
                		
                		],
                      
						
						
						[
                		'label' => 'Patients',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'adn',
                		'url' => ['/callcentre/callcentre/patients'],
                		
                		],
						
                          [
                            'label' => 'Users',
                            //'class' => 'fa fa-user-md',
                            'icon' => 'user-plus',
                            'url' => '#',
                            'items' => [
                                    
                                    [
                                        'label' => 'Doctors',
                                        //'class' => 'fa fa-user-md',
                                        'icon' => 'user-md',
                                        'url' => ['/users/doctors'],
                                        
                                        ],
                                        [
                                            'label' => 'Dietician',
                                            //'class' => 'fa fa-user-md',
                                            'icon' => 'user-md',
                                            'url' => ['/users/dietician'],
                                            
                                            ],
                                         /*   [
                                                'label' => 'Coach',
                                                //'class' => 'fa fa-user-md',
                                                'icon' => 'user-md',
                                                'url' => ['/users/coach'],
                                                
                                                ],*/
												
                                     
                            ],
                        ],
						
						
						
						[
                                        'label' => 'Services',
                                        //'class' => 'fa fa-user-md',
                                        'icon' => 'briefcase',
                                        'url' => ['/packages/packages'],
                                       
                                        ],
						[
                            'label' => 'Sugar Programs',
                            //'class' => 'fa fa-user-md',
                            'icon' => 'fort-awesome',
                            'url' => ['/packages/plans'],                           
                                       
                                     
                            
                        ],
						
					[
                		'label' => 'Webinars',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'youtube-square',
                		'url' => ['/webinar/webinars'],
                		
                		],
                   
                ],
            ]
        ) ?>
	
	<?php }else{ ?>
	
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                   [
                		'label' => 'Dashboard',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'dashboard',
                		'url' => ['/site/index'],
                		
                		],
                        [
                		'label' => 'Roles',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'users',
                		'url' => ['/roles/roles'],
                		
                		],
						[
                		'label' => 'Franchise',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'users',
                		'url' => ['/franchies/franchies'],
                		
                		],
						 [
                		'label' => 'Clinics',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'users',
                		'url' => ['/clinics/clinics'],
                		
                		],
						[
                		'label' => 'Call Centre',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'users',
                		'url' => ['/callcentre/callcentre'],
                		
                		],
						  
						[
                		'label' => 'Articles',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'adn',
                		'url' => ['/article/articles'],
                		
                		],
						[
                		'label' => 'Notifications',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'bell',
                		'url' => ['/notifications/notifications'],
					/*	'items' => [
                                    ['label' => 'Notification Type', 'icon' => 'plus-circle', 'url' => ['/notifications/notificationtypes'],
                                   ],
								   [
                		'label' => 'Notifications',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'user',
                		'url' => ['/notifications/notifications'],
                		
                		],
                		
								   ],*/
                                    
                		
                		],
                		[
                		'label' => 'Banners',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'file-image-o',
                		'url' => ['/banners/mobilebanners'],
                		
                		],
                        [
                            'label' => 'Masters',
                            //'class' => 'fa fa-user-md',
                            'icon' => 'cc-mastercard',
                            'url' => '#',
                            'items' => [
                                    ['label' => 'Reading Type', 'icon' => 'info', 'url' => ['/common/readingtype'],
                                   
                                    ],
									[
                		'label' => 'Medicines',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'medkit',
                		'url' => ['/common/medicinemaster'],
                		
                		],
						 [
                		'label' => 'Labtests',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'hospital-o',
                		'url' => ['/common/labtests'],
                		
                		],
                                    [
                                        'label' => 'MealType',
                                        //'class' => 'fa fa-user-md',
                                        'icon' => 'info-circle',
                                        'url' => ['/common/mealtype']
                                        
                                        ],
                                        
												
												[
                                                'label' => 'Categories',
                                                //'class' => 'fa fa-user-md',
                                                'icon' => 'tags',
                                                'url' => ['/common/categories'],
                                                
                                                ],
												
												[
                                                'label' => 'Exercises',
                                                //'class' => 'fa fa-user-md',
                                                'icon' => 'universal-access',
                                                'url' => ['/common/excercise'],
                                               
                                                ],
												[
                                                'label' => 'Usages',
                                                //'class' => 'fa fa-user-md',
                                                'icon' => 'vcard',
                                                'url' => ['/common/usagemaster'],
                                               
                                                ],
												[
                                                'label' => 'Durations',
                                                //'class' => 'fa fa-user-md',
                                                'icon' => 'calendar',
                                                'url' => ['/common/durations'],
                                               
                                                ],
												[
                            'label' => 'Inclusions',
                            //'class' => 'fa fa-user-md',
                            'icon' => 'envelope',
                            'url' => ['/packages/itemdetails']
                           
                        ],
						[
                            'label' => 'Food Items',
                            //'class' => 'fa fa-user-md',
                            'icon' => 'apple',
                            'url' => ['/common/fooditems'],                            
                        ],
                		
                                     
                            ],
                            ],
                          [
                            'label' => 'Users',
                            //'class' => 'fa fa-user-md',
                            'icon' => 'user-plus',
                            'url' => '#',
                            'items' => [
                                    
                                    [
                                        'label' => 'Doctors',
                                        //'class' => 'fa fa-user-md',
                                        'icon' => 'user-md',
                                        'url' => ['/users/doctors'],
                                        
                                        ],
                                        [
                                            'label' => 'Dietician',
                                            //'class' => 'fa fa-user-md',
                                            'icon' => 'user-md',
                                            'url' => ['/users/dietician'],
                                            
                                            ],
                                         /*   [
                                                'label' => 'Coach',
                                                //'class' => 'fa fa-user-md',
                                                'icon' => 'user-md',
                                                'url' => ['/users/coach'],
                                                
                                                ],*/
												
                                     
                            ],
                        ],
						[
                                                'label' => 'Customer',
                                                //'class' => 'fa fa-user-md',
                                                'icon' => 'user',
                                                'url' => ['/users/userprofile'],
                                                
                                                ],
						
						
						[
                                        'label' => 'Services',
                                        //'class' => 'fa fa-user-md',
                                        'icon' => 'briefcase',
                                        'url' => ['/packages/packages'],
                                       
                                        ],
						[
                            'label' => 'Sugar Programs',
                            //'class' => 'fa fa-user-md',
                            'icon' => 'fort-awesome',
                            'url' => ['/packages/plans'],                           
                                       
                                     
                            
                        ],
						
					[
                		'label' => 'Webinars',
                		//'class' => 'fa fa-user-md',
                		'icon' => 'youtube-square',
                		'url' => ['/webinar/webinars'],
                		
                		],
                   
                ],
            ]
        ) ?>
	<?php } ?>
    </section>

</aside>
