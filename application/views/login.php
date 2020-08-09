<body style="background-color: #ffb4aa">
<div class="authPage">
	<h1>Yodude</h1>
	<p>Fill in the details to login Yodude!</p>

	<?php if (isset($_SESSION['error'])) { ?>
		<div class="alert alert-danger"> <?php echo $_SESSION['error']; ?></div>
		<?php
	} ?>

	<?php if (isset($_SESSION['success'])) { ?>
		<div class="alert alert-success"> <?php echo $_SESSION['success']; ?></div>
		<?php
	} ?>

	<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

	<form action="" method="post">
		<div class="form-group">
			<label for="username" class="label-default">Username:</label>
			<input class="form-control" name="username" id="username" type="text" value="<?php if (get_cookie('username')) { echo get_cookie('username'); } ?>">
		</div>

		<div class="form-group">
			<label for="password" class="label-default">Password:</label>
			<input class="form-control" name="password" id="password" type="password" value="<?php if (get_cookie('password')) { echo get_cookie('password'); } ?>">
		</div>

		<div>
			<p id="captImg"><?php echo $captchaImg; ?></p>
			<p>Can't read the image? click <a href="javascript:void(0);" class="refreshCaptcha">here</a> to refresh.</p>
			<p>Enter the code :</p>
			<input type="text" name="captcha" value=""/>
		</div>

		<div class="form-group">
			<input type="checkbox" name="remember" value="Remember me"<?php if (get_cookie('username')) { ?> checked="checked" <?php } ?>
			<label> Remember me</label>
		</div>

		<br>

		<div class="text-center">
			<button class="btn btn-primary" name="login">Login</button>
			<a href="<?=base_url().'auth/signup';?>" style="margin-left: 30px">Sign Up</a>
			<br><br>
			<a href="<?php echo base_url(); ?>">Back to home page</a>
		</div>

	</form>
</div>

<script>
	$(document).ready(function(){
		$('.refreshCaptcha').on('click', function(){
			$.get('<?php echo base_url().'auth/refresh'; ?>', function(data){
				$('#captImg').html(data);
			});
		});
	});
</script>

