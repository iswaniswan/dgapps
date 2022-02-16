var Geo = function () {

    // Select2 for length menu styling
    var _Highcharts = function () {

        small = $('#container').width() < 400;
        $.ajax({
            cache: false,
            dataType: 'json',
            type: 'post',
            url: base_url + 'geo-analytic/data_nasional',
            success: function (result) {
                var data = Highcharts.geojson(Highcharts.maps['countries/id/id-all']),
                    separators = Highcharts.geojson(Highcharts.maps['countries/id/id-all'], 'mapline'),
                    // Some responsiveness
                    small = $('#container').width() < 400;

                // Set drilldown pointers
                var cek = 0;
                $.each(data, function (i) {
                    for (let x = 0; x < result.features.length; x++) {
                        if(result.features[x]['hc-key'] == this.properties['hc-key']){
                            this.drilldown = this.properties['hc-key'];
                            this.value = result.features[x].value; // Non-random bogus data
                            cek = cek + 1;
                        }
                    }
                    if(cek == 0){
                        this.drilldown = this.properties['id'];
                        this.value = 0;
                    }
                    cek = 0;
                });

                // Instantiate the map
                Highcharts.mapChart('container', {
                    chart: {
                        events: {
                            drilldown: function (e) {
                                if (!e.seriesOptions) {
                                    window.location.replace(base_url + 'geo-analytic/view/' + e.point.drilldown);
                                    // var chart = this,
                                    //     mapKey = base_url + 'global_assets/js/plugins/maps/' + e.point.drilldown + '.json',

                                    //     // Handle error, the timeout is cleared on success
                                    //     fail = setTimeout(function () {
                                    //         if (!Highcharts.maps[mapKey]) {
                                    //             chart.showLoading('<i class="icon-frown"></i> Failed loading ' + e.point.name);
                                    //             fail = setTimeout(function () {
                                    //                 chart.hideLoading();
                                    //             }, 1000);
                                    //         }
                                    //     }, 3000);

                                    // // Show the spinner
                                    // chart.showLoading('<i class="icon-spinner icon-spin icon-3x"></i>'); // Font Awesome spinner

                                    // // Load the drilldown map
                                    // $.getJSON(mapKey, function (json) {

                                    //     data = Highcharts.geojson(json);

                                    //     // alert(data);
                                    //     // Set a non-random bogus value
                                    //     $.each(data, function (i) {
                                    //         this.value = i;
                                    //     });

                                    //     // Hide loading and add series
                                    //     chart.hideLoading();
                                    //     clearTimeout(fail);
                                    //     chart.addSeriesAsDrilldown(e.point, {
                                    //         name: e.point.name,
                                    //         data: data,
                                    //         dataLabels: {
                                    //             enabled: true,
                                    //             format: '{point.name}'
                                    //         }
                                    //     });
                                    // });
                                }

                                this.setTitle(null, {
                                    text: e.point.name
                                });
                            },
                            drillup: function () {
                                this.setTitle(null, {
                                    text: ''
                                });
                            }
                        }
                    },

                    title: {
                        text: 'Statistik Nasional'
                    },

                    subtitle: {
                        text: '',
                        floating: true,
                        align: 'center',
                        y: 50,
                        style: {
                            fontSize: '16px'
                        }
                    },

                    legend: small ? {} : {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle'
                    },

                    colorAxis: {
                        min: 0,
                        minColor: '#E6E7E8',
                        maxColor: '#005645'
                    },

                    mapNavigation: {
                        enabled: true,
                        buttonOptions: {
                            verticalAlign: 'bottom'
                        }
                    },

                    plotOptions: {
                        map: {
                            states: {
                                hover: {
                                    color: '#EEDD66'
                                }
                            }
                        }
                    },

                    series: [{
                        data: data,
                        name: 'Nasional',
                        states: {
                            hover: {
                                color: '#BADA55'
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }, {
                        type: 'mapline',
                        data: separators,
                        color: 'silver',
                        enableMouseTracking: false,
                        animation: {
                            duration: 500
                        }
                    }],

                    drilldown: {
                        activeDataLabelStyle: {
                            color: '#FFFFFF',
                            textDecoration: 'none',
                            textOutline: '1px #000000'
                        },
                        drillUpButton: {
                            relativeTo: 'spacingBox',
                            position: {
                                x: 0,
                                y: 60
                            }
                        }
                    }
                });

            }
        });


        // Create the chart

    };

    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _Highcharts();
        }
    }
}();

document.addEventListener('DOMContentLoaded', function () {
    Geo.init();
});