<?php
/**
 * Skins support
 *
 * @package CARZ
 * @since CARZ 1.0.46
 */

if ( ! function_exists( 'carz_skins_get_active_skin_name' ) ) {
	/**
	 * Return a name of the active skin
	 * 
	 * @return string  The name of the active skin
	 */
	function carz_skins_get_active_skin_name() {
		static $carz_active_skin_saved = false;
		$carz_active_skin = '';
		if ( ! is_admin() ) {
			$carz_active_skin = carz_get_value_gp( 'skin' );
			if ( CARZ_REMEMBER_SKIN ) {
				if ( empty( $carz_active_skin ) ) {
					$carz_active_skin = carz_get_cookie( 'carz_current_skin' );
				} else if ( ! $carz_active_skin_saved ) {
					carz_set_cookie( 'carz_current_skin', $carz_active_skin );
					$carz_active_skin_saved = true;
				}
			}
		}
		if ( empty( $carz_active_skin ) ) {
			$carz_active_skin = get_option( sprintf( 'theme_skin_%s', get_stylesheet() ), CARZ_DEFAULT_SKIN );
		}
		return $carz_active_skin;
	}
}

if ( ! function_exists( 'carz_skins_admin_notice_skin_missing' ) ) {
	/**
	 * Show the admin notice if the current skin is missing
	 * 
	 * @hooked 'admin_notices'
	 */
	function carz_skins_admin_notice_skin_missing() {
		get_template_part( apply_filters( 'carz_filter_get_template_part', 'skins/skins-notice-missing' ) );
	}
}

/**
 * Define the constant with the current skin name
 */
if ( ! defined( 'CARZ_SKIN_NAME' ) ) {
	$carz_current_skin = carz_skins_get_active_skin_name();
	// Set current 
	if ( ! file_exists( CARZ_THEME_DIR . "skins/{$carz_current_skin}/skin.php" )
		&&
		( CARZ_CHILD_DIR == CARZ_THEME_DIR || ! file_exists( CARZ_CHILD_DIR . "skins/{$carz_current_skin}/skin.php" ) )
	) {
		if ( is_admin() ) {
			add_action( 'admin_notices', 'carz_skins_admin_notice_skin_missing' );
		}
		$carz_current_skin = 'default';
		// Remove condition to set 'default' as an active skin if current skin is absent
		if ( false ) {
			update_option( sprintf( 'theme_skin_%s', get_stylesheet() ), $carz_current_skin );
		}
	}
	define( 'CARZ_SKIN_NAME', $carz_current_skin );
}

if ( ! function_exists( 'carz_skins_get_current_skin_name' ) ) {
	/**
	 * Return the name of the current skin (can be overriden on the page)
	 * 
	 * @return string  The name of the current skin
	 */
	function carz_skins_get_current_skin_name() {
		return carz_esc( CARZ_SKIN_NAME );
	}
}

if ( ! function_exists( 'carz_skins_get_current_skin_dir' ) ) {
	/**
	 * Return the replative path (from the theme root folder) of the current skin (can be overriden on the page)
	 * 
	 * @param string|bool $skin  The name of the skin. If false, the current skin is used.
	 * 
	 * @return string  The relative path of the current skin
	 */
	function carz_skins_get_current_skin_dir( $skin=false ) {
		return 'skins/' . trailingslashit( $skin ? $skin : carz_skins_get_current_skin_name() );
	}
}

// Theme init priorities:
// Action 'after_setup_theme'
// 1 - register filters to add/remove lists items in the Theme Options
if ( ! function_exists( 'carz_skins_theme_setup1' ) ) {
	add_action( 'after_setup_theme', 'carz_skins_theme_setup1', 1 );
	/**
	 * Theme setup function to initialize skins support. Add the list of available skins to the storage
	 * (must be filled by the filter 'carz_filter_skins_list').
	 * 
	 * @hooked 'after_setup_theme', 1
	 * 
	 * @trigger carz_filter_skins_list
	 */
	function carz_skins_theme_setup1() {
		carz_storage_set( 'skins', apply_filters( 'carz_filter_skins_list', array() ) );
	}
}


if ( ! function_exists( 'carz_skins_add_body_class' ) ) {
	add_filter( 'body_class', 'carz_skins_add_body_class' );
	/**
	 * Add a class "skin_xxx" to the body with current skin name (slug) instead 'xxx'
	 * 
	 * @hooked 'body_class'
	 * 
	 * @param array $classes  The list of classes to be added to the body
	 * 
	 * @return array  The list of classes to be added to the body
	 */
	function carz_skins_add_body_class( $classes ) {
		$classes[] = sprintf( 'skin_%s', carz_skins_get_current_skin_name() );
		return $classes;
	}
}


if ( ! function_exists( 'carz_skins_get_available_skins' ) ) {
	add_filter( 'carz_filter_skins_list', 'carz_skins_get_available_skins' );
	/**
	 * Retrieve available skins from the upgrade server. The list of skins is stored to the cache for 2 days.
	 * 
	 * @hooked 'carz_filter_skins_list'
	 * 
	 * @param array $skins  The list of skins to be updated with the available skins
	 * 
	 * @return array  The list of available skins
	 */
	function carz_skins_get_available_skins( $skins = array() ) {
		$skins_file      = carz_get_file_dir( 'skins/skins.json' );
		$skins_installed = json_decode( carz_fgc( $skins_file ), true );
		$skins           = get_transient( 'carz_list_skins' );
		if ( ! is_array( $skins ) || count( $skins ) == 0 || ! empty( $_GET['force-check'] ) ) {
			$skins_available = carz_get_upgrade_data( array(
				'action' => 'info_skins'
			) );
			if ( empty( $skins_available['error'] ) && ! empty( $skins_available['data'] ) && $skins_available['data'][0] == '{' ) {
				$skins = json_decode( $skins_available['data'], true );
			}
			if ( ! is_array( $skins ) || count( $skins ) == 0 ) {
				$skins = $skins_installed;
			}
			set_transient( 'carz_list_skins', $skins, 2 * 24 * 60 * 60 );       // Store to the cache for 2 days
		}
		// Check if new skins appears after the theme update
		// (included in the folder 'skins' inside the theme)
		if ( is_array( $skins_installed ) && count( $skins_installed ) > 0 ) {
			foreach( $skins_installed as $k => $v ) {
				if ( ! isset( $skins[ $k ] ) ) {
					$skins[ $k ] = $v;
				}
			}
		}
		// Check the state of each skin
		if ( is_array( $skins ) && count( $skins ) > 0 ) {
			foreach( $skins as $k => $v ) {
				if ( ! is_array( $v ) ) {
					unset( $skins[ $k ] );
				} else {
					$skins[ $k ][ 'installed' ] = carz_skins_get_file_dir( "skin.php", $k ) != '' && ! empty( $skins_installed[ $k ][ 'version' ] )
													? $skins_installed[ $k ][ 'version' ]
													: '';
				}
			}
		}
		return $skins;
	}
}

if ( ! function_exists( 'carz_skins_delete_skins_list' ) ) {
	add_action( 'activated_plugin', 'carz_skins_delete_skins_list');
	/**
	 * Delete the cache with a skins list on any plugin activated
	 * 
	 * @hooked 'activated_plugin'
	 * 
	 * @param string $plugin  The name of the activated plugin
	 * @param bool $network  True if the plugin is activated for the whole network
	 */
	function carz_skins_delete_skins_list( $plugin = '', $network = false) {
		if ( strpos( $plugin, 'trx_addons' ) !== false ) {
			delete_transient( 'carz_list_skins' );
		}
	}
}



// Notice with info about new skins or new versions of installed skins
//------------------------------------------------------------------------

