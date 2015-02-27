<?php
/**
* Plugin Name: Dashboard access log monitor
* Plugin URI: https://github.com/Seravo/wp-dashboard-log-monitor
* Description: Take a sneak peek on your access logs from the wordpress dashboard.
* Author: Tari / Seravo Oy
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
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */

function example_add_dashboard_widgets() {

	wp_add_dashboard_widget(
                 'example_dashboard_widget',         // Widget slug.
                 'Example Dashboard Widget',         // Title.
                 'example_dashboard_widget_function' // Display function.
        );	
}
add_action( 'wp_dashboard_setup', 'example_add_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function example_dashboard_widget_function() {

	// Display whatever it is you want to show.
	echo "Hello World, I'm a great Dashboard Widget";
}
?>
