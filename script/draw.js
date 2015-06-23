(function ($) {
$( document ).ready(function(){
  
  //values and labels for charts
  var chartLabel = [];
  var lineValue = [];
  var barValue = [];
  //ajax-options
  var ajaxData = {'action': 'get_chart_data'}; 
  //chart.js 
  var lineCanvas =  $("#lineChart").get(0);
  var barCanvas = $("#barChart").get(0);
  var lineCtx = lineCanvas.getContext("2d");
  var barCtx = barCanvas.getContext("2d");
  var lineData;
  var barData;
  var myLineChart;
  
  //fetch ajax-data as json
  $.getJSON(ajaxurl, ajaxData, function(json){
    $.each(json, function (i,value){
      chartLabel.push(value.time);
      lineValue.push(value.request_count);
      barValue.push(value.avg_resp);
    });
  
    lineData = {
      labels: chartLabel,
      datasets: [
        {
            //label: "Avg requests",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: lineValue
        }
      ]
    };
    
    barData = {
      labels: chartLabel,
      datasets: [{
            //label: "Responsetimes (in seconds)",
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: barValue
      }]
    };
    
    
    myLineChart = new Chart(lineCtx).Line(lineData,{bezierCurve:false});
    myBarChart = new Chart(barCtx).Bar(barData);
  
  });
  
  $("#btnSubmit").click(function(){
    
    ajaxData = {'action': 'get_chart_data','amount' : 3}; 
    
    //destroy the charts and clear canvases for updated values
    myLineChart.destroy();
    myBarChart.destroy();
    lineCtx.clearRect(0, 0, barCanvas.width, barCanvas.height);
    barCtx.clearRect(0, 0, barCanvas.width, barCanvas.height);
    
    //clear old data
    chartLabel.length = 0;
    lineValue.length = 0;
    barValue.length = 0;
    
    $.getJSON(ajaxurl, ajaxData, function(json){
      $.each(json, function (i,value){
        chartLabel.push(value.time);
        lineValue.push(value.request_count);
        barValue.push(value.avg_resp);
      });
      
      barData = {
      labels: chartLabel,
      datasets: [{
            //label: "Responsetimes (in seconds)",
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: barValue
      }]
      };
    
      lineData = {
      labels: chartLabel,
      datasets: [
        {
            //label: "Avg requests",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: lineValue
        }
        ]};
      
      myLineChart = new Chart(lineCtx).Line(lineData,{bezierCurve:false});
      myBarChart = new Chart(barCtx).Bar(barData);
    });
    
        
        
    
    
  });
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  /*
  //get context
  //var context = $("#lineChart").get(0).getContext("2d");
  //var context2 = $("#barChart").get(0).getContext("2d");
  //doesn't work, fix it later:
  //var options = { legendTemplate : "<ul id=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].pointColor%>\"><%if(datasets[i].label){%><%=datasets[i].label%><%}%></span></li><%}%></ul>" };

  var ajaxData = {
      'action': 'get_chart_data',
  };

  var chartLabel = [];
  var lineValue = [];
  var barValue = [];
  var myLineChart; //shows amount of requests
  var myBarChart; //shows response time
  var context; $.getJSON(ajaxurl, ajaxData, function(json){

    $.each(json, function (i,value){

      chartLabel.push(value.time);
      lineValue.push(value.request_count);
      barValue.push(value.avg_resp);
    });
  var context2;
  var barAvg;
  var lineAvg;
  
  
  //the first time the chart is drawn
 

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

        },* /
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
    
    context = $("#lineChart").get(0).getContext("2d");
    context2 = $("#barChart").get(0).getContext("2d");
    
    barAvg = countAvg(barValue);
    lineAvg = Math.round(countAvg(lineValue));
    myLineChart = new Chart(context).Line(LineChartData); 
    myBarChart = new Chart(context2).Bar(BarChartData);
    
    //var legend = myLineChart.generateLegend();
    //$( '#chart-legend' ).html(legend);
    $("#lineChartAvg").text('Number of requests per day (average ' + lineAvg + ')');
    $("#barChartAvg").text('Average response time per day (week average ' + barAvg.toFixed(3) + 'ms )');


  });
  
  //alter the amount of data shown
  $("#btnSubmit").click(function(){
    var ajaxData = {
        'action': 'get_chart_data','amount':3
      };
      var newChartLabel = [];
      var newLineValue = [];
      var newBarValue = [];
    
    $.getJSON(ajaxurl, ajaxData, function(json){
      $.each(json, function (i,value){
        newChartLabel.push(value.time);
        newLineValue.push(value.request_count);
        newBarValue.push(value.avg_resp);
      });
      i = 0;
      while (i < newBarValue.length) {
      myBarChart[0].datasets[0].bars[i].value = newBarValue[i];
      myBarChart[0].datasets[0].bars[i].label = newChartLabel[i];
      myBarChart[0].update();
      i++;
      }
    });
   
  });
  
  function countAvg(array){
    var sum = 0;
    for(i = 0; i < array.length ; i++){
      sum = array[i] + sum;
    }
    return (sum/array.length);
  }

*/
});
})(jQuery);
