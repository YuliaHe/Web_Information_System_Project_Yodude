<body>

<div class="webTitle form-inline">
	<h1 class="offset-lg-1" style="margin-inline-end: 60%">Yodude</h1>
	<button class="btn btn-primary" style="margin-right: 20px" onclick="location='video/upload'">Upload A Video</button>
	<a href="<?=base_url().'user/profile';?>"><?php if (isset($_SESSION['username'])) {
			echo "Hello, ", $_SESSION['username'];
		} else { echo "Login"; } ?></a>
</div>

<br>
<a class="offset-lg-1" href="<?php echo base_url(),'video/back'; ?>">Back to home page</a>
<br><br>

<div class="offset-lg-1">
	<h3><?php echo $postData['title']; ?></h3>
	<p><?php echo $postData['description']; ?></p>
	<div class="content"><video src="<?=base_url().'assets/videosUploaded/'.$postData['videoContent'];?>" type="video/mp4" controls height="600px"></video></div>
	<br>
	<div class="rate">
		<input type="radio" id="star5" name="rating" value="5" <?php echo ($postData['average_rating'] == 5)?'checked="checked"':''; ?>>
		<label for="star5"></label>
		<input type="radio" id="star4" name="rating" value="4" <?php echo ($postData['average_rating'] == 4)?'checked="checked"':''; ?>>
		<label for="star4"></label>
		<input type="radio" id="star3" name="rating" value="3" <?php echo ($postData['average_rating'] == 3)?'checked="checked"':''; ?>>
		<label for="star3"></label>
		<input type="radio" id="star2" name="rating" value="2" <?php echo ($postData['average_rating'] == 2)?'checked="checked"':''; ?>>
		<label for="star2"></label>
		<input type="radio" id="star1" name="rating" value="1" <?php echo ($postData['average_rating'] == 1)?'checked="checked"':''; ?>>
		<label for="star1"></label>
	</div>
	<br><br>
	<div class="overall-rating">
		<p style="font-size: 12px">Average Rating <span id="avgrat"><?php echo $postData['average_rating']; ?></span>.
			Based on <span id="totalrat"><?php echo $postData['rating_num']; ?></span> rating</span>.</p>
	</div>
	<div>
		<a href="<?php echo 'http://www.facebook.com/sharer.php?u='.current_url();?>" target="_blank">
			<img src="<?php echo base_url().'/assets/facebook.png'?>"
				 alt="Facebook" style="width: 40px" >
		</a>
	</div>
</div class="offset-lg-1">

<script>
	$(function() {
		$('.rate input').on('click', function(){
			var videoID = <?php echo $postData['videoID']; ?>;
			var ratingNum = $(this).val();

			$.ajax({
				type: 'POST',
				url: '<?php echo base_url() ?>video/rating',
				data: 'videoID='+videoID+'&ratingNum='+ratingNum,
				dataType: 'json',
				success : function(resp) {
					if(resp.status == 1){
						$('#avgrat').text(resp.data.average_rating);
						$('#totalrat').text(resp.data.rating_num);
						alert('Thanks! You have rated '+ratingNum+' to "<?php echo $postData['title']; ?>"');
					}else if(resp.status == 2){
						alert('You have already rated to "<?php echo $postData['title']; ?>"');
					}

					$( ".rate input" ).each(function() {
						if($(this).val() <= parseInt(resp.data.average_rating)){
							$(this).attr('checked', 'checked');
							window.location.reload();
						}else{
							$(this).prop('checked', false);
						}
					});
				}
			});
		});
	});
</script>

<style>
	.rate {
		float:left;
	}

	.rate:not(:checked) > input {
		position:absolute;
		top:-9999px;
		clip:rect(0,0,0,0);
	}

	.rate:not(:checked) > label {
		float:right;
		width:1em;
		padding:0 .1em;
		overflow:hidden;
		white-space:nowrap;
		cursor:pointer;
		font-size:200%;
		line-height:1.2;
		color:#ddd;
	}

	.rate:not(:checked) > label:before {
		content: '★ ';
	}

	.rate > input:checked ~ label {
		color: #f70;
	}

	.rate:not(:checked) > label:hover,
	.rate:not(:checked) > label:hover ~ label {
		color: gold;
	}

	.rate > input:checked ~ label:hover,
	.rate > label:hover ~ input:checked ~ label {
		color: gold;
	}

	.rate > label:active {
		position:relative;
		top:2px;
		left:2px;
	}
</style>




