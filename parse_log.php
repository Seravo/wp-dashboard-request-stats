<?php

class time_data {
    public $time = NULL;
    public $request_count = 0;
    public $post_count = 0;
    public $get_count= 0;

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

define('__ROOT__', dirname(__FILE__));
require_once __ROOT__."/include/FormatException.php";
require_once __ROOT__."/include/LogParser.php"; //kassner log parser
require_once __ROOT__."/include/Factory.php";

$parser = new \Kassner\LogParser\LogParser();

$log_location = '/home/tari/data/log/';
$log_file = 'php-error*';
$log_format; // use kassner logparser here
$log_files = glob( $log_location . $log_file ); // all available logfiles, including gzipped ones
$file_count = count( $log_files );

//if no files are found, print "No logfile found error" on the UI with javascript

if($file_count == 0){
  
  //this is here just for the structures sake, implement inside the dashboard... class
  //reply to ajax with array content that triggers error message
}


//if files are found, make a list of files to be parsed

else{
  //check which files are gzipped and get rid of them
  foreach($log_files as $key => $file){
   
   if(preg_match('#gz#',$file)){
    
    unset($log_files[$key]);
    
    }
    
  }



}


//

foreach($log_files as $file){
  
  $lines = file($file);
  foreach($lines as $line){
    
    /* $entry = $parser->parse($line);
    var_dump($entry);*/
  }
  
}
//pass the files to kassner logparser


//

//

//first, the name of the logfile(s) to be parsed has to be known
//then, their location is needed
//how log rotation renames old files
//count how many logfiles are found

//define the information to be parsed from the logfile



 '$host ' '$remote_addr - $remote_user [$time_local] '
'"$request" $status $body_bytes_sent '
  '"$http_referer" "$http_user_agent" '
  '$upstream_cache_status $sent_http_x_powered_by '
  '$request_time';
  
  '%h %a - %u %t "%r" %>s %b "%{Referer}i" "%{User-Agent}i" %{Cache-Status}i %T'

?>
