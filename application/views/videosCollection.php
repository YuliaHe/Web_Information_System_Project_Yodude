<body>

<div class="webTitle form-inline">
	<h1 class="offset-lg-1" style="margin-inline-end: 60%">Yodude</h1>
	<button class="btn btn-primary" style="margin-right: 20px"
			onclick="window.location.href='https://infs3202-2904dd11.uqcloud.net/yodude/video/upload'">
		Upload A Video</button>
	<a href="<?=base_url().'user/profile';?>"><?php if (isset($_SESSION['user_logged'])) {
		echo "Hello, ", $_SESSION['username'];
	} else { echo "Login"; } ?></a>
</div>

<div style="color:red">
	<?php if (isset($_SESSION['success'])) { ?>
		<div class="alert alert-success"> <?php echo $_SESSION['success']; ?></div>
		<?php
	} ?>
</div>

<p class="offset-lg-1">Welcome to Yodude!</p>

<div class="container offset-lg-1" style="padding-left: 0">
	<form class="form-inline" action="video/search" method="post">
		<input type="text" name="video" id="video" class="form-control" placeholder="Search here" style="width: 88%">
		<button type="submit" class="btn btn-success" style="width: 7%; margin-left: 5%">Go</button>
	</form>

	<div id="videosList"></div>
	<br>
</div>

<div class="form-inline offset-lg-1">
	<?php if(!empty($videos_list)){ foreach($videos_list as $row){ ?>
		<div style="margin-right: 16px; margin-bottom: 12px">
			<h3><?php echo $row["title"]; ?></h3>
			<p><?php echo $row["description"]; ?></p>
			<a href="<?=base_url().'video/video_display/'.$row["videoContent"];?>">
				<video src="<?=base_url().'assets/videosUploaded/'.$row["videoContent"]; ?>" class="video_display" height="300" width="400"></video>
				<?php $this->session->set_userdata('referred_from', current_url()); ?>
			</a>
		</div>
	<?php } }else{ ?>
		<p>Video(s) not found...</p>
	<?php } ?>
</div>

<!-- Render pagination links -->
<div class="pagination form-inline offset-lg-1">
	<?php echo $this->pagination->create_links(); ?>
</div>

<br>

<div>
	<a class="top-link hide" href="" id="js-top">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 6"><path d="M12 6H0l6-6z"/></svg>
		<span class="screen-reader-text">Back to top</span>
	</a>
</div>

<div class="container offset-lg-1" style="padding: 0">
	<h3>Comments</h3>
	<div class="alert alert-success" style="display: none;"></div>
	<button id="btnAdd" class="btn btn-success">Add New</button>
	<table class="table table-responsive" style="margin-top: 20px;">
		<thead>
		<tr>
			<td width="100px">User</td>
			<td width="200px">Created at</td>
			<td width="600px">Comment</td>
			<td></td>
		</tr>
		</thead>
		<tbody id="showdata">

		</tbody>
	</table>
</div>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Modal title</h4>
			</div>
			<div class="modal-body">
				<form id="myForm" action="" method="post" class="form-horizontal">
					<input type="hidden" name="comment_id" value="0">
					<div class="form-group">
						<label for="username" class="label-control col-md-4">User</label>
						<div class="col-md-8">
							<input type="text" name="username" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="content" class="label-control col-md-4">Comment</label>
						<div class="col-md-8">
							<textarea class="form-control" name="content"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" id="btnSave" class="btn btn-primary">Save</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Confirm Delete</h4>
			</div>
			<div class="modal-body">
				Do you want to delete this record?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" id="btnDelete" class="btn btn-danger">Delete</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style>
	.top-link {
		transition: all .25s ease-in-out;
		position: fixed;
		bottom: 0;
		right: 0;
		display: inline-flex;
		cursor: pointer;
		align-items: center;
		justify-content: center;
		margin: 0 3em 3em 0;
		border-radius: 50%;
		padding: .25em;
		width: 80px;
		height: 80px;
		background-color: #F8F8F8;
	}

	.top-link.show {
		visibility: visible;
		opacity: 1;
	}

	.top-link.hide {
		visibility: hidden;
		opacity: 0;
	}

	.top-link svg {
		fill: #000;
		width: 24px;
		height: 12px;
	}

	.top-link:hover {
		background-color: #E8E8E8;
	}

	.top-link:hover svg {
		fill: #000000;
	}

	.screen-reader-text {
		position: absolute;
		clip-path: inset(50%);
		margin: -1px;
		border: 0;
		padding: 0;
		width: 1px;
		height: 1px;
		overflow: hidden;
		word-wrap: normal !important;
		clip: rect(1px, 1px, 1px, 1px);
	}

	.screen-reader-text:focus {
		display: block;
		top: 5px;
		left: 5px;
		z-index: 100000;
		clip-path: none;
		background-color: #eee;
		padding: 15px 23px 14px;
		width: auto;
		height: auto;
		text-decoration: none;
		line-height: normal;
		color: #444;
		font-size: 1em;
		clip: auto !important;
	}

