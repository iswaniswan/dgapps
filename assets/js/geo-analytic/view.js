var Geo = function () {

    // Select2 for length menu styling
    var _Highcharts = function () {

        small = $('#container').width() < 400;
        $.ajax({
            cache: false,
            dataType: 'json',
            type: 'post',
            url: base_url + 'geo-analytic/data_city',
            async: false,
            data: {
                'id_maps': id_maps,
            },
            success: function (result) {
                mapKey = base_url + 'global_assets/js/plugins/maps/' + id_maps + '.json';

                small = $('#container').width() < 400;

                $.getJSON(mapKey, function (json) {

                    data = Highcharts.geojson(json);
                    var cek = 0;
                    $.each(data, function (i, j) {
                        for (let x = 1; x < result.features.length; x++) {
                            if(result.features[x]['hc-key'] == this.properties['id']){
                                // alert(result.features[x]['hc-key']);
                                this.drilldown = this.properties['id'];
                                this.value = result.features[x]['value']; // Non-random bogus data
                                cek = cek + 1;
                            }
                        }
                        if(cek == 0){
                            this.drilldown = this.properties['id'];
                            this.value = 0;
                        }
                        cek = 0;
                        // $.each(result.features, function (a, v) {
                        //     if(v['hc-key'] == j['properties']['id']){
                        //         this.drilldown = j['properties']['id'];
                        //         this.value = v['value']; // Non-random bogus data
                        //     }
                        // });
                    });



                    // Instantiate the map
                    Highcharts.mapChart('container', {
                        chart: {
                            events: {
                                drilldown: function (e) {
                                    if (!e.seriesOptions) {
                                        window.location.replace(base_url + 'geo-analytic/maps/' + e.point.drilldown);
                                    }

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