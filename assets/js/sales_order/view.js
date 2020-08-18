var swalInit = swal.mixin({
    buttonsStyling: false,
    confirmButtonClass: 'btn btn-primary',
    cancelButtonClass: 'btn btn-light'
});

function cancel(spb, area){
    swalInit({
        title: 'Are you sure cancel sales order '+ spb +' ?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false
    }).then(function(result) {
        if(result.value) {
            $.ajax({
                url: base_url + "sales_order/cancel_so",
                type: "post",
                data: {
                    'i_spb': spb,
                    'i_area': area,
                },
                success: function (response) {
                    window.location.replace(base_url + 'sales-order');
                },
            });

        }
        else if(result.dismiss === swal.DismissReason.cancel) {
            // swalInit(
            //     'Cancelled',
            //     'Your imaginary file is safe :)',
            //     'error'
            // );
        }
    });
};