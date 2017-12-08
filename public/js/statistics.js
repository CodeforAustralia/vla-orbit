var ChartsAmcharts = function() 
{
    var initChartSample1 = function(statistics) 
    {        
        var chart = AmCharts.makeChart("chart_1", 
        {
            "type": "serial",
            "theme": "light",
            "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
            "autoMargins": false,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,

            "fontFamily": 'Open Sans',            
            "color":    '#888',
            
            "dataProvider": statistics,
            "valueAxes": [{
                "axisAlpha": 0,
                "position": "left"
            }],
            "startDuration": 1,
            "graphs": [{
                "alphaField": "alpha",
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]: <b>[[value]]</b> - [[ServiceProviderName]]</span>",
                "dashLengthField": "dashLengthColumn",
                "fillAlphas": 1,
                "title": "Total Bookings",
                "type": "column",
                "valueField": "TotalBookings",
                "additional": "ServiceProviderName"
            }], //ServiceProviderName
            "categoryField": "ServiceName",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0,
                "labelFunction": function(label, item, axis) {
                  var chart = axis.chart;
                  if (  label.length > 15 )
                    return label.substr(0, 15) + '...';
                  return label;
                }
            }
        });

        $('#chart_1').closest('.portlet').find('.fullscreen').click(function() 
        {
            chart.invalidateSize();
        });
    }

    var initChartSample2 = function(statistics) 
    {        
        var chart = AmCharts.makeChart("chart_2", 
        {
            "type": "serial",
            "theme": "light",
            "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
            "autoMargins": false,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,

            "fontFamily": 'Open Sans',            
            "color":    '#888',
            
            "dataProvider": statistics,
            "valueAxes": [{
                "axisAlpha": 0,
                "position": "left"
            }],
            "startDuration": 1,
            "graphs": [{
                "alphaField": "alpha",
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]: <b>[[value]]</b> - [[ServiceProviderName]]</span>",
                "dashLengthField": "dashLengthColumn",
                "fillAlphas": 1,
                "title": "Average Time",
                "type": "column",
                "valueField": "AverageTime"
            }], 
            "categoryField": "ServiceName",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0,
                "labelFunction": function(label, item, axis) {
                  var chart = axis.chart;
                  if (  label.length > 15 )
                    return label.substr(0, 15) + '...';
                  return label;
                }
            }
        });

        $('#chart_2').closest('.portlet').find('.fullscreen').click(function() 
        {
            chart.invalidateSize();
        });
    }

    var initStatisticsInformation = function () 
    {
        
        $.get("/statistic/list", function(data, status)
        {
            initChartSample1(data.BookinsPerService);
            initChartSample2(data.BookinsAvgTime);
        });
    }

    return {
        //main function to initiate the module

        init: function() {

            initStatisticsInformation();
        }

    };

}();

jQuery(document).ready(function() {    
   ChartsAmcharts.init(); 
});