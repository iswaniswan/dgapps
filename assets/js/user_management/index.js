var Customer = function () {

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

    return {
        init: function () {
            _componentSelect2();
        }
    }
}();

function loadDatatable(link, column) {
    $("#serverside").DataTable({
        serverSide: true,
        autoWidth: false,
        processing: true,
        ajax: {
            url: base_url + link,
            type: "post",
            error: function(data, err) {
                $(".serverside-error").html("");
                $("#serverside tbody").empty();
                $("#serverside").append(
                    '<tr><td class="text-center" colspan="' +
                    column +
                    '">No data available in table</td></tr>'
                );
                $("#serverside_processing").css("display", "none");
            },
        },
        jQueryUI: false,
        autoWidth: false,
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
        order: [[5, "DESC"], [0, "ASC"]]
    });
}

document.addEventListener('DOMContentLoaded', function () {
    var controller = 'user_management/serverside';
    var column = 7;
    loadDatatable(controller, column);
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
    }).then(function (result) {
        if (result.value) {

            $.ajax({
                url: base_url + "user_management/change_password",
                type: "post",
                data: {
                    'username': id,
                    'password': result.value,
                },
                success: function (response) {

                    swalInit.fire({
                        type: 'success',
                        html: 'Change Password Success'
                    });
                },
            });


        }
    });
}