/* ------------------------------------------------------------------------------
 *
 *  # Echarts - Pie and Donut charts
 *
 *  Demo JS code for echarts_pies_donuts.html page
 *
 * ---------------------------------------------------------------------------- */

// Setup module
// ------------------------------

var JqueryUiForms = (function() {
    //
    // Setup module components
    //

    // Datepicker
    var _componentUiDatepicker = function() {
        if (!$().datepicker) {
            console.warn("Warning - jQuery UI components are not loaded.");
            return;
        }

        //
        // Date range
        //

        // From
        $("#range-from").datepicker({
            // defaultDate: '+1w',
            numberOfMonths: 1,
            dateFormat: "dd-mm-yy",
            onClose: function(selectedDate) {
                $("#range-to").datepicker("option", "minDate", selectedDate);
            },
            isRTL: $("html").attr("dir") == "rtl" ? true : false
        });

        // To
        $("#range-to").datepicker({
            // defaultDate: '+1w',
            numberOfMonths: 1,
            dateFormat: "dd-mm-yy",
            onClose: function(selectedDate) {
                $("#range-from").datepicker("option", "maxDate", selectedDate);
            },
            isRTL: $("html").attr("dir") == "rtl" ? true : false
        });
    };

    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentUiDatepicker();
        }
    };
})();
var Buttons = (function() {
    //
    // Setup module components
    //

    // Progress buttons
    var _componentLadda = function() {
        if (typeof Ladda == "undefined") {
            console.warn("Warning - ladda.min.js is not loaded.");
            return;
        }

        $(".btn-ladda-progress").click(function(e) {
            let dfrom = $("#range-from").val();
            let dto = $("#range-to").val();
            let tahun = $("#tahun").val();

            let l = Ladda.create(this);
            let $input = $(this);
            let type = $input.attr("data");

            /* alert(dfrom);
            alert(dto); */

            $.ajax({
                cache: false,
                type: "POST",
                url: base_url + "report/export",
                data: {
                    dfrom: dfrom,
                    dto: dto,
                    tahun: tahun,
                    type: type
                },
                dataType: "json",
                beforeSend: function() {
                    l.start();
                },
                success: function(response) {
                    var a = document.createElement("a");
                    a.href = response.file;
                    a.download = response.name;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    l.stop();
                },
                error: function(response) {
                    l.stop();
                }
            });
            return false;
        });
    };

    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentLadda();
        }
    };
})();

// Initialize module
// ------------------------------

document.addEventListener("DOMContentLoaded", function() {
    JqueryUiForms.init();
    Buttons.init();
    $('.select-search').select2();
});