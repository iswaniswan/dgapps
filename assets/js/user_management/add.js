var Customer = (function() {
  // Select2 for length menu styling
  var _componentSelect2 = function() {
    if (!$().select2) {
      console.warn("Warning - select2.min.js is not loaded.");
      return;
    }
    $(".select-search").select2();

    $("#coverage_area_checkbox").click(function(){
      const selectAll = $(this).is(':checked');
      if (selectAll) {
        $("#coverage_area_select > option").prop("selected","selected");
      } else {
        $('#coverage_area_select').val(null).trigger('change');
      }
      $("#coverage_area_select").trigger("change");
    });
  };

  var _componentValidation = function() {
    if (!$().validate) {
      console.warn("Warning - validate.min.js is not loaded.");
      return;
    }

    // Initialize
    var validator = $(".form-validate").validate({
      ignore: "input[type=hidden], .select2-search__field", // ignore hidden fields
      errorClass: "validation-invalid-label",
      successClass: "validation-valid-label",
      validClass: "validation-valid-label",
      highlight: function(element, errorClass) {
        $(element).removeClass(errorClass);
      },
      unhighlight: function(element, errorClass) {
        $(element).removeClass(errorClass);
      },
      success: function(label) {
        label.addClass("validation-valid-label").text("Success."); // remove to hide Success message
      },

      // Different components require proper error label placement
      errorPlacement: function(error, element) {
        // Unstyled checkboxes, radios
        if (element.parents().hasClass("form-check")) {
          error.appendTo(element.parents(".form-check").parent());
        }

        // Input with icons and Select2
        else if (
          element.parents().hasClass("form-group-feedback") ||
          element.hasClass("select2-hidden-accessible")
        ) {
          error.appendTo(element.parent());
        }

        // Input group, styled file input
        else if (
          element.parent().is(".uniform-uploader, .uniform-select") ||
          element.parents().hasClass("input-group")
        ) {
          error.appendTo(element.parent().parent());
        }

        // Other elements
        else {
          error.insertAfter(element);
        }
      },
      rules: {
        phone: {
          maxlength: 15
        },
        address: {
          minlength: 1
        },
        e_name: {
          minlength: 1,
          maxlength: 50
        },
        email: {
          maxlength: 50
        },
        e_password: {
          minlength: 5,
          maxlength: 50
        },
        i_staff: {
          maxlength: 2
        }
      },
      messages: {
        i_role: {
          required: "Select your Role"
        },
        e_password: {
          required: "Enter your Password"
        },
        i_area: {
          required: "Select your Area"
        },
        f_active: {
          required: "Select your Status"
        },
        address: {
          required: "Enter your Address"
        },
        e_name: {
          required: "Enter your Name"
        },
        phone: {
          required: "Enter your Phone No"
        },
        email: {
          required: "Enter your Email"
        }
      }
    });
  };

  //
  // Return objects assigned to module
  //

  return {
    init: function() {
      _componentSelect2();
      _componentValidation();
    }
  };
})();

document.addEventListener("DOMContentLoaded", function() {
  Customer.init();
});
