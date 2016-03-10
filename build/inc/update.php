<?php

/**
 * Add styles and scripts to specific pages.
 */
add_action( 'admin_enqueue_scripts', 'repoupdater_update__admin_enqueue_scripts' );
function repoupdater_update__admin_enqueue_scripts( $hook ) {
	if ( in_array( $hook, array(
			'plugins.php',
			'update-core.php'
		) ) && ( $settings = get_transient( 'repoupdater_settings' ) )
	) {
		foreach ( $settings as $basename => $setting ) {
			if ( $setting ) {
				$repoupdater_data[ $basename ] = $setting['URL'];
			}
		}

		// add js
		if ( isset( $repoupdater_data ) ) {
			wp_enqueue_script( 'repoupdater-update', REPOUPDATER_PLUGIN_URL . '/js/update.min.js', array( 'jquery' ), '20160309' );

			// add data to script
			wp_localize_script( 'repoupdater-update', 'repoupdaterData', $repoupdater_data );
		}
	}
}

/**
 * Git update notification.
 */
add_filter( 'site_transient_update_plugins', 'repoupdater__site_transient_update_plugins', 100 );
function repoupdater__site_transient_update_plugins( $value ) {
	$plugin_file = plugin_basename( REPOUPDATER_PLUGIN_FILE );

	// remove this' plugin entry (in case of name conflict with existing https://wordpress.org/plugins/ plugin)
	foreach ( array( 'response', 'no_update' ) as $group ) {
		if ( isset( $value->{$group}[ $plugin_file ] ) ) {
			unset( $value->{$group}[ $plugin_file ] );
			break;
		}
	}

	// check all plugins
	if ( $settings = get_transient( 'repoupdater_settings' ) ) {
		$versions = repoupdater_versions();

		foreach ( $settings as $basename => $setting ) {
			if ( ! $setting ) {
				continue;
			}

			// remove plugin entry (in case of name conflict with existing https://wordpress.org/plugins/ plugin)
			foreach ( array( 'response', 'no_update' ) as $group ) {
				if ( isset( $value->{$group}[ $basename ] ) ) {
					unset( $value->{$group}[ $basename ] );
					break;
				}
			}

			// 'create' plugin object
			$plugin = (object) array(
				'plugin' => $basename,
				'url' => $setting['URL'],
				'package' => $setting['URL'] . 'zipball/' . ( $setting['branch'] ? $setting['branch'] : 'master' ),
			);

			// check versions
			if ( isset( $versions[ $basename ] ) ) {
				// mark plugin for update
				if ( version_compare( $versions[ $basename ], $value->checked[ $basename ], '>' ) ) {
					$plugin->new_version = $versions[ $basename ];
					$value->response[ $basename ] = $plugin;
				} // or not
				else {
					$value->no_update[ $basename ] = $plugin;
				}
			}
		}
	}

	return $value;
}

/**
 * Check location and naming of downloaded files.
 *
 * @param $source
 * @param $remote_source
 * @param $this
 * @param $hook_extra
 *
 * @return string
 */
add_filter( 'upgrader_source_selection', 'repoupdater_update__upgrader_source_selection', 100, 4 );
function repoupdater_update__upgrader_source_selection( $source, $remote_source, $this, $hook_extra ) {
	if ( $settings = get_transient( 'repoupdater_settings' ) ) {
		if ( isset( $settings[ $hook_extra['plugin'] ] ) ) {
			// copy (maybe) subfolder to source
			if ( $settings[ $hook_extra['plugin'] ]['subfolder'] ) {
				require_once( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();

				global $wp_filesystem;

				// create temporary folder
				$tmp = untrailingslashit( $source );
				$tmp .= '_repoupdater';
				$tmp = trailingslashit( $tmp );
				wp_mkdir_p( $tmp );

				// copy plugin files from subfolder to tmp
				$result = copy_dir( trailingslashit( $source . $settings[ $hook_extra['plugin'] ]['subfolder'] ), $tmp );

				if ( ! is_wp_error( $result ) ) {
					// remove source
					$wp_filesystem->delete( $source, TRUE );

					// rename folder: tmp to source
					rename( $tmp, $source );
				}
			}

			// current plugin folder and its source file
			list( $folder, $file ) = explode( '/', $hook_extra['plugin'] );

			// rename (possible wrong) GitHub folder name
			if ( $source != ( $new_source = $remote_source . '/' . $folder . '/' ) ) {
				rename( $source, $new_source );

				// set source to rename folder
				$source = $new_source;
			}
		}
	}

	return $source;
}
