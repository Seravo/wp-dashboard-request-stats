jQuery(document).ready(function($){
  //get context
  var ctx = $("#myChart").get(0).getContext("2d");

  //doesn't work, fix it later:
  //var options = { legendTemplate : "<ul id=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].pointColor%>\"><%if(datasets[i].label){%><%=datasets[i].label%><%}%></span></li><%}%></ul>" };

  var ajaxData = {
			'action': 'get_chart_data',
  };

  var label = [];
  var chartValue = [];

  jQuery.getJSON(ajaxurl, ajaxData, function(json){

    jQuery.each(json, function (i,value){

      label.push(value.date);
      chartValue.push(value.request_count);

    });

    //chart options
    var chartData = {
      labels: label,
      datasets: [
       /** {
            label: "PHP",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: chartValue

        },*/
        {
            label: "Total requests",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: chartValues
        }
      ]
    };

  
    var myLineChart = new Chart(ctx).Line(chartData);
    //var legend = myLineChart.generateLegend();
    //$( '#chart-legend' ).html(legend);
    

  });

});
