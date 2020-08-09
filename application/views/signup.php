<body style="background-color: #ffb4aa">
<div class="authPage">
	<h1>Yodude</h1>
	<p>Fill in the details to sign up Yodude!</p>

	<?php if (isset($_SESSION['success'])) { ?>
		<div class="alert alert-success"> <?php echo $_SESSION['success']; ?></div>
		<?php
	} ?>

	<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

	<form action="" method="post">
		<div class="form-group">
			<label for="username" class="label-default">Username:</label>
			<input class="form-control" name="username" id="username" type="text">
		</div>

		<div class="form-group">
			<label for="email" class="label-default">Email:</label>
			<input class="form-control" name="email" id="email" type="email">
		</div>

		<div class="form-group">
			<label for="phone" class="label-default">Phone:</label>
			<input class="form-control" name="phone" id="phone" type="text">
		</div>

		<div class="form-group">
			<label for="password" class="label-default">Password:</label>
			<input class="form-control" name="password" id="password" type="password">
			<p>(At least 6 chars;
				incl. at least one lowercase letter, uppercase letter, one number and one special character; )</p>
		</div>

		<div class="form-group">
			<label for="comfirm_password" class="label-default">Confirm Password:</label>
			<input class="form-control" name="comfirm_password" id="comfirm_password" type="password">
		</div>

		<br>

		<div class="text-center">
			<button class="btn btn-primary" name="signup">Sign Up</button>
			<br>
			<a href="<?php echo base_url(); ?>">Back to home page</a>
		</div>
	</form>
</div>

