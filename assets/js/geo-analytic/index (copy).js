var Geo = function() {

    // Select2 for length menu styling
    var _Highcharts = function() {

        // var data = array();
        $.ajax({
            cache: false,
            dataType: 'json',
            type: 'post',
            url: base_url + 'geo-analytic/data_nasional',
            success:function(result)
            {
                var aa = [
                    ['id-jr', 800],
                    ['id-jt', 800],
                ];
                // alert(result);
                var data = [];
                $.each(result, function(index, element) {
                   data.push([element.id_maps, element.jumlah]);
                });
                Highcharts.mapChart('container', {
                    chart: {
                        map: 'countries/id/id-all'
                    },
                
                    title: {
                        text: 'Statistik Nasional'
                    },
                
                    subtitle: {
                        text: 'Jumlah Customer ' + result[0].jumlah
                    },
                
                    mapNavigation: {
                        enabled: true,
                        buttonOptions: {
                            verticalAlign: 'bottom'
                        }
                    },
                
                    colorAxis: {
                        min: 0,
                    },
                
                    series: [{
                        data: data,
                        name: 'Jumlah Customer',
                        states: {
                            hover: {
                                color: '#BADA55'
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }]
                });
            }
        });

        
        // Create the chart

    };

    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _Highcharts();
        }
    }
}();

document.addEventListener('DOMContentLoaded', function() {
    Geo.init();
});