if ( ! function_exists( 'carz_skins_admin_notice' ) ) {
	add_action('admin_notices', 'carz_skins_admin_notice');
	/**
	 * Show the admin notice if new skins are available or if installed skins have new versions
	 * 
	 * @hooked 'admin_notices'
	 */
	function carz_skins_admin_notice() {
		// Check if new skins are available
		if ( current_user_can( 'update_themes' ) && carz_is_theme_activated() ) {
			$hide = get_option( 'carz_hide_notice_skins' ) || get_transient( 'carz_hide_notice_skins' );
			if ( $hide || ! carz_exists_trx_addons() ) {
				return;
			}
			$skins  = carz_storage_get( 'skins' );
			$update = 0;
			$free   = 0;
			$pay    = 0;
			foreach ( $skins as $skin => $data ) {
				if ( ! empty( $data['installed'] ) ) {
					if ( version_compare( $data['installed'], $data['version'], '<' ) ) {
						$update++;
					}
				} else if ( ! empty( $data['buy_url'] ) ) {
					$pay++;
				} else { 
					$free++;
				}
			}
			// Show notice
			if ( $update + $free + $pay == 0 ) {
				return;
			}
			set_query_var( 'carz_skins_notice_args', compact( 'update', 'free', 'pay' ) );
			get_template_part( apply_filters( 'carz_filter_get_template_part', 'skins/skins-notice' ) );
		}
	}
}

if ( ! function_exists( 'carz_callback_hide_skins_notice' ) ) {
	add_action('wp_ajax_carz_hide_skins_notice', 'carz_callback_hide_skins_notice');
	/**
	 * Dismiss the admin notice for 7 days on the "Close" button clicked
	 * 
	 * @hooked 'wp_ajax_carz_hide_skins_notice'
	 */
	function carz_callback_hide_skins_notice() {
		carz_verify_nonce();
		if ( current_user_can( 'update_themes' ) ) {
			set_transient( 'carz_hide_notice_skins', true, 7 * 24 * 60 * 60 );	// 7 days
		}
		carz_exit();
	}
}

if ( ! function_exists( 'carz_callback_hide_forever_skins_notice' ) ) {
	add_action('wp_ajax_carz_hide_forever_skins_notice', 'carz_callback_hide_forever_skins_notice');
	/**
	 * Hide the admin notice forever on the "Dismiss" button clicked
	 * 
	 * @hooked 'wp_ajax_carz_hide_forever_skins_notice'
	 */
	function carz_callback_hide_forever_skins_notice() {
		carz_verify_nonce();
		if ( current_user_can( 'update_themes' ) ) {
			update_option( 'carz_hide_notice_skins', true );
		}
		carz_exit();
	}
}


// Add skins folder to the theme-specific file search
//------------------------------------------------------------

if ( ! function_exists( 'carz_skins_get_file_dir' ) ) {
	/**
	 * Add skins folder to the theme-specific file search: check if a file exists
	 * in the skin folder and return its path or empty string if a file is not found
	 * 
	 * @param string $file  The file name to be searched
	 * @param string|bool $skin  The name of the skin. If false, the current skin is used.
	 * @param bool $return_url  If true, return the URL of the file, otherwise return the path
	 * 
	 * @return string  The path or URL of the file in the skin folder, or empty string if not found
	 */
	function carz_skins_get_file_dir( $file, $skin = false, $return_url = false ) {
		if ( carz_is_url( $file ) ) {
			$dir = $file;
		} else {
			$dir = '';
			if ( CARZ_ALLOW_SKINS ) {
				$skin_dir = carz_skins_get_current_skin_dir( $skin );
				if ( strpos( $file, $skin_dir ) === 0 ) {
					$skin_dir = '';
				}
				if ( CARZ_CHILD_DIR != CARZ_THEME_DIR && file_exists( CARZ_CHILD_DIR . ( $skin_dir ) . ( $file ) ) ) {
					$dir = ( $return_url ? CARZ_CHILD_URL : CARZ_CHILD_DIR ) . ( $skin_dir ) . carz_check_min_file( $file, CARZ_CHILD_DIR . ( $skin_dir ) );
				} elseif ( file_exists( CARZ_THEME_DIR . ( $skin_dir ) . ( $file ) ) ) {
					$dir = ( $return_url ? CARZ_THEME_URL : CARZ_THEME_DIR ) . ( $skin_dir ) . carz_check_min_file( $file, CARZ_THEME_DIR . ( $skin_dir ) );
				}
			}
		}
		return $dir;
	}
}

if ( ! function_exists( 'carz_skins_get_file_url' ) ) {
	/**
	 * Add skins folder to the theme-specific file search: check if a file exists
	 * in the skin folder and return its url or empty string if a file is not found
	 * 
	 * @param string $file  The file name to be searched
	 * @param string|bool $skin  The name of the skin. If false, the current skin is used.
	 * 
	 * @return string  The URL of the file in the skin folder, or empty string if not found
	 */
	function carz_skins_get_file_url( $file, $skin = false ) {
		return carz_skins_get_file_dir( $file, $skin, true );
	}
}

if ( ! function_exists( 'carz_skins_get_theme_file_dir' ) ) {
	add_filter( 'carz_filter_get_theme_file_dir', 'carz_skins_get_theme_file_dir', 10, 3 );
	/**
	 * Add skins folder to the theme-specific file search: check if a file exists
	 * in the skin folder and return its url or empty string if a file is not found
	 * 
	 * @hooked 'carz_filter_get_theme_file_dir'
	 * 
	 * @param string $dir  The directory to be searched
	 * @param string $file  The file name to be searched
	 * @param bool $return_url  If true, return the URL of the file, otherwise return the path
	 * 
	 * @return string  The path or URL of the file in the skin folder, or empty string if not found
	 */
	function carz_skins_get_theme_file_dir( $dir, $file, $return_url = false ) {
		return carz_skins_get_file_dir( $file, carz_skins_get_current_skin_name(), $return_url );
	}
}


if ( ! function_exists( 'carz_skins_get_folder_dir' ) ) {
	/**
	 * Check if a folder exists in the current skin folder and return its path
	 * or empty string if the folder is not found
	 * 
	 * @param string $folder  The folder name to be searched
	 * @param string|bool $skin  The name of the skin. If false, the current skin is used.
	 * @param bool $return_url  If true, return the URL of the folder, otherwise return the path
	 * 
	 * @return string  The path or URL of the folder in the skin folder, or empty string if not found
	 */
	function carz_skins_get_folder_dir( $folder, $skin = false, $return_url = false ) {
		$dir = '';
		if ( CARZ_ALLOW_SKINS ) {
			$skin_dir = carz_skins_get_current_skin_dir( $skin );
			if ( CARZ_CHILD_DIR != CARZ_THEME_DIR && is_dir( CARZ_CHILD_DIR . ( $skin_dir ) . ( $folder ) ) ) {
				$dir = ( $return_url ? CARZ_CHILD_URL : CARZ_CHILD_DIR ) . ( $skin_dir ) . ( $folder );
			} elseif ( is_dir( CARZ_THEME_DIR . ( $skin_dir ) . ( $folder ) ) ) {
				$dir = ( $return_url ? CARZ_THEME_URL : CARZ_THEME_DIR ) . ( $skin_dir ) . ( $folder );
			}
		}
		return $dir;
	}
}

if ( ! function_exists( 'carz_skins_get_folder_url' ) ) {
	/**
	 * Check if folder exists in the skin folder and return its url
	 * or empty string if folder is not found
	 * 
	 * @param string $folder  The folder name to be searched
	 * @param string|bool $skin  The name of the skin. If false, the current skin is used.
	 * 
	 * @return string  The URL of the folder in the skin folder, or empty string if not found
	 */
	function carz_skins_get_folder_url( $folder, $skin = false ) {
		return carz_skins_get_folder_dir( $folder, $skin, true );
	}
}

if ( ! function_exists( 'carz_skins_get_theme_folder_dir' ) ) {
	add_filter( 'carz_filter_get_theme_folder_dir', 'carz_skins_get_theme_folder_dir', 10, 3 );
	/**
	 * Check if folder exists in the skin folder and return its path
	 * or empty string if folder is not found
	 * 
	 * @hooked 'carz_filter_get_theme_folder_dir'
	 * 
	 * @param string $dir  The directory to be searched
	 * @param string $folder  The folder name to be searched
	 * @param bool $return_url  If true, return the URL of the folder, otherwise return the path
	 * 
	 * @return string  The path or URL of the folder in the skin folder, or empty string if not found
	 */
	function carz_skins_get_theme_folder_dir( $dir, $folder, $return_url = false ) {
		return carz_skins_get_folder_dir( $folder, carz_skins_get_current_skin_name(), $return_url );
	}
}


