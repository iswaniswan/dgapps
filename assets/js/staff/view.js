var Staff = function () {

    // Select2 for length menu styling
    var _componentSelect2 = function () {
        if (!$().select2) {
            console.warn('Warning - select2.min.js is not loaded.');
            return;
        }


        // Initialize
        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            dropdownAutoWidth: true,
            width: 'auto'
        });

        $('.dts').select2({
            minimumResultsForSearch: Infinity,
            dropdownAutoWidth: true,
            width: 'auto'
        });
    };

    var _googleMapMarkerAnimation = function () {
        if (typeof google == 'undefined') {
            console.warn('Warning - Google Maps library is not loaded.');
            return;
        }

        // If you're adding a number of markers, you may want to
        // drop them on the map consecutively rather than all at once.
        // This example shows how to use setTimeout() to space
        // your markers' animation.
        $.ajax({
            cache: false,
            dataType: 'json',
            type: 'post',
            url: base_url + 'staff/maps',
            async: false,
            data: {
                'username': username,
            },
            success: function (result) {

                var latitude = '-2.3931607';
                var longitude = '108.8376707';
                var markers = [];
                var infowindow = [];
                var waypoints = [];
                var TILE_SIZE = 256;
                var city = new google.maps.LatLng(latitude, longitude);

                // Minimum and maximum values
                function bound(value, opt_min, opt_max) {
                    if (opt_min != null) value = Math.max(value, opt_min);
                    if (opt_max != null) value = Math.min(value, opt_max);
                    return value;
                }

                // Degrees to radians
                function degreesToRadians(deg) {
                    return deg * (Math.PI / 180);
                }

                // Radians to degrees
                function radiansToDegrees(rad) {
                    return rad / (Math.PI / 180);
                }

                // Constructor
                function MercatorProjection() {
                    this.pixelOrigin_ = new google.maps.Point(TILE_SIZE / 2, TILE_SIZE / 2);
                    this.pixelsPerLonDegree_ = TILE_SIZE / 360;
                    this.pixelsPerLonRadian_ = TILE_SIZE / (2 * Math.PI);
                }

                // From latitude to longitude
                MercatorProjection.prototype.fromLatLngToPoint = function (latLng, opt_point) {
                    var me = this;
                    var point = opt_point || new google.maps.Point(0, 0);
                    var origin = me.pixelOrigin_;

                    point.x = origin.x + latLng.lng() * me.pixelsPerLonDegree_;

                    // Truncating to 0.9999 effectively limits latitude to 89.189. This is
                    // about a third of a tile past the edge of the world tile.
                    var siny = bound(Math.sin(degreesToRadians(latLng.lat())), -0.9999, 0.9999);
                    point.y = origin.y + 0.5 * Math.log((1 + siny) / (1 - siny)) * -me.pixelsPerLonRadian_;
                    return point;
                };

                // From longitude to latitude
                MercatorProjection.prototype.fromPointToLatLng = function (point) {
                    var me = this;
                    var origin = me.pixelOrigin_;
                    var lng = (point.x - origin.x) / me.pixelsPerLonDegree_;
                    var latRadians = (point.y - origin.y) / -me.pixelsPerLonRadian_;
                    var lat = radiansToDegrees(2 * Math.atan(Math.exp(latRadians)) - Math.PI / 2);
                    return new google.maps.LatLng(lat, lng);
                };


                function initialize() {

                    // Define map element
                    var map_marker_simple_element = document.getElementById('container');

                    // Set coordinates
                    var myLatlng = new google.maps.LatLng(latitude, longitude);

                    // Options
                    var mapOptions = {
                        zoom: 5,
                        center: myLatlng
                    };

                    // Apply options
                    var map = new google.maps.Map(map_marker_simple_element, mapOptions);
                    var directionsRenderer = new google.maps.DirectionsRenderer({
                        map: map,
                        suppressMarkers: true
                    });
                    var directionsService = new google.maps.DirectionsService;

                    for (let x = 0; x < result.data.length; x++) {

                        function createInfoWindowContent() {
                            var numTiles = 1 << map.getZoom();
                            var projection = new MercatorProjection();
                            var worldCoordinate = projection.fromLatLngToPoint(city);
                            var pixelCoordinate = new google.maps.Point(worldCoordinate.x * numTiles, worldCoordinate.y * numTiles);
                            var tileCoordinate = new google.maps.Point(
                                Math.floor(pixelCoordinate.x / TILE_SIZE),
                                Math.floor(pixelCoordinate.y / TILE_SIZE));

                            return [
                                result.data[x].e_customer_name,
                                'C/in : ' + result.data[x].createdat_checkin,
                                'C/out :' + result.data[x].createdat_checkout,
                                'LatLng: ' + result.data[x].latitude_checkin + ' , ' + result.data[x].longitude_checkin,
                            ].join('<br>');
                        }

                        infowindow.push(new google.maps.InfoWindow({
                            content: createInfoWindowContent()
                        }));
                        // Add marker
                        var label = (x + 1);

                        markers.push(new google.maps.Marker({
                            position: new google.maps.LatLng(result.data[x].latitude_checkin, result.data[x].longitude_checkin),
                            map: map,
                            draggable: false,
                            label: String(label)
                            // icon: 'https://maps.google.com/mapfiles/kml/shapes/' + result.data[x].type
                        }));

                        // Attach click event
                        google.maps.event.addListener(markers[x], 'click', function (evt) {
                            infowindow[x].open(map, markers[x]);
                        });

                        waypoints.push({
                            location: new google.maps.LatLng(result.data[x].latitude_checkin, result.data[x].longitude_checkin),
                            stopover: true
                        })

                    }

                    directionsService.route({
                        origin: {
                            lat: parseFloat(result.data[0].latitude_checkin),
                            lng: parseFloat(result.data[0].longitude_checkin)
                        },
                        destination: {
                            lat: parseFloat(result.data[result.data.length - 1].latitude_checkin),
                            lng: parseFloat(result.data[result.data.length - 1].longitude_checkin)
                        },
                        waypoints: waypoints,
                        travelMode: google.maps.TravelMode['WALKING']
                    }, function (response, status) {
                        if (status == 'OK') {
                            directionsRenderer.setDirections(response);
                        } else {
                            window.alert('Directions request failed due to ' + status);
                        }
                    });



                };

                // Initialize map on window load
                google.maps.event.addDomListener(window, 'load', initialize);
            }
        });

        $.ajax({
            cache: false,
            dataType: 'json',
            type: 'post',
            url: base_url + 'staff/tracking',
            async: false,
            data: {
                'username': username,
            },
            success: function (result) {

                var latitude = '-2.3931607';
                var longitude = '108.8376707';
                var markers = [];
                var infowindow = [];
                var waypoints = [];
                var TILE_SIZE = 256;
                var city = new google.maps.LatLng(latitude, longitude);

                // Minimum and maximum values
                function bound(value, opt_min, opt_max) {
                    if (opt_min != null) value = Math.max(value, opt_min);
                    if (opt_max != null) value = Math.min(value, opt_max);
                    return value;
                }

                // Degrees to radians
                function degreesToRadians(deg) {
                    return deg * (Math.PI / 180);
                }

                // Radians to degrees
                function radiansToDegrees(rad) {
                    return rad / (Math.PI / 180);
                }

                // Constructor
                function MercatorProjection() {
                    this.pixelOrigin_ = new google.maps.Point(TILE_SIZE / 2, TILE_SIZE / 2);
                    this.pixelsPerLonDegree_ = TILE_SIZE / 360;
                    this.pixelsPerLonRadian_ = TILE_SIZE / (2 * Math.PI);
                }

                // From latitude to longitude
                MercatorProjection.prototype.fromLatLngToPoint = function (latLng, opt_point) {
                    var me = this;
                    var point = opt_point || new google.maps.Point(0, 0);
                    var origin = me.pixelOrigin_;

                    point.x = origin.x + latLng.lng() * me.pixelsPerLonDegree_;

                    // Truncating to 0.9999 effectively limits latitude to 89.189. This is
                    // about a third of a tile past the edge of the world tile.
                    var siny = bound(Math.sin(degreesToRadians(latLng.lat())), -0.9999, 0.9999);
                    point.y = origin.y + 0.5 * Math.log((1 + siny) / (1 - siny)) * -me.pixelsPerLonRadian_;
                    return point;
                };

                // From longitude to latitude
                MercatorProjection.prototype.fromPointToLatLng = function (point) {
                    var me = this;
                    var origin = me.pixelOrigin_;
                    var lng = (point.x - origin.x) / me.pixelsPerLonDegree_;
                    var latRadians = (point.y - origin.y) / -me.pixelsPerLonRadian_;
                    var lat = radiansToDegrees(2 * Math.atan(Math.exp(latRadians)) - Math.PI / 2);
                    return new google.maps.LatLng(lat, lng);
                };


                function initialize() {
                    // Define map element
                    var map_marker_simple_element = document.getElementById('map-tracking');

                    // Set coordinates
                    var myLatlng = new google.maps.LatLng(latitude, longitude);

                    // Options
                    var mapOptions = {
                        zoom: 5,
                        center: myLatlng
                    };

                    // Apply options
                    var map = new google.maps.Map(map_marker_simple_element, mapOptions);
                    var directionsRenderer = new google.maps.DirectionsRenderer({
                        map: map,
                        suppressMarkers: true
                    });
                    var directionsService = new google.maps.DirectionsService;

                    for (let x = 0; x < result.data.length; x++) {

                        function createInfoWindowContent() {
                            var numTiles = 1 << map.getZoom();
                            var projection = new MercatorProjection();
                            var worldCoordinate = projection.fromLatLngToPoint(city);
                            var pixelCoordinate = new google.maps.Point(worldCoordinate.x * numTiles, worldCoordinate.y * numTiles);


                            return [
                                'Name : ' + result.data[x].e_name,
                                'Last Seen :' + result.data[x].createdat,
                            ].join('<br>');
                        }

                        infowindow.push(new google.maps.InfoWindow({
                            content: createInfoWindowContent()
                        }));
                        // Add marker
                        var label = (x + 1);

                        if ((x == 0)) {
                            markers.push(new google.maps.Marker({
                                position: new google.maps.LatLng(parseFloat(result.data[x].latitude), parseFloat(result.data[x].longitude)),
                                map: map,
                                draggable: false,
                                label: String('1')
                            }));

                            google.maps.event.addListener(markers[0], 'click', function (evt) {
                                infowindow[x].open(map, markers[0]);
                                map.setZoom(15);
                                map.panTo(markers[0].position);
                            });
                        }

                        if (parseInt(label) == parseInt(result.data.length)) {
                            markers.push(new google.maps.Marker({
                                position: new google.maps.LatLng(parseFloat(result.data[x].latitude), parseFloat(result.data[x].longitude)),
                                map: map,
                                draggable: false,
                                label: String('2')
                            }));

                            google.maps.event.addListener(markers[1], 'click', function (evt) {
                                infowindow[x].open(map, markers[1]);
                                map.setZoom(15);
                                map.panTo(markers[1].position);
                            });
                        }


                    }


                    for (var i = 0, parts = [], max = 25 - 1; i < result.data.length; i = i + max) {
                        parts.push(result.data.slice(i, i + max + 1));
                    }
                    var service_callback = function (response, status) {
                        if (status != 'OK') {
                            console.log('Directions request failed due to ' + status);
                            return;
                        }
                        var renderer = new google.maps.DirectionsRenderer;
                        renderer.setMap(map);
                        renderer.setOptions({
                            suppressMarkers: true,
                            preserveViewport: true
                        });
                        renderer.setDirections(response);
                    };

                    for (var i = 0; i < parts.length; i++) {
                        var waypoints = [];
                        for (var j = 1; j < parts[i].length - 1; j++) {

                            waypoints.push({
                                location: new google.maps.LatLng(parts[i][j].latitude, parts[i][j].longitude),
                                stopover: true
                            })

                        }

                        // Service options
                        var service_options = {
                            origin: {
                                lat: parseFloat(parts[i][0].latitude),
                                lng: parseFloat(parts[i][0].longitude)
                            },
                            destination: {
                                lat: parseFloat(parts[i][parts[i].length - 1].latitude),
                                lng: parseFloat(parts[i][parts[i].length - 1].longitude)
                            },
                            waypoints: waypoints,
                            travelMode: 'WALKING'
                        };
                        // Send request
                        directionsService.route(service_options, service_callback);
                    }


                };

                // Initialize map on window load
                google.maps.event.addDomListener(window, 'load', initialize);
            }
        });
    };

    var _componentUiDatepicker = function () {
        if (!$().datepicker) {
            console.warn('Warning - jQuery UI components are not loaded.');
            return;
        }

        $('#datepicker-checkin').datepicker({
            numberOfMonths: 1,
            dateFormat: 'dd-mm-yy',
            onClose: function (selectedDate) {
                change_date(selectedDate);
            },
            isRTL: $('html').attr('dir') == 'rtl' ? true : false
        });

        $('#datepicker-tracking').datepicker({
            numberOfMonths: 1,
            dateFormat: 'dd-mm-yy',
            onClose: function (selectedDate) {
                change_date_tracking(selectedDate);
            },
            isRTL: $('html').attr('dir') == 'rtl' ? true : false
        });

        function change_date(date) {
            $.ajax({
                cache: false,
                dataType: 'json',
                type: 'POST',
                url: base_url + 'staff/maps',
                data: {
                    'date': date,
                    'username': username,
                },
                beforeSend: function () {
                    $('#container').block({
                        message: '<i class="icon-spinner2 spinner"></i>',
                        overlayCSS: {
                            backgroundColor: '#fff',
                            opacity: 0.8,
                            cursor: 'wait',
                            'box-shadow': '0 0 0 1px #ddd'
                        },
                        css: {
                            border: 0,
                            padding: 0,
                            backgroundColor: 'none'
                        }
                    });
                },
                success: function (result) {

                    var latitude = '-2.3931607';
                    var longitude = '108.8376707';
                    var markers = [];
                    var infowindow = [];
                    var waypoints = [];
                    var TILE_SIZE = 256;
                    var city = new google.maps.LatLng(latitude, longitude);
                    // Minimum and maximum values
                    function bound(value, opt_min, opt_max) {
                        if (opt_min != null) value = Math.max(value, opt_min);
                        if (opt_max != null) value = Math.min(value, opt_max);
                        return value;
                    }

                    // Degrees to radians
                    function degreesToRadians(deg) {
                        return deg * (Math.PI / 180);
                    }

                    // Radians to degrees
                    function radiansToDegrees(rad) {
                        return rad / (Math.PI / 180);
                    }

                    // Constructor
                    function MercatorProjection() {
                        this.pixelOrigin_ = new google.maps.Point(TILE_SIZE / 2, TILE_SIZE / 2);
                        this.pixelsPerLonDegree_ = TILE_SIZE / 360;
                        this.pixelsPerLonRadian_ = TILE_SIZE / (2 * Math.PI);
                    }

                    // From latitude to longitude
                    MercatorProjection.prototype.fromLatLngToPoint = function (latLng, opt_point) {
                        var me = this;
                        var point = opt_point || new google.maps.Point(0, 0);
                        var origin = me.pixelOrigin_;

                        point.x = origin.x + latLng.lng() * me.pixelsPerLonDegree_;

                        // Truncating to 0.9999 effectively limits latitude to 89.189. This is
                        // about a third of a tile past the edge of the world tile.
                        var siny = bound(Math.sin(degreesToRadians(latLng.lat())), -0.9999, 0.9999);
                        point.y = origin.y + 0.5 * Math.log((1 + siny) / (1 - siny)) * -me.pixelsPerLonRadian_;
                        return point;
                    };

                    // From longitude to latitude
                    MercatorProjection.prototype.fromPointToLatLng = function (point) {
                        var me = this;
                        var origin = me.pixelOrigin_;
                        var lng = (point.x - origin.x) / me.pixelsPerLonDegree_;
                        var latRadians = (point.y - origin.y) / -me.pixelsPerLonRadian_;
                        var lat = radiansToDegrees(2 * Math.atan(Math.exp(latRadians)) - Math.PI / 2);
                        return new google.maps.LatLng(lat, lng);
                    };

                    function initialize() {
                        // Define map element
                        var map_marker_simple_element = document.getElementById('container');

                        // Set coordinates
                        var myLatlng = new google.maps.LatLng(latitude, longitude);

                        // Options
                        var mapOptions = {
                            zoom: 5,
                            center: myLatlng
                        };

                        // Apply options
                        var map = new google.maps.Map(map_marker_simple_element, mapOptions);
                        var directionsRenderer = new google.maps.DirectionsRenderer({
                            map: map,
                            suppressMarkers: true
                        });
                        var directionsService = new google.maps.DirectionsService;

                        for (let x = 0; x < result.data.length; x++) {

                            function createInfoWindowContent() {
                                var numTiles = 1 << map.getZoom();
                                var projection = new MercatorProjection();
                                var worldCoordinate = projection.fromLatLngToPoint(city);
                                var pixelCoordinate = new google.maps.Point(worldCoordinate.x * numTiles, worldCoordinate.y * numTiles);
                                var tileCoordinate = new google.maps.Point(
                                    Math.floor(pixelCoordinate.x / TILE_SIZE),
                                    Math.floor(pixelCoordinate.y / TILE_SIZE));

                                return [
                                    result.data[x].e_customer_name,
                                    'C/in : ' + result.data[x].createdat_checkin,
                                    'C/out :' + result.data[x].createdat_checkout,
                                    'LatLng: ' + result.data[x].latitude_checkin + ' , ' + result.data[x].longitude_checkin,
                                ].join('<br>');
                            }

                            infowindow.push(new google.maps.InfoWindow({
                                content: createInfoWindowContent()
                            }));
                            // Add marker
                            var label = (x + 1);

                            markers.push(new google.maps.Marker({
                                position: new google.maps.LatLng(result.data[x].latitude_checkin, result.data[x].longitude_checkin),
                                map: map,
                                draggable: false,
                                label: String(label)
                                // icon: 'https://maps.google.com/mapfiles/kml/shapes/' + result.data[x].type
                            }));

                            // Attach click event
                            google.maps.event.addListener(markers[x], 'click', function (evt) {
                                infowindow[x].open(map, markers[x]);
                            });

                            waypoints.push({
                                location: new google.maps.LatLng(result.data[x].latitude_checkin, result.data[x].longitude_checkin),
                                stopover: true
                            })

                        }
                        directionsService.route({
                            origin: {
                                lat: parseFloat(result.data[0].latitude_checkin),
                                lng: parseFloat(result.data[0].longitude_checkin)
                            },
                            destination: {
                                lat: parseFloat(result.data[result.data.length - 1].latitude_checkin),
                                lng: parseFloat(result.data[result.data.length - 1].longitude_checkin)
                            },
                            waypoints: waypoints,
                            travelMode: google.maps.TravelMode['WALKING']
                        }, function (response, status) {
                            if (status == 'OK') {
                                directionsRenderer.setDirections(response);
                            } else {
                                window.alert('Directions request failed due to ' + status);
                            }
                        });
                    };
                    // Initialize map on window load
                    initialize();
                    $('#container').unblock();
                }
            });
        }

        function change_date_tracking(date) {
            $.ajax({
                cache: false,
                dataType: 'json',
                type: 'POST',
                url: base_url + 'staff/tracking',
                data: {
                    'date': date,
                    'username': username,
                },
                beforeSend: function () {
                    $('#map-tracking').block({
                        message: '<i class="icon-spinner2 spinner"></i>',
                        overlayCSS: {
                            backgroundColor: '#fff',
                            opacity: 0.8,
                            cursor: 'wait',
                            'box-shadow': '0 0 0 1px #ddd'
                        },
                        css: {
                            border: 0,
                            padding: 0,
                            backgroundColor: 'none'
                        }
                    });
                },
                success: function (result) {

                    var latitude = '-2.3931607';
                    var longitude = '108.8376707';
                    var markers = [];
                    var infowindow = [];
                    var waypoints = [];
                    var TILE_SIZE = 256;
                    var city = new google.maps.LatLng(latitude, longitude);
                    // Minimum and maximum values
                    function bound(value, opt_min, opt_max) {
                        if (opt_min != null) value = Math.max(value, opt_min);
                        if (opt_max != null) value = Math.min(value, opt_max);
                        return value;
                    }

                    // Degrees to radians
                    function degreesToRadians(deg) {
                        return deg * (Math.PI / 180);
                    }

                    // Radians to degrees
                    function radiansToDegrees(rad) {
                        return rad / (Math.PI / 180);
                    }

                    // Constructor
                    function MercatorProjection() {
                        this.pixelOrigin_ = new google.maps.Point(TILE_SIZE / 2, TILE_SIZE / 2);
                        this.pixelsPerLonDegree_ = TILE_SIZE / 360;
                        this.pixelsPerLonRadian_ = TILE_SIZE / (2 * Math.PI);
                    }

                    // From latitude to longitude
                    MercatorProjection.prototype.fromLatLngToPoint = function (latLng, opt_point) {
                        var me = this;
                        var point = opt_point || new google.maps.Point(0, 0);
                        var origin = me.pixelOrigin_;

                        point.x = origin.x + latLng.lng() * me.pixelsPerLonDegree_;

                        // Truncating to 0.9999 effectively limits latitude to 89.189. This is
                        // about a third of a tile past the edge of the world tile.
                        var siny = bound(Math.sin(degreesToRadians(latLng.lat())), -0.9999, 0.9999);
                        point.y = origin.y + 0.5 * Math.log((1 + siny) / (1 - siny)) * -me.pixelsPerLonRadian_;
                        return point;
                    };

                    // From longitude to latitude
                    MercatorProjection.prototype.fromPointToLatLng = function (point) {
                        var me = this;
                        var origin = me.pixelOrigin_;
                        var lng = (point.x - origin.x) / me.pixelsPerLonDegree_;
                        var latRadians = (point.y - origin.y) / -me.pixelsPerLonRadian_;
                        var lat = radiansToDegrees(2 * Math.atan(Math.exp(latRadians)) - Math.PI / 2);
                        return new google.maps.LatLng(lat, lng);
                    };

                    function initialize() {
                        // Define map element
                        var map_marker_simple_element = document.getElementById('map-tracking');

                        // Set coordinates
                        var myLatlng = new google.maps.LatLng(latitude, longitude);

                        // Options
                        var mapOptions = {
                            zoom: 5,
                            center: myLatlng
                        };

                        // Apply options
                        var map = new google.maps.Map(map_marker_simple_element, mapOptions);
                        var directionsRenderer = new google.maps.DirectionsRenderer({
                            map: map,
                            suppressMarkers: true
                        });
                        var directionsService = new google.maps.DirectionsService;

                        for (let x = 0; x < result.data.length; x++) {

                            function createInfoWindowContent() {
                                var numTiles = 1 << map.getZoom();
                                var projection = new MercatorProjection();
                                var worldCoordinate = projection.fromLatLngToPoint(city);
                                var pixelCoordinate = new google.maps.Point(worldCoordinate.x * numTiles, worldCoordinate.y * numTiles);


                                return [
                                    'Name : ' + result.data[x].e_name,
                                    'Last Seen :' + result.data[x].createdat,
                                ].join('<br>');
                            }

                            infowindow.push(new google.maps.InfoWindow({
                                content: createInfoWindowContent()
                            }));
                            // Add marker
                            var label = (x + 1);

                            if ((x == 0)) {
                                markers.push(new google.maps.Marker({
                                    position: new google.maps.LatLng(parseFloat(result.data[x].latitude), parseFloat(result.data[x].longitude)),
                                    map: map,
                                    draggable: false,
                                    label: String('1')
                                }));

                                google.maps.event.addListener(markers[0], 'click', function (evt) {
                                    infowindow[x].open(map, markers[0]);
                                    map.setZoom(15);
                                    map.panTo(markers[0].position);
                                });
                            }

                            if (parseInt(label) == parseInt(result.data.length)) {
                                markers.push(new google.maps.Marker({
                                    position: new google.maps.LatLng(parseFloat(result.data[x].latitude), parseFloat(result.data[x].longitude)),
                                    map: map,
                                    draggable: false,
                                    label: String('2')
                                }));

                                google.maps.event.addListener(markers[1], 'click', function (evt) {
                                    infowindow[x].open(map, markers[1]);
                                    map.setZoom(15);
                                    map.panTo(markers[1].position);
                                });
                            }


                        }


                        for (var i = 0, parts = [], max = 25 - 1; i < result.data.length; i = i + max) {
                            parts.push(result.data.slice(i, i + max + 1));
                        }
                        var service_callback = function (response, status) {
                            if (status != 'OK') {
                                console.log('Directions request failed due to ' + status);
                                return;
                            }
                            var renderer = new google.maps.DirectionsRenderer;
                            renderer.setMap(map);
                            renderer.setOptions({
                                suppressMarkers: true,
                                preserveViewport: true
                            });
                            renderer.setDirections(response);
                        };

                        for (var i = 0; i < parts.length; i++) {
                            var waypoints = [];
                            for (var j = 1; j < parts[i].length - 1; j++) {

                                waypoints.push({
                                    location: new google.maps.LatLng(parts[i][j].latitude, parts[i][j].longitude),
                                    stopover: true
                                })

                            }

                            // Service options
                            var service_options = {
                                origin: {
                                    lat: parseFloat(parts[i][0].latitude),
                                    lng: parseFloat(parts[i][0].longitude)
                                },
                                destination: {
                                    lat: parseFloat(parts[i][parts[i].length - 1].latitude),
                                    lng: parseFloat(parts[i][parts[i].length - 1].longitude)
                                },
                                waypoints: waypoints,
                                travelMode: 'WALKING'
                            };
                            // Send request
                            directionsService.route(service_options, service_callback);
                        }


                    };
                    // Initialize map on window load
                    initialize();
                    $('#map-tracking').unblock();
                }
            });
        }

    };

    var _componentFullCalendarStyling = function () {
        if (typeof FullCalendar == 'undefined') {
            console.warn('Warning - Fullcalendar files are not loaded.');
            return;
        }


        // Add events
        // ------------------------------
        // Define element
        var calendarEventColorsElement = document.querySelector('.fullcalendar-event-colors');

        // Initialize
        if (calendarEventColorsElement) {
            var calendarEventColorsInit = new FullCalendar.Calendar(calendarEventColorsElement, {
                plugins: ['dayGrid', 'interaction'],
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,dayGridDay'
                },
                editable: true,
                events: {
                    url: base_url + 'staff/journey_plan',
                    method: 'POST',
                    extraParams: {
                        username: username,
                    },
                }
            }).render();
            //$('.fc-title').css('font-size', '1px');
            //$('#journey_id').collapse('toggle');
        }


    };
    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _componentSelect2();
            _googleMapMarkerAnimation();
            _componentUiDatepicker();
            _componentFullCalendarStyling();

        }
    }
}();

document.addEventListener('DOMContentLoaded', function () {
    var controller = 'staff/view_serverside/' + username;
    var column = 5;
    datatable(controller, column);
    Staff.init();
});

$( document ).ready(function() {
        // $(".fc-title").css({
        //     'cssText':'font-size:0.1rem !important'
        // });
    $('.fc-event-container').css('word-wrap', 'break-word !important');
});

