<?php
/** Copyright 2014-2016 Seravo Oy
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/
?>

<div class="wpdrsContainer">

    
    <div id="requestChartCont"><canvas id="requestChart"></canvas></div>
    <div id="requestChartAvg">Number of requests / 7 days ( 7 days average X )</div>
   
    <div id="responseChartCont"><canvas id="responseChart"></canvas></div>
    <div id="responseChartAvg">Average response time / 7 day ( 7 days average X s)"</div>
    <div id="buttonRow">
      <div class="submit-button">
        <input id="btnSubmit1" type="submit" value="3 Days" />
      </div>
      <div class="submit-button">
        <input id="btnSubmit2" type="submit" value="7 days" />
      </div>
      <div class="submit-button">
        <input id="btnSubmit3" type="submit" value="30 days" />
      </div>
    </div>
</div>






