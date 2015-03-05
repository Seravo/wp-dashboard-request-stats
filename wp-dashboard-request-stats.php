<?php
/**
* Plugin Name: Dashboard access log monitor
* Plugin URI: https://github.com/Seravo/wp-dashboard-log-monitor
* Description: Take a sneak peek on your access logs from the wordpress dashboard.
* Author: Tari Zahabi / Seravo Oy
* Author URI: http://seravo.fi
* Version: 1.0.3
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


/**
 * Initialize the plugin
 */

function wpdrs_init() {
  //wp_register_script('chartjs', plugins_url('/script/Chart.js', __FILE__), array('chartjs'),'1.0.1', true);

  //styles
  wp_register_style( 'stylesheet', plugins_url('style.css', __FILE__) );
  wp_enqueue_style( 'stylesheet');

  //external scripts
  wp_register_script( 'chartjs', plugins_url( '/script/Chart.js' , __FILE__) );
  wp_register_script( 'drawjs', plugins_url( '/script/draw.js' , __FILE__) );
  wp_enqueue_script( 'chartjs' );
  wp_enqueue_script( 'drawjs' );
  wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}
 

/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */

function wpdrs_add_dashboard_widgets() {
	wp_add_dashboard_widget(
                 'wp-dashboard-request-stats',         // Widget slug.
                 'WP Dashboard Request Stats',         // Title.
                 'wpdrs_dashboard_widget_function' // Display function.
        );	
}



/**
 * Create the function to output the contents of our Dashboard Widget.
 */

function wpdrs_dashboard_widget_function() {

	// Display canvas.
	echo '<canvas id="myChart" width="450" height="400"></canvas>';
  echo '<div id="chart-legend" ></div>';
}

/**
 * Fetch the chart data
 *
 */ 

function get_chart_data_callback(){

  echo '[90, 55, 10, 81, 10, 10, 60]';
  wp_die(); // this is required to terminate immediately and return a proper response

}


add_action( 'wp_dashboard_setup', 'wpdrs_add_dashboard_widgets' );
add_action( 'admin_enqueue_scripts', 'wpdrs_init' );
add_action( 'wp_ajax_get_chart_data', 'get_chart_data_callback' );

?>
