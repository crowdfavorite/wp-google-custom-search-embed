<?php

class CFGoogleCustomSearchEmbedAdmin {

	public static function adminInit() {
		if (!empty($_POST['cf_google_custom_search_save']) &&
			isset($_POST['cf_gcse_engine_id']) &&
			check_admin_referer('gcse_save_options')
		) {
			update_option('_cf_gcse_engine_id', $_POST['cf_gcse_engine_id']);
			wp_redirect($_SERVER['REQUEST_URI']);
			die();
		}
	}

	public static function adminMenu() {
		add_submenu_page('options-general.php', __('CF Google Search', 'cfgcse'),
			__('CF Google Search', 'cfgcse'), 'manage_options', 'cf-google-custom-search',
			'CFGoogleCustomSearchEmbedAdmin::adminPage'
		);
	}

	public static function adminPage() {
		$cse_id = get_option('_cf_gcse_engine_id', '');
		?>
<?php screen_icon(); ?><h1><?php echo __('CF Google Custom Search Engine Configuration', 'cfgcse'); ?></h1>
<form action="" method="post" id="cf_google_custom_search_admin" style="max-width: 500px;">
<?php wp_nonce_field('gcse_save_options'); ?>
<table class="form-table">
	<tbody>
	<tr>
		<th scope="row"><label for="cf_gcse_remote_id"><?php _e('Search Engine ID', 'cfgcse'); ?>:</label></th>
		<td><input type="text" id="cf_gcse_engine_id" name="cf_gcse_engine_id" value="<?php echo esc_attr($cse_id); ?>" class="widefat" /></td>
	</tr>
	<tr>
		<th><input type="submit" name="cf_google_custom_search_save" value="<?php echo esc_attr(__('Save', 'cfgcse')); ?>" class="button-primary" /></th>
		<td></td>
	</tr>
	</tbody>
</table>
</form>
<?php
	}
}
add_action('admin_init', 'CFGoogleCustomSearchEmbedAdmin::adminInit');
add_action('admin_menu', 'CFGoogleCustomSearchEmbedAdmin::adminMenu');
