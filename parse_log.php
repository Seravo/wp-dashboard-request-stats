<?php

class day_data
{
    public $date;
    public $request_count = 0;
    public $post_count = 0;
    public $get_count= 0;

}





$total_request_count = 0; //one request per line

$time_exp = '#[0-3][0-9]/.{3}/20[0-9]{2}#';

// opens the content of a file into an array
$lines = file('nginx-access.log');
$matches;
$previous = NULL;
$day = new day_data;
$days_array = array();

foreach ( $lines as $line ){
  ++$total_request_count;
  //find and extract timestamp
  preg_match( $time_exp, $line, $matches );

  //this is done only once to set the previous
  if( is_null($previous) ){
    $previous = $matches[0];
    $day->date = $previous;
  }
  
  if($previous != $matches[0]){
    //new day-object
    // push old into array
    $previous = $matches[0];
    // push old into array
    $days_array[] = $day;
    $day = new day_data;
    $day->date = $previous;
  }

  $day->request_count++;
  if (preg_match( "/GET/" , $line )){
      $day->get_count++ ;
      } elseif (preg_match( "/POST/" , $line )){
      $day->post_count++; 
    }
  

}

foreach ( $days_array as $data ){

echo("DATE: " . $data->date . "\n");
echo("GET: " . $data->get_count . "\n");

}

?>
