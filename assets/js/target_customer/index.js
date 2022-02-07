var Customer = function() {

    // Select2 for length menu styling
    var _componentSelect2 = function() {
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

    return {
        init: function() {
            _componentSelect2();
        }
    }
}();

document.addEventListener('DOMContentLoaded', function() {
    var controller = 'target_customer/serverside';
    var column = 6;
    datatable(controller, column);
    Customer.init();
});

var swalInit = swal.mixin({
    buttonsStyling: false,
    confirmButtonClass: 'btn btn-primary',
    cancelButtonClass: 'btn btn-light'
});

function change_password(id) {
    swalInit.fire({
        title: 'Change Password',
        input: 'password',
        inputClass: 'form-control',
        inputPlaceholder: 'Enter New Password',
        inputAttributes: {
            'minlength': 5,
            'autocapitalize': 'off',
            'autocorrect': 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Save',
        showLoaderOnConfirm: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
    }).then(function(result) {
        if (result.value) {

            $.ajax({
                url: base_url + "target_customer/change_password",
                type: "post",
                data: {
                    'username': id,
                    'password': result.value,
                },
                success: function(response) {

                    swalInit.fire({
                        type: 'success',
                        html: 'Change Password Success'
                    });
                },
            });


        }
    });
}