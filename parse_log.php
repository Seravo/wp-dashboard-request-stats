<?php

class time_data {
    public $time = NULL;
    public $request_count = 0;
    public $post_count = 0;
    public $get_count= 0;

}

//return an array with x amount of time objects
function parse_log_file( $path , $regexp ){

  $lines = file( $path );
  if($lines==false){
    return false;
  } 

  $unit = new time_data;
  $time_array = array();
  $matches;

  foreach ( $lines as $line ){
  //find and extract timestamp
  if (preg_match( $regexp, $line, $matches )){

    //this is done only once to set the previous
    if( is_null($unit->time) ){
      $unit->time = $matches[0];

    }
    //if date has changed, write to new date object
    if( $unit->time != $matches[0] ){
      // push old into array
      $time_array[] = $unit;
      $unit = new time_data;
      $unit->time = $matches[0];
    }

    $unit->request_count++;

    /*if ( preg_match( $resp_exp , $line )){
      $day->get_count++ ;
    } elseif (preg_match( "/MISS/" , $line )){
      $day->post_count++; 
    }*/
  
  }
  }

  // push last into array

  $time_array[] = $unit;
  return $time_array;
}

$time_exp = '#[0-3][0-9]/.{3}/20[0-9]{2}#';
//$time_exp_hour = '#[0-3][0-9]/.{3}/20[0-9]{2}:[0-2][0-9]#';
$unit_data;
$path = 'empty.log';
//desired length of the array
$desired_size = 10;

//if the following returns true, the time unit is one day
if (file_exists($path . '.1')){
  $time_exp = '#[0-3][0-9]/.{3}/20[0-9]{2}#';
  $unit_data = parse_log_file( $path, $time_exp );
  $real_size = count($unit_data);
  if($real_size < $desired_size){
    echo $real_size . "\n";
    echo $desired_size . "\n";
    $temp = parse_log_file( $path . '.1', $time_exp );
    //might require some optimization later on
    for($i = 0; $i <= ($desired_size - $real_size);$i++){
      $unit_data=array_reverse($unit_data);
      $unit_data[] = array_pop($temp);
      $unit_data=array_reverse($unit_data);
    }
  }


}
//this is done only when a) *log.1 doesn't exist and when
//*.log contains only the data for one day or less
else{
  //time unit is one hour
  $time_exp = '#[0-3][0-9]/.{3}/20[0-9]{2}:[0-2][0-9]#';
  $unit_data = parse_log_file( $path, $time_exp );
}



foreach($unit_data as $unit){

  echo $unit->time . "\n";

}



/*
//here we check if the thing works or not
echo("TOTAL REQUEST COUNT:  " . $total_request_count . "\n");
foreach ( $days_array as $data ){
  echo("DATE: " . $data->date . "\n");
  echo("All Requests: : " . $data->request_count . "\n");
  echo("Get Requests: : " . $data->get_count . "\n");
  echo("Post Requests: : " . $data->post_count . "\n");
}


define('__ROOT__', dirname(__FILE__));

require_once __ROOT__."/include/FormatException.php";
require_once __ROOT__."/include/LogParser.php";

$default_access_log_format = '%h %a %{User-Identifier}i %u %t "%r" %>s %b "%{Referer}i" "%{User-Agent}i" %{Cache-Status}i %{Powered-By}i %T';
$path = 'total-access.log';
$parser = new \Kassner\LogParser\LogParser();
$parser->setFormat($default_access_log_format);
$lines = file( $path );
  foreach ($lines as $line) {
    $entry = $parser->parse($line);
    echo $entry->host;
}


?>
*/
