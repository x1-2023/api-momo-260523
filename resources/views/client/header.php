<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="API Miễn phí">
    <meta name="author" content="Bootlab">

    <title><?=$title?></title>
    <link rel="stylesheet" href="<?=BASE_URL('')?>public/assets/css/style.css">
    <link href="<?=BASE_URL('')?>public/assets/cute/cute-alert.css" rel="stylesheet">
    <script src="<?=BASE_URL('')?>public/assets/cute/cute-alert.js"></script>
    <script src="<?=BASE_URL('');?>public/assets/js/jquery-3.6.0.min.js"></script>
        <script src="<?=BASE_URL('');?>public/assets/js/loadingoverlay.min.js"></script>
    <?=$body['header'];?> 
</head>

<body>
    <!-- [ Pre-loader ] start -->
	<div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
	</div>
	<!-- [ Pre-loader ] End -->