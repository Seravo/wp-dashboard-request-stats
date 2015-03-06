<?php

class day_data {
    public $date = NULL;
    public $request_count = 0;
    public $post_count = 0;
    public $get_count= 0;

}

//return an array with x amount of day objects
function parse_log_file( $path ){
  $time_exp = '#[0-3][0-9]/.{3}/20[0-9]{2}#';
  $lines = file( $path );
  $day = new day_data;
  $days_array = array();
  $matches;

  foreach ( $lines as $line ){
  //find and extract timestamp
  if (preg_match( $time_exp, $line, $matches )){

    //this is done only once to set the previous
    if( is_null($day->date) ){
      $day->date = $matches[0];

    }
    //if date has changed, write to new date object
    if( $day->date != $matches[0] ){
      // push old into array
      $days_array[] = $day;
      $day = new day_data;
      $day->date = $matches[0];
    }

    $day->request_count++;

    /*if ( preg_match( $resp_exp , $line )){
      $day->get_count++ ;
    } elseif (preg_match( "/MISS/" , $line )){
      $day->post_count++; 
    }*/
  
  }
  }

  // push last into array
  $days_array[] = $day;

  return $days_array;
}



$day_array1 = parse_log_file('total-access.log');
$day_array2 = array();
$day_total;
$d_count = count($day_array1);
//if there's less than $d_amount days worth of data
$d_amount = 7;
if($d_count <= $d_amount){

  //count how many days are taken from the previous log
  $x = $d_amount - $d_count;
  $day_array2 = parse_log_file('total-access.log.1');

  for($i = 0; $i < $x ; $i++){
    array_shift($day_array2);
  }
  //
  $day_total=array_merge($day_array1,$day_array2);
}

else{

  $day_total = $day_array1;

}
foreach($day_total as $day){

  echo $day->date . "\n";

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
