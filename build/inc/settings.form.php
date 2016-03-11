<div class="wrap">
	<h2><?php _e( 'Repository Connect', 'repoupdater' ); ?></h2>

	<p><?php printf( __( 'Connect your WordPress plugins with the corresponding repositories on GitHub. Select the branch you want to use (default is \'master\') and define opionally the subfolder the plugin files are located. Once configured you can update the plugin via the <a href="%1$s">Plugins page</a> as usual.', 'repoupdater' ), admin_url( 'plugins.php' ) ); ?></p>

	<form method="post" action="">
		<?php wp_nonce_field( 'repoupdater-settings' ); ?>

		<?php // get all plugins
		$plugins = get_plugins();
		foreach ( $plugins as $basename => $data ) {
			// 'reload' plugin data to get translated strings
			$plugins[$basename] = get_plugin_data( WP_PLUGIN_DIR . '/' . $basename );
		}

		// get master versions
		$versions = repoupdater_versions();

		// get settings
		if ( ! $settings = get_transient( 'repoupdater_settings' ) ) {
			$settings = array();
		} ?>

		<div id="repoupdater-settings">

			<ul class="tabs">

				<?php foreach ( $plugins as $basename => $data ) :
					if ( ! isset( $settings[ $basename ] ) ) {
						$settings[ $basename ] = array();
					}

					// merge defaults
					$settings[ $basename ] += array(
						'URL' => '',
						'branch' => '',
						'subfolder' => '',
						'classes' => array(),
					);

					if ( array_filter( $settings[ $basename ] ) ) {
						$settings[ $basename ]['classes'][] = 'github';
					}

					if ( ! empty( $settings[ $basename ]['URL'] ) && ! isset( $versions[ $basename ] ) ) {
						$settings[ $basename ]['classes'][] = 'error';
					} ?>

					<li>
						<a href="#tab_<?php echo sanitize_title( $data['Name'] ); ?>"<?php echo( $settings[ $basename ]['classes'] ? ' class="' . implode( ' ', $settings[ $basename ]['classes'] ) . '"' : '' ); ?>>
							<?php echo $data['Name']; ?>
						</a>
					</li>

				<?php endforeach; ?>
			</ul>

			<div class="panels">
				<?php foreach ( $plugins as $basename => $data ) : ?>

					<table id="tab_<?php echo sanitize_title( $data['Name'] ); ?>" class="form-table">

						<?php if ( in_array( 'error', $settings[ $basename ]['classes'] ) ) :
							$error[] = $data['Name']; ?>
						<thead>
							<td colspan="2">
								<div>
									<p>
										<strong><?php _e( 'Connection to GitHub repository failed.', 'repoupdater' ); ?></strong>
										<?php _e( 'Please check settings.', 'repoupdater' ); ?>
									</p>
								</div>
							</td>
						</thead>
						<?php endif; ?>

						<tbody>

						<tr valign="top">
							<th><label for=""><?php _e( 'Repository URL', 'repoupdater' ); ?></label></th>
							<td>
								<input type="text" class="regular-text"
								       value="<?php echo $settings[ $basename ]['URL']; ?>"
								       name="repoupdater_settings[<?php echo $basename; ?>][URL]"/>

								<p class="description">
									<?php _e( 'Format: https://github.com/username/repo/', 'repoupdater' ); ?>
								</p>
							</td>
						</tr>

						<tr valign="top">
							<th><label for=""><?php _e( 'Branch or Release', 'repoupdater' ); ?></label></th>
							<td>
								<input type="text" class="regular-text"
								       value="<?php echo $settings[ $basename ]['branch']; ?>"
								       name="repoupdater_settings[<?php echo $basename; ?>][branch]" placeholder="master"/>

								<p class="description">
									<?php _e( "Which one you want to use? Default is 'master'.", 'repoupdater' ) ?>
								</p>
							</td>
						</tr>

						<tr valign="top">
							<th><label for=""><?php _e( 'Subfolder', 'repoupdater' ); ?></label></th>
							<td>
								<input type="text" class="regular-text"
								       value="<?php echo $settings[ $basename ]['subfolder']; ?>"
								       name="repoupdater_settings[<?php echo $basename; ?>][subfolder]"/>

								<p class="description">
									<?php _e( "In case WordPress plugin files are not in repository's root directory.", 'repoupdater' ) ?>
								</p>
							</td>
						</tr>

						</tbody>

					</table>

				<?php endforeach; ?>
			</div>

		</div>
		<!-- #repoupdater-settings -->

		<?php if ( !empty( $error ) ) : ?>
			<div class="error">
				<p>
					<strong><?php _e( 'Connection to GitHub repository failed.', 'repoupdater' ); ?></strong>
					<?php printf( __( 'Please check settings for: %1$s', 'repoupdater' ), implode( ', ', $error ) ); ?>
				</p>
			</div>
		<?php endif; ?>

		<?php submit_button(); ?>

	</form>
</div>
