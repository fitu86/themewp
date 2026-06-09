<?php
/**
 * Required plugins
 *
 * @package CARZ
 * @since CARZ 1.76.0
 */

// THEME-SUPPORTED PLUGINS
// If plugin not need - remove its settings from next array
//----------------------------------------------------------
if ( ! function_exists( 'carz_skin_required_plugins' ) ) {
	add_action( 'after_setup_theme', 'carz_skin_required_plugins', -1 );
	/**
	 * Create the list of required plugins for the skin/theme.
	 * Priority -1 is used to create the list of plugins before the rest skin/theme actions.
	 * 
	 * @hooked 'after_setup_theme', -1
	 */
	function carz_skin_required_plugins() {
		$carz_theme_required_plugins_groups = array(
		'core'          => esc_html__( 'Core', 'carz' ),
		'page_builders' => esc_html__( 'Page Builders', 'carz' ),
		'ecommerce'     => esc_html__( 'E-Commerce & Donations', 'carz' ),
		'socials'       => esc_html__( 'Socials and Communities', 'carz' ),
		'events'        => esc_html__( 'Events and Appointments', 'carz' ),
		'content'       => esc_html__( 'Content', 'carz' ),
		'other'         => esc_html__( 'Other', 'carz' ),
		);
		$carz_theme_required_plugins = array(
			'trx_addons'                 => array(
				'title'       => esc_html__( 'ThemeREX Addons', 'carz' ),
				'description' => esc_html__( "Will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options", 'carz' ),
				'required'    => true,
				'logo'        => 'trx_addons.png',
				'group'       => $carz_theme_required_plugins_groups['core'],
			),
			'elementor'                  => array(
				'title'       => esc_html__( 'Elementor', 'carz' ),
				'description' => esc_html__( "Is a beautiful PageBuilder, even the free version of which allows you to create great pages using a variety of modules.", 'carz' ),
				'required'    => false,
				'logo'        => 'elementor.png',
				'group'       => $carz_theme_required_plugins_groups['page_builders'],
			),
			'gutenberg'                  => array(
				'title'       => esc_html__( 'Gutenberg', 'carz' ),
				'description' => esc_html__( "It's a posts editor coming in place of the classic TinyMCE. Can be installed and used in parallel with Elementor", 'carz' ),
				'required'    => false,
				'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
				'logo'        => 'gutenberg.png',
				'group'       => $carz_theme_required_plugins_groups['page_builders'],
			),
			'js_composer'                => array(
				'title'       => esc_html__( 'WPBakery PageBuilder', 'carz' ),
				'description' => esc_html__( "Popular PageBuilder which allows you to create excellent pages", 'carz' ),
				'required'    => false,
				'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
				'logo'        => 'js_composer.jpg',
				'group'       => $carz_theme_required_plugins_groups['page_builders'],
			),
			'woocommerce'                => array(
				'title'       => esc_html__( 'WooCommerce', 'carz' ),
				'description' => esc_html__( "Connect the store to your website and start selling now", 'carz' ),
				'required'    => false,
				'logo'        => 'woocommerce.png',
				'group'       => $carz_theme_required_plugins_groups['ecommerce'],
			),
			'elegro-payment'             => array(
				'title'       => esc_html__( 'Elegro Crypto Payment', 'carz' ),
				'description' => esc_html__( "Extends WooCommerce Payment Gateways with an elegro Crypto Payment", 'carz' ),
				'required'    => false,
				'install'     => false, // TRX_addons has marked the "Elegro Crypto Payment" plugin as obsolete and no longer recommends it for installation, even if it had been previously recommended by the theme
				'logo'        => 'elegro-payment.png',
				'group'       => $carz_theme_required_plugins_groups['ecommerce'],
			),
			'instagram-feed'             => array(
				'title'       => esc_html__( 'Instagram Feed', 'carz' ),
				'description' => esc_html__( "Displays the latest photos from your profile on Instagram", 'carz' ),
				'required'    => false,
				'logo'        => 'instagram-feed.png',
				'group'       => $carz_theme_required_plugins_groups['socials'],
			),
			'mailchimp-for-wp'           => array(
				'title'       => esc_html__( 'MailChimp for WP', 'carz' ),
				'description' => esc_html__( "Allows visitors to subscribe to newsletters", 'carz' ),
				'required'    => false,
				'logo'        => 'mailchimp-for-wp.png',
				'group'       => $carz_theme_required_plugins_groups['socials'],
			),
			'booked'                     => array(
				'title'       => esc_html__( 'Booked Appointments', 'carz' ),
				'description' => '',
				'required'    => false,
				'install'     => false,
				'logo'        => 'booked.png',
				'group'       => $carz_theme_required_plugins_groups['events'],
			),
			'quickcal'                     => array(
				'title'       => esc_html__( 'QuickCal', 'carz' ),
				'description' => '',
				'required'    => false,
				'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
				'logo'        => 'quickcal.png',
				'group'       => $carz_theme_required_plugins_groups['events'],
			),
			'the-events-calendar'        => array(
				'title'       => esc_html__( 'The Events Calendar', 'carz' ),
				'description' => '',
				'required'    => false,
				'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
				'logo'        => 'the-events-calendar.png',
				'group'       => $carz_theme_required_plugins_groups['events'],
			),
			'contact-form-7'             => array(
				'title'       => esc_html__( 'Contact Form 7', 'carz' ),
				'description' => esc_html__( "CF7 allows you to create an unlimited number of contact forms", 'carz' ),
				'required'    => false,
				'logo'        => 'contact-form-7.png',
				'group'       => $carz_theme_required_plugins_groups['content'],
			),

			'latepoint'                  => array(
				'title'       => esc_html__( 'LatePoint', 'carz' ),
				'description' => '',
				'required'    => false,
				'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
				'logo'        => carz_get_file_url( 'plugins/latepoint/latepoint.png' ),
				'group'       => $carz_theme_required_plugins_groups['events'],
			),
			'advanced-popups'                  => array(
				'title'       => esc_html__( 'Advanced Popups', 'carz' ),
				'description' => '',
				'required'    => false,
				'logo'        => carz_get_file_url( 'plugins/advanced-popups/advanced-popups.jpg' ),
				'group'       => $carz_theme_required_plugins_groups['content'],
			),
			'devvn-image-hotspot'                  => array(
				'title'       => esc_html__( 'Image Hotspot by DevVN', 'carz' ),
				'description' => '',
				'required'    => false,
				'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
				'logo'        => carz_get_file_url( 'plugins/devvn-image-hotspot/devvn-image-hotspot.png' ),
				'group'       => $carz_theme_required_plugins_groups['content'],
			),
			'ti-woocommerce-wishlist'                  => array(
				'title'       => esc_html__( 'TI WooCommerce Wishlist', 'carz' ),
				'description' => '',
				'required'    => false,
				'logo'        => carz_get_file_url( 'plugins/ti-woocommerce-wishlist/ti-woocommerce-wishlist.png' ),
				'group'       => $carz_theme_required_plugins_groups['ecommerce'],
			),
			'woo-smart-quick-view'                  => array(
				'title'       => esc_html__( 'WPC Smart Quick View for WooCommerce', 'carz' ),
				'description' => '',
				'required'    => false,
				'logo'        => carz_get_file_url( 'plugins/woo-smart-quick-view/woo-smart-quick-view.png' ),
				'group'       => $carz_theme_required_plugins_groups['ecommerce'],
			),
			'twenty20'                  => array(
				'title'       => esc_html__( 'Twenty20 Image Before-After', 'carz' ),
				'description' => '',
				'required'    => false,
				'install'     => false,
				'logo'        => carz_get_file_url( 'plugins/twenty20/twenty20.png' ),
				'group'       => $carz_theme_required_plugins_groups['content'],
			),
			'essential-grid'             => array(
				'title'       => esc_html__( 'Essential Grid', 'carz' ),
				'description' => '',
				'required'    => false,
				'install'     => false,
				'logo'        => 'essential-grid.png',
				'group'       => $carz_theme_required_plugins_groups['content'],
			),
			'revslider'                  => array(
				'title'       => esc_html__( 'Revolution Slider', 'carz' ),
				'description' => '',
				'required'    => false,
				'logo'        => 'revslider.png',
				'group'       => $carz_theme_required_plugins_groups['content'],
			),
			'sitepress-multilingual-cms' => array(
				'title'       => esc_html__( 'WPML - Sitepress Multilingual CMS', 'carz' ),
				'description' => esc_html__( "Allows you to make your website multilingual", 'carz' ),
				'required'    => false,
				'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
				'logo'        => 'sitepress-multilingual-cms.png',
				'group'       => $carz_theme_required_plugins_groups['content'],
			),
			'wp-gdpr-compliance'         => array(
				'title'       => esc_html__( 'Cookie Information', 'carz' ),
				'description' => esc_html__( "Allow visitors to decide for themselves what personal data they want to store on your site", 'carz' ),
				'required'    => false,
				'install'     => false,
				'logo'        => 'wp-gdpr-compliance.png',
				'group'       => $carz_theme_required_plugins_groups['other'],
			),
			'gdpr-framework'         => array(
				'title'       => esc_html__( 'The GDPR Framework', 'carz' ),
				'description' => esc_html__( "Tools to help make your website GDPR-compliant. Fully documented, extendable and developer-friendly.", 'carz' ),
				'required'    => false,
				'install'     => false,
				'logo'        => 'gdpr-framework.png',
				'group'       => $carz_theme_required_plugins_groups['other'],
			),
			'trx_updater'                => array(
				'title'       => esc_html__( 'ThemeREX Updater', 'carz' ),
				'description' => esc_html__( "Update theme and theme-specific plugins from developer's upgrade server.", 'carz' ),
				'required'    => false,
				'logo'        => 'trx_updater.png',
				'group'       => $carz_theme_required_plugins_groups['other'],
			),
		);

		if ( CARZ_THEME_FREE ) {
			unset( $carz_theme_required_plugins['js_composer'] );
			unset( $carz_theme_required_plugins['booked'] );
			unset( $carz_theme_required_plugins['quickcal'] );
			unset( $carz_theme_required_plugins['the-events-calendar'] );
			unset( $carz_theme_required_plugins['calculated-fields-form'] );
			unset( $carz_theme_required_plugins['essential-grid'] );
			unset( $carz_theme_required_plugins['revslider'] );
			unset( $carz_theme_required_plugins['sitepress-multilingual-cms'] );
			unset( $carz_theme_required_plugins['trx_updater'] );
			unset( $carz_theme_required_plugins['trx_popup'] );
		}

		// Add plugins list to the global storage
		carz_storage_set( 'required_plugins', $carz_theme_required_plugins );
	}
}