if ( ! function_exists( 'carz_skins_get_template_part' ) ) {
	add_filter( 'carz_filter_get_template_part', 'carz_skins_get_template_part', 10, 2 );
	/**
	 * Add skins folder to the get_template_part: check if a template part exists
	 * in the skin folder and return its path or empty string if a template part is not found
	 * 
	 * @hooked 'carz_filter_get_template_part'
	 * 
	 * @param string $slug  The slug of the template part to be searched
	 * @param string $part  The part of the template to be searched (optional)
	 * 
	 * @return string  The path of the template part in the skin folder, or empty string if not found
	 */
	function carz_skins_get_template_part( $slug, $part = '' ) {
		if ( ! empty( $part ) ) {
			$part = "-{$part}";
		}
		$slug_in_skins = str_replace( '//', '/', sprintf( 'skins/%1$s/%2$s', carz_skins_get_current_skin_name(), $slug ) );
		if ( carz_skins_get_file_dir( "{$slug}{$part}.php" ) != '' ) {
			$slug = $slug_in_skins;
		} else {
			if ( carz_get_file_dir( "{$slug}{$part}.php" ) == '' && carz_skins_get_file_dir( "{$slug}.php" ) != '' ) {
				$slug = $slug_in_skins;
			}
		}
		return $slug;
	}
}


if ( ! function_exists( 'carz_skins_gutenberg_get_styles' ) ) {
	add_filter( 'carz_filter_gutenberg_get_styles', 'carz_skins_gutenberg_get_styles' );
	/**
	 * Add skin-specific styles to the Gutenberg preview compiled CSS
	 * 
	 * @hooked 'carz_filter_gutenberg_get_styles'
	 * 
	 * @param string $css  The compiled CSS styles
	 * 
	 * @return string  The compiled CSS styles with skin-specific styles added
	 */
	function carz_skins_gutenberg_get_styles( $css ) {
		$css .= carz_fgc( carz_get_file_dir( carz_skins_get_current_skin_dir() . 'css/style.css' ) );
		return $css;
	}
}



// Add tab with skins to the 'Theme Panel'
//------------------------------------------------------

if ( ! function_exists( 'carz_skins_theme_panel_section_filters' ) ) {
	/**
	 * Return a list of categories from the skins list to be used as filters in the Theme Panel
	 * 
	 * @hooked 'carz_skins_theme_panel_section_filters'
	 * 
	 * @param array $skins  The list of skins to be filtered
	 * 
	 * @return array  The list of categories to be used as filters in the Theme Panel
	 */
	function carz_skins_theme_panel_section_filters( $skins ) {
		$list = array();
		if ( is_array( $skins ) ) {
			foreach ( $skins as $skin ) {
				if ( ! empty( $skin['category'] ) ) {
					$parts = array_map( 'strtolower', array_map( 'trim', explode( ',', $skin['category'] ) ) );
					foreach ( $parts as $cat ) {
						if ( ! in_array( $cat, $list ) ) {
							$list[] = $cat;
						}
					}
				}
			}
			if ( count( $list ) > 0 ) {
				sort( $list );
				array_unshift( $list, 'all' );
			}
		}
		return $list;
	}
}

if ( ! function_exists( 'carz_skins_theme_panel_steps' ) ) {
	add_filter( 'trx_addons_filter_theme_panel_steps', 'carz_skins_theme_panel_steps' );
	/**
	 * Add a step (tab) 'Skins' to the Theme Panel
	 * 
	 * @hooked 'trx_addons_filter_theme_panel_steps'
	 * 
	 * @param array $steps  The list of steps (tabs) in the Theme Panel
	 * 
	 * @return array  The list of steps (tabs) in the Theme Panel with the 'Skins' step added
	 */
	function carz_skins_theme_panel_steps( $steps ) {
		if ( CARZ_ALLOW_SKINS ) {
			$steps = carz_array_merge( array( 'skins' => wp_kses_data( __( 'Select a skin for your website.', 'carz' ) ) ), $steps );
		}
		return $steps;
	}
}

if ( ! function_exists( 'carz_skins_theme_panel_tabs' ) ) {
	add_filter( 'trx_addons_filter_theme_panel_tabs', 'carz_skins_theme_panel_tabs' );
	/**
	 * Add a tab link 'Skins' to the Theme Panel
	 * 
	 * @hooked 'trx_addons_filter_theme_panel_tabs'
	 * 
	 * @param array $tabs  The list of tabs in the Theme Panel
	 * 
	 * @return array  The list of tabs in the Theme Panel with the 'Skins' tab added
	 */
	function carz_skins_theme_panel_tabs( $tabs ) {
		if ( CARZ_ALLOW_SKINS ) {
			carz_array_insert_after( $tabs, 'general', array( 'skins' => esc_html__( 'Skins', 'carz' ) ) );
		}
		return $tabs;
	}
}

