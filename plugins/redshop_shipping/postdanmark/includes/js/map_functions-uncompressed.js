var geocoder;
var map;
var startpoint;
var selected;
var closest;
var markers = [];

function initMap(addresses, name, number, opening, close, opening_sat, close_sat, lat, lng, servicePointId) {
	var lat_max = '';
	var lat_min = '';
	var lng_max = '';
	var lng_min = '';
	markers = [];

	map = new google.maps.Map(document.getElementById("map_canvas"), {
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	google.maps.event.trigger(map, 'resize');
	map.setZoom(map.getZoom());
	geocoder = new google.maps.Geocoder();

	for (i = 0; i < addresses.length; i++) {
		codeAddress(addresses[i], name[i], number[i], i, opening[i], close[i], opening_sat[i], close_sat[i], lat[i], lng[i], '', servicePointId[i]);
	}

	for (var i = 0; i < lat.length; i++) {
		if (lat[i] > lat_max || lat_max === '') {
			lat_max = lat[i];
		}

		if (lat[i] < lat_min || lat_min === '') {
			lat_min = lat[i];
		}
	}

	for (var i = 0; i < lng.length; i++) {
		if (lng[i] > lng_max || lng_max === '') {
			lng_max = lng[i];
		}

		if (lng[i] < lng_min || lng_min === '') {
			lng_min = lng[i];
		}
	}

	if (lat_max !== '' && lat_min !== '' && lng_max !== '' && lng_min !== '') {
		map.setCenter(new google.maps.LatLng(
			((lat_max + lat_min) / 2.0), ((lng_max + lng_min) / 2.0)
		));

		map.fitBounds(new google.maps.LatLngBounds(
			//bottom left
			new google.maps.LatLng(lat_min, lng_min),
			//top right
			new google.maps.LatLng(lat_max, lng_max)
		));
	}
}

function deleteOverlays() {
	for (var i = 0; i < markersArray.length; i++) {
		markersArray[i].setMap(null);
	}
	markersArray = [];
}

function codeAddress(address, name, number, i, opening, close, opening_sat, close_sat, lat, lng, city, servicePointId) {
	if (typeof opening !== 'undefined' && typeof close !== 'undefined' && opening !== '' && close !== '' && opening !== null && close !== null) {
		opening = 'Abningstider:<br />Man-Fre: ' + opening.substring(0, 2) + ':' + opening.substring(2) + ' - ';
		close = close.substring(0, 2) + ':' + close.substring(2);
	} else {
		opening = '';
		close = '';
	}

	if (typeof opening_sat !== 'undefined' && typeof close_sat !== 'undefined' && opening_sat !== '' && close_sat !== '' && opening_sat !== null && close_sat !== null) {
		opening_sat = '<br />' + decodeURIComponent('L%C3%B8rdag') + ': ' + opening_sat.substring(0, 2) + ':' + opening_sat.substring(2) + ' - ';
		close_sat = close_sat.substring(0, 2) + ':' + close_sat.substring(2);
	} else {
		opening_sat = '';
		close_sat = '';
	}

	contentString = '<div class="infoWindow"';
	if ((opening + close + opening_sat + close_sat).length > 0) {
		contentString += '  style="width: 140px;"'
	}
	contentString += '>' + opening + close + opening_sat + close_sat + '<div>';

	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});


	var latlng = new google.maps.LatLng(lat, lng);
	var marker = new google.maps.Marker({
		map: map,
		position: latlng,
		animation: google.maps.Animation.DROP,
		icon: "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + (i + 1) + "|FF0000|000000",
		serviceId: servicePointId
	});

	if (typeof number === 'undefined') {
		number = '';
	}

	if (typeof name === 'undefined') {
		name = '';
	}

	markers.push(marker);
	google.maps.event.addListener(marker, "click", toggleBounce);

	google.maps.event.addListener(marker, "click", function() {
		if (typeof currentIw !== 'undefined') {
			currentIw.close();
		}

		if (infowindow.content !== '<div class="infoWindow"><div>') {
			infowindow.open(map, marker);
			currentIw = infowindow;
		}
	});


	function toggleBounce() {
		selected = this;
		var x = 0;
		var v = 0;
		while (x < markers.length) {
			if (markers[x].serviceId === this.serviceId) {
				v = x;
			}
			if (x < 20) {
				markers[x].setZIndex(15);
				markers[x].setAnimation(null);
				markers[x].setIcon("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + (x + 1) + "|FF0000|000000");
			}
			x++;
		}

		this.setIcon("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + (v + 1) + "|336699|FFFFFF");
		this.setZIndex(100);

		selectRadioOnMarkerClick(this);
	}

	function selectRadioOnMarkerClick(marker) {
		jQuery(document).ready(function() {
			jQuery('input[name="postdanmark_pickupLocation"][value="' + marker.serviceId + '"]')[0].checked = true;
			jQuery('.radio_point_container').removeClass('sel');
			jQuery(jQuery('input[name="postdanmark_pickupLocation"][value="' + marker.serviceId + '"]')[0]).parent().parent().parent().parent().parent().addClass('sel');
		});
	}
}

function selectMarker(id) {
	var bouncex = false;
	var x = 0;
	while (x < markers.length) {
		markers[x].setAnimation(null);
		if (x < 20) {
			markers[x].setZIndex(15);
			markers[x].setIcon("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + (x + 1) + "|FF0000|000000");
		}
		if (markers[x].serviceId == id) {
			bouncex = x;
		}
		x++;
	}

	if (bouncex !== false) {
		google.maps.event.trigger(markers[bouncex], 'click');
		selected = markers[bouncex];
		markers[bouncex].setZIndex(100);
		markers[bouncex].setIcon("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + (bouncex + 1) + "|336699|FFFFFF");
		if (typeof startpoint !== 'undefined') {
			calcRoute(startpoint, markers[bouncex]);
		}
	}

	jQuery(document).ready(function() {
		jQuery('input[name="postdanmark_pickupLocation"][value="' + id + '"]')[0].checked = true;
		jQuery('.radio_point_container').removeClass('sel');
		jQuery(jQuery('input[name="postdanmark_pickupLocation"][value="' + id + '"]')[0]).parent().parent().parent().parent().parent().addClass('sel');
	});
}
