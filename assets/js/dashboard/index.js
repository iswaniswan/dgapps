// Setup module
// ------------------------------

var EchartsPiesDonuts = (function () {
  //
  // Setup module components
  //

  // Pie and donut charts
  var _piesDonutsExamples = function () {
    if (typeof echarts == "undefined") {
      console.warn("Warning - echarts.min.js is not loaded.");
      return;
    }

    // Define elements
    var area_values_element = document.getElementById("area_values");
    var columns_compositive_waterfall_element = document.getElementById(
      "columns_compositive_waterfall"
    );
    var columns_basic_element = document.getElementById("columns_basic");
    var columns_stacked_element = document.getElementById("columns_stacked");

    //
    // Charts configuration
    //

    if (area_values_element) {
      // Initialize chart
      var area_values = echarts.init(area_values_element);

      //
      // Chart config
      //
      $.getJSON(base_url + "dashboard/data_salesovertime", function (json) {
        var date = [];
        var val = [];
        $.each(json, function (index, value) {
          date.push([value.d_spb]);
          val.push(value.v_spb_netto);
        });

        // Options
        area_values.setOption({
          // Define colors
          color: ["#EC407A"],

          // Global text styles
          textStyle: {
            fontFamily: "Roboto, Arial, Verdana, sans-serif",
            fontSize: 13,
          },

          // Chart animation duration
          animationDuration: 750,

          // Setup grid
          grid: {
            left: 0,
            right: 40,
            top: 10,
            bottom: 0,
            containLabel: true,
          },

          // Add tooltip
          tooltip: {
            trigger: "axis",
            backgroundColor: "rgba(0,0,0,0.75)",
            padding: [10, 15],
            textStyle: {
              fontSize: 13,
              fontFamily: "Roboto, sans-serif",
            },
          },

          // Horizontal axis
          xAxis: [
            {
              type: "category",
              boundaryGap: false,
              data: date,
              axisLabel: {
                color: "#333",
              },
              axisLine: {
                lineStyle: {
                  color: "#999",
                },
              },
              splitLine: {
                lineStyle: {
                  color: "#eee",
                },
              },
            },
          ],

          // Vertical axis
          yAxis: [
            {
              type: "value",
              axisLabel: {
                //formatter: "{value} IDR",
                color: "#333",
              },
              axisLine: {
                lineStyle: {
                  color: "#999",
                },
              },
              splitLine: {
                lineStyle: {
                  color: "#eee",
                },
              },
              splitArea: {
                show: true,
                areaStyle: {
                  color: ["rgba(250,250,250,0.1)", "rgba(0,0,0,0.01)"],
                },
              },
            },
          ],

          // Add series
          series: [
            {
              name: "",
              type: "line",
              data: val,
              smooth: true,
              symbolSize: 7,
              label: {
                normal: {
                  show: true,
                },
              },
              areaStyle: {
                normal: {
                  opacity: 0.25,
                },
              },
              itemStyle: {
                normal: {
                  borderWidth: 2,
                },
              },
            },
          ],
        });
      });
    }

    if (columns_compositive_waterfall_element) {
      // Initialize chart
      var columns_compositive_waterfall = echarts.init(
        columns_compositive_waterfall_element
      );

      //
      // Chart config
      //

      // Options
      $.getJSON(base_url + "dashboard/data_customeroverview", function (json) {
        columns_compositive_waterfall.setOption({
          // Define colors
          color: ["#f17a52", "#03A9F4"],

          // Global text styles
          textStyle: {
            fontFamily: "Roboto, Arial, Verdana, sans-serif",
            fontSize: 13,
          },

          // Chart animation duration
          animationDuration: 750,

          // Setup grid
          grid: {
            left: 10,
            right: 10,
            top: 35,
            bottom: 0,
            containLabel: true,
          },

          // Tooltip
          tooltip: {
            trigger: "axis",
            backgroundColor: "rgba(0,0,0,0.75)",
            padding: [10, 15],
            textStyle: {
              fontSize: 13,
              fontFamily: "Roboto, sans-serif",
            },
            axisPointer: {
              type: "shadow",
              shadowStyle: {
                color: "rgba(0,0,0,0.025)",
              },
            },
            formatter: function (params) {
              var tar = params[0];
              return tar.name + "<br/>" + tar.seriesName + ": " + tar.value;
            },
          },

          // Horizontal axis
          xAxis: [
            {
              type: "category",
              data: ["Registered Store", "Visited Store", "Productive Store"],
              axisLabel: {
                color: "#333",
              },
              axisLine: {
                lineStyle: {
                  color: "#999",
                },
              },
              splitLine: {
                show: true,
                lineStyle: {
                  color: "#eee",
                  type: "dashed",
                },
              },
            },
          ],

          // Vertical axis
          yAxis: [
            {
              type: "value",
              axisLabel: {
                color: "#333",
              },
              axisLine: {
                lineStyle: {
                  color: "#999",
                },
              },
              splitLine: {
                lineStyle: {
                  color: "#eee",
                },
              },
              splitArea: {
                show: true,
                areaStyle: {
                  color: ["rgba(250,250,250,0.1)", "rgba(0,0,0,0.015)"],
                },
              },
            },
          ],

          // Add series
          series: [
            {
              name: "Total",
              type: "bar",
              stack: "Total",
              itemStyle: {
                normal: {
                  barBorderRadius: 3,
                  color: "#42A5F5",
                  label: {
                    show: true,
                    position: "inside",
                  },
                },
                emphasis: {
                  color: "#42A5F5",
                },
              },
              data: json.data,
            },
          ],
        });
      });
    }

    if (columns_basic_element) {
      // Initialize chart
      var columns_basic = echarts.init(columns_basic_element);

      //
      // Chart config
      //
      $.getJSON(base_url + "dashboard/data_call", function (json) {
        var call = [];
        var title = [];
        var effective = [];
        $.each(json, function (index, value) {
          title.push(value.title);
          call.push(value.call);
          effective.push(value.effective);
        });
        // Options
        columns_basic.setOption({
          // Define colors
          color: ["#2ec7c9", "#b6a2de", "#5ab1ef", "#ffb980", "#d87a80"],

          // Global text styles
          textStyle: {
            fontFamily: "Roboto, Arial, Verdana, sans-serif",
            fontSize: 13,
          },

          // Chart animation duration
          animationDuration: 750,

          // Setup grid
          grid: {
            left: 0,
            right: 40,
            top: 35,
            bottom: 0,
            containLabel: true,
          },

          // Add legend
          legend: {
            data: ["Sales Call", "Effective Call"],
            itemHeight: 8,
            itemGap: 20,
            textStyle: {
              padding: [0, 5],
            },
          },

          // Add tooltip
          tooltip: {
            trigger: "axis",
            backgroundColor: "rgba(0,0,0,0.75)",
            padding: [10, 15],
            textStyle: {
              fontSize: 13,
              fontFamily: "Roboto, sans-serif",
            },
          },

          // Horizontal axis
          xAxis: [
            {
              type: "category",
              data: title,
              axisLabel: {
                color: "#333",
              },
              axisLine: {
                lineStyle: {
                  color: "#999",
                },
              },
              splitLine: {
                show: true,
                lineStyle: {
                  color: "#eee",
                  type: "dashed",
                },
              },
            },
          ],

          // Vertical axis
          yAxis: [
            {
              type: "value",
              axisLabel: {
                color: "#333",
              },
              axisLine: {
                lineStyle: {
                  color: "#999",
                },
              },
              splitLine: {
                lineStyle: {
                  color: ["#eee"],
                },
              },
              splitArea: {
                show: true,
                areaStyle: {
                  color: ["rgba(250,250,250,0.1)", "rgba(0,0,0,0.01)"],
                },
              },
            },
          ],

          // Add series
          series: [
            {
              name: "Sales Call",
              type: "bar",
              data: call,
              itemStyle: {
                normal: {
                  label: {
                    show: true,
                    position: "top",
                    textStyle: {
                      fontWeight: 500,
                    },
                  },
                },
              },
              markLine: {
                data: [
                  {
                    type: "average",
                    name: "Average",
                  },
                ],
              },
            },
            {
              name: "Effective Call",
              type: "bar",
              data: effective,
              itemStyle: {
                normal: {
                  label: {
                    show: true,
                    position: "top",
                    textStyle: {
                      fontWeight: 500,
                    },
                  },
                },
              },
              markLine: {
                data: [
                  {
                    type: "average",
                    name: "Average",
                  },
                ],
              },
            },
          ],
        });
      });
    }

    if (columns_stacked_element) {
      // Initialize chart
      var columns_stacked = echarts.init(columns_stacked_element);

      //
      // Chart config
      //

      // Options
      $.getJSON(base_url + "dashboard/data_attendance", function (json) {
        let d_login = [];
        let hadir = [];
        let tidak_hadir = [];
        $.each(json[0].d_login, function (index, value) {
          d_login.push(value);
        });
        $.each(json[0].hadir, function (index, value) {
          hadir.push(value);
        });
        $.each(json[0].tidak_hadir, function (index, value) {
          tidak_hadir.push(value);
        });

        columns_stacked.setOption({
          // Define colors
          color: ["#5ab1ef", "#d87a80"],

          // Global text styles
          textStyle: {
            fontFamily: "Roboto, Arial, Verdana, sans-serif",
            fontSize: 13,
          },

          // Chart animation duration
          animationDuration: 750,

          // Setup grid
          grid: {
            left: 0,
            right: 10,
            top: 35,
            bottom: 0,
            containLabel: true,
          },

          // Add legend
          legend: {
            data: ["Present", "Absent"],
            itemHeight: 8,
            itemGap: 20,
          },

          // Add tooltip
          tooltip: {
            trigger: "axis",
            backgroundColor: "rgba(0,0,0,0.75)",
            padding: [10, 15],
            textStyle: {
              fontSize: 13,
              fontFamily: "Roboto, sans-serif",
            },
            axisPointer: {
              type: "shadow",
              shadowStyle: {
                color: "rgba(0,0,0,0.025)",
              },
            },
          },

          // Horizontal axis
          xAxis: [
            {
              type: "category",
              data: d_login,
              axisLabel: {
                color: "#333",
              },
              axisLine: {
                lineStyle: {
                  color: "#999",
                },
              },
              splitLine: {
                show: true,
                lineStyle: {
                  color: "#eee",
                  type: "dashed",
                },
              },
            },
          ],

          // Vertical axis
          yAxis: [
            {
              type: "value",
              axisLabel: {
                color: "#333",
              },
              axisLine: {
                lineStyle: {
                  color: "#999",
                },
              },
              splitLine: {
                lineStyle: {
                  color: "#eee",
                },
              },
              splitArea: {
                show: true,
                areaStyle: {
                  color: ["rgba(250,250,250,0.1)", "rgba(0,0,0,0.01)"],
                },
              },
            },
          ],

          // Add series
          series: [
            {
              name: "Present",
              type: "bar",
              stack: "Advertising",
              data: hadir,
            },
            {
              name: "Absent",
              type: "bar",
              stack: "Advertising",
              data: tidak_hadir,
            },
          ],
        });
      });
    }

    //
    // Resize charts
    //

    // Resize function
    var triggerChartResize = function () {
      area_values_element && area_values.resize();
      columns_compositive_waterfall_element &&
        columns_compositive_waterfall.resize();
      columns_basic_element && columns_basic.resize();
      columns_stacked_element && columns_stacked.resize();
    };

    // On sidebar width change
    $(document).on("click", ".sidebar-control", function () {
      setTimeout(function () {
        triggerChartResize();
      }, 0);
    });

    // On window resize
    var resizeCharts;
    window.onresize = function () {
      clearTimeout(resizeCharts);
      resizeCharts = setTimeout(function () {
        triggerChartResize();
      }, 200);
    };
  };

  //
  // Return objects assigned to module
  //

  return {
    init: function () {
      _piesDonutsExamples();
    },
  };
})();

