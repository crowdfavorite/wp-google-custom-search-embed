# CF Google Custom Search Engine

## Description

Replaces WordPress' search with results from a [Google Custom Search Engine](http://www.google.com/cse)

## Installation

1. Create a [Google Custom Search Engine](http://www.google.com/cse)
2. Activate this plugin
3. Go to its [settings page](/wp/wp-admin/options-general.php?page=cf-google-custom-search) to enter the Custom Search Engine's UID.
	- Find "Search engine unique ID:" on the CSE's Basic Settings Page
4. Modify the theme's search template to output results.  (see Example below)

## Example

	<h1 class="page-title"><?php printf(__('Search Results for: %s', 'stn'), $search_title); ?></h1>

	<?php
	if (class_exists('CFGoogleCustomSearchEmbeddedEndpoint')) {
		echo CFGoogleCustomSearchEmbeddedEndpoint::onResultsShortcode();
	}
	?>
