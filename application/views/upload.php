<body style="background-color: #ffb4aa">
<div class="col-lg-6 offset-lg-3 authPage">
	<h1>Yodude</h1>
	<p>Post Your Video on Yodude</p>
	<hr />
	<div style="color:red">
		<?php echo validation_errors(); ?>
		<?php if(isset($error)){print $error;}?>
	</div>
	<?php echo form_open_multipart('video/video_data');?>
	<div class="form-group">
		<label for="title">Video Title*:</label>
		<input type="text" class="form-control" name="title" value="<?= set_value('title'); ?>" id="title">
	</div>

	<div class="form-group">
		<label for="description">Video Description:</label>
		<textarea name="description" class="form-control" id="description"><?= set_value('description'); ?></textarea>
	</div>

	<div class="form-group">
		<label for="videoContent">Select Video* (You can also drag video in this field and drop to upload it.):</label>
		<input type="file" name="videoContent" class="form-control dropzone"  id="videoContent" style="align-items: center">
	</div>

	<br>

	<a href="<?=base_url();?>" class="btn btn-warning">Back</a>
	<button type="submit" class="btn btn-success">Submit</button>
	</form>
</div>
