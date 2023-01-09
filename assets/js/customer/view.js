var Customer = (function() {
  // Select2 for length menu styling
  var _componentSelect2 = function() {
    if (!$().select2) {
      console.warn("Warning - select2.min.js is not loaded.");
      return;
    }

    // Initialize
    $(".dataTables_length select").select2({
      minimumResultsForSearch: Infinity,
      dropdownAutoWidth: true,
      width: "auto"
    });

    $(".dts").select2({
      minimumResultsForSearch: Infinity,
      dropdownAutoWidth: true,
      width: "auto"
    });
  };

  var _googleMapCoordinates = function() {
    if (typeof google == "undefined") {
      console.warn("Warning - Google Maps library is not loaded.");
      return;
    }
    $.ajax({
      cache: false,
      dataType: "json",
      type: "post",
      url: base_url + "customer/getlocation",
      async: false,
      data: {
        i_customer: i_customer
      },
      success: function(result) {
        var latitude = result[0].latitude;
        var longitude = result[0].longitude;
        var e_customer_name = result[0].e_customer_name;
        var map;
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
        MercatorProjection.prototype.fromLatLngToPoint = function(
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
        MercatorProjection.prototype.fromPointToLatLng = function(point) {
          var me = this;
          var origin = me.pixelOrigin_;
          var lng = (point.x - origin.x) / me.pixelsPerLonDegree_;
          var latRadians = (point.y - origin.y) / -me.pixelsPerLonRadian_;
          var lat = radiansToDegrees(
            2 * Math.atan(Math.exp(latRadians)) - Math.PI / 2
          );
          return new google.maps.LatLng(lat, lng);
        };

        // Create content
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
            e_customer_name,
            "LatLng: " + city.lat() + " , " + city.lng()
          ].join("<br>");
        }

        // Initialize
        function initialize() {
          // Define map element
          var map_coordinates_element = document.getElementById("maps");

          // Options
          var mapOptions = {
            zoom: 10,
            center: city
          };

          // Apply options
          map = new google.maps.Map(map_coordinates_element, mapOptions);

          // Info window
          var coordInfoWindow = new google.maps.InfoWindow();
          coordInfoWindow.setContent(createInfoWindowContent());
          coordInfoWindow.setPosition(city);
          coordInfoWindow.open(map);

          // Add "Change" event
          google.maps.event.addListener(map, "zoom_changed", function() {
            coordInfoWindow.setContent(createInfoWindowContent());
            coordInfoWindow.open(map);
          });
        }

        // Load map
        google.maps.event.addDomListener(window, "load", initialize);
      }
    });
    // Variables
  };

  var _componentSweetAlert = function() {
    if (typeof swal == "undefined") {
      console.warn("Warning - sweet_alert.min.js is not loaded.");
      return;
    }
    var swalInit = swal.mixin({
      buttonsStyling: false,
      confirmButtonClass: "btn btn-primary",
      cancelButtonClass: "btn btn-light"
    });
    $("#edit").on("click", function() {
      swal
        .mixin({
          input: "text",
          confirmButtonText: 'Next <i class="icon-arrow-right14 ml-2"></i>',
          showCancelButton: true,
          inputClass: "form-control",
          animation: false,
          buttonsStyling: false,
          confirmButtonClass: "btn btn-primary",
          cancelButtonClass: "btn btn-light"
          // progressSteps: ["1", "2", "3"]
        })
        .queue([
          {
            title: "Change Address",
            text: "Step #1 - Address",
            inputPlaceholder: "Enter Address",
            inputValue: $("#address").val()
          },
          {
            title: "Change Address",
            text: "Step #2 - Latitude",
            inputPlaceholder: "Enter Latitude"
          },
          {
            title: "Change Address",
            text: "Step #3 - Longitude",
            inputPlaceholder: "Enter Longitude"
          }
        ])
        .then(function(result) {
          if (result.value) {
            $.ajax({
              url: base_url + "customer/change_address",
              type: "post",
              data: {
                i_customer: i_customer,
                data: result.value
              },
              dataType: "JSON",
              success: function(response) {
                if (response.status == "success") {
                  swalInit.fire({
                    type: "success",
                    title: "Change Address Success",
                    text: "Refresh to see changes"
                  });
                } else {
                  swalInit.fire({
                    type: "error",
                    title: "Latitude dan Longitude Tidak Boleh Ada Tanda Koma"
                  });
                }
              }
            });

            // swalInit({
            //   title: "All done!",
            //   html:
            //     'Your answers: <pre class="mt-3">' +
            //     JSON.stringify(result.value) +
            //     "</pre>",
            //   confirmButtonText: "Lovely!"
            // });
          }
        });
      //   return false;
    });
  };

  //
  // Return objects assigned to module
  //

  return {
    init: function() {
      _componentSelect2();
      _googleMapCoordinates();
      _componentSweetAlert();
    }
  };
})();

document.addEventListener("DOMContentLoaded", function() {
  var controller = "customer/view_serverside/" + i_customer;
  var column = 5;
  datatable(controller, column);
  Customer.init();
});
