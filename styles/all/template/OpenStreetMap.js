
OpenLayers.Layer.OSM.Mapnik = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://a.tile.openstreetmap.org/${z}/${x}/${y}.png",
            "https://b.tile.openstreetmap.org/${z}/${x}/${y}.png",
            "https://c.tile.openstreetmap.org/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 20,
            attribution: "&copy; <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> contributors",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.Mapnik"
});



OpenLayers.Layer.OSM.CycleMap = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "http://a.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",
            "http://b.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",
            "http://c.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 22,
            attribution: "&copy; <a href='http://opencyclemap.org/about/'>OpenCycleMap</a>",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.CycleMap"
});


OpenLayers.Layer.OSM.Landscape = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://a.tile.thunderforest.com/landscape/${z}/${x}/${y}.png",
            "https://b.tile.thunderforest.com/landscape/${z}/${x}/${y}.png",
            "https://c.tile.thunderforest.com/landscape/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 22,
            attribution: "&copy; <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> contributors",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.Landscape"
});


OpenLayers.Layer.OSM.TransportMap = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "http://a.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png",
            "http://b.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png",
            "http://c.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 22,
            attribution: "&copy; <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> contributors.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.TransportMap"
});


OpenLayers.Layer.OSM.Toner = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "http://a.tile.stamen.com/toner/${z}/${x}/${y}.png",
            "http://b.tile.stamen.com/toner/${z}/${x}/${y}.png",
            "http://c.tile.stamen.com/toner/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 18,
            attribution: "&copy; <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> contributors.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.Toner"
});

OpenLayers.Layer.OSM.Watercolor = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "http://a.tile.stamen.com/watercolor/${z}/${x}/${y}.png",
            "http://b.tile.stamen.com/watercolor/${z}/${x}/${y}.png",
            "http://c.tile.stamen.com/watercolor/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 15,
            attribution: "&copy; <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> contributors.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.Watercolor"
});

OpenLayers.Layer.OSM.Maptookit = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://tile1.maptoolkit.net/terrain/${z}/${x}/${y}.png",
            "https://tile2.maptoolkit.net/terrain/${z}/${x}/${y}.png",
			"https://tile3.maptoolkit.net/terrain/${z}/${x}/${y}.png",
			"https://tile4.maptoolkit.net/terrain/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 19,
            attribution: "&copy; <a href='http://www.toursprung.com/'>Toursprung GmbH</a> - &copy; Map Data: <a href='http://www.openstreetmap.org/copyright'>OSM Contributors</a>",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.Maptookit"
});

OpenLayers.Layer.OSM.OpenSnowMap = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "http://www.opensnowmap.org/opensnowmap-overlay/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 19,
            attribution: "&copy; <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> contributors.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.OpenSnowMap"
});

OpenLayers.Layer.OSM.Esri = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/${z}/${y}/${x}.jpg"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 20,
            attribution: "&copy; <a href='http://www.esri.com/'>Esri</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.Esri"
});

OpenLayers.Layer.OSM.EsriSatellite = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/${z}/${y}/${x}.jpg"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 20,
            attribution: "&copy; <a href='http://www.esri.com/'>Esri</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.EsriSatellite"
});

OpenLayers.Layer.OSM.EsriPhysical = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://server.arcgisonline.com/ArcGIS/rest/services/World_Physical_Map/MapServer/tile/${z}/${y}/${x}.jpg"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 9,
            attribution: "&copy; <a href='http://www.esri.com/'>Esri</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.EsriPhysical"
});

OpenLayers.Layer.OSM.EsriShadedRelief = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://server.arcgisonline.com/ArcGIS/rest/services/World_Shaded_Relief/MapServer/tile/${z}/${y}/${x}.jpg"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 13,
            attribution: "&copy; <a href='http://www.esri.com/'>Esri</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.EsriShadedRelief"
});

OpenLayers.Layer.OSM.EsriTerrain = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://server.arcgisonline.com/ArcGIS/rest/services/World_Terrain_Base/MapServer/tile/${z}/${y}/${x}.jpg"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 10,
            attribution: "&copy; <a href='http://www.esri.com/'>Esri</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.EsriTerrain"
});

