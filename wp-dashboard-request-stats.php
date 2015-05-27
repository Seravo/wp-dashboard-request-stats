<?php
/**
* Plugin Name: WP Dashboard Request Stats
* Plugin URI: https://github.com/Seravo/wp-dashboard-request-stats
* Description: Draws a graph from access log data into a dashboard widget
* Author: Tari Zahabi / Seravo Oy
* Author URI: http://seravo.fi
* Version: 0.0.1
* License: GPLv2 or later
*/
/** Copyright 2014 Seravo Oy
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

//define('__ROOT__', dirname(__FILE__));


/**
 * Class to store parsed data
 */
class time_data {
    public $time = NULL;
    public $request_count = 0;
    public $avg_resp = 0;
    //public $get_count= 0;
}

class dashboard_request_stats{

 public static function get_instance() {
    static $drs_instance = null;
    if (null === $drs_instance) {
      $drs_instance = new dashboard_request_stats();
    }
    return $drs_instance;
  }





/**
 * Initialize the plugin
 */
public function __construct() {
  add_action( 'admin_enqueue_scripts', array( $this, 'init' ) );
  add_action( 'wp_dashboard_setup', array( $this,'add_dashboard_widget') );
  add_action( 'wp_ajax_get_chart_data',array( $this,'get_chart_data_callback') );
}


public function init( $page_hook ) {

   //make sure the scripts/stylesheets are loaded only where needed
  if ( 'index.php' == $page_hook ){
    //external scripts
    wp_register_script( 'chartjs', plugins_url( '/script/Chart.js' , __FILE__), null, null, true );
    wp_register_script( 'drawjs', plugins_url( '/script/draw.js' , __FILE__), null, null, true );
    wp_enqueue_script( 'chartjs' );
    wp_enqueue_script( 'drawjs' );
    //styles
    //wp_register_style( 'stylesheet', plugins_url('style.css', __FILE__) );
    //wp_enqueue_style( 'stylesheet');

    //wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
  }

  else{
    return;
  }

}


/**
 * Add a widget to the dashboard.
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
public function add_dashboard_widget() {
  wp_add_dashboard_widget(
                 'wp-dashboard-request-stats',     // Widget slug.
                 'Request Stats',                  // Title.
                 array($this,'create_widget_content') // Display function.
        );
}


/**
 * Create the function to output the contents of our Dashboard Widget.
 */
public function create_widget_content() {
  // Display canvas.
  echo '<canvas id="lineChart" style="width:100%; height:100%"></canvas>';
  echo '<canvas id="barChart" style="width:100%; height:100%"></canvas>';
 
}


/**
 * Parse a logfile and return an array of day_data objects
 */
private function parse_log_file( $path , $regexp ){

  if(preg_match('#gz#',$path)){
    $lines = gzfile( $path );
   //error_log("laama on laamoista laamoin");
  }
  else{
    $lines = file( $path );
  }
  if($lines==false){
    return false;
  }

  $unit = new time_data;
  $time_array = array();
  $matches;
  $resp_exp= "#[0-9]+.[0-9]+$#";
  $res_sum=0;
  foreach ( $lines as $line ){
  //find and extract timestamp
    if (preg_match( $regexp, $line, $matches )){
      //convert the timestamp for easier sorting
      $matches[0] = str_replace('/',' ',$matches[0]);
      //this is done only once to set the previous
      if( is_null($unit->time) ){
        $unit->time = $matches[0];
      }
      //if date has changed, write to new date object
      if( $unit->time != $matches[0] ){
        //divide the sum of response times with requestcount
        $unit->avg_resp = floatval($res_sum) / $unit->request_count;
        // push old into array
        $time_array[] = $unit;
        $unit = new time_data;
        $unit->time = $matches[0];
        $res_sum=0;
      }

      if(preg_match( $resp_exp, $line, $matches )){

        $res_sum = $res_sum + floatval($matches[0]);
  
      }
      $unit->request_count++;

    /*if ( preg_match( $resp_exp , $line )){
      $day->get_count++ ;
    } elseif (preg_match( "/MISS/" , $line )){
      $day->post_count++;
    }*/

  }
  }

  //divide the sum of response times with requestcount
  // push last into array
  
  $unit->avg_resp = floatval($res_sum) / $unit->request_count;
  $time_array[] = $unit;
  
  
  return $time_array;
}


/**
 * Fetch the chart data
 */
public function get_chart_data_callback() {

  $log_location = dirname( ini_get( 'error_log' ) );
  $log_file = '/total-access.log*';
  $log_files = glob( $log_location . $log_file ); // all available logfiles, including gzipped ones
  //$file_count = count( $log_files );
  //$time_exp = '#[0-3][0-9]/.{3}/20[0-9]{2}#';
  $amount = 10; //amount of days
  
  $unit_data = $this->get_log_data( $log_files, $amount );
  
  /*else{
    //check which files are gzipped
    foreach($log_files as $key => $file){
      if(preg_match('#gz#',$file)){
        unset($log_files[$key]);
      }
    }
  }
  
  unset($file);
  //parse files
  foreach( $log_files as $file ){
    $temp_array[] = $this->parse_log_file( $file, $time_exp );
      foreach( $temp_array as $time_array ){
        foreach($time_array as $day){
          $unit_data[] = $day;
        }
      }
  }*/

  //$unit_data = $this->clean_array($unit_data);

  //error_log(print_r($toinen,true),0);
  
  echo ( json_encode( $unit_data ) );
  wp_die();

}

/**
 * Remove duplicates and make sure the entries in the array are in proper order
 */

private function clean_array( $array ){
  
  $temp_array = array();
  $temp_array2 = array();
  
  //get a list of unique dates
  for( $x = 0; $x < count($array) ; $x++ ){
     $temp_array[]= $array[$x]->time;
  }
  
  $temp_array = array_unique($temp_array);
  $temp_array = array_values($temp_array);
  //sort the array entries by date
  /*for( $x = 0; $x < count($temp_array); $x++ ){
    strtotime($temp_array[$x]);

  }*/
  
  //sort($temp_array);
  
  usort($temp_array, function($item1, $item2) {
    $ts1 = strtotime($item1);
    $ts2 = strtotime($item2);
    return $ts2 - $ts1;
  });
  //usort reverses the values,
  $temp_array  = array_reverse($temp_array,false);
  
  //error_log(print_r($temp_array,true),0);

  //remove duplicates from array
  foreach( $temp_array as $date ){
    $asd = new time_data();
    $asd->time = $date;
    for( $x = 0; $x < count( $array );$x++ ){
      if( $date == $array[$x]->time ){
          $asd->request_count = $asd->request_count + $array[$x]->request_count;
          $asd->avg_resp = $asd->avg_resp + $array[$x]->avg_resp;
      }
    }
    $temp_array2[] = $asd;
  }
  
  $array = $temp_array2;
  return $array;
}
/**
 * Return desired amount of data specified in days
 * Eats an array
 */

private function get_log_data( $logfiles, $amount ){
  $time_exp = '#[0-3][0-9]/.{3}/20[0-9]{2}#';
  $temp_array = array();
  $unit_data = array();
  if( count($logfiles) == 0 ){
      return;
  }
  /*else{
    //check which files are gzipped
    foreach($logfiles as $key => $file){
      if(preg_match('#gz#',$file)){
        unset($logfiles[$key]);
      }
    }
  }
  unset($file);*/
  //parse files
  
  /*foreach( $logfiles as $file ){
    $temp_array[] = $this->parse_log_file( $file, $time_exp );
      foreach( $temp_array as $time_array ){
        foreach($time_array as $day){
          $unit_data[] = $day;
        }
      }
  }*/
  
  foreach($logfiles as $file){
    $temp_array = $this->parse_log_file( $file, $time_exp );
    $this->clean_array($temp_array);
    foreach($temp_array as $entry){
      if(count($unit_data) >= $amount){
        break 2;
      }
      else{
        $unit_data[] = $entry;
      }
    }
    
  }
  



return $this->clean_array($unit_data);
}

}

$dashboard_request_stats = dashboard_request_stats::get_instance();
