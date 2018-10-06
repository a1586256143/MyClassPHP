<!doctype html>
<html lang="en" class="fullscreen-bg">

<head>
	<title>后台管理系统</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="<?php echo __CSS__; ?>/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo __VENDOR__; ?>/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo __VENDOR__; ?>/linearicons/style.css">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="<?php echo __CSS__; ?>/main.css">
	<!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
	<link rel="stylesheet" href="<?php echo __CSS__; ?>/demo.css">
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo __IMG__; ?>/apple-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo __IMG__; ?>/favicon.png">
</head>

<body>
	<!-- WRAPPER -->
	<div id="wrapper">
		<div class="vertical-align-wrap">
			<div class="vertical-align-middle">
				<div class="auth-box ">
					<div class="left">
						<div class="content">
							<div class="header">
								<p class="lead">后台管理系统</p>
							</div>
							<form class="form-auth-small" action="index.php">
								<div class="form-group">
									<label for="signin-email" class="control-label sr-only">账号</label>
									<input type="text" class="form-control" id="signin-email" name="name" placeholder="请输入您的账号">
								</div>
								<div class="form-group">
									<label for="signin-password" class="control-label sr-only">密码</label>
									<input type="password" name="password" class="form-control" id="signin-password" placeholder="请输入您的密码">
								</div>
								<?php echo _token() ?>
								<button type="button" class="btn btn-primary btn-lg btn-block button-login">LOGIN</button>
							</form>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- END WRAPPER -->
</body>
<script type="text/javascript" src="<?php echo __VENDOR__; ?>/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo __JS__; ?>/tool.js"></script>
<script type="text/javascript" src="<?php echo __VENDOR__; ?>/layer/layer.min.js"></script>
<script type="text/javascript" src="<?php echo __JS__; ?>/admin_login.js"></script>
</html>