if ( ! function_exists( 'carz_skins_theme_panel_section' ) ) {
	add_action( 'trx_addons_action_theme_panel_section', 'carz_skins_theme_panel_section', 10, 2);
	/**
	 * Display the section 'Skins' in the Theme Panel
	 * 
	 * @hooked 'trx_addons_action_theme_panel_section'
	 * 
	 * @param string $tab_id  The ID of the tab in the Theme Panel
	 * @param array $theme_info  The information about the theme
	 */
	function carz_skins_theme_panel_section( $tab_id, $theme_info ) {

		if ( 'skins' !== $tab_id ) return;

		$theme_activated = trx_addons_is_theme_activated();
		$skins = $theme_activated ? carz_storage_get( 'skins' ) : false;

		?>
		<div id="trx_addons_theme_panel_section_<?php echo esc_attr($tab_id); ?>"
			class="trx_addons_tabs_section trx_addons_section_mode_thumbs">

			<?php
			do_action('trx_addons_action_theme_panel_section_start', $tab_id, $theme_info);

			if ( $theme_activated ) {
				?>
				<div class="trx_addons_theme_panel_section_content trx_addons_theme_panel_skins_selector">

					<?php do_action('trx_addons_action_theme_panel_before_section_title', $tab_id, $theme_info); ?>

					<h1 class="trx_addons_theme_panel_section_title">
						<?php esc_html_e( 'Choose a Skin', 'carz' ); ?>
					</h1>

					<?php do_action('trx_addons_action_theme_panel_after_section_title', $tab_id, $theme_info); ?>

					<div class="trx_addons_theme_panel_section_description">
						<p><?php echo wp_kses_data( __( 'Select the desired style of your website. Some skins may require you to install additional plugins.', 'carz' ) ); ?></p>
					</div>

					<div class="trx_addons_theme_panel_section_toolbar">
						<div class="trx_addons_theme_panel_section_filters">
							<form class="trx_addons_theme_panel_section_filters_form">
								<input class="trx_addons_theme_panel_section_filters_search" type="text" placeholder="<?php esc_attr_e( 'Search for skin', 'carz' ); ?>" value="" />
							</form>
							<?php
							$cats = carz_skins_theme_panel_section_filters( $skins );
							if ( is_array( $cats ) && count( $cats ) > 2 ) {
								?>
								<ul class="trx_addons_theme_panel_section_filters_list">
									<?php
									foreach( $cats as $k => $cat ) {
										?>
										<li class="trx_addons_theme_panel_section_filters_list_item<?php
												if ( $k == 0 ) { echo ' filter_active'; }
											?>"
											data-filter="<?php echo esc_attr( $cat ); ?>"
										>
											<a href="#" role="button"><?php echo esc_html( ucfirst( $cat ) ); ?></a>
										</li>
										<?php
									}
									?>
								</ul>
								<?php
							}
							?>
						</div>
						<?php
						// View mode buttons: thumbs | list
						if ( apply_filters( 'carz_filter_skins_view_mode', true ) ) {
							?>
							<div class="trx_addons_theme_panel_section_view_mode">
								<span class="trx_addons_theme_panel_section_view_mode_thumbs" data-mode="thumbs" title="<?php esc_attr_e( 'Large thumbnails', 'carz' ); ?>"></span>
								<span class="trx_addons_theme_panel_section_view_mode_list" data-mode="list" title="<?php esc_attr_e( 'List with details', 'carz' ); ?>"></span>
							</div>
							<?php
						}
						?>
					</div>

					<?php do_action('trx_addons_action_theme_panel_before_list_items', $tab_id, $theme_info); ?>
					
					<div class="trx_addons_theme_panel_skins_list trx_addons_image_block_wrap">
						<?php
						if ( is_array( $skins ) ) {
							// Time to show new skins at the start of the list
							$time_new = strtotime( apply_filters( 'carz_filter_time_to_show_new_skins', '-2 weeks' ) );
							// Sort skins by slug
							uksort( $skins, function( $a, $b ) use ( $skins, $time_new ) {
								$rez = apply_filters( 'carz_filter_skins_sorted', true )
										? strcmp( $a, $b )
										: -1;
								// Move an active skin to the top of the list
								if ( $a == CARZ_SKIN_NAME ) $rez = -1;
								else if ( $b == CARZ_SKIN_NAME ) $rez = 1;
								// Move the skin 'Default' to the top of the list (after the active skin)
								else if ( $a == 'default' ) $rez = -1;
								else if ( $b == 'default' ) $rez = 1;
								// Move installed skins to the top of the list (after skin 'Default')
								else if ( ! empty( $skins[ $a ]['installed'] ) && ! empty( $skins[ $b ]['installed'] ) ) $rez = strcmp( $a, $b );
								else if ( ! empty( $skins[ $a ]['installed'] ) ) $rez = -1;
								else if ( ! empty( $skins[ $b ]['installed'] ) ) $rez = 1;
								// Move new skins to the top of the list (after installed skins)
								else if ( ! empty( $skins[ $a ]['uploaded'] ) && strtotime( $skins[ $a ]['uploaded'] ) > $time_new && ! empty( $skins[ $b ]['uploaded'] ) && strtotime( $skins[ $b ]['uploaded'] ) > $time_new ) $rez = strcmp( $a, $b );
								else if ( ! empty( $skins[ $a ]['uploaded'] ) && strtotime( $skins[ $a ]['uploaded'] ) > $time_new ) $rez = -1;
								else if ( ! empty( $skins[ $b ]['uploaded'] ) && strtotime( $skins[ $b ]['uploaded'] ) > $time_new ) $rez = 1;
								// Move updated skins to the top of the list (after new skins)
								else if ( ! empty( $skins[ $a ]['updated'] ) && strtotime( $skins[ $a ]['updated'] ) > $time_new && ! empty( $skins[ $b ]['updated'] ) && strtotime( $skins[ $b ]['updated'] ) > $time_new ) $rez = strcmp( $a, $b );
								else if ( ! empty( $skins[ $a ]['updated'] ) && strtotime( $skins[ $a ]['updated'] ) > $time_new ) $rez = -1;
								else if ( ! empty( $skins[ $b ]['updated'] ) && strtotime( $skins[ $b ]['updated'] ) > $time_new ) $rez = 1;
								return $rez;
							} );
							foreach ( $skins as $skin => $data ) {
								$skin_classes = array();
								if ( CARZ_SKIN_NAME == $skin ) {
									$skin_classes[] = 'skin_active';
								}
								if ( ! empty( $data['installed'] ) ) {
									$skin_classes[] = 'skin_installed';
								} else if ( ! empty( $data['buy_url'] ) ) {
									$skin_classes[] = 'skin_buy';
								} else {
									$skin_classes[] = 'skin_free';
								}
								if ( ! empty( $data['updated'] ) && ( empty( $data['uploaded'] ) || $data['updated'] > $data['uploaded'] ) && strtotime( $data['updated'] ) > $time_new ) {
									$skin_classes[] = 'skin_updated';
								} else if ( empty( $data['installed'] ) && ! empty( $data['uploaded'] ) && strtotime( $data['uploaded'] ) > $time_new ) {
									$skin_classes[] = 'skin_new';
								}
								// 'trx_addons_image_block' is a inline-block element and spaces around it are not allowed
								?><div class="trx_addons_image_block <?php echo esc_attr( join( ' ', $skin_classes ) ); ?>"<?php
									if ( ! empty( $data['category'] ) ) {
										?> data-filter-value="<?php echo esc_attr( $data['category'] ); ?>"<?php
									}
									?> data-search-value="<?php
										if ( ! empty( $data['title'] ) ) {
											echo esc_attr( strtolower( $data['title'] ) );
										} else {
											echo esc_attr( $skin );
										}
										if ( ! empty( $data['keywords'] ) ) {
											echo ( ! empty( $data['title'] ) ? ', ' : '' ) . esc_attr( strtolower( $data['keywords'] ) );
										}
									?>"<?php
								?>>
									<div class="trx_addons_image_block_inner" tabindex="0">
										<div class="trx_addons_image_block_image
										 	<?php 
											$theme_slug  = get_template();
											// Skin image
											$img = ! empty( $data['installed'] )
													? carz_skins_get_file_url( 'skin.jpg', $skin )
													: trailingslashit( carz_storage_get( 'theme_upgrade_url' ) ) . 'skins/' . urlencode( apply_filters( 'carz_filter_original_theme_slug', $theme_slug ) ) . '/' . urlencode( $skin ) . '/skin.jpg';
											if ( ! empty( $img ) ) {
												echo carz_add_inline_css_class( 'background-image: url(' . esc_url( $img ) . ');' );
											}				 	
										 	?>">
										 	<?php
											// Link to demo site
											if ( ! empty( $data['demo_url'] ) ) {
												?>
												<a href="<?php echo esc_url( $data['demo_url'] ); ?>"
													class="trx_addons_image_block_link trx_addons_image_block_link_view_demo"
													<?php echo carz_external_links_target( true ); ?>
													title="<?php esc_attr_e( 'Live Preview', 'carz' ); ?>"
												>
													<span class="trx_addons_image_block_link_caption">
														<?php
														esc_html_e( 'Live Preview', 'carz' );
														?>
													</span>
												</a>
												<?php
											}
											// Labels
											if ( ! empty( $data['updated'] ) && ( empty( $data['uploaded'] ) || $data['updated'] > $data['uploaded'] ) && strtotime( $data['updated'] ) > $time_new ) {
												?><span class="skin_label"><?php esc_html_e( 'Updated', 'carz' ); ?></span><?php
											} else if ( ! empty( $data['installed'] ) && strtotime( $data['installed'] ) > $time_new ) {
												?><span class="skin_label"><?php esc_html_e( 'Downloaded', 'carz' ); ?></span><?php
											} else if ( ! empty( $data['uploaded'] ) && strtotime( $data['uploaded'] ) > $time_new ) {
												?><span class="skin_label"><?php esc_html_e( 'New', 'carz' ); ?></span><?php
											}
											?>
									 	</div>
									 	<div class="trx_addons_image_block_footer">
											<?php
											// Links to choose skin, update, download, purchase
											if ( ! empty( $data['installed'] ) ) {
												// Active skin
												if ( CARZ_SKIN_NAME == $skin ) {
													?>
													<span class="trx_addons_image_block_link trx_addons_image_block_link_active">
														<?php
														esc_html_e( 'Active', 'carz' );
														?>
													</span>
													<?php
												} else {
													// Button 'Delete'
													?>
													<a href="#" role="button" tabindex="0"
														class="trx_addons_image_block_link trx_addons_image_block_link_delete trx_addons_image_block_link_delete_skin trx_addons_button trx_addons_button_small trx_addons_button_fail"
														data-skin="<?php echo esc_attr( $skin ); ?>"
													>
														<span data-tooltip-text="<?php
															esc_html_e( 'Delete', 'carz' );
														?>"></span>
														<span class="trx_addons_image_block_link_caption"><?php
															esc_html_e( 'Delete', 'carz' );
														?></span>
													</a>
													<?php
													// Button 'Activate'
													?>
													<a href="#" role="button" tabindex="0"
														class="trx_addons_image_block_link trx_addons_image_block_link_activate trx_addons_image_block_link_choose_skin trx_addons_button trx_addons_button_small trx_addons_button_accent trx_addons_image_block_icon_hidden"
														data-skin="<?php echo esc_attr( $skin ); ?>">
															<?php
															esc_html_e( 'Activate', 'carz' );
															?>
													</a>
													<?php
												}
												// Button 'Update'
												if ( version_compare( $data['installed'], $data['version'], '<' ) ) {
													?>
													<a href="#" role="button"
														class="trx_addons_image_block_link trx_addons_image_block_link_update trx_addons_image_block_link_update_skin trx_addons_button trx_addons_button_small trx_addons_button_warning trx_addons_image_block_icon_hidden"
														data-skin="<?php echo esc_attr( $skin ); ?>">
															<?php
															// Translators: Add new version of the skin to the string
															echo esc_html( sprintf( __( 'Update to v.%s', 'carz' ), $data['version'] ) );
															?>
													</a>
													<?php
												}

											} else if ( ! empty( $data['buy_url'] ) ) {
												// Button 'Purchase'
												?>
												<a href="#" role="button" tabindex="0"
													class="trx_addons_image_block_link trx_addons_image_block_link_download trx_addons_image_block_link_buy_skin trx_addons_button trx_addons_button_small trx_addons_button_success trx_addons_image_block_icon_hidden"
													data-skin="<?php echo esc_attr( $skin ); ?>"
													data-buy="<?php echo esc_url( $data['buy_url'] ); ?>">
														<?php
														esc_html_e( 'Purchase', 'carz' );
														?>
												</a>
												<?php

											} else {
												// Button 'Download'
												?>
												<a href="#" role="button" tabindex="0"
													class="trx_addons_image_block_link trx_addons_image_block_link_download trx_addons_image_block_link_download_skin trx_addons_button trx_addons_button_small trx_addons_image_block_icon_hidden"
													data-skin="<?php echo esc_attr( $skin ); ?>">
														<?php
														esc_html_e( 'Download', 'carz' );
														?>
												</a>
												<?php
											}
											// Skin title
											if ( ! empty( $data['title'] ) ) {
												?>
												<h5 class="trx_addons_image_block_title">
													<?php
													echo esc_html( $data['title'] );
													?>
												</h5>
												<?php
											}
											// Skin version
											if ( ! empty( $data['installed'] ) ) {
												?>
												<div class="trx_addons_image_block_description">
													<?php
													echo esc_html( sprintf( __( 'Version %s', 'carz' ), $data['installed'] ) );
													?>
												</div>
												<?php
											}
											?>
										</div>
									</div>
								</div><?php // No spaces allowed after this <div>, because it is an inline-block element
							}
						}
						?>
					</div>

					<?php do_action('trx_addons_action_theme_panel_after_list_items', $tab_id, $theme_info); ?>

				</div>
				<?php
				do_action('trx_addons_action_theme_panel_after_section_data', $tab_id, $theme_info);
			} else {
				?>
				<div class="<?php
					if ( carz_exists_trx_addons() ) {
						echo 'trx_addons_info_box trx_addons_info_box_warning';
					} else {
						echo 'error';
					}
				?>"><p>
					<?php esc_html_e( 'Activate your theme in order to be able to change skins.', 'carz' ); ?>
				</p></div>
				<?php
			}

			do_action('trx_addons_action_theme_panel_section_end', $tab_id, $theme_info);
			?>
		</div>
		<?php
	}
}


