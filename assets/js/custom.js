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
      error: function (data, err) {
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