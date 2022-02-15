var Product = (function () {
  // Select2 for length menu styling
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

    $(".dts").select2({
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
      _componentSelect2();
    },
  };
})();

document.addEventListener("DOMContentLoaded", function () {
  var controller = "product/serverside";
  var column = 4;
  datatable(controller, column);
  Product.init();
});

function change_status(i_product, i_company, val) {
  if (confirm("Change Status Product ?")) {
    val = val.value;

    $.ajax({
      type: "POST",
      url: base_url + "/product/change_status",
      data: { i_product: i_product, i_company: i_company, val: val },
      cache: false,
      dataType: "json",
      beforeSend: function () {
        $("#serverside").block({
          message: '<i class="icon-spinner2 spinner"></i>',
          overlayCSS: {
            backgroundColor: "#fff",
            opacity: 0.8,
            cursor: "wait",
            "box-shadow": "0 0 0 1px #ddd",
          },
          css: {
            border: 0,
            padding: 0,
            backgroundColor: "none",
          },
        });
      },
      success: function (response) {
        if (response.status == true) {
          var swalInit = swal.mixin({
            buttonsStyling: false,
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-light",
          });
          swalInit({
            title: "Good job!",
            text: "Success Change Status",
            type: "success",
            timer: 500,
          }).then(function () {
            let url = base_url + "/product";
            window.location.href = url;
          });
        } else {
          Swal.fire("Error !", "Sorry Error !", "error");
        }
        $("#serverside").unblock();
      },
    });
  } else {
    $(val).val(val.getAttribute("PrvSelectedValue"));
  }
}
