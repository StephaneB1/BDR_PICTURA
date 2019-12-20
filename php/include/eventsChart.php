<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 19.05.2017
 * Summary: Initialize the events' chart.
 *
 * The variable "eventLimit" must be declared before this file is being included.
 * An element of id "dataSwitchButton" is necessary to switch data sorting
 * An element of id "eventsChart" (which will contain the chart) must be in the document
 */

?>

<script>
    var type = 0; //0 = sort by date / 1 = sort by participants number
    var eventLimit = 10;

    $("#dataSwitchButton").click(getEventsData); //onclick function

    /**
     * Get data and update chart with them
     */
    function getEventsData() {
        $.ajax({
            //Request content
            url: "php/get/getTopPopularEvents.php?t=" + type +"&n=" + eventLimit,
            success: function(result) {
                //Arrange events titles
                var categories = [];
                for(var i=0; i < result.length; i++) {
                    categories[i] = result[i]["eveTitle"];
                }

                //Arrange main data
                var data = [];
                for(var i=0; i < result.length; i++) {
                    data[i] =parseInt(result[i]["countEvent"]);
                }

                //Update chart's data and categories
                chart.series[0].setData(data);
                chart.xAxis[0].setCategories(categories);

                //Change type (next time, the other type of data will be requested)
                if(type == 0) {
                    type = 1;
                    //Change button's text
                    $("#dataSwitchButton").html("Classer par date de début");
                } else {
                    type = 0;
                    //Change button's text
                    $("#dataSwitchButton").html("Classer par nombre de participants");
                }
            },
        }); //ajax
    }

    //Initialize chart
    var chart = Highcharts.chart('eventsChart', {
        colors: ["#5B91D6"],
        chart: {
            type: 'bar',
            animation: false,
            style: {
                fontSize: '1em'
            }
        },
        title: {
            text: null
        },
        tooltip: {
            headerFormat: '<span style="font-size: 0.8em">{point.key}</span><br/>',
            style: {
                fontSize: '1em'
            }
        },
        xAxis: {
            categories: [],
            title: {
                text: null
            },
            labels: {
                style: {
                    fontSize:'1.1em',
                    lineHeight: "25px"
                }
            }
        },
        yAxis: {
			opposite: true,
			allowDecimals: false,
				min: 0,
				title: {
				text: 'Nombre de participants',
					align: 'low'
			},
			labels: {
				overflow: 'justify',
                style: {
                    fontSize: '0.9em'
                }
			}
		},
        plotOptions: {
            series: {
                animation: false,
                cursor: 'pointer',
                point: {
                    events: {
                        click: function () {
                            location.href = "<?php //echo getHostUrl(); ?>/events.php";
                        }
                    }
                }
            },
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Participants',
            data: []
        }]
    }); //Chart

    getEventsData();

</script>