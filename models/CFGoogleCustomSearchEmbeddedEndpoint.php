<?php

load_plugin_textdomain('cfgcse');
if (!class_exists('CFGoogleCustomSearchEmbeddedEndpoint')) {
class CFGoogleCustomSearchEmbeddedEndpoint {
	public static $term;
	private static $options = array();

	public static function onSearch($where, $query) {
		global $wp_the_query;
		// compare query to wp_the_query to see if it is main. This is for compatibility prior to 3.3
		// 3.3+ use is_main_query() instead
		if ($query === $wp_the_query && is_search() && !is_admin()) {
			remove_filter('posts_where', 'CFGoogleCustomSearchEmbeddedEndpoint::onSearch');
			self::$term = get_query_var('s');
			$where = 'WHERE 1 = 0 ';
			self::$options = apply_filters('cf_gcse_options', self::$options);
		}
		return $where;
	}
	
	public static function onBoxShortcode($atts = array()) {
		$cse_id = get_option('_cf_gcse_engine_id', null);
		$search_url = apply_filters('cf_gcse_search_url', home_url());
		$protocol = is_ssl() ? 'https' : 'http';
		ob_start();
	?>
		<div id="cse-search-form" style="width: 100%;"><?php _e('Loading', 'cfgcse'); ?></div>
		<script src="<?php echo $protocol; ?>://www.google.com/jsapi" type="text/javascript"></script>
		<script type="text/javascript"> 
			google.load('search', '1', {language : 'en'});
			google.setOnLoadCallback(function() {
				var customSearchOptions = <?php echo json_encode(self::$options); ?>;
				var customSearchControl = new google.search.CustomSearchControl(
					<?php echo json_encode($cse_id); ?>, customSearchOptions);
				customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
				var options = new google.search.DrawOptions();
				options.setAutoComplete(true);
				options.enableSearchboxOnly(<?php echo json_encode($search_url); ?>, "s");
				customSearchControl.draw('cse-search-form', options);
			}, true);
		</script>
	<?php
		return ob_get_clean();
	}
	
	public static function onResultsShortcode($atts = array()) {
		$cse_id = get_option('_cf_gcse_engine_id', null);
		$protocol = is_ssl() ? 'https' : 'http';
		ob_start();
	?>
		<div id="cse" style="width: 100%;"><?php _e('Loading', 'cfgcse'); ?></div>
		<script src="<?php echo $protocol; ?>://www.google.com/jsapi" type="text/javascript"></script>
		<script type="text/javascript"> 
			google.load('search', '1', {language : 'en'});
			google.setOnLoadCallback(function() {
				var customSearchOptions = {};
				<?php if (!empty(self::$options)) { ?>
				customSearchOptions[google.search.Search.RESTRICT_EXTENDED_ARGS] = <?php echo json_encode(self::$options); ?>;
				<?php } ?>
				var customSearchControl = new google.search.CustomSearchControl(
					<?php echo json_encode($cse_id); ?>, customSearchOptions);
				customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
				var options = new google.search.DrawOptions();
				options.setAutoComplete(true);
				customSearchControl.setLinkTarget(google.search.Search.LINK_TARGET_SELF);
				customSearchControl.draw('cse', options);
				var queryFromUrl = <?php echo json_encode(self::$term); ?>;
				if (queryFromUrl) {
					customSearchControl.execute(queryFromUrl);
				}
			}, true);
		</script>
 	<?php
		return ob_get_clean();
	}
}

add_filter('posts_where', 'CFGoogleCustomSearchEmbeddedEndpoint::onSearch', 10, 2);
add_shortcode('cf-google-search-embedded-box', 'CFGoogleCustomSearchEmbeddedEndpoint::onBoxShortcode');
add_shortcode('cf-google-search-embedded-results', 'CFGoogleCustomSearchEmbeddedEndpoint::onResultsShortcode');

}