OpenLayers.Layer.OSM.EsriTopo = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://services.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/${z}/${y}/${x}.jpg"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 20,
            attribution: "&copy; <a href='http://www.esri.com/'>Esri</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.EsriTopo"
});

OpenLayers.Layer.OSM.EsriGray = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://services.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/${z}/${y}/${x}.jpg"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 17,
            attribution: "&copy; <a href='http://www.esri.com/'>Esri</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.EsriGray"
});

OpenLayers.Layer.OSM.EsriNationalGeographic = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://services.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/tile/${z}/${y}/${x}.jpg"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 13,
            attribution: "&copy; <a href='http://www.esri.com/'>Esri</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.EsriNationalGeographic"
});


OpenLayers.Layer.OSM.EsriOcean = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://services.arcgisonline.com/ArcGIS/rest/services/Ocean/World_Ocean_Base/MapServer/tile/${z}/${y}/${x}.jpg"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 11,
            attribution: "&copy; <a href='http://www.esri.com/'>Esri</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.EsriOcean"
});

OpenLayers.Layer.OSM.Komoot = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "https://a.tile.hosted.thunderforest.com/komoot-2/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 19,
            attribution: "&copy; <a href='https://www.komoot.de/'>Komoot</a> | Map data &copy; <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> contributors.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.Komoot"
});

OpenLayers.Layer.OSM.CartoDBLight = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "http://a.basemaps.cartocdn.com/light_all/${z}/${x}/${y}.png",
			"http://b.basemaps.cartocdn.com/light_all/${z}/${x}/${y}.png",
			"http://c.basemaps.cartocdn.com/light_all/${z}/${x}/${y}.png",
			"http://d.basemaps.cartocdn.com/light_all/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 19,
            attribution: "&copy; <a href='https://carto.com/'>CARTO</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.CartoDBLight"
});

OpenLayers.Layer.OSM.CartoDBDark = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "http://a.basemaps.cartocdn.com/dark_all/${z}/${x}/${y}.png",
			"http://b.basemaps.cartocdn.com/dark_all/${z}/${x}/${y}.png",
			"http://c.basemaps.cartocdn.com/dark_all/${z}/${x}/${y}.png",
			"http://d.basemaps.cartocdn.com/dark_all/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 19,
            attribution: "&copy; <a href='https://carto.com/'>CARTO</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.CartoDBDark"
});

OpenLayers.Layer.OSM.Sputnik = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "http://a.tiles.maps.sputnik.ru/tiles/kmt2/${z}/${x}/${y}.png",
			"http://b.tiles.maps.sputnik.ru/tiles/kmt2/${z}/${x}/${y}.png",
			"http://c.tiles.maps.sputnik.ru/tiles/kmt2/${z}/${x}/${y}.png",
			"http://d.tiles.maps.sputnik.ru/tiles/kmt2/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 19,
            attribution: "&copy; <a href='http://www.sputnik.ru/'>Sputnik</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.Sputnik"
});

OpenLayers.Layer.OSM.Kosmosnimki = OpenLayers.Class(OpenLayers.Layer.OSM, {
    initialize: function(name, options) {
        var url = [
            "http://a.tile.osm.kosmosnimki.ru/kosmo/${z}/${x}/${y}.png",
			"http://b.tile.osm.kosmosnimki.ru/kosmo/${z}/${x}/${y}.png",
			"http://c.tile.osm.kosmosnimki.ru/kosmo/${z}/${x}/${y}.png",
			"http://d.tile.osm.kosmosnimki.ru/kosmo/${z}/${x}/${y}.png"
        ];
        options = OpenLayers.Util.extend({
            numZoomLevels: 19,
            attribution: "&copy; <a href='http://www.kosmosnimki.ru/'>Kosmosnimki</a>.",
            buffer: 0,
            transitionEffect: "resize"
        }, options);
        var newArguments = [name, url, options];
        OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
    },

    CLASS_NAME: "OpenLayers.Layer.OSM.Kosmosnimki"
});


