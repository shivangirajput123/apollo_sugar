<?php 

use yii\helpers\Html;
use yii\helpers\Url;

use frontend\assets\AppAsset;

AppAsset::register($this);
$baseurl= Url::base();
//echo $baseurl; exit;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	  
	<title>Apollo Diagnostics Quiz</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">
 
  <!-- Favicons -->
<!--  <link href="images/logo.png" rel="icon">
  <link href="images/logo.png" rel="apple-touch-icon">
-->  <script type = "text/javascript" 
         src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
          <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' type='text/javascript'></script>
<!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
--><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href=" https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
 <?php $this->beginBody() ?>
<header>
	<div class="container">
<div class="row">
<div class="logo img-responsive">
	<img class="img-fluid" src="<?php echo $baseurl.'/images/apollodiagnostics.png'?>">
</div>
</div>
</div>
</header>

   <?= $content ?>

	<footer>
		<div class="toptitle">
			<div class="container">
			<p class="text-center">Copyright © 2020, Apollo Diagnostics. All Rights Reserved
</p>
		</div>
	</div>
	</footer>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