if ( ! function_exists( 'carz_skins_about_enqueue_scripts' ) ) {
	add_action( 'admin_enqueue_scripts', 'carz_skins_about_enqueue_scripts' );
	/**
	 * Load a page-specific scripts and styles for the 'Skins' section in the Theme Panel
	 * 
	 * @hooked 'admin_enqueue_scripts'
	 */
	function carz_skins_about_enqueue_scripts() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		if ( ! empty( $screen->id ) && ( false !== strpos($screen->id, '_page_trx_addons_theme_panel') || in_array( $screen->id, array( 'update-core', 'update-core-network' ) ) ) ) {
			wp_enqueue_style( 'carz-skins-admin', carz_get_file_url( 'skins/skins-admin.css' ), array(), null );
			wp_enqueue_script( 'carz-skins-admin', carz_get_file_url( 'skins/skins-admin.js' ), array( 'jquery' ), null, true );
		}
	}
}

if ( ! function_exists( 'carz_skins_localize_script' ) ) {
	add_filter( 'carz_filter_localize_script_admin', 'carz_skins_localize_script' );
	/**
	 * Add a page-specific vars to the localize array for the 'Skins' section in the Theme Panel
	 * 
	 * @hooked 'carz_filter_localize_script_admin'
	 * 
	 * @param array $arr  The array of localized strings
	 * 
	 * @return array  The array of localized strings with the 'Skins' section strings added
	 */
	function carz_skins_localize_script( $arr ) {

		// Switch an active skin
		$arr['msg_switch_skin_caption']           = esc_html__( "Attention!", 'carz' );
		$arr['msg_switch_skin']                   = apply_filters( 'carz_filter_msg_switch_skin',
			'<p>'
			. esc_html__( "Some skins require installation of additional plugins.", 'carz' )
			. '</p><p>'
			. esc_html__( "After selecting a new skin, your theme settings will be changed.", 'carz' )
			. '</p>'
		);
		$arr['msg_switch_skin_success']           = esc_html__( 'A new skin is selected. The page will be reloaded.', 'carz' );
		$arr['msg_switch_skin_success_caption']   = esc_html__( 'Skin is changed!', 'carz' );

		// Delete a skin
		$arr['msg_delete_skin_caption']           = esc_html__( "Delete skin", 'carz' );
		$arr['msg_delete_skin']                   = apply_filters( 'carz_filter_msg_delete_skin',
			'<p>'
			. esc_html__( "Attention! This skin will be deleted from the 'skins' folder inside your theme folder.", 'carz' )
			. '</p>'
		);
		$arr['msg_delete_skin_success']           = esc_html__( 'Specified skin is deleted. The page will be reloaded.', 'carz' );
		$arr['msg_delete_skin_success_caption']   = esc_html__( 'Skin is deleted!', 'carz' );
		$arr['msg_delete_skin_error_caption']     = esc_html__( 'Skin delete error!', 'carz' );

		// Download a new skin
		$arr['msg_download_skin_caption']         = esc_html__( "Download skin", 'carz' );
		$arr['msg_download_skin']                 = apply_filters( 'carz_filter_msg_download_skin',
			'<p>'
			. esc_html__( "The new skin will be installed in the 'skins' folder inside your theme folder.", 'carz' )
			. '</p><p>'
			. esc_html__( "Attention! Do not forget to activate the new skin after installation.", 'carz' )
			. '</p>'
		);
		$arr['msg_download_skin_success']         = esc_html__( 'A new skin is installed. The page will be reloaded.', 'carz' );
		$arr['msg_download_skin_success_caption'] = esc_html__( 'Skin is installed!', 'carz' );
		$arr['msg_download_skin_error_caption']   = esc_html__( 'Skin download error!', 'carz' );

		// Buy a new skin
		$arr['msg_buy_skin_caption']              = esc_html__( "Download purchased skin", 'carz' );
		$arr['msg_buy_skin']                      = apply_filters( 'carz_filter_msg_buy_skin',
			'<p>'
			. esc_html__( "1. Follow the link below and purchase the selected skin. After payment you will receive a purchase code.", 'carz' )
			. '</p><p>'
			. '<a href="#" role="button"' . carz_external_links_target( true ) . '>' . esc_html__( "Purchase the selected skin.", 'carz' ) . '</a>'
			. '</p><p>'
			. esc_html__( "2. Enter the purchase code of the selected skin in the field below and press the button 'Apply'.", 'carz' )
			. '</p><p>'
			. esc_html__( "3. The new skin will be installed to the folder 'skins' inside your theme folder.", 'carz' )
			. '</p><p>'
			. esc_html__( "Attention! Do not forget to activate the new skin after installation.", 'carz' )
			. '</p>'
		);
		$arr['msg_buy_skin_placeholder']          = esc_html__( 'Enter the purchase code of the skin.', 'carz' );
		$arr['msg_buy_skin_success']              = esc_html__( 'A new skin is installed. The page will be reloaded.', 'carz' );
		$arr['msg_buy_skin_success_caption']      = esc_html__( 'Skin is installed!', 'carz' );
		$arr['msg_buy_skin_error_caption']        = esc_html__( 'Skin download error!', 'carz' );

		// Update an installed skin
		$arr['msg_update_skin_caption']           = esc_html__( "Update skin", 'carz' );
		$arr['msg_update_skin']                   = apply_filters( 'carz_filter_msg_update_skin',
			'<p>'
			. esc_html__( "Attention! The new version of the skin will be installed in the same folder instead the current version!", 'carz' )
			. '</p><p>'
			. esc_html__( "If you made any changes in the files from the folder of the selected skin - they will be lost.", 'carz' )
			. '</p><p>'
			. esc_html__( "If you do not have the latest version of the theme installed, be sure to update the theme before updating the skin to avoid errors.", 'carz' )
			. '</p>'
		);
		$arr['msg_update_skin_success']           = esc_html__( 'The skin is updated. The page will be reloaded.', 'carz' );
		$arr['msg_update_skin_success_caption']   = esc_html__( 'Skin is updated!', 'carz' );
		$arr['msg_update_skin_error_caption']     = esc_html__( 'Skin update error!', 'carz' );
		$arr['msg_update_skins_result']           = esc_html__( 'Selected skins are updated.', 'carz' );
		$arr['msg_update_skins_error']            = esc_html__( 'Not all selected skins have been updated.', 'carz' );

		// Hide notice about skins
		$arr['msg_hide_skins_notice_forever']         = esc_html__( "We regularly add new skins with fresh designs and features. Hide this if you're happy with your current setup – just note that you won’t be notified about new skins in the future.", 'carz' );
		$arr['msg_hide_skins_notice_forever_caption'] = esc_html__( "Are you sure?", 'carz' );
		$arr['msg_hide_skins_notice_forever_ok']      = esc_html__( "Never show again", 'carz' );
		$arr['msg_hide_skins_notice_forever_cancel']  = esc_html__( "Keep showing updates", 'carz' );

		return $arr;
	}
}


