<body>
<div class="webTitle">
	<h1 class="col-lg-6 offset-lg-1">Yodude</h1>
	<h4 class="col-lg-6 offset-lg-1">Your Profile</h4>
</div>

<div class="col-lg-6 offset-lg-1">
	<?php if (isset($_SESSION['success'])) { ?>
		<div class="alert alert-success"> <?php echo $_SESSION['success']; ?></div>
	<?php
	} ?>

	<br>
	<br>
	<div>
		<?php if (isset($_SESSION['profilePhoto'])) { ?>
			<img src="<?=base_url().'assets/profilePhotosUploaded/'.$_SESSION['profilePhoto'];?>" alt="You haven't set your profile photo.">
			<?php
		} ?>
	</div>

	<div>
		<br>
		Hello, <?php if(isset($_SESSION['username'])) {echo $_SESSION['username'];}?>

		<br>
		Your Email is <?php if(isset($_SESSION['email'])) {echo $_SESSION['email'];}?>
		<br>
		Email Active Status: <?php if(isset($_SESSION['emailStatus'])) {echo $_SESSION['emailStatus'];}?>
		<br><br>
		Your Phone is <?php if(isset($_SESSION['phone'])) {echo $_SESSION['phone'];}?>
		<br><br>
		You joined us on <?php if(isset($_SESSION['createdDate'])) {echo $_SESSION['createdDate'];}?>
		<br><br>
		Your location
		<div id="map"></div>

		<br><br><br><br>
	</div>

	<div>
		<a href="<?php echo base_url(); ?>user/edit">Edit Your Profile</a>
		<br>
		<a href="<?php echo base_url(); ?>">Back to home page</a>
		<br>
		<a href="<?php echo base_url(); ?>auth/logout">Logout</a>
	</div>

</div>

<script>
	var map, infoWindow;
	function initMap() {
		map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: -34.397, lng: 150.644},
			zoom: 12
		});
		infoWindow = new google.maps.InfoWindow;

		// HTML5 geolocation for getting location.
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var pos = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};

				infoWindow.setPosition(pos);
				infoWindow.setContent('You are here.');
				infoWindow.open(map);
				map.setCenter(pos);

				var marker = new google.maps.Marker({
					position:map.getCenter(),
				});
				marker.setMap(map);
			}, function() {
				handleLocationError(true, infoWindow, map.getCenter());
			});
		} else {
			// Browser doesn't support Geolocation
			handleLocationError(false, infoWindow, map.getCenter());
		}
	}

	function handleLocationError(browserHasGeolocation, infoWindow, pos) {
		infoWindow.setPosition(pos);
		infoWindow.setContent(browserHasGeolocation ?
			'Error: The Geolocation service failed.' :
			'Error: Your browser doesn\'t support geolocation.');
		infoWindow.open(map);
	}
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB55iTi0n4_7lS5DzNi3f-F91LTxt7hkzw&callback=initMap">
</script>

<style>
	#map{
		width: 100%;
		height: 400px;
	}
</style>






