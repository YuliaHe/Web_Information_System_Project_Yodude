<body style="background-color: #ffb4aa">
<div class="authPage">
	<h1>Yodude</h1>
	<p>Update your information</p>

	<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

	<?php if (isset($_SESSION['error'])) { ?>
		<div class="alert alert-danger"> <?php echo $_SESSION['error']; ?></div>
		<?php
	} ?>

	<?php echo form_open_multipart('user/edit');?>
		<div class="form-group form-inline">
			<label for="profilePhoto">Upload your profile photo:</label>
			<input type="file" name="profilePhoto" class="form-control"  id="profilePhoto">
			<button class="btn btn-primary" type="submit" name="check" style="margin-left: 20px">Done</button>
		</div>

		<div>
			<?php if (isset($_SESSION['profilePhoto'])) { ?>
				<img src="<?=base_url().'assets/profilePhotosUploaded/'.$_SESSION['profilePhoto'];?>" alt="after choosing the image. click done to check it.">
				<?php
			} ?>
		</div>
	</form>

	<form action="" method="post">
		<div class="form-group">
			<label for="username" class="label-default">Username:</label>
			<input class="form-control" name="username" id="username" type="text"
				   value="<?php if(isset($_SESSION['username'])) {echo $_SESSION['username'];}?>">
		</div>

		<div class="form-group">
			<label for="phone" class="label-default">Phone:</label>
			<input class="form-control" name="phone" id="phone" type="text"
				value="<?php if(isset($_SESSION['phone'])) {echo $_SESSION['phone'];}?>">
		</div>

		<div class="text-center">
			<button class="btn btn-primary" name="update">Update</button>
			<br>
			<a href="<?=base_url().'user/profile';?>">Cancel</a>
		</div>
	</form>
</div>