if ( ! function_exists( 'carz_skins_ajax_switch_skin' ) ) {
	add_action( 'wp_ajax_carz_switch_skin', 'carz_skins_ajax_switch_skin' );
	/**
	 * AJAX handler: switch the current skin
	 * 
	 * @hooked 'wp_ajax_carz_switch_skin'
	 */
	function carz_skins_ajax_switch_skin() {

		carz_verify_nonce();

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			carz_forbidden( esc_html__( 'Sorry, you are not allowed to switch skins.', 'carz' ) );
		}

		$response = array( 'error' => '' );

		$skin  = carz_sanitize_slug( carz_get_value_gp( 'skin' ) );
		$skins = carz_storage_get( 'skins' );

		if ( empty( $skin ) || ! isset( $skins[ $skin ] ) || empty( $skins[ $skin ]['installed'] ) ) {
			// Translators: Add the skin's name to the message
			$response['error'] = sprintf( esc_html__( 'Can not switch to the skin %s', 'carz' ), $skin );

		} elseif ( CARZ_SKIN_NAME == $skin ) {
			// Translators: Add the skin's name to the message
			$response['error'] = sprintf( esc_html__( 'Skin %s is already active', 'carz' ), $skin );

		} else {
			// Get current theme slug
			$theme_slug = get_stylesheet();
			// Get previously saved options for new skin
			$skin_mods = get_option( sprintf( 'theme_mods_%1$s_skin_%2$s', $theme_slug, $skin ), false );
			if ( ! $skin_mods ) {
				// First activation of the skin - get options from the file
				if ( file_exists( CARZ_THEME_DIR . 'skins/skins-options.php' ) ) {
					require_once CARZ_THEME_DIR . 'skins/skins-options.php';
					if ( isset( $skins_options[ $skin ]['options'] ) ) {
						$skin_mods = apply_filters(
										'carz_filter_skin_options_restore_from_file',
										carz_unserialize( $skins_options[ $skin ]['options'], true )
										);
					}
				}
			}
			if ( empty( $skin_mods ) ) {
				$response['success'] = esc_html__( 'A new skin is selected, but options of the new skin are not found!', 'carz' );
			}
			// Save current options
			update_option( sprintf( 'theme_mods_%1$s_skin_%2$s', $theme_slug, CARZ_SKIN_NAME ), apply_filters( 'carz_filter_skin_options_store', get_theme_mods() ) );
			// Replace theme mods with options from new skin
			if ( ! empty( $skin_mods ) ) {
				carz_options_update( apply_filters( 'carz_filter_skin_options_restore', $skin_mods ) );
			}
			// Replace current skin
			update_option( sprintf( 'theme_skin_%s', $theme_slug ), $skin );
			// Clear current skin from visitor's storage
			if ( CARZ_REMEMBER_SKIN ) {
				carz_set_cookie( 'skin_current', '' );
			}
			// Set a flag to recreate custom layouts
			update_option('trx_addons_cpt_layouts_created', 0);
			// Set a flag to regenerate styles and scripts on first run
			if ( apply_filters( 'carz_filter_regenerate_merged_files_after_switch_skin', true ) ) {
				carz_set_action_save_options();
			}
			// Clear a list with posts for the importer
			delete_transient( 'trx_addons_installer_posts' );
			// Trigger action
			do_action( 'carz_action_skin_switched', $skin, CARZ_SKIN_NAME );
		}

		carz_ajax_response( $response );
	}
}

if ( ! function_exists( 'carz_skins_clear_saved_shapes_list' ) ) {
	add_action( 'carz_action_skin_switched', 'carz_skins_clear_saved_shapes_list', 10, 2 );
	/**
	 * Remove a saved shapes list after switching the skin
	 * 
	 * @hooked 'carz_action_skin_switched'
	 * 
	 * @param string $skin       The name of the new skin (not used here)
	 * @param string $skin_name  The name of the previous skin (not used here)
	 */
	function carz_skins_clear_saved_shapes_list( $skin = '', $skin_name = '' ) {
		delete_transient( 'trx_addons_shapes' );
	}
}

if ( ! function_exists( 'carz_skins_options_restore_from_file' ) ) {
	add_filter( 'carz_filter_skin_options_restore_from_file', 'carz_skins_options_restore_from_file' );
	/**
	 * Remove all entries with a media from options restored from the file
	 * 
	 * @hooked 'carz_filter_skin_options_restore_from_file'
	 * 
	 * @param array $mods  The options to restore
	 * 
	 * @return array  The options to restore without media entries
	 */
	function carz_skins_options_restore_from_file( $mods ) {
		$options = carz_storage_get( 'options' );
		if ( is_array( $options ) ) {
			foreach( $options as $k => $v ) {
				if ( ! empty( $v['type'] ) && in_array( $v['type'], array( 'image', 'media', 'video', 'audio' ) ) && isset( $mods[ $k ] ) ) {
					unset( $mods[ $k ] );
				}
			}
		}
		return $mods;
	}
}


