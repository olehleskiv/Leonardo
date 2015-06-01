		function initialize()
		{
			var myLatlng = new google.maps.LatLng(42.722804,19.080156);
			var leoLatlng = new google.maps.LatLng(42.451348,18.578366);
			var infoContent = 'Hotel Leonardo';
			var myOptions = {
							zoom: 8,
							disableDefaultUI: true,
							keyboardShortcuts: true,
							
							scrollwheel: true,
							mapTypeControl: true,

 panControl: true,
  zoomControl: true,
  mapTypeControl: true,
  scaleControl: true,
  streetViewControl: true,
  overviewMapControl: true,

							mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
							navigationControl: true,
							navigationControlOptions: {style: google.maps.NavigationControlStyle.ZOOM_PAN},
							center: myLatlng,
							mapTypeId: google.maps.MapTypeId.HYBRID
 

							}
			var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
			var infowindow = new google.maps.InfoWindow({
														content: infoContent
														});
			var marker = new google.maps.Marker({
												position: leoLatlng,
												map: map,
												title: 'Hotel Leonardo'
												});
			google.maps.event.addListener(marker, 'click', function() {
																		infowindow.open(map,marker);
																	});
		}