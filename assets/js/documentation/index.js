// Setup module
// ------------------------------

var MediaLibrary = (function () {
  //
  // Setup module components
  //

  // Datatable
  var _componentDatatable = function () {
    if (!$().DataTable) {
      console.warn("Warning - datatables.min.js is not loaded.");
      return;
    }

    // Initialize table
    var media_library = $(".media-library").DataTable({
      serverSide: true,
      autoWidth: false,
      processing: true,
      order: [[3, "desc"]],
      ajax: {
        url: base_url + "documentation/data_documentation",
        type: "post",
        error: function (data, err) {
          $(".media-library-error").html("");
          $(".media-library tbody").empty();
          $(".media-library").append(
            '<tr><td class="text-center" colspan="' +
              5 +
              '">No data available in table</td></tr>'
          );
          $(".media-library_processing").css("display", "none");
        },
        complete: function (data) {
          _componentFancybox();
          _componentUniform();
          _componentSelect2();
        },
      },
      jQueryUI: false,
      pagingType: "full_numbers",
      dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
      language: {
        infoPostFix: "",
        search: "<span>Search:</span> _INPUT_",
        url: "",
        paginate: {
          previous: $("html").attr("dir") == "rtl" ? "&rarr;" : "&larr;",
          next: $("html").attr("dir") == "rtl" ? "&larr;" : "&rarr;",
        },
      },
    });

    var media_library = $(".media-library2").DataTable({
      serverSide: true,
      autoWidth: false,
      processing: true,
      order: [[3, "desc"]],
      ajax: {
        url: base_url + "documentation/data_checkin",
        type: "post",
        error: function (data, err) {
          $(".media-library2-error").html("");
          $(".media-library2 tbody").empty();
          $(".media-library2").append(
            '<tr><td class="text-center" colspan="' +
              5 +
              '">No data available in table</td></tr>'
          );
          $(".media-library2_processing").css("display", "none");
        },
        complete: function (data) {
          _componentFancybox();
          _componentUniform();
          _componentSelect2();
        },
      },
      jQueryUI: false,
      pagingType: "full_numbers",
      dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
      language: {
        infoPostFix: "",
        search: "<span>Search:</span> _INPUT_",
        url: "",
        paginate: {
          previous: $("html").attr("dir") == "rtl" ? "&rarr;" : "&larr;",
          next: $("html").attr("dir") == "rtl" ? "&larr;" : "&rarr;",
        },
      },
    });
  };

  // Lightbox
  var _componentFancybox = function () {
    if (!$().fancybox) {
      console.warn("Warning - fancybox.min.js is not loaded.");
      return;
    }

    // Image lightbox
    $('[data-popup="lightbox"]').fancybox({
      padding: 3,
    });
  };

  // Uniform
  var _componentUniform = function () {
    if (!$().uniform) {
      console.warn("Warning - uniform.min.js is not loaded.");
      return;
    }

    // Initialize
    $(".form-input-styled").uniform();
  };

  // Select2
  var _componentSelect2 = function () {
    if (!$().select2) {
      console.warn("Warning - select2.min.js is not loaded.");
      return;
    }

    // Initialize
    $(".dataTables_length select").select2({
      minimumResultsForSearch: Infinity,
      dropdownAutoWidth: true,
      width: "auto",
    });
  };

  //
  // Return objects assigned to module
  //

  return {
    init: function () {
      _componentDatatable();
      _componentFancybox();
      _componentUniform();
      _componentSelect2();
    },
  };
})();

// Initialize module
// ------------------------------

document.addEventListener("DOMContentLoaded", function () {
  MediaLibrary.init();
  var controller = "documentation/serverside";
  var column = 8;
  datatable(controller, column);
});

function click_image(url, title) {
  swal
    .queue([
      {
        title: title,
        /* text: "Bootstrap is awesome.", */
        imageUrl: base_url + url,
        imageWidth: 500,
        // background: '#FFFF0080',
        background: "rgba(255,255,255,.6)",
        grow: "column",
        // backdrop: false,
        // grow: 'fullscreen',
        // backdrop-filter: 'blur(2px)',

        /* type: "error", */
        confirmButtonClass: "btn bg-slate-800",
        confirmButtonText: "Close",
      },
    ])
    .then(function (result) {
      // if (result.value) {
      //   // alert('x');
      //   $(".swal2-image").css({
      //     "-webkit-transform": "rotate(90deg)",
      //     "-moz-transform": "rotate(90deg)",
      //     transform: "rotate(90deg)" /* For modern browsers(CSS3)  */,
      //   });
      //   // return false;
      //   click_image(url, title);
      // }
      // console.log(result.value == true);
      // .css({
      //     "-webkit-transform": "rotate(90deg)",
      //     "-moz-transform": "rotate(90deg)",
      //     "transform": "rotate(90deg)" /* For modern browsers(CSS3)  */
      // });
      /* console.log($('.swal2-image').val()); */
    });
}
