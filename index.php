<?php

require 'load.php';
$data = getData();

?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Public Washrooms in Vancouver</title>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPjo_cCYIkkWKcnfpPsGV4d7G5nQ5GqmA&callback=initMap" type="text/javascript"></script>
		<style>
			body {
				margin: 0;
			}
			#map {
				width: 100%;
				height: 800px;
			}
		</style>
    </head>
    <body>
		<div id="map"></div>
		<script>
			
			function initMap() {
				var markers = [];
				var infoWindows = [];
				var data = <?=$data?>;
				var vancouver = {lat: 49.2427, lng: -123.1207};
				var icon = {
					url: 'icon.png',
					scaledSize: new google.maps.Size(25, 25), // scaled size
					origin: new google.maps.Point(0,0), // origin
					anchor: new google.maps.Point(0, 0) // anchor
				};
				var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 12,
					center: vancouver
				});
				for (let i = 0; i < data.length; i++) {
					var d = data[i];
					var latLng = new google.maps.LatLng(d.latitude, d.longitude);
					markers[i] = new google.maps.Marker({
						position: latLng,
						map: map,
						icon: icon
					});
					
					infoWindows[i] = new google.maps.InfoWindow({
						content: 
							'<table>' +
								'<tr>' +
									'<td width="30%"><img src="toilet.jpg" width=110 height=90></td>' +
									'<td><h2>Welcome to Vancouver Toilets!<br>Hope you will enjoy your visit!<br>Good luck! <img src="poop.png" width=20 height=20></h2></td>' +
								'</tr>' +
								'<tr>' +
									'<td><strong>Name</strong></td>' +
									'<td>' + d.name + '</td>' +
								'</tr>' +
								'<tr>' +
									'<td><strong>Address</strong></td>' +
									'<td>' + d.address + '</td>' +
								'</tr>' +
								'<tr>' +
									'<td><strong>Type</strong></td>' +
									'<td>' + d.type + '</td>' +
								'</tr>' +
								'<tr>' +
									'<td><strong>Location</strong></td>' +
									'<td>' + d.location + '</td>' +
								'</tr>' +
								'<tr>' +
									'<td><strong>Summer hours</strong></td>' +
									'<td>' + d.summer_hours + '</td>' +
								'</tr>' +
								'<tr>' +
									'<td><strong>Winter hours</strong></td>' +
									'<td>' + d.winter_hours + '</td>' +
								'</tr>' +
								'<tr>' +
									'<td><strong>Wheelchair</strong></td>' +
									'<td>' + d.wheelchair_access + '</td>' +
								'</tr>' +
								'<tr>' +
									'<td><strong>Note</strong></td>' +
									'<td>' + d.note + '</td>' +
								'</tr>' +
								'<tr>' +
									'<td><strong>Maintainer</strong></td>' +
									'<td>' + d.maintainer + '</td>' +
								'</tr>' +
							'</table>'
					});
					markers[i].addListener('click', function() {
						infoWindows.forEach(function(element) { element.close() });
						infoWindows[i].open(map, this);
					});
				};
			};
		</script>
    </body>
</html>
