/* ------------------------------------------------------------------------------
 *
 *  # Animated markers
 *
 *  Specific JS code additions for maps_google_markers.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var GoogleMapMarkerAnimation = function () {


    //
    // Setup module components
    //

    // Line chart
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
            url: base_url + 'geo-analytic/getcity',
            async: false,
            data: {
                'i_city': i_city,
            },
            success: function (result) {

                var latitude = result[0][0].latitude;
                var longitude = result[0][0].longitude;

                // Add Berlin coordinates
                var city = new google.maps.LatLng(latitude, longitude);
                var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';

                var icons = {
                    parking: {
                        icon: iconBase + 'parking_lot_maps.png'
                    },
                    library: {
                        icon: iconBase + 'library_maps.png'
                    },
                    info: {
                        icon: iconBase + 'info-i_maps.png'
                    }
                };

                // Add neighborhoods coordinates
                // var neighborhoods = [
                //     {
                //         position: new google.maps.LatLng(-7.327016, 108.354368),
                //         type: 'parking'
                //       }, {
                //         position: new google.maps.LatLng(-7.329116, 108.352753),
                //         type: 'library'
                //       }, {
                //         position: new google.maps.LatLng(-7.326285, 108.350017),
                //         type: 'info'
                //       }
                // ];
                var neighborhoods = [];
                for (let x = 0; x < result.data.length; x++) {
                    neighborhoods.push({
                        position: new google.maps.LatLng(result.data[x].latitude, result.data[x].longitude),
                        type: result.data[x].type
                    });
                }

                // Other variables
                var markers = [];
                var iterator = 0;
                var map;

                // Initialize
                function initialize() {

                    // Define map element
                    var map_marker_animation_element = document.getElementById('container');

                    // Options
                    var mapOptions = {
                        zoom: 12,
                        center: city
                    };

                    // Apply options
                    map = new google.maps.Map(map_marker_animation_element, mapOptions);
                    drop();
                }

                // Drop markers
                function drop() {
                    for (var i = 0; i < result.data.length; i++) {
                        setTimeout(function () {
                            addMarker();
                        }, i * 50);
                    }
                }

                // Add markers
                function addMarker() {

                    markers.push(new google.maps.Marker({
                        position: neighborhoods[iterator].position,
                        map: map,
                        draggable: false,
                        icon: icons[neighborhoods[iterator].type].icon,
                        animation: google.maps.Animation.DROP
                    }));
                    iterator++;
                }

                // Initialize map on window load
                google.maps.event.addDomListener(window, 'load', initialize);

                // end map
            }
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _googleMapMarkerAnimation();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    GoogleMapMarkerAnimation.init();
});