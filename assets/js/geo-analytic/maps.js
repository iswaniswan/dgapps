/* ------------------------------------------------------------------------------
 *
 *  # Animated markers
 *
 *  Specific JS code additions for maps_google_markers.html page
 *
 * ---------------------------------------------------------------------------- */

// Setup module
// ------------------------------

var GoogleMapMarkerAnimation = (function () {
  //
  // Setup module components
  //

  // Line chart
  var _googleMapMarkerAnimation = function () {
    if (typeof google == "undefined") {
      console.warn("Warning - Google Maps library is not loaded.");
      return;
    }

    // If you're adding a number of markers, you may want to
    // drop them on the map consecutively rather than all at once.
    // This example shows how to use setTimeout() to space
    // your markers' animation.
    $.ajax({
      cache: false,
      dataType: "json",
      type: "post",
      url: base_url + "geo-analytic/getcity",
      async: false,
      data: {
        i_city: i_city,
      },
      success: function (result) {
        var latitude = result[0][0].latitude;
        var longitude = result[0][0].longitude;
        var markers = [];
        var infowindow = [];
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
          this.pixelOrigin_ = new google.maps.Point(
            TILE_SIZE / 2,
            TILE_SIZE / 2
          );
          this.pixelsPerLonDegree_ = TILE_SIZE / 360;
          this.pixelsPerLonRadian_ = TILE_SIZE / (2 * Math.PI);
        }

        // From latitude to longitude
        MercatorProjection.prototype.fromLatLngToPoint = function (
          latLng,
          opt_point
        ) {
          var me = this;
          var point = opt_point || new google.maps.Point(0, 0);
          var origin = me.pixelOrigin_;

          point.x = origin.x + latLng.lng() * me.pixelsPerLonDegree_;

          // Truncating to 0.9999 effectively limits latitude to 89.189. This is
          // about a third of a tile past the edge of the world tile.
          var siny = bound(
            Math.sin(degreesToRadians(latLng.lat())),
            -0.9999,
            0.9999
          );
          point.y =
            origin.y +
            0.5 * Math.log((1 + siny) / (1 - siny)) * -me.pixelsPerLonRadian_;
          return point;
        };

        // From longitude to latitude
        MercatorProjection.prototype.fromPointToLatLng = function (point) {
          var me = this;
          var origin = me.pixelOrigin_;
          var lng = (point.x - origin.x) / me.pixelsPerLonDegree_;
          var latRadians = (point.y - origin.y) / -me.pixelsPerLonRadian_;
          var lat = radiansToDegrees(
            2 * Math.atan(Math.exp(latRadians)) - Math.PI / 2
          );
          return new google.maps.LatLng(lat, lng);
        };

        function initialize() {
          // Define map element
          var map_marker_simple_element = document.getElementById("container");

          // Set coordinates
          var myLatlng = new google.maps.LatLng(latitude, longitude);

          // Options
          var mapOptions = {
            zoom: 10,
            center: myLatlng,
          };

          // Apply options
          var map = new google.maps.Map(map_marker_simple_element, mapOptions);
          for (let x = 0; x < result.data.length; x++) {
            function createInfoWindowContent() {
              var numTiles = 1 << map.getZoom();
              var projection = new MercatorProjection();
              var worldCoordinate = projection.fromLatLngToPoint(city);
              var pixelCoordinate = new google.maps.Point(
                worldCoordinate.x * numTiles,
                worldCoordinate.y * numTiles
              );
              var tileCoordinate = new google.maps.Point(
                Math.floor(pixelCoordinate.x / TILE_SIZE),
                Math.floor(pixelCoordinate.y / TILE_SIZE)
              );

              return [
                result.data[x].e_customer_name,
                "Visit : " + result.data[x].visit,
                "Sales Order : " +
                  parseInt(result.data[x].total_order)
                    .toFixed(0)
                    .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
                "LatLng: " +
                  result.data[x].latitude +
                  " , " +
                  result.data[x].longitude,
              ].join("<br>");
            }

            infowindow.push(
              new google.maps.InfoWindow({
                content: createInfoWindowContent(),
              })
            );
            // Add marker
            markers.push(
              new google.maps.Marker({
                position: new google.maps.LatLng(
                  result.data[x].latitude,
                  result.data[x].longitude
                ),
                map: map,
                draggable: false,
                // icon: 'https://maps.google.com/mapfiles/kml/shapes/' + result.data[x].type
              })
            );

            // Attach click event
            google.maps.event.addListener(markers[x], "click", function (evt) {
              infowindow[x].open(map, markers[x]);
            });
          }
        }

        // Initialize map on window load
        google.maps.event.addDomListener(window, "load", initialize);
      },
    });
  };

  //
  // Return objects assigned to module
  //

  return {
    init: function () {
      _googleMapMarkerAnimation();
    },
  };
})();

// Initialize module
// ------------------------------

document.addEventListener("DOMContentLoaded", function () {
  GoogleMapMarkerAnimation.init();
});
