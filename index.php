<?php

require 'load.php';

$drinking_fountains = 'ftp://webftp.vancouver.ca/OpenData/csv/drinking_fountains.csv';
$public_wc = 'ftp://webftp.vancouver.ca/OpenData/csv/public_washrooms.csv';

$drinking_fountains_data = 'data/drinking.txt';
$toilets_data = 'data/toilets.txt';

$drinking_data = getData($drinking_fountains, $drinking_fountains_data);
$toilets_data = getData($public_wc, $toilets_data);

?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Public Washrooms in Vancouver</title>
		<script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOam-j_b7cy3iJZ9sp4cFOUehyonBalEA&callback=initMap&libraries=geometry" type="text/javascript"></script>
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
				var vancouver = {lat: 49.2427, lng: -123.1207};
				
				var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 13,
					center: vancouver
				});
				
				var tMarkers = [];
				var dMarkers = [];
				
				var tInfoWindows = [];
				//var dInfoWindows = [];
				
				var tData = <?=$toilets_data?>;
				var dData = <?=$drinking_data?>;
				
				var tIcon = {
					url: 'images/t-icon.png',
					scaledSize: new google.maps.Size(20, 20), // scaled size
					origin: new google.maps.Point(0, 0), // origin
					anchor: new google.maps.Point(10, 10) // anchor
				};
				var dIcon = {
					url: 'images/d-icon.png',
					scaledSize: new google.maps.Size(10, 10), // scaled size
					origin: new google.maps.Point(0, 0), // origin
					anchor: new google.maps.Point(5, 5) // anchor
				};
				
				for (let i = 0; i < tData.length; i++) {
					var d = tData[i];
					var latLng = new google.maps.LatLng(d.latitude, d.longitude);
					tMarkers[i] = new google.maps.Marker({
						position: latLng,
						map: map,
						icon: tIcon
					});
					
					tInfoWindows[i] = new google.maps.InfoWindow({
						content: 
							'<table>' +
								'<tr>' +
									'<td width="30%"><img src="images/toilet.jpg" width=110 height=90></td>' +
									'<td><h2>Welcome to Vancouver Toilets!<br>Hope you will enjoy your visit!<br>Good luck! <img src="images/poop.png" width=20 height=20></h2></td>' +
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
					tMarkers[i].addListener('click', function() {
						tInfoWindows.forEach(function(element) { element.close(); });
						tInfoWindows[i].open(map, this);
					});
				};
				var lines = [];
				var circles = [];
				for (let i = 0; i < dData.length; i++) {
					var d = dData[i];
					var latLng = new google.maps.LatLng(d.latitude, d.longitude);
					dMarkers[i] = new google.maps.Marker({
						position: latLng,
						map: map,
						icon: dIcon
					});
					dMarkers[i].addListener('click', function() {
						var distances = [];
						tMarkers.forEach(function(element) {
							distances.push(google.maps.geometry.spherical.computeDistanceBetween(dMarkers[i].getPosition(), element.getPosition()));
						});
						index = distances.indexOf(Math.min.apply(window,distances));
						lines[i] = new google.maps.Polyline({
							path: [
								dMarkers[i].getPosition(), 
								tMarkers[index].getPosition()
							],
							strokeColor: "#FFD69B",
							strokeOpacity: 1.0,
							strokeWeight: 5,
							geodesic: true,
							map: map
						});
						circles[i] = new google.maps.Circle({
							center: tMarkers[index].getPosition(),
							map: map,
							strokeColor: "#FFD69B",
							strokeOpacity: 1.0,
							strokeWeight: 5,
							radius: 100
						});
						circles[i].addListener('click', function() {
							this.setMap(null);
							lines[i].setMap(null);
						});
						lines[i].addListener('click', function() {
							this.setMap(null);
							circles[i].setMap(null);
						});
					});
				};
			};
		</script>
    </body>
</html>