</style>

<script>
	$(function(){
		showAllComments();

		// Add New
		$('#btnAdd').click(function(){
			$('#myModal').modal('show');
			$('#myModal').find('.modal-title').text('Add New Comment');
			$('#myForm').attr('action', '<?php echo base_url() ?>video/addComment');
		});

		$('#btnSave').click(function(){
			let data = $('#myForm').serialize();
			//validate form
			let username = $('input[name=username]');
			let content = $('textarea[name=content]');
			let result = '';
			if(username.val()==''){
				username.parent().parent().addClass('has-error');
			}else{
				username.parent().parent().removeClass('has-error');
				result +='1';
			}
			if(content.val()==''){
				content.parent().parent().addClass('has-error');
			}else{
				content.parent().parent().removeClass('has-error');
				result +='2';
			}

			if(result=='12'){
				$.ajax({
					type: 'ajax',
					method: 'post',
					url: '<?php echo base_url() ?>video/addComment',
					data: data,
					async: false,
					dataType: 'json',
					success: function(response){
						if(response.success){
							$('#myModal').modal('hide');
							$('#myForm')[0].reset();

							$('.alert-success').html('Comment added successfully').fadeIn().delay(4000).fadeOut('slow');
							showAllComments();
						}else{
							alert('Error');
						}
					},
					error: function(){
						alert('Could not add data');
					}
				});
			}
		});

		// delete
		$('#showdata').on('click', '.item-delete', function(){
			let comment_id = $(this).attr('data');
			$('#deleteModal').modal('show');

			$('#btnDelete').unbind().click(function(){
				$.ajax({
					type: 'ajax',
					method: 'get',
					async: false,
					url: '<?php echo base_url() ?>video/deleteComment',
					data:{comment_id:comment_id},
					dataType: 'json',
					success: function(response){
						if(response.success){
							$('#deleteModal').modal('hide');
							$('.alert-success').html('Comment Deleted successfully').fadeIn().delay(4000).fadeOut('slow');
							showAllComments();
						}else{
							alert('Error');
						}
					},
					error: function(){
						alert('Error deleting');
					}
				});
			});
		});

		function showAllComments(){
			$.ajax({
				type: 'ajax',
				url: '<?php echo base_url() ?>video/showAllComments',
				async: false,
				dataType: 'json',
				success: function(data){

					var html = '';
					var i;
					for(i=0; i<data.length; i++){

						html +='<tr>'+
							'<td>'+data[i].username+'</td>'+
							'<td>'+data[i].created_at+'</td>'+
							'<td width="900px">'+data[i].content+'</td>'+
							'<td>'+
							'<a href="javascript:;" class="btn btn-danger item-delete" data="'+data[i].comment_id+'">Delete</a>'+
							'</td>'+
							'</tr>';
					}
					$('#showdata').html(html);
				},
				error: function(){
					alert('Could not get Data from Database');
				}
			});
		}
	});

	// autocomplete
	$(document).ready(function(){
		$('#video').keyup(function(){
			var query = $(this).val();
			if(query != '')
			{
				$.ajax({
					url:"<?php echo base_url() ?>video/autoComplete",
					method:"POST",
					data:{query:query},
					success:function(data)
					{
						$('#videosList').fadeIn();
						$('#videosList').html(data);
					}
				});
			}
		});

		$(document).on('click', 'li', function(){
			$('#video').val($(this).text());
			$('#videosList').fadeOut();
		});
	});

	// sticky button to return top.
	// Set a variable for our button element.
	const scrollToTopButton = document.getElementById('js-top');

	// Set up a function that shows our scroll-to-top button if we scroll beyond the height of the initial window.
	const scrollFunc = () => {
		// Get the current scroll value
		let y = window.scrollY;

		// If the scroll value is greater than the window height, let's add a class to the scroll-to-top button to show it!
		if (y > 250) {
			scrollToTopButton.className = "top-link show";
		} else {
			scrollToTopButton.className = "top-link hide";
		}
	};

	window.addEventListener("scroll", scrollFunc);

	const scrollToTop = () => {
		// set a variable for the number of pixels we are from the top of the document.
		const c = document.documentElement.scrollTop || document.body.scrollTop;

		// If that number is greater than 0, we'll scroll back to 0, or the top of the document.
		if (c > 0) {
			window.requestAnimationFrame(scrollToTop);
			// ScrollTo takes an x and a y coordinate.
			// Increase the '10' value to get a smoother/slower scroll!
			window.scrollTo(0, c - c / 10);
		}
	};

	scrollToTopButton.onclick = function(e) {
		e.preventDefault();
		scrollToTop();
	}

	$('.video_display').mouseover(function(){
		$(this).get(0).play();
	}).mouseout(function(){
		$(this).get(0).pause();
	})
</script>

