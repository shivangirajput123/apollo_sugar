<?php
use yii\helpers\Html;
use yii\helpers\Url;

$baseurl = Url::base();
/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                
       
                <!-- Tasks: style can be found in dropdown.less -->
             
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="https://apollosugar.com/wp-content/uploads/2020/09/Apollo-Sugar-New-Logo-1-e1598960920160.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?php echo Yii::$app->user->identity->username;?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="https://apollosugar.com/wp-content/uploads/2020/09/Apollo-Sugar-New-Logo-1-e1598960920160.jpg" class="img-circle"
                                 alt="User Image"/>

                            <p>
                               <?php echo Yii::$app->user->identity->username;?>
                               
                            </p>
                        </li>
                        <!-- Menu Body -->
       
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <div class="pull-left">
                                  <?= Html::a(
                                    'change-password',
                                    ['/site/change-password','id'=>Yii::$app->user->identity->id],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

              
            </ul>
        </div>
    </nav>
</header>
