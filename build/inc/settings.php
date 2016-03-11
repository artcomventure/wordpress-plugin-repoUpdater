<?php

/**
 * Add submenu page to 'Plugins'.
 */
add_action( 'admin_menu', 'repoupdater_settings__admin_menu' );
function repoupdater_settings__admin_menu() {
	add_plugins_page(
		__( 'Repository Connect', 'repoupdater' ),
		__( 'Repository Connect', 'repoupdater' ),
		'update_plugins',
		'repoupdater-settings',
		'repoupdater_settings_page'
	);
}

/**
 * Settings page markup.
 * + post action
 */
function repoupdater_settings_page() {
	// post
	if ( ! empty( $_POST['repoupdater_settings'] ) && check_admin_referer( 'repoupdater-settings' ) ) {
		// validate data
		foreach ( $_POST['repoupdater_settings'] as $basename => &$setting ) {
			array_map( 'trim', $setting );

			// check github.com URL format: https://github.com/USERNAME/REPO
			$url_was_set = !!$setting['URL'];
			if ( ! preg_match( '/^(https:\/\/github\.com\/[^\/]+\/[^\/]+\/?)(.*)/', $setting['URL'], $setting['URL'] ) ) {
				// error message
				if ( $url_was_set ) {
					$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $basename );
					echo '<div class="error"><p>' . sprintf( __( 'Wrong repository URL for %1$s. Please set again.' ), '<i>' . $plugin_data['Name'] . '</i>' ) . '</p></div>';
				}

				$setting = NULL;

				continue;
			} else {
				// auto-set from URL
				if ( preg_match( '/^tree\/([^\/]+)(\/.*)?/', $setting['URL'][2], $setting['URL'][2] ) ) {
					// auto branch
					if ( $setting['URL'][2][1] != 'master' ) {
						$setting['branch'] = $setting['URL'][2][1];
					}

					// auto subfolder
					$setting['subfolder'] = $setting['URL'][2][2];
				}

				$setting['URL'] = $setting['URL'][1];

				// force end with slash
				if ( ! preg_match( '/\/$/', $setting['URL'] ) ) {
					$setting['URL'] .= '/';
				}
			}

			// no leading or trailing slash
			$setting['subfolder'] = preg_replace( '/(^\/+|\/+$)/', '', $setting['subfolder'] );
		}

		// save settings
		set_transient( 'repoupdater_settings', $_POST['repoupdater_settings'] );

		// success message
		echo '<div class="updated"><p>' . __( 'Settings saved.' ) . '</p></div>';

		// (re-)load
		repoupdater_versions( FALSE );
	}

	// todo: use minimized css (doesn't work for now because of background image url 'destroyed' by cssnano) :/
	wp_enqueue_style( 'repoupdater-settings', REPOUPDATER_PLUGIN_URL . '/css/settings.css' );
	wp_enqueue_script( 'repoupdater-settings', REPOUPDATER_PLUGIN_URL . '/js/settings.min.js' );

	include( REPOUPDATER_PLUGIN_DIR . 'inc/settings.form.php' );
}
