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
  
  Chart.defaults.global.responsive = true;
  
  //fetch ajax-data as json
  $.getJSON(ajaxurl, ajaxData, function(json){
    $.each(json, function (i,value){
      chartLabel.push(value.time);
      lineValue.push(value.request_count);
      barValue.push(value.avg_resp);
    });
    //define chart specific stuff here
    lineData = {
      labels: chartLabel,
      datasets: [
        {
            //label: "Avg requests",
            fillColor: "rgba(40,43,42,0.4)",
            strokeColor: "rgba(40,43,42,1)",
            pointColor: "rgba(40,43,42,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(40,43,42,1)",
            data: lineValue
        }
      ]
    };
    
    barData = {
      labels: chartLabel,
      datasets: [{
            //label: "Responsetimes (in seconds)",
            fillColor: "rgba(40,43,42,0.4)",
            strokeColor: "rgba(40,43,42,1)",
            highlightFill: "rgba(40,43,42,1)",
            highlightStroke: "rgba(40,43,42,1)",
            data: barValue
      }]
    };
    
    //draw charts with supplied data
    myLineChart = new Chart(lineCtx).Line(lineData,{bezierCurve:false,pointDot:false});
    myBarChart = new Chart(barCtx).Bar(barData);
    
    //calculate and show the averages of received data
    barAvg = countAvg(barValue);
    lineAvg = Math.round(countAvg(lineValue));
    $("#lineChartAvg").text('Number of requests / 7 days | ( Average: ' + lineAvg + ' )');
    $("#barChartAvg").text('Average response time / 7 days | ( 7 days average: ' + barAvg.toFixed(3) + 's )');
  });
  
  //button functionality
  $("#btnSubmit1").click(function(){
    submitDays(3);
  });
  $("#btnSubmit2").click(function(){
    submitDays(7);
  });
  $("#btnSubmit3").click(function(){
    submitDays(30);
  });  
  

  function submitDays( amount ){
    //
    ajaxData = {'action': 'get_chart_data','amount' : amount}; 
    
    //destroy the charts and clear canvases for updated values
    myLineChart.destroy();
    myBarChart.destroy();
    lineCtx.clearRect(0, 0, barCanvas.width, barCanvas.height);
    barCtx.clearRect(0, 0, barCanvas.width, barCanvas.height);
    
    //clear old data
    chartLabel.length = 0;
    lineValue.length = 0;
    barValue.length = 0;
    
    //fetch the wanted amount of data
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
            fillColor: "rgba(40,43,42,0.2)",
            strokeColor: "rgba(40,43,42,1)",
            highlightFill: "rgba(40,43,42,1)",
            highlightStroke: "rgba(40,43,42,1)",
            data: barValue
      }]
      };
    
      lineData = {
      labels: chartLabel,
      datasets: [
        {
            //label: "Avg requests",
            fillColor: "rgba(40,43,42,0.2)",
            strokeColor: "rgba(40,43,42,1)",
            pointColor: "rgba(40,43,42,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(40,43,42,1)",
            data: lineValue
        }
        ]};
      
      myLineChart = new Chart(lineCtx).Line(lineData,{bezierCurve:false,pointDot:false});
      myBarChart = new Chart(barCtx).Bar(barData);
      //calculate and show the averages of received data
      barAvg = countAvg(barValue);
      lineAvg = Math.round(countAvg(lineValue));
      $("#lineChartAvg").text('Number of requests / ' + amount +' days | ( Average: ' + lineAvg + ' )');
      $("#barChartAvg").text('Average response time / ' + amount + ' days | ( ' + amount + ' days average: ' + barAvg.toFixed(3) + 's )');
    
    });
  }
  
  function countAvg(array){
    var sum = 0;
    for(i = 0; i < array.length ; i++){
      sum = array[i] + sum;
    }
    return (sum/array.length);
  }
});
})(jQuery);
