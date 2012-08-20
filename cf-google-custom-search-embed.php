<?php
/* 
Plugin Name: CF Google Custom Search (Embedded)
Plugin URI:
Description: Replaces WordPress search, providing search box information and results page options.
Version: 1.1
Author: Crowd Favorite
Author URI: http://crowdfavorite.com/
*/

load_plugin_textdomain('cfgcse');
define('CF_GCSE_URL', apply_filters('cf_gcse_url', plugins_url(basename(dirname(__file__))), basename(dirname(__file__))));
include dirname(__file__).'/models/CFGoogleCustomSearchEmbeddedEndpoint.php';

if (is_admin()) {
	include dirname(__file__).'/models/CFGoogleCustomSearchEmbeddedAdmin.php';
}
