var usermap = {};
var click, map, layer_markers, layer_position_markers;
var s_touch = false;
var marker_cache = {};

(function($) {
var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection

usermap.add_layer = function(layer, name, is_default){

	switch(layer)
	{
		case 'osm_mapnik':
			var layer = new OpenLayers.Layer.OSM.Mapnik(name);
			break;
		case 'osm_cyclemap':
			var layer = new OpenLayers.Layer.OSM.CycleMap(name);
			break;
		case 'transportmap':
			var layer = new OpenLayers.Layer.OSM.TransportMap(name);
			break;
		case 'Landscape':
			var layer = new OpenLayers.Layer.OSM.Landscape(name);
			break;
		case 'Toner':
			var layer = new OpenLayers.Layer.OSM.Toner(name);
			break;
		case 'Watercolor':
			var layer = new OpenLayers.Layer.OSM.Watercolor(name);
			break;
		case 'Maptookit':
			var layer = new OpenLayers.Layer.OSM.Maptookit(name);
			break;
		case 'OpenSnowMap':
			var layer = new OpenLayers.Layer.OSM.OpenSnowMap(name);
			break;
		case 'Esri':
			var layer = new OpenLayers.Layer.OSM.Esri(name);
			break;
		case 'EsriSatellite':
			var layer = new OpenLayers.Layer.OSM.EsriSatellite(name);
			break;
		case 'EsriPhysical':
			var layer = new OpenLayers.Layer.OSM.EsriPhysical(name);
			break;
		case 'EsriShadedRelief':
			var layer = new OpenLayers.Layer.OSM.EsriShadedRelief(name);
			break;
		case 'EsriTerrain':
			var layer = new OpenLayers.Layer.OSM.EsriTerrain(name);
			break;
		case 'EsriTopo':
			var layer = new OpenLayers.Layer.OSM.EsriTopo(name);
			break;
		case 'EsriGray':
			var layer = new OpenLayers.Layer.OSM.EsriGray(name);
			break;
		case 'EsriNationalGeographic':
			var layer = new OpenLayers.Layer.OSM.EsriNationalGeographic(name);
			break;
		case 'EsriOcean':
			var layer = new OpenLayers.Layer.OSM.EsriOcean(name);
			break;
		case 'Komoot':
			var layer = new OpenLayers.Layer.OSM.Komoot(name);
			break;
		case 'CartoDBLight':
			var layer = new OpenLayers.Layer.OSM.CartoDBLight(name);
			break;
		case 'CartoDBDark':
			var layer = new OpenLayers.Layer.OSM.CartoDBDark(name);
			break;
		case 'Sputnik':
			var layer = new OpenLayers.Layer.OSM.Sputnik(name);
			break;
		case 'Kosmosnimki':
			var layer = new OpenLayers.Layer.OSM.Kosmosnimki(name);
			break;

		case 'google_terrain':
			var layer = new OpenLayers.Layer.Google(name, {type: google.maps.MapTypeId.TERRAIN, numZoomLevels: 20});
			break;
		case 'google_roadmap':
			var layer = new OpenLayers.Layer.Google(name, {type: google.maps.MapTypeId.ROADMAP, numZoomLevels: 20});
			break;
		case 'google_hybrid':
			var layer = new OpenLayers.Layer.Google(name, {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20});
			break;
		case 'google_satellite':
			var layer = new OpenLayers.Layer.Google(name, {type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 20});
			break;

		case 'bing_road':
			var layer = new OpenLayers.Layer.Bing({name: name, key: BingAPIKey, type: "Road"});
			break;
		case 'bing_hybrid':
			var layer = new OpenLayers.Layer.Bing({name: name, key: BingAPIKey, type: "AerialWithLabels"});
			break;
		case 'bing_aerial':
			var layer = new OpenLayers.Layer.Bing({name: name, key: BingAPIKey, type: "Aerial"});
			break;
	}
	map.addLayers([layer]);
	if(is_default === 1)
	{
		map.setBaseLayer(layer);
	}
}

usermap.load = function() {
	map = new OpenLayers.Map('map',{projection: 'EPSG:3857'});
	map.events.register("moveend",map,function(e){usermap.reload();$('#searchresult').hide();});

	// Handle touch events
	var timeout;
	map.events.register('touchstart', map, function(e) {
		if(e.touches.length > 1) {return;}
		usermap.hide_menu(true);
        s_touch = true;
        timeout = setTimeout(function() {
			var lonlat = map.getLonLatFromPixel(e.xy);
			pos= new OpenLayers.LonLat(lonlat.lon,lonlat.lat).transform(toProjection,fromProjection);
			$('#map_menu').css({'display':'block'});
			$('#map_menu').find('a').each(function() {
				var href = $(this).attr('href');
				$(this).attr('href', href.replace('LONLAT', 'lon='+pos.lon+'&lat='+pos.lat));
			});
         }, 1000);
     }, true);

    map.events.register('touchmove', map, function(e) {setTimeout(function(){clearTimeout(timeout);},1000);});
	map.events.register('touchend',map,function(e){clearTimeout(timeout);});

	layer_markers = new OpenLayers.Layer.Markers("", {
		projection: new OpenLayers.Projection("EPSG:4326"),
		visibility: true,
		displayInLayerSwitcher: false
	});
	layer_position_markers = new OpenLayers.Layer.Markers("", {
		projection: new OpenLayers.Projection("EPSG:4326"),
		visibility: true,
		displayInLayerSwitcher: false
	});
	map.addLayers([layer_markers, layer_position_markers]);
};

// A control class for capturing click events...
OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {
	defaultHandlerOptions: {
		'single': true,
		'double': false,
		'pixelTolerance': 0,
		'stopSingle': false,
		'stopDouble': false
	},
	handleRightClicks:true,
	initialize: function(options) {
		this.handlerOptions = OpenLayers.Util.extend(
			{}, this.defaultHandlerOptions
		);
		OpenLayers.Control.prototype.initialize.apply(
			this, arguments
		);
		this.handler = new OpenLayers.Handler.Click(
			this, this.eventMethods, this.handlerOptions
		);
	},
	CLASS_NAME: "OpenLayers.Control.Click"
});

// Add an instance of the Click control that listens to various click events:
click = new OpenLayers.Control.Click({eventMethods:{
	'rightclick': function(e) {
		var lonlat = map.getLonLatFromPixel(e.xy);
		pos= new OpenLayers.LonLat(lonlat.lon,lonlat.lat).transform(toProjection,fromProjection);
		usermap.display_menu(e, pos.lon,pos. lat);
		$('#searchresult').hide();
	},
	'click': function(e) {
		if(s_touch) return;
		usermap.hide_menu(true);
		$('#searchresult').hide();
	}
}});

// Get control of the right-click event:
document.getElementById('map').oncontextmenu = function(e){
	e = e?e:window.event;
	if (e.preventDefault) e.preventDefault(); // For non-IE browsers.
	   else return false; // For IE browsers.
};

phpbb.addAjaxCallback('usermap.set_position', function(response) {
	usermap.reload();
});

phpbb.addAjaxCallback('usermap.get_distance', function(response) {
	usermap.hide_menu(true);
});

usermap.reload = function() {
	var tlLonLat = map.getLonLatFromPixel(new OpenLayers.Pixel(1,1));
	var pos0= new OpenLayers.LonLat(tlLonLat.lon,tlLonLat.lat).transform(toProjection,fromProjection);

	var mapsize = map.getSize();
	var brLonLat = map.getLonLatFromPixel(new OpenLayers.Pixel(mapsize.w - 1, mapsize.h - 1));
	var pos1= new OpenLayers.LonLat(brLonLat.lon,brLonLat.lat).transform(toProjection,fromProjection);
	reload_marker(pos0.lon, pos0.lat, pos1.lon, pos1.lat);
};

usermap.display_menu=function(e, lon, lat) {
	usermap.hide_menu(true);
	$('#map_menu').css({'top':e.pageY,'left':e.pageX,'display':'block'});
	$('#map_menu').find('a').each(function() {
		var href = $(this).attr('href');
		$(this).attr('href', href.replace('LONLAT', 'lon='+lon+'&lat='+lat));
	});
};

usermap.hide_menu=function(full) {
	$('#map_menu').css('display','none');
	if(full) {
		$('#map_menu').find('a').each(function() {
			var href = $(this).attr('href');
			$(this).attr('href', href.replace(/&?lon=(.*)&lat=(.*)/gi, 'LONLAT'));
		});
	}
};

usermap.jumpTo=function(lon, lat, zoom) {
	var x = usermap.Lon2Merc(lon);
	var y = usermap.Lat2Merc(lat);
	map.setCenter(new OpenLayers.LonLat(x, y), zoom);
	return false;
};

usermap.Lon2Merc=function(lon) {
	return 20037508.34 * lon / 180;
};

usermap.Lat2Merc=function(lat) {
	var PI = 3.14159265358979323846;
	lat = Math.log(Math.tan( (90 + lat) * PI / 360)) / (PI / 180);
	return 20037508.34 * lat / 180;
};

usermap.generateMarker=function(image){
	var size = new OpenLayers.Size(26,26);
	var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
	var i = new OpenLayers.Icon(image, size, offset);
	return i;
};

usermap.addMarker=function(id, layer, lon, lat, popupContentHTML, marker) {

	if(marker_cache[id])
	{
		return;
	}

	marker_cache[id] = true;

	var ll = new OpenLayers.LonLat(usermap.Lon2Merc(lon), usermap.Lat2Merc(lat));
	var feature = new OpenLayers.Feature(layer, ll);
	feature.closeBox = true;
	feature.popupClass = OpenLayers.Class(OpenLayers.Popup.FramedCloud, {minSize: new OpenLayers.Size(100, 10) } );
	feature.data.popupContentHTML = popupContentHTML;
	feature.data.overflow = "hidden";

	var marker = new OpenLayers.Marker(ll, marker);
	marker.feature = feature;
	var markerClick = function(evt) {
		if (this.popup == null) {
			this.popup = this.createPopup(this.closeBox);
			map.addPopup(this.popup);
			this.popup.show();
		} else {
			this.popup.toggle();
		}
		OpenLayers.Event.stop(evt);
	};
	marker.events.register("mousedown", feature, markerClick);
	marker.events.register("touchstart", feature, markerClick);
	layer.addMarker(marker);
};

usermap.jump=function(lon, lat, text)
{
	layer_position_markers.clearMarkers();
	usermap.jumpTo(lon, lat, 13);
	usermap.addMarker(layer_position_markers, parseFloat(lon), parseFloat(lat), text, usermap.position_marker);
	return false;
};

$.event.special.inputchange = {
    setup: function() {
        var self = this, val;
        $.data(this, 'timer', window.setInterval(function() {
            val = self.value;
            if ( $.data( self, 'cache') != val ) {
                $.data( self, 'cache', val );
                $( self ).trigger( 'inputchange' );
            }
        }, 2000));
    },
    teardown: function() {
        window.clearInterval( $.data(this, 'timer') );
    },
    add: function() {
        $.data(this, 'cache', this.value);
    }
};

$('#mapsearch').on('inputchange', function() {
	$.getJSON('https://maps.google.com/maps/api/geocode/json?address=' + $(this).val(), function(returndata){
		$('#searchresult').html('');

		$.each( returndata.results, function( i, item ) {
			$('#searchresult').html($('#searchresult').html()+'<a href="#" onclick="return usermap.jump('+item.geometry.location.lng+','+item.geometry.location.lat+',\''+item.formatted_address+'\')">'+item.formatted_address+'</a><br>');
		});
		if($('#searchresult').html() !== '')
		{
			$('#searchresult').slideDown();
		}
	});
});
$('#mapsearch').click(function(){
	if($('#searchresult').html() !== '')
	{
		$('#searchresult').slideDown();
	}
});

})(jQuery);