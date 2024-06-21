<?php

/* @var $this yii\web\View */
use yii\grid\GridView;
use frontend\models\Userprofile;
use frontend\models\Glucose;
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = "Dashboard";
?>
<style>
body
{
  background-color: #f7f7ff;
}
.radius-10 
{
    border-radius: 10px !important;
}
.border-info 
{
    border-left: 5px solid  #0dcaf0 !important;
}
.border-danger 
{
    border-left: 5px solid  #fd3550 !important;
}
.border-success 
{
    border-left: 5px solid  #15ca20 !important;
}
.border-warning 
{
    border-left: 5px solid  #ffc107 !important;
}
.card 
{
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #e4e4e4;
    border-radius: .25rem;
    margin-bottom: 1.5rem;
   /* box-shadow: 1px 2px 2px 0 rgb(218 218 253 / 65%), 1px 2px 2px 1px rgb(206 206 238 / 54%); */
	margin-right:6px;
	text-align: center;
	
}
.card p{
	font-size:20px;
	font-weight: bold;
	color:#007c9d
}
.card-body
{
	height:100px;	
	margin-left:50px;
	margin-top:30px;
}
.bg-gradient-scooter 
{
    background: #17ead9;
    background: -webkit-linear-gradient( 
45deg
 , #17ead9, #6078ea)!important;
    background: linear-gradient( 
45deg
 , #17ead9, #6078ea)!important;
}
.widgets-icons-2 
{
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ededed;
    font-size: 27px;
    border-radius: 10px;
}
.rounded-circle {
    border-radius: 50%!important;
}
.text-white {
    color: #fff!important;
}
.ms-auto {
    margin-left: auto!important;
}
.bg-gradient-bloody 
{
    background: #f54ea2;
    background: -webkit-linear-gradient( 
45deg
 , #f54ea2, #ff7676)!important;
    background: linear-gradient( 
45deg
 , #f54ea2, #ff7676)!important;
}
.bg-gradient-ohhappiness 
{
    background: #00b09b;
    background: -webkit-linear-gradient( 
45deg
 , #00b09b, #96c93d)!important;
    background: linear-gradient( 
45deg
 , #00b09b, #96c93d)!important;
}
.bg-gradient-blooker 
{
    background: #ffdf40;
    background: -webkit-linear-gradient( 
45deg
 , #ffdf40, #ff8359)!important;
background: linear-gradient( 
45deg
 , #ffdf40, #ff8359)!important;
}
.col-lg-3 
{
    width: 23% !important;
}
</style>
<div class="container">	
</div>
<div class="" >   
    <div class="card col-lg-3 col-sm-12">         
          <h4>Active Programs</h4>
		  <p><?= $activeprograms;?></p>    
    </div>
	 <div class="card col-lg-3 col-sm-12">      
          <h4>Past Webinars</h4>
		  <p><?= $pastwebnarcount;?></p>      
    </div>
    <div class="card col-lg-3 col-sm-12">
         <h4>Upcoming Webinars</h4>
	     <p><?= $webnarcount;?></p>
    </div>
    <div class="card col-lg-3 col-sm-12">         
        <h4>Total Doctors</h4>
		 <p><?= $doctorcount;?></p>
            
    </div>
    <div class="card col-lg-3 col-sm-12">         
        <h4>Total Dieticians</h4>
		 <p><?= $dieticiancount;?></p>       
    </div>
	<div class="card col-lg-3 col-sm-12">         
         <h4>Subscribed Patients</h4>
		 <p><?= $subscribecount;?></p>       
    </div>
    <div class="card col-lg-12 col-sm-12">
     	 <h4>Top 5 Subscribed Programs</h4>
	     <div id="line-chart-2" style="width:30%"></div>
	</div> 
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
$(function() {		
		
		var options = {
          series: [
		  {
		  name: "Subscribed",
          data: [<?php print_r($yvalues);?>]
		  }],
          chart: {
			  toolbar: {
      show: false
    },
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