if ( ! function_exists( 'carz_skins_ajax_delete_skin' ) ) {
	add_action( 'wp_ajax_carz_delete_skin', 'carz_skins_ajax_delete_skin' );
	/**
	 * AJAX handler: delete the specified (not current) skin
	 * 
	 * @hooked 'wp_ajax_carz_delete_skin'
	 */
	function carz_skins_ajax_delete_skin() {

		carz_verify_nonce();

		$response = array( 'error' => '' );

		if ( ! current_user_can( 'update_themes' ) ) {
			$response['error'] = esc_html__( 'Sorry, you are not allowed to delete skins.', 'carz' );

		} else {
			$skin            = carz_sanitize_slug( carz_get_value_gp( 'skin' ) );
			$skins_file      = carz_get_file_dir( 'skins/skins.json' );
			$skins_installed = json_decode( carz_fgc( $skins_file ), true );

			$dest = CARZ_THEME_DIR . 'skins'; // Used instead carz_get_folder_dir( 'skins' ) to prevent install skin to the child-theme

			if ( empty( $skin ) || ! isset( $skins_installed[ $skin ] ) ) {
				// Translators: Add the skin's name to the message
				$response['error'] = sprintf( esc_html__( 'Can not delete the skin "%s"', 'carz' ), $skin );

			} else if ( empty( $skins_installed[ $skin ] ) ) {
				// Translators: Add the skin's name to the message
				$response['error'] = sprintf( esc_html__( 'Skin "%s" is not installed', 'carz' ), $skin );

			} else if ( carz_skins_get_current_skin_name() == $skin ) {
				// Translators: Add the skin's name to the message
				$response['error'] = sprintf( esc_html__( 'Can not delete the active skin "%s"', 'carz' ), $skin );

			} else if ( ! is_dir( "{$dest}/{$skin}" ) ) {
				// Translators: Add the skin's name to the message
				$response['error'] = sprintf( esc_html__( 'A skin folder "%s" is not exists', 'carz' ), $skin );

			} else {
				// Delete a skin folder
				carz_unlink( "{$dest}/{$skin}" );
				// Remove a skin from json
				unset( $skins_installed[ $skin ] );
				carz_fpc( $skins_file, json_encode( $skins_installed, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT ) );
				// Remove a stored list to reload it while next site visit occurs
				delete_transient( 'carz_list_skins' );

			}
		}

		carz_ajax_response( $response );
	}
}


if ( ! function_exists( 'carz_skins_ajax_download_skin' ) ) {
	add_action( 'wp_ajax_carz_download_skin', 'carz_skins_ajax_download_skin' );
	add_action( 'wp_ajax_carz_buy_skin', 'carz_skins_ajax_download_skin' );
	add_action( 'wp_ajax_carz_update_skin', 'carz_skins_ajax_download_skin' );
	/**
	 * AJAX handler: download a new skin or update the installed skin
	 * 
	 * @hooked 'wp_ajax_carz_download_skin'
	 * @hooked 'wp_ajax_carz_buy_skin'
	 * @hooked 'wp_ajax_carz_update_skin'
	 */
	function carz_skins_ajax_download_skin() {

		carz_verify_nonce();

		$response = array( 'error' => '' );

		if ( ! current_user_can( 'update_themes' ) ) {
			$response['error'] = esc_html__( 'Sorry, you are not allowed to download/update skins.', 'carz' );

		} else {
			$action = current_action() == 'wp_ajax_carz_download_skin'
							? 'download'
							: ( current_action() == 'wp_ajax_carz_buy_skin'
								? 'buy'
								: 'update' );

			$key    = carz_get_theme_activation_code();

			$skin   = carz_sanitize_slug( carz_get_value_gp( 'skin' ) );
			$code   = 'update' == $action
							? get_option( sprintf( 'purchase_code_%s_%s', get_template(), $skin ), '' )
							: carz_get_value_gp( 'code' );

			$skins  = carz_storage_get( 'skins' );

			if ( empty( $key ) ) {
				$response['error'] = esc_html__( 'Theme is not activated!', 'carz' );

			} else if ( empty( $skin ) || ! isset( $skins[ $skin ] ) ) {
				// Translators: Add the skin's name to the message
				$response['error'] = sprintf( esc_html__( 'Can not download the skin %s', 'carz' ), $skin );

			} else if ( ! empty( $skins[ $skin ]['installed'] ) && 'update' != $action ) {
				// Translators: Add the skin's name to the message
				$response['error'] = sprintf( esc_html__( 'Skin %s is already installed', 'carz' ), $skin );

			} else {
				$result = carz_get_upgrade_data( array(
					'action'   => 'download_skin',
					'key'      => $key,
					'skin'     => $skin,
					'skin_key' => $code,
				) );
				if ( substr( $result['data'], 0, 2 ) == 'PK' ) {
					carz_allow_upload_archives();
					$tmp_name = 'tmp-' . rand() . '.zip';
					$tmp      = wp_upload_bits( $tmp_name, null, $result['data'] );
					carz_disallow_upload_archives();
					if ( $tmp['error'] ) {
						$response['error'] = esc_html__( 'Problem with save upgrade file to the folder with uploads', 'carz' );
					} else {
						$response['error'] .= carz_skins_install_skin( $skin, $tmp['file'], $result['info'], $action );
						// Store purchase code to update skins in the future
						if ( ! empty( $code ) && empty( $response['error'] ) ) {
							update_option( sprintf( 'purchase_code_%s_%s', get_template(), $skin ), $code );
						}
					}
				} else {
					$response['error'] = ! empty( $result['error'] )
											? $result['error']
											: esc_html__( 'Package with skin is corrupt', 'carz' );
				}
			}
		}

		carz_ajax_response( $response );
	}
}


if ( ! function_exists( 'carz_skins_install_skin' ) ) {
	/**
	 * Unpack and install the skin
	 * 
	 * @param string $skin  The name of the skin to install
	 * @param string $file  The path to the skin package file
	 * @param array  $info  The skin info array
	 * @param string $action  The action type: 'download', 'buy', or 'update'
	 * 
	 * @return string  An error message if the installation failed, or an empty string if successful
	 */
	function carz_skins_install_skin( $skin, $file, $info, $action ) {
		if ( file_exists( $file ) ) {
			ob_start();
			// Unpack skin
			$dest = CARZ_THEME_DIR . 'skins'; // Used instead carz_get_folder_dir( 'skins' ) to prevent install skin to the child-theme
			if ( ! empty( $dest ) ) {
				carz_unzip_file( $file, $dest );
			}
			// Remove uploaded archive
			unlink( $file );
			$log = ob_get_contents();
			ob_end_clean();
			// Save skin options (if an action is not 'update')
			if ( 'update' != $action && ! empty( $info['skin_options'] ) ) {
				if ( is_string( $info['skin_options'] ) && is_serialized( $info['skin_options'] ) ) {
					$info['skin_options'] = carz_unserialize( stripslashes( $info['skin_options'] ), true );
				}
				if ( is_array( $info['skin_options'] ) ) {
					$theme_slug  = get_stylesheet();
					update_option( sprintf( 'theme_mods_%1$s_skin_%2$s', $theme_slug, $skin ), $info['skin_options'] );
				}
			}
			// Update skins list
			$skins_file      = carz_get_file_dir( 'skins/skins.json' );
			$skins_installed = json_decode( carz_fgc( $skins_file ), true );
			$skins_available = carz_storage_get( 'skins' );
			if ( isset( $skins_available[ $skin ][ 'installed' ] ) ) {
				unset( $skins_available[ $skin ][ 'installed' ] );
			}
			$skins_installed[ $skin ] = $skins_available[ $skin ];
			carz_fpc( $skins_file, json_encode( $skins_installed, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT ) );
			// Remove a stored list to reload it while next site visit occurs
			delete_transient( 'carz_list_skins' );
			// Set a flag to regenerate styles and scripts on first run if a current skin is updated
			if ( 'update' == $action
				&& carz_skins_get_current_skin_name() == $skin
				&& apply_filters( 'carz_filter_regenerate_merged_files_after_switch_skin', true )
			) {
				carz_set_action_save_options();
			}
			// Trigger action
			do_action( 'carz_action_skin_updated', $skin );
		} else {
			return esc_html__( 'Uploaded file with skin package is not available', 'carz' );
		}
	}
}



//-------------------------------------------------------
//-- Update skins via WordPress update screen
//-------------------------------------------------------

