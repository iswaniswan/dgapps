var Push = function () {

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

document.addEventListener('DOMContentLoaded', function () {
    var controller = 'push/serverside';
    var column = 6;
    datatable(controller, column);
    Push.init();
});