var JqueryUiForms = (function () {
  //
  // Setup module components
  //

  // Datepicker
  var _componentUiDatepicker = function () {
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
      onClose: function (selectedDate) {
        $("#range-to").datepicker("option", "minDate", selectedDate);
        sales_overtime();
        change_sales_call();
        ActivityList();
        attendancereport();
      },
      isRTL: $("html").attr("dir") == "rtl" ? true : false,
    });

    // To
    $("#range-to").datepicker({
      // defaultDate: '+1w',
      numberOfMonths: 1,
      dateFormat: "dd-mm-yy",
      onClose: function (selectedDate) {
        $("#range-from").datepicker("option", "maxDate", selectedDate);
        sales_overtime();
        change_sales_call();
        ActivityList();
        attendancereport();
      },
      isRTL: $("html").attr("dir") == "rtl" ? true : false,
    });

    function change_sales_call() {
      var area = $("#area_call").val();
      var dfrom = $("#range-from").val();
      var dto = $("#range-to").val();
      $.ajax({
        cache: false,
        dataType: "json",
        type: "POST",
        url: base_url + "dashboard/data_call",
        data: {
          dfrom: dfrom,
          dto: dto,
          area: area,
        },
        beforeSend: function () {
          $("#columns_basic").block({
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
        success: function (json) {
          var columns_basic_element = document.getElementById("columns_basic");
          if (columns_basic_element) {
            // Initialize chart
            var columns_basic = echarts.init(columns_basic_element);

            //
            // Chart config
            //
            var call = [];
            var title = [];
            var effective = [];
            $.each(json, function (index, value) {
              title.push(value.title);
              call.push(value.call);
              effective.push(value.effective);
            });
            // Options
            columns_basic.setOption({
              // Define colors
              color: ["#2ec7c9", "#b6a2de", "#5ab1ef", "#ffb980", "#d87a80"],

              // Global text styles
              textStyle: {
                fontFamily: "Roboto, Arial, Verdana, sans-serif",
                fontSize: 13,
              },

              // Chart animation duration
              animationDuration: 750,

              // Setup grid
              grid: {
                left: 0,
                right: 40,
                top: 35,
                bottom: 0,
                containLabel: true,
              },

              // Add legend
              legend: {
                data: ["Sales Call", "Effective Call"],
                itemHeight: 8,
                itemGap: 20,
                textStyle: {
                  padding: [0, 5],
                },
              },

              // Add tooltip
              tooltip: {
                trigger: "axis",
                backgroundColor: "rgba(0,0,0,0.75)",
                padding: [10, 15],
                textStyle: {
                  fontSize: 13,
                  fontFamily: "Roboto, sans-serif",
                },
              },

              // Horizontal axis
              xAxis: [
                {
                  type: "category",
                  data: title,
                  axisLabel: {
                    color: "#333",
                  },
                  axisLine: {
                    lineStyle: {
                      color: "#999",
                    },
                  },
                  splitLine: {
                    show: true,
                    lineStyle: {
                      color: "#eee",
                      type: "dashed",
                    },
                  },
                },
              ],

              // Vertical axis
              yAxis: [
                {
                  type: "value",
                  axisLabel: {
                    color: "#333",
                  },
                  axisLine: {
                    lineStyle: {
                      color: "#999",
                    },
                  },
                  splitLine: {
                    lineStyle: {
                      color: ["#eee"],
                    },
                  },
                  splitArea: {
                    show: true,
                    areaStyle: {
                      color: ["rgba(250,250,250,0.1)", "rgba(0,0,0,0.01)"],
                    },
                  },
                },
              ],

              // Add series
              series: [
                {
                  name: "Sales Call",
                  type: "bar",
                  data: call,
                  itemStyle: {
                    normal: {
                      label: {
                        show: true,
                        position: "top",
                        textStyle: {
                          fontWeight: 500,
                        },
                      },
                    },
                  },
                  markLine: {
                    data: [
                      {
                        type: "average",
                        name: "Average",
                      },
                    ],
                  },
                },
                {
                  name: "Effective Call",
                  type: "bar",
                  data: effective,
                  itemStyle: {
                    normal: {
                      label: {
                        show: true,
                        position: "top",
                        textStyle: {
                          fontWeight: 500,
                        },
                      },
                    },
                  },
                  markLine: {
                    data: [
                      {
                        type: "average",
                        name: "Average",
                      },
                    ],
                  },
                },
              ],
            });
          }
          $("#columns_basic").unblock();
        },
      });
    }

    $("#area_call").change(function () {
      change_sales_call();
    });

    function sales_overtime() {
      var dfrom = $("#range-from").val();
      var dto = $("#range-to").val();

      $.ajax({
        cache: false,
        dataType: "json",
        type: "POST",
        url: base_url + "dashboard/data_salesovertime",
        data: {
          dfrom: dfrom,
          dto: dto,
        },
        beforeSend: function () {
          $("#area_values").block({
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
        success: function (json) {
          var area_values_element = document.getElementById("area_values");
          if (area_values_element) {
            // Initialize chart
            var area_values = echarts.init(area_values_element);
            var date = [];
            var val = [];
            $.each(json, function (index, value) {
              date.push([value.d_spb]);
              val.push(value.v_spb_netto);
            });
          }
          area_values.setOption({
            // Define colors
            color: ["#EC407A"],

            // Global text styles
            textStyle: {
              fontFamily: "Roboto, Arial, Verdana, sans-serif",
              fontSize: 13,
            },

            // Chart animation duration
            animationDuration: 750,

            // Setup grid
            grid: {
              left: 0,
              right: 40,
              top: 10,
              bottom: 0,
              containLabel: true,
            },

            // Add tooltip
            tooltip: {
              trigger: "axis",
              backgroundColor: "rgba(0,0,0,0.75)",
              padding: [10, 15],
              textStyle: {
                fontSize: 13,
                fontFamily: "Roboto, sans-serif",
              },
            },

            // Horizontal axis
            xAxis: [
              {
                type: "category",
                boundaryGap: false,
                data: date,
                axisLabel: {
                  color: "#333",
                },
                axisLine: {
                  lineStyle: {
                    color: "#999",
                  },
                },
                splitLine: {
                  lineStyle: {
                    color: "#eee",
                  },
                },
              },
            ],

            // Vertical axis
            yAxis: [
              {
                type: "value",
                axisLabel: {
                  formatter: "{value} IDR",
                  color: "#333",
                },
                axisLine: {
                  lineStyle: {
                    color: "#999",
                  },
                },
                splitLine: {
                  lineStyle: {
                    color: "#eee",
                  },
                },
                splitArea: {
                  show: true,
                  areaStyle: {
                    color: ["rgba(250,250,250,0.1)", "rgba(0,0,0,0.01)"],
                  },
                },
              },
            ],

            // Add series
            series: [
              {
                name: "",
                type: "line",
                data: val,
                smooth: true,
                symbolSize: 7,
                label: {
                  normal: {
                    show: true,
                  },
                },
                areaStyle: {
                  normal: {
                    opacity: 0.25,
                  },
                },
                itemStyle: {
                  normal: {
                    borderWidth: 2,
                  },
                },
              },
            ],
          });
          $("#area_values").unblock();
        },
      });
    }

    function attendancereport() {
      var dfrom = $("#range-from").val();
      var dto = $("#range-to").val();

      $.ajax({
        cache: false,
        dataType: "json",
        type: "POST",
        url: base_url + "dashboard/data_attendance",
        data: {
          dfrom: dfrom,
          dto: dto,
        },
        beforeSend: function () {
          $("#columns_stacked").block({
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
        success: function (json) {
          var columns_stacked_element = document.getElementById(
            "columns_stacked"
          );
          if (columns_stacked_element) {
            // Initialize chart
            var columns_stacked = echarts.init(columns_stacked_element);

            //
            // Chart config
            //

            // Options
            let d_login = [];
            let hadir = [];
            let tidak_hadir = [];
            $.each(json[0].d_login, function (index, value) {
              d_login.push(value);
            });
            $.each(json[0].hadir, function (index, value) {
              hadir.push(value);
            });
            $.each(json[0].tidak_hadir, function (index, value) {
              tidak_hadir.push(value);
            });

            columns_stacked.setOption({
              // Define colors
              color: ["#5ab1ef", "#d87a80"],

              // Global text styles
              textStyle: {
                fontFamily: "Roboto, Arial, Verdana, sans-serif",
                fontSize: 13,
              },

              // Chart animation duration
              animationDuration: 750,

              // Setup grid
              grid: {
                left: 0,
                right: 10,
                top: 35,
                bottom: 0,
                containLabel: true,
              },

              // Add legend
              legend: {
                data: ["Present", "Absent"],
                itemHeight: 8,
                itemGap: 20,
              },

              // Add tooltip
              tooltip: {
                trigger: "axis",
                backgroundColor: "rgba(0,0,0,0.75)",
                padding: [10, 15],
                textStyle: {
                  fontSize: 13,
                  fontFamily: "Roboto, sans-serif",
                },
                axisPointer: {
                  type: "shadow",
                  shadowStyle: {
                    color: "rgba(0,0,0,0.025)",
                  },
                },
              },

              // Horizontal axis
              xAxis: [
                {
                  type: "category",
                  data: d_login,
                  axisLabel: {
                    color: "#333",
                  },
                  axisLine: {
                    lineStyle: {
                      color: "#999",
                    },
                  },
                  splitLine: {
                    show: true,
                    lineStyle: {
                      color: "#eee",
                      type: "dashed",
                    },
                  },
                },
              ],

              // Vertical axis
              yAxis: [
                {
                  type: "value",
                  axisLabel: {
                    color: "#333",
                  },
                  axisLine: {
                    lineStyle: {
                      color: "#999",
                    },
                  },
                  splitLine: {
                    lineStyle: {
                      color: "#eee",
                    },
                  },
                  splitArea: {
                    show: true,
                    areaStyle: {
                      color: ["rgba(250,250,250,0.1)", "rgba(0,0,0,0.01)"],
                    },
                  },
                },
              ],

              // Add series
              series: [
                {
                  name: "Present",
                  type: "bar",
                  stack: "Advertising",
                  data: hadir,
                },
                {
                  name: "Absent",
                  type: "bar",
                  stack: "Advertising",
                  data: tidak_hadir,
                },
              ],
            });
          }
          $("#columns_stacked").unblock();
        },
      });
    }

    // ? Activity List
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
    ActivityList();

    function ActivityList() {
      var dfrom = $("#range-from").val();
      var dto = $("#range-to").val();
      $("#activitylist").DataTable({
        destroy: true,
        serverSide: true,
        autoWidth: false,
        processing: true,
        ajax: {
          url: base_url + "dashboard/activitylist/" + dfrom + "/" + dto,
          type: "post",
          error: function (data, err) {
            $(".activitylist-error").html("");
            $("#activitylist tbody").empty();
            $("#activitylist").append(
              '<tr><td class="text-center" colspan="6">No data available in table</td></tr>'
            );
            $("#activitylist_processing").css("display", "none");
          },
        },
        jQueryUI: false,
        autoWidth: false,
        pagingType: "full_numbers",
        dom:
          '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
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
  };

  //
  // Return objects assigned to module
  //

  return {
    init: function () {
      _componentUiDatepicker();
    },
  };
})();

// Initialize module
// ------------------------------

document.addEventListener("DOMContentLoaded", function () {
  JqueryUiForms.init();
  EchartsPiesDonuts.init();
});
