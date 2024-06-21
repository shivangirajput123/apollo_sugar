<?php

/* @var $this yii\web\View */
use yii\grid\GridView;
use frontend\models\Userprofile;
use frontend\models\Glucose;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\User;
$this->title = "Patient Profile";
?>
<style>
.content-wrapper {
    min-height: calc(100vh - 101px);
    background-color: white !important;
    z-index: 800;
}
.mb-0
{
	color:blue;
}
</style>
<div class="notifications-index">

<div class="container mt-5 mb-3">
    <div class="row">
        <div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">Name</h6> <span><?= $user->firstName.' '.$user->lastName ?></span>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
        <div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-dribbble"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">Mobile</h6> <span><?= User::find()->where(['id'=>$user->userId])->one()->username;?></span>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>
        <div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">DOB</h6> <span><?= $user->DOB; ?></span>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
		<div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">Age</h6> <span><?= $user->age; ?></span>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
		<div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">Gender</h6> <span><?= $user->gender; ?></span>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
		<div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">weight</h6> <span><?= $user->weight; ?></span>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
		<div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">height</h6> <span><?= $user->height; ?></span>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
		<div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">diabeticcondition</h6> <span><?= $user->diabeticcondition; ?></span>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
		<div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">period</h6> <span><?= $user->period; ?></span>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
		<div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">manageDiabetes</h6> <span><?= $user->manageDiabetes; ?></span>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
		<div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">typicalDay</h6> <span><?= $user->typicalDay; ?></span>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
		<div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">expLowSugar</h6> <span><?= $user->expLowSugar; ?></span>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
		<div class="col-md-2">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">HbA1c</h6> <span><?= $user->HbA1c; ?></span>
                        </div>
                    </div>
                    
                </div>
               
            </div>
        </div>
        </div>
    </div>
</div>
<?php $url = Yii::$app->urlManager->createUrl(['site/graphview']); ?>

<h4>Average Glucose</h4>  
<nav class="nav">
  
  <a class="nav-link" href="<?php echo $url.'&access_token='.$_GET['access_token'].'&type=week';?>">Week</a>
  <a class="nav-link" href="<?php echo $url.'&access_token='.$_GET['access_token'].'&type=month';?>">Month</a>
  <a class="nav-link disabled" href="<?php echo $url.'&access_token='.$_GET['access_token'].'&type=year';?>" tabindex="-1" aria-disabled="true">Year</a>
</nav>
 
                                           
                                               <div class="card-body">
                                               <div id="line-chart-2" style="width:50%"></div>
                                               </div>
                                           
                                       
										


</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
$(function() {		
		
		var options = {
          series: [
		  {
		  name: "Break Fast",
          data: [<?php print_r($morningdata);?>]
        }, {
			name: "Lunch",
          data: [<?php print_r($afternoondata);?>]
        },
		{
			name: "Dinner",
          data: [<?php print_r($dinnerdata);?>]
        }],
          chart: {
          type: 'bar',
          height: 430
        },
        plotOptions: {
          bar: {
            //horizontal: true,
            dataLabels: {
              position: 'top',
            },
          }
        },
        dataLabels: {
          enabled: false,
          offsetX: -6,
          style: {
            fontSize: '12px',
            colors: ['#fff']
          }
        },
        stroke: {
          show: true,
          width: 1,
          colors: ['#fff']
        },
		
        tooltip: {
          shared: true,
          intersect: false
        },
        xaxis: {
          categories: [<?php print_r($xvalues);?>],
        },
        };

        var chart = new ApexCharts(document.querySelector("#line-chart-2"), options);
        chart.render();
}); 
</script>