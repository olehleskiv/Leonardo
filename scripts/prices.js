$.getJSON("admin_page/assets/js/singlePrices.json", function( data ) {
	var dates = [];
	var prices2 = [];
	var prices3 = [];
	$.each( data.single, function( key, val ) {
		dates.push( "<th>" + val.date + "</th>" );
		prices2.push("<td>" + val.price2 + "</td>");
		prices3.push("<td>" + val.price3 + "</td>");
	});
	$('#single-header').append(dates.join( "" ));
	$('#single-price2').append(prices2.join( "" ));
	$('#single-price3').append(prices3.join( "" ));
});


$.getJSON("admin_page/assets/js/doublePrices.json", function( data ) {
	var dates = [];
	var prices = [];
	$.each( data.double, function( key, val ) {
		dates.push( "<th>" + val.date + "</th>" );
		prices.push("<td>" + val.price + "</td>");
	});
	$('#double-header').append(dates.join( "" ));
	$('#double-prices').append(prices.join( "" ));
});

$.getJSON("admin_page/assets/js/apartmentPrices.json", function( data ) {
	var pricesHigh = [];
	var pricesLow = [];
	$.each( data.apartment, function( key, val ) {
		pricesHigh.push("<td>" + val.priceHigh + "</td>");
		pricesLow.push("<td>" + val.priceLow + "</td>");
	});
	for (var i = 0; i < pricesHigh.length; i++) {
		$('#apartment' + [i]).append((pricesHigh[i] + pricesLow[i]));
	}
});

