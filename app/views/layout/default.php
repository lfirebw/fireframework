<?php
//Import::Clases('middleware/session');
//session::checkSession();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $this->title ?></title>
		<meta name="description" content="<?php echo $this->description; ?>">
		<meta name="keywords" content="<?php echo $this->keywords; ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php echo URL_ASSETS; ?>/assets/css/all.min.css" />
		<link rel="stylesheet" href="<?php echo URL_ASSETS; ?>/assets/css/w3.css" />
		<link rel="stylesheet" href="<?php echo URL_ASSETS; ?>/assets/css/font-awesome.min.css" />
		<link rel="stylesheet" href="<?php echo URL_ASSETS; ?>/assets/css/bootstrap.min.css" />
		<!--<link rel="stylesheet" href="<?php echo URL_ASSETS; ?>/assets/css/bootstrap.min.css.map" />-->
		<link rel="stylesheet" href="<?php echo URL_ASSETS; ?>/assets/css/dataTables.bootstrap4.min.css" />
		<link rel="stylesheet" href="<?php echo URL_ASSETS; ?>/assets/css/summernote-bs4.css" />
		<link rel="stylesheet" href="<?php echo URL_ASSETS; ?>/assets/css/jquery-confirm.min.css" />
		<?php echo $this->getHeadStyle(); ?> 
	<style type="text/css">

	</style>
	</head>
	<body>
		<script  src="<?php echo URL_ASSETS; ?>/assets/js/jquery-3.3.1.min.js" ></script>
		<script  src="<?php echo URL_ASSETS; ?>/assets/js/popper.min.js"></script>
		<script  src="<?php echo URL_ASSETS; ?>/assets/js/bootstrap.min.js" ></script>
		<script  src="<?php echo URL_ASSETS; ?>/assets/js/summernote-bs4.min.js"></script>
		<script  src="<?php echo URL_ASSETS; ?>/assets/js/utils.js"></script>
		
		<script type="text/javascript"> const _URLASSETS_ = '<?php echo URL_ASSETS ?>'; const _URLWEB_ = '<?php echo URL_WEB ?>'; </script>		
		
		
		<div id="wrapper" class="w3-container toggle">
			<main style="padding-left: 5px;">
				<?php echo $this->getChildHtml('content'); ?>
			</main>		
		</div>
		<div id="footer-main" style="margin:0;" class="toggle" >
			<div class="">
				<div class="w3-dark-grey" style="width:100%;">
					<p style="margin:0; padding: 15px 5px;"><?php echo $this->copyright ?></p>
				</div>
			</div>
		</div>

		<script  src="<?php echo URL_ASSETS; ?>/assets/js/jquery-confirm.min.js"></script>
		<script  src="<?php echo URL_ASSETS; ?>/assets/js/jquery.dataTables.min.js"></script>
		<script  src="<?php echo URL_ASSETS; ?>/assets/js/dataTables.bootstrap4.min.js"></script>
		<?php echo $this->getBodyScript(); ?>

	</body>
</html>
