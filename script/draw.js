//apparently the script breaks other pages because when other pages are loaded the canvas doesn't exist,
//which results in the script breaking other scripts, so make sure it's only loaded where needed in WP

(function ($) {
$( document ).ready(function(){
  //get context
  var context = $("#lineChart").get(0).getContext("2d");
  var context2 = $("#barChart").get(0).getContext("2d");
  //doesn't work, fix it later:
  //var options = { legendTemplate : "<ul id=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].pointColor%>\"><%if(datasets[i].label){%><%=datasets[i].label%><%}%></span></li><%}%></ul>" };

  var ajaxData = {
      'action': 'get_chart_data',
  };

  var chartLabel = [];
  var lineValue = [];
  var barValue = [];
  var myLineChart;
  var myBarChart;
  
  
  
  $.getJSON(ajaxurl, ajaxData, function(json){

    $.each(json, function (i,value){

      chartLabel.push(value.time);
      lineValue.push(value.request_count);
      barValue.push(value.avg_resp);
    });

    //chart options
    var LineChartData = {
      labels: chartLabel,
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
            data: lineValue
        }
      ]};


    var BarChartData = {
      labels: chartLabel,
      datasets: [{
            label: "Responsetimes (in seconds)",
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: barValue
      }]
    };
    //count the average response times etc
    var barAvg = countAvg(barValue);
    var lineAvg = Math.round(countAvg(lineValue));
   
    function countAvg(array){
      var sum = 0;
      for(i = 0; i < array.length ; i++){
      sum = array[i] + sum;
      }
      return (sum/array.length);
    }
  
    myLineChart = new Chart(context).Line(LineChartData);
    myBarChart = new Chart(context2).Bar(BarChartData);
    //var legend = myLineChart.generateLegend();
    //$( '#chart-legend' ).html(legend);
    $("#lineChartAvg").text('Average: ' + lineAvg);
    $("#barChartAvg").text('Average: ' + barAvg.toFixed(3) + 'ms');

  });

  $("#btnSubmit").click(function(){
    $.getJSON(ajaxurl, ajaxData, function(json){
      var ajaxData = {
        'action': 'get_chart_data','amount':3,
      };

      $.each(json, function (i,value){
        chartLabel.push(value.time);
        lineValue.push(value.request_count);
        barValue.push(value.avg_resp);
      });
      }); 
    myLineChart.update();
  });

});
})(jQuery);