if ( ! function_exists( 'carz_skins_update_list' ) ) {
	add_action('core_upgrade_preamble', 'carz_skins_update_list');
	/**
	 * Add new skins versions to the WordPress update screen
	 * 
	 * @hooked 'core_upgrade_preamble'
	 */
	function carz_skins_update_list() {
		if ( current_user_can( 'update_themes' ) && carz_is_theme_activated() ) {
			$skins  = carz_storage_get( 'skins' );
			$update = 0;
			foreach ( $skins as $skin => $data ) {
				if ( ! empty( $data['installed'] ) && version_compare( $data['installed'], $data['version'], '<' ) ) {
					$update++;
				}
			}
			?>
			<h2>
				<?php esc_html_e( 'Active theme components: Skins', 'carz' ); ?>
			</h2>
			<?php
			if ( $update == 0 ) {
				?>
				<p><?php esc_html_e( 'Skins of the current theme are all up to date.', 'carz' ); ?></p>
				<?php
				return;
			}
			?>
			<p>
				<?php esc_html_e( 'The following skins have new versions available. Check the ones you want to update and then click &#8220;Update Skins&#8221;.', 'carz' ); ?>
			</p>
			<p>
				<?php echo wp_kses_data( __( '<strong>Please Note:</strong> Any customizations you have made to skin files will be lost.', 'carz' ) ); ?>
			</p>
			<div class="upgrade carz_upgrade_skins">
				<p><input id="upgrade-skins" class="button carz_upgrade_skins_button" type="button" value="<?php esc_attr_e( 'Update Skins', 'carz' ); ?>" /></p>
				<table class="widefat updates-table" id="update-skins-table">
					<thead>
					<tr>
						<td class="manage-column check-column"><input type="checkbox" id="skins-select-all" /></td>
						<td class="manage-column"><label for="skins-select-all"><?php esc_html_e( 'Select All', 'carz' ); ?></label></td>
					</tr>
					</thead>
					<tbody class="plugins">
						<?php
						foreach ( $skins as $skin => $data ) {
							if ( empty( $data['installed'] ) || ! version_compare( $data['installed'], $data['version'], '<' ) ) {
								continue;
							}
							$checkbox_id = 'checkbox_' . md5( $skin );
							?>
							<tr>
								<td class="check-column">
									<input type="checkbox" name="checked[]" id="<?php echo esc_attr( $checkbox_id ); ?>" value="<?php echo esc_attr( $skin ); ?>" />
									<label for="<?php echo esc_attr( $checkbox_id ); ?>" class="screen-reader-text">
										<?php
										// Translators: %s: Skin name
										printf( esc_html__( 'Select %s', 'carz' ), $data['title'] );
										?>
									</label>
								</td>
								<td class="plugin-title"><p>
									<img src="<?php echo esc_url( carz_skins_get_file_url( 'skin.jpg', $skin ) ); ?>" width="85" class="updates-table-screenshot" alt="<?php echo esc_attr( $data['title'] ); ?>" />
									<strong><?php echo esc_html( $data['title'] ); ?></strong>
									<?php
									// Translators: 1: skin version, 2: new version
									printf(
										esc_html__( 'You have version %1$s installed. Update to %2$s.', 'carz' ),
										$data['installed'],
										$data['version']
									);
									?>
								</p></td>
							</tr>
							<?php
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<td class="manage-column check-column"><input type="checkbox" id="skins-select-all-2" /></td>
							<td class="manage-column"><label for="skins-select-all-2"><?php esc_html_e( 'Select All', 'carz' ); ?></label></td>
						</tr>
					</tfoot>
				</table>
				<p><input id="upgrade-skins-2" class="button carz_upgrade_skins_button" type="button" value="<?php esc_attr_e( 'Update Skins', 'carz' ); ?>" /></p>
			</div>
			<?php
		}
	}
}


if ( ! function_exists( 'carz_skins_update_counts' ) ) {
	add_filter('wp_get_update_data', 'carz_skins_update_counts', 10, 2);
	/**
	 * Add new skins count to the WordPress updates count
	 * 
	 * @hooked 'wp_get_update_data'
	 * 
	 * @param array $update_data  The array of update data
	 * @param array $titles       The array of titles for the updates
	 * 
	 * @return array  The updated array of update data with skins count added
	 */
	function carz_skins_update_counts($update_data, $titles) {
		if ( current_user_can( 'update_themes' ) ) {
			$skins  = carz_storage_get( 'skins' );
			$update = 0;
			foreach ( $skins as $skin => $data ) {
				if ( ! empty( $data['installed'] ) && version_compare( $data['installed'], $data['version'], '<' ) ) {
					$update++;
				}
			}
			if ( $update > 0 ) {
				$update_data[ 'counts' ][ 'skins' ]  = $update;
				$update_data[ 'counts' ][ 'total' ] += $update;
				// Translators: %d: number of updates available to installed skins
				$titles['skins']                     = sprintf( _n( '%d Skin Update', '%d Skin Updates', $update, 'carz' ), $update );
				$update_data[ 'title' ]              = esc_attr( implode( ', ', $titles ) );
			}
		}
		return $update_data;
	}
}


// One-click import support
//------------------------------------------------------------------------

if ( ! function_exists( 'carz_skins_importer_export' ) ) {
	if ( false && is_admin() ) {
		add_action( 'trx_addons_action_importer_export', 'carz_skins_importer_export', 10, 1 );
	}
	/**
	 * Export a skins options to the file for the one-click importer. Not used now, because the current skin options are only exported
	 * 
	 * @hooked 'trx_addons_action_importer_export'
	 * 
	 * @param object $importer  The importer object
	 */	
	function carz_skins_importer_export( $importer ) {
		$skins  = carz_storage_get( 'skins' );
		$output = '';
		if ( is_array( $skins ) && count( $skins ) > 0 ) {
			$output     = '<?php'
						. "\n//" . esc_html__( 'Skins', 'carz' )
						. "\n\$skins_options = array(";
			$counter    = 0;
			$theme_mods = get_theme_mods();
			$theme_slug = get_stylesheet();
			foreach ( $skins as $skin => $skin_data ) {
				$options = $skin != carz_skins_get_current_skin_name()
								? get_option( sprintf( 'theme_mods_%1$s_skin_%2$s', $theme_slug, $skin ), false )
								: false;
				if ( false === $options ) {
					$options = $theme_mods;
				}
				$output .= ( $counter++ ? ',' : '' )
						. "\n\t'{$skin}' => array("
						. "\n\t\t'options' => " . "'" . str_replace( array( "\r", "\n" ), array( '\r', '\n' ), serialize( apply_filters( 'carz_filter_export_skin_options', $options, $skin ) ) ) . "'"
						. "\n\t)";
			}
			$output .= "\n);"
					. "\n?>";
		}
		carz_fpc( $importer->export_file_dir( 'skins.txt' ), $output );
	}
}

if ( ! function_exists( 'carz_skins_importer_export_fields' ) ) {
	if ( is_admin() ) {
		add_action( 'trx_addons_action_importer_export_fields', 'carz_skins_importer_export_fields', 12, 1 );
	}
	/**
	 * Display exported skin options in the fields list
	 * 
	 * @hooked 'trx_addons_action_importer_export_fields'
	 * 
	 * @param object $importer  The importer object
	 */
	function carz_skins_importer_export_fields( $importer ) {
		$importer->show_exporter_fields(
			array(
				'slug'     => 'skins',
				'title'    => esc_html__( 'Skins', 'carz' ),
				'download' => 'skins-options.php',
			)
		);
	}
}

if ( ! function_exists( 'carz_skins_importer_set_archive_name' ) ) {
	add_action( 'after_setup_theme', 'carz_skins_importer_set_archive_name', 1 );
	/**
	 * Set a name for the archive with demo data for the one-click importer
	 * with the current skin name
	 * 
	 * @hooked 'after_setup_theme'
	 */
	function carz_skins_importer_set_archive_name() {
		$GLOBALS['CARZ_STORAGE']['theme_demofiles_archive_name'] = sprintf( 'demo/%s.zip', carz_skins_get_active_skin_name() );
	}
}


// Load file with current skin
//----------------------------------------------------------
$carz_skin_file = carz_skins_get_file_dir( 'skin.php' );
if ( '' != $carz_skin_file ) {
	require_once $carz_skin_file;
}
