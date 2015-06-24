<?php

class time_data {
    public $time = NULL;
    public $request_count = 0;
    public $avg_resp = 0;

}


//WHAT HAPPENS WHEN AJAX REQUEST IS PROCESSED ->

//check settings / logfile_dir , logfile_name , log_roll_extension
//function int count_files (logfile_dir, logfile_name)
//parse files (logfile_dir,logfile_name,count,log_roll_extension)

//

//first, the name of the logfile(s) to be parsed has to be known
//then, their location is needed
//how log rotation renames old files
//count how many logfiles are found
//count how many logfiles are gzipped


$log_location = '/home/tari/data/log/';
$log_file = 'total-access.log';
$log_format; // use kassner logparser here
$lines = file($log_location . $log_file);

//array of regex, 

$unit = new time_data;
$time_array = array();
$matches;
$resp_exp = "#[0-9]+.[0-9]+$#";
$res_sum = 0;
$regexp = '#[0-3][0-9]/.{3}/20[0-9]{2}#';

//for now, allow the user to only set the following things to match
//so that the UI defines which values are extracted (), but the user
//can define the regexp itself
//this however keeps the door open for the future possibility
//of adding more things to parse
$regexes = array('time'=>$regexp,'response'=>$resp_exp);
$asd_array = array();

//one parsed line is one request

foreach ( $lines as $line ){
  //do the parsing
  $temmi = wpdrs_parser($line,$regexes);
  $temmi['time'] = str_replace('/',' ',$temmi['time']);
  
  if(is_null($unit->time))
    $unit->time = $temmi['time'];
  if($unit->time != $temmi['time']){
    $unit->avg_resp = $unit->avg_resp / $unit->request_count;
    $time_array[] = $unit;
    $unit = new time_data;
  }  
  $unit->request_count++;
  $unit->avg_resp = $unit->avg_resp + $temmi['response']; 
  
}
  $unit->avg_resp = $unit->avg_resp / $unit->request_count;
  $time_array[] = $unit;


//parses everything that is defined in the regex array
private function wpdrs_parser( $line, $regex_array){
  $matches;
  $results = array();
  foreach($regex_array as $key => $exp){
    if(preg_match($exp, $line,$matches)){
      $results[$key] = $matches[0];
    }
  }
  return $results;
}







//first, the name of the logfile(s) to be parsed has to be known
//then, their location is needed
//how log rotation renames old files
//count how many logfiles are found

//define the information to be parsed from the logfile

/*

 '$host ' '$remote_addr - $remote_user [$time_local] '
'"$request" $status $body_bytes_sent '
  '"$http_referer" "$http_user_agent" '
  '$upstream_cache_status $sent_http_x_powered_by '
  '$request_time';
  
  '%h %a - %u %t "%r" %>s %b "%{Referer}i" "%{User-Agent}i" %{Cache-Status}i %T'
*/
?>
