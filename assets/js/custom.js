/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

function datatable(link, column) {
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
    });
}

function datatableimage(link, column) {
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
        columnDefs: [{
            targets: 0,
            render: function(data) {
                return '<img width="60px" height="40px" src="' + data + '">'
            }
        }],
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
}

/** Update Status */
function changestatus(link, id) {
    $.ajax({
        type: "POST",
        data: {
            id: id
        },
        url: link + "/changestatus",
        dataType: "json",
        beforeSend: function() {
            $(".page-content").block({
                message: '<img src="../assets/img/Preloader_2.gif" alt="loading" /><h1 class="text-muted d-block">P l e a s e &nbsp;&nbsp; W a i t</h1>',
                /* message:
					'<div class="spinner-grow text-primary"></div><div class="spinner-grow text-success"></div><div class="spinner-grow text-teal"></div><div class="spinner-grow text-info"></div><div class="spinner-grow text-warning"></div><div class="spinner-grow text-orange"></div><div class="spinner-grow text-danger"></div><div class="spinner-grow text-secondary"></div><div class="spinner-grow text-dark"></div><div class="spinner-grow text-muted"></div><br><h1 class="text-muted d-block">P l e a s e &nbsp;&nbsp; W a i t</h1>', */
                centerX: false,
                centerY: false,
                overlayCSS: {
                    backgroundColor: "#fff",
                    opacity: 0.8,
                    cursor: "wait"
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: "none"
                }
            });
        },
        success: function(data) {
            if (data.sukses == true) {
                swal
                    .queue([{
                        title: "Sukses",
                        text: "Status berhasil diubah :)",
                        type: "success",
                        confirmButtonClass: "btn btn-success"
                    }])
                    .then(function(result) {
                        window.location = link;
                    });
            } else {
                swal.queue([{
                    title: "Maaf :(",
                    text: "Gagal merubah status :(",
                    type: "error",
                    confirmButtonClass: "btn btn-danger"
                }]);
            }
            $(".page-content").unblock();
        },
        error: function() {
            swal.queue([{
                title: "Maaf :(",
                text: "Gagal merubah status :(",
                type: "error",
                confirmButtonClass: "btn btn-danger"
            }]);
            $(".page-content").unblock();
        }
    });
}