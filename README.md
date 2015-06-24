# wp-dashboard-request-stats:
[![Build Status](https://travis-ci.org/Seravo/wp-dashboard-request-stats.svg?branch=master)](https://travis-ci.org/Seravo/wp-dashboard-request-stats)

A Wordpress plugin for reading and viewing accesslog data. At the moment the plugin extracts the amount requests per day and the average
responsetime per day, with hardcoded regular expressions.


##Installation

Create a *.zip archive containing a folder with the same name as the plugin and
install it via wp-admin

###or

Copy the folder wp-dashboard-request-stats including it's contents to you WordPress plugins directory.

Also remember to add the following to wp-config.php

```
define('WPDRS_LOGPATH','YOUR_LOG_PATH_HERE');
```
