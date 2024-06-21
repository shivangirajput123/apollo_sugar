<?php


use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\users\models\DoctorsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use backend\modules\users\models\Doctors;
$this->title = 'Doctors Roaster';
$this->params['breadcrumbs'][] = $this->title;
$model = Doctors::find();
?>
<div class="doctors-index">
<div class="container">
  <table class="table">
    <thead>
      <tr>
        <th>DoctorID</th>
        <th>Mobile Number</th>
        <th>Location</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Default</td>
        <td>Defaultson</td>
        <td>def@somemail.com</td>
      </tr>      
      
    </tbody>
  </table>
</div>

</div>
