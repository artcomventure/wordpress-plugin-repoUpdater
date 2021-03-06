<?php

/**
 * Plugin Name: Repository Updater
 * Plugin URI: https://github.com/artcomventure/wordpress-plugin-repoUpdater/
 * Description: Update plugins directly from GitHub.
 * Version: 1.1.4
 * Text Domain: repoupdater
 * Author: artcom venture GmbH
 * Author URI: http://www.artcom-venture.de/
 */

if ( ! defined( 'REPOUPDATER_PLUGIN_FILE' ) ) {
	define( 'REPOUPDATER_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'REPOUPDATER_PLUGIN_URL' ) ) {
	define( 'REPOUPDATER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'REPOUPDATER_PLUGIN_DIR' ) ) {
	define( 'REPOUPDATER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * On activation.
 */
register_activation_hook( __FILE__, 'repoupdater_activate' );
function repoupdater_activate() {
	$plugin_data = get_plugin_data( __FILE__ );

	// pre-fill this plugin
	set_transient( 'repoupdater_settings', array(
		plugin_basename( __FILE__ ) => array(
			'URL' => $plugin_data['PluginURI'],
			'subfolder' => 'build',
		),
	) );

	repoupdater_versions( FALSE );
}

/**
 * t9n.
 */
add_action( 'after_setup_theme', 'repoupdater__after_setup_theme' );
function repoupdater__after_setup_theme() {
	load_theme_textdomain( 'repoupdater', REPOUPDATER_PLUGIN_DIR . 'languages' );
}

/**
 * Retrieve github.com' plugin version numbers.
 *
 * @param bool $cache
 *
 * @return mixed
 */
function repoupdater_versions( $cache = TRUE ) {
	// use cache? already cached? cache expired?
	if ( $cache && ( $versions = get_transient( 'repoupdater_versions' ) ) && HOUR_IN_SECONDS > ( time() - $versions['last_checked'] ) ) {
		unset( $versions['last_checked'] );

		return $versions;
	}

	$versions = array();

	if ( $settings = get_transient( 'repoupdater_settings' ) ) {
		foreach ( $settings as $basename => $setting ) {
			if ( ! $setting['URL'] ) {
				continue;
			}

			// GitHub vs Bitbucket

			$github = '/^https:\/\/github.com\//';
			$bitbucket = '/^https:\/\/bitbucket.org\//';

			// build plugin file raw URL
			if ( preg_match( $github, $setting['URL'] ) ) {
				$url = preg_replace( $github, 'https://raw.githubusercontent.com/', $setting['URL'] );
				$url .= $setting['branch'] ? $setting['branch'] : 'master';
			}
			elseif ( preg_match( $bitbucket, $setting['URL'] ) ) {
				$url = $setting['URL'] . 'raw/';
				$url .= $setting['branch'] ? $setting['branch'] : 'tip';
			}
			else continue;

			$url .= $setting['subfolder'] ? '/' . $setting['subfolder'] : '';
			// plugin file
			$file = explode( '/', $basename );
			$url .= '/' . $file[1];

			// eventually (try to) retrieve master's version number
			$versions[ $basename ] = wp_remote_get( $url );

			if ( ! is_wp_error( $versions[ $basename ] ) ) {
				// grep masters version number
				if ( preg_match( '/\* Version:\s*(\d+\.\d+\.\d+)/', wp_remote_retrieve_body( $versions[ $basename ] ), $versions[ $basename ] ) ) {
					$versions[ $basename ] = $versions[ $basename ][1];
				} else {
					unset( $versions[ $basename ] );
				}
			} else {
				unset( $versions[ $basename ] );
			}
		}

		// cache data
		set_transient( 'repoupdater_versions', $versions + array(
				'last_checked' => time(),
			) );
	}

	return $versions;
}

/**
 * Change details link to GitHub repository.
 */
add_filter( 'plugin_row_meta', 'repoupdater__plugin_row_meta', 10, 2 );
function repoupdater__plugin_row_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) == $file ) {
		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $file );

		$links[2] = '<a href="' . $plugin_data['PluginURI'] . '">' . __( 'Visit plugin site' ) . '</a>';

		$links[] = '<a href="' . admin_url( 'plugins.php?page=repoupdater-settings' ) . '">' . __( 'Settings' ) . '</a>';
	}

	return $links;
}

// settings
include( REPOUPDATER_PLUGIN_DIR . 'inc/settings.php' );
// update
include( REPOUPDATER_PLUGIN_DIR . 'inc/update.php' );

/**
 * Delete traces on deactivation.
 */
register_deactivation_hook( __FILE__, 'repoupdater_deactivate' );
function repoupdater_deactivate() {
	delete_transient( 'repoupdater_settings' );
	delete_transient( 'repoupdater_versions' );
}