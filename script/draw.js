(function ($) {
$( document ).ready(function(){
  
  //values and labels for charts
  var chartLabel = [];
  var requestValue = [];
  var responseValue = [];
  //ajax-options
  var ajaxData = {'action': 'get_chart_data'}; 
  //chart.js 
  var requestCanvas =  $("#requestChart").get(0);
  var responseCanvas = $("#responseChart").get(0);
  var requestCtx = requestCanvas.getContext("2d");
  var responseCtx = responseCanvas.getContext("2d");
  var responseData;
  var requestData;
  var requestChart;
  
  Chart.defaults.global.responsive = true;
  
  //fetch ajax-data as json
  $.getJSON(ajaxurl, ajaxData, function(json){
    $.each(json, function (i,value){
      chartLabel.push(value.time);
      requestValue.push(value.request_count);
      responseValue.push(value.avg_resp);
    });
    //define chart specific stuff here
    requestData = {
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
            data: requestValue
        }
      ]
    };
    
    responseData = {
      labels: chartLabel,
      datasets: [{
            //label: "Responsetimes (in seconds)",
            fillColor: "rgba(40,43,42,0.4)",
            strokeColor: "rgba(40,43,42,1)",
            highlightFill: "rgba(40,43,42,1)",
            highlightStroke: "rgba(40,43,42,1)",
            data: responseValue
      }]
    };
    
    //draw charts with supplied data
    requestChart = new Chart(requestCtx).Line(requestData,{bezierCurve:false,pointDot:false});
    responseChart = new Chart(responseCtx).Line(responseData,{bezierCurve:false,pointDot:false});
    
    //calculate and show the averages of received data
    responseAvg = countAvg(responseValue);
    requestAvg = Math.round(countAvg(requestValue));
    $("#requestChartAvg").text('Number of requests / 7 days | ( Average: ' + requestAvg + ' )');
    $("#responseChartAvg").text('Average response time / 7 days | ( 7 days average: ' + responseAvg.toFixed(3) + 's )');
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
    requestChart.destroy();
    responseChart.destroy();
    requestCtx.clearRect(0, 0, requestCanvas.width, requestCanvas.height);
    responseCtx.clearRect(0, 0, responseCanvas.width, responseCanvas.height);
    
    //clear old data
    chartLabel.length = 0;
    requestValue.length = 0;
    responseValue.length = 0;
    
    //fetch the wanted amount of data
    $.getJSON(ajaxurl, ajaxData, function(json){
      $.each(json, function (i,value){
        chartLabel.push(value.time);
        requestValue.push(value.request_count);
        responseValue.push(value.avg_resp);
      });
      
      responseData = {
      labels: chartLabel,
      datasets: [{
            //label: "Responsetimes (in seconds)",
            fillColor: "rgba(40,43,42,0.2)",
            strokeColor: "rgba(40,43,42,1)",
            highlightFill: "rgba(40,43,42,1)",
            highlightStroke: "rgba(40,43,42,1)",
            data: responseValue
      }]
      };
    
      requestData = {
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
            data: requestValue
        }
        ]};
      
      requestChart = new Chart(requestCtx).Line(requestData,{bezierCurve:false,pointDot:false});
      responseChart = new Chart(responseCtx).Line(responseData,{bezierCurve:false,pointDot:false});
      //calculate and show the averages of received data
      responseAvg = countAvg(responseValue);
      requestAvg = Math.round(countAvg(requestValue));
      $("#requestChartAvg").text('Number of requests / ' + amount +' days | ( Average: ' + requestAvg + ' )');
      $("#responseChartAvg").text('Average response time / ' + amount + ' days | ( ' + amount + ' days average: ' + responseAvg.toFixed(3) + 's )');
    
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
