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

  $lines = file( $path );
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
  //$path = "$log_location/total-access.log";
  
  $log_file = '/total-access.log*';
  $log_files = glob( $log_location . $log_file ); // all available logfiles, including gzipped ones
  $file_count = count( $log_files );
  $time_exp = '#[0-3][0-9]/.{3}/20[0-9]{2}#';
  $unit_data = array();
  $temp_array = array();
  

  if($file_count == 0){
      return;
  }
  
  else{
    //check which files are gzipped
    foreach($log_files as $key => $file){
      if(preg_match('#gz#',$file)){
        unset($log_files[$key]);
      }
    }
  }
  unset($file);
  foreach( $log_files as $file ){
    $temp_array[] = $this->parse_log_file( $file, $time_exp );
      foreach( $temp_array as $time_array ){
        foreach($time_array as $day){
          $unit_data[] = $day;
        }
      }
  }
  
  
  error_log(print_r($unit_data,true),0);
  
  $temp_array = array();
  
  for($x=0;$x<count($unit_data);$x++){
    for($y=0;$y<count($unit_data);$y++){
      if( $x != $y){
        /*if( $unit_data[$x]->time == $unit_data[$y]->time ){
          /*$unit_data[$x]->request_count = $unit_data[$x]->request_count + $unit_data[$y]->request_count;
          $unit_data[$x]->avg_resp = $unit_data[$x]->avg_resp +  $unit_data[$y]->avg_resp;
          $temp_array[] = $unit_data[$x];
        }*/
      }
    }
  }
  
  echo ( json_encode( $unit_data ) );
  wp_die();

}
}

$dashboard_request_stats = dashboard_request_stats::get_instance();
