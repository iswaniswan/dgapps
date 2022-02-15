			<!-- Page header -->
			<div class="page-header page-header-light">
			    <div class="page-header-content header-elements-md-inline">
			        <div class="page-title d-flex">
			            <h4><span class="font-weight-semibold">GEO ANALYTIC</span></h4>
			        </div>
			    </div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">

			    <div class="row">
			        <div class="col-xl-12">

			            <div class="card">
			                <div class="card-header header-elements-inline">
			                    <h5 class="card-title">Indonesia</h5>
			                    <div class="header-elements">
			                        <div class="list-icons">
			                            <a class="list-icons-item" data-action="collapse"></a>
			                        </div>
			                    </div>
			                </div>
							<div id="container"></div>
			            </div>

			        </div>

			    </div>

			</div>
			<!-- /content area -->

			<script>
			/*
TODO:
- Check data labels after drilling. Label rank? New positions?
*/

var data = Highcharts.geojson(Highcharts.maps['countries/id/id-all']),
    separators = Highcharts.geojson(Highcharts.maps['countries/id/id-all'], 'mapline'),
    // Some responsiveness
    small = $('#container').width() < 400;

// Set drilldown pointers
$.each(data, function (i) {
    this.drilldown = this.properties['hc-key'];
    this.value = i; // Non-random bogus data
});

// Instantiate the map
Highcharts.mapChart('container', {
    chart: {
        events: {
            drilldown: function (e) {
                if (!e.seriesOptions) {
                    var chart = this,
						mapKey = '<?= base_url(); ?>global_assets/js/plugins/maps/' + e.point.drilldown + '.json',
						
                        // Handle error, the timeout is cleared on success
                        fail = setTimeout(function () {
                            if (!Highcharts.maps[mapKey]) {
                                chart.showLoading('<i class="icon-frown"></i> Failed loading ' + e.point.name);
                                fail = setTimeout(function () {
                                    chart.hideLoading();
                                }, 1000);
                            }
                        }, 3000);
						
                    // Show the spinner
                    chart.showLoading('<i class="icon-spinner icon-spin icon-3x"></i>'); // Font Awesome spinner

                    // Load the drilldown map
                    $.getJSON(mapKey, function (json) {

                        data = Highcharts.geojson(json);

						// alert(data);
                        // Set a non-random bogus value
                        $.each(data, function (i) {
                            this.value = i;
                        });

                        // Hide loading and add series
                        chart.hideLoading();
                        clearTimeout(fail);
                        chart.addSeriesAsDrilldown(e.point, {
                            name: e.point.name,
                            data: data,
                            dataLabels: {
                                enabled: true,
                                format: '{point.name}'
                            }
                        });
                    });
                }

                this.setTitle(null, { text: e.point.name });
            },
            drillup: function () {
                this.setTitle(null, { text: '' });
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

			</script>