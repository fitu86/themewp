<?php
/**
 * Skin Setup
 *
 * @package CARZ
 * @since CARZ 1.76.0
 */


//--------------------------------------------
// SKIN DEFAULTS
//--------------------------------------------

// Return theme's (skin's) default value for the specified parameter
if ( ! function_exists( 'carz_theme_defaults' ) ) {
	function carz_theme_defaults( $name='', $value='' ) {
		$defaults = array(
			'page_width'          => 1290,
			'page_boxed_extra'  => 60,
			'page_fullwide_max' => 1920,
			'page_fullwide_extra' => 60,
			'sidebar_width'       => 410,
			'sidebar_gap'       => 40,
			'grid_gap'          => 30,
			'rad'               => 0
		);
		if ( empty( $name ) ) {
			return $defaults;
		} else {
			if ( $value === '' && isset( $defaults[ $name ] ) ) {
				$value = $defaults[ $name ];
			}
			return $value;
		}
	}
}


// WOOCOMMERCE SETUP
//--------------------------------------------------

// Allow extended layouts for WooCommerce
if ( ! function_exists( 'carz_skin_woocommerce_allow_extensions' ) ) {
	add_filter( 'carz_filter_load_woocommerce_extensions', 'carz_skin_woocommerce_allow_extensions' );
	function carz_skin_woocommerce_allow_extensions( $allow ) {
		return true;
	}
}


// Theme init priorities:
// Action 'after_setup_theme'
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options. Attention! After this step you can use only basic options (not overriden)
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
// Action 'wp_loaded'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)


//--------------------------------------------
// SKIN SETTINGS
//--------------------------------------------
if ( ! function_exists( 'carz_skin_setup' ) ) {
	add_action( 'after_setup_theme', 'carz_skin_setup', 1 );
	function carz_skin_setup() {

		$GLOBALS['CARZ_STORAGE'] = array_merge( $GLOBALS['CARZ_STORAGE'], array(

			// Key validator: market[env|loc]-vendor[axiom|ancora|themerex]
			'theme_pro_key'       => 'env-ancora',

			'theme_doc_url'       => '//doc.themerex.net/carz/',

			'theme_demofiles_url' => '//demofiles.ancorathemes.com/carz/',
			
			'theme_rate_url'      => '//themeforest.net/downloads',

			'theme_custom_url'    => '//themerex.net/offers/?utm_source=offers&utm_medium=click&utm_campaign=themeinstall',

			'theme_support_url'   => '//themerex.net/support/',

			'theme_download_url'  => '//themeforest.net/user/ancorathemes/portfolio',        // Ancora

			'theme_video_url'     => '//www.youtube.com/channel/UCdIjRh7-lPVHqTTKpaf8PLA',   // Ancora

			'theme_privacy_url'   => '//ancorathemes.com/privacy-policy/',                   // Ancora

			'portfolio_url'       => '//themeforest.net/user/ancorathemes/portfolio',        // Ancora

			// Comma separated slugs of theme-specific categories (for get relevant news in the dashboard widget)
			// (i.e. 'children,kindergarten')
			'theme_categories'    => '',
		) );
	}
}


// Add/remove/change Theme Settings
if ( ! function_exists( 'carz_skin_setup_settings' ) ) {
	add_action( 'after_setup_theme', 'carz_skin_setup_settings', 1 );
	function carz_skin_setup_settings() {
		// Example: enable (true) / disable (false) thumbs in the prev/next navigation
		carz_storage_set_array( 'settings', 'thumbs_in_navigation', false );
	}
}

//--------------------------------------------
// SKIN FONTS
//--------------------------------------------
if ( ! function_exists( 'carz_skin_setup_fonts' ) ) {
	add_action( 'after_setup_theme', 'carz_skin_setup_fonts', 1 );
	function carz_skin_setup_fonts() {
		// Fonts to load when theme start
		// It can be:
		// - Google fonts (specify name, family and styles)
		// - Adobe fonts (specify name, family and link URL)
		// - uploaded fonts (specify name, family), placed in the folder css/font-face/font-name inside the skin folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		// example: font name 'TeX Gyre Termes', folder 'TeX-Gyre-Termes'
		carz_storage_set(
			'load_fonts', array(
				// Google font
				array(
					'name'   => 'Manrope',
					'family' => 'sans-serif',
					'link'   => '',
					'styles' => 'wght@200..800',     // Parameter 'style' used only for the Google fonts
				),
			)
		);

		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		carz_storage_set( 'load_fonts_subset', 'latin,latin-ext' );

		// Settings of the main tags.
		// Default value of 'font-family' may be specified as reference to the array $load_fonts (see above)
		// or as comma-separated string.
		// In the second case (if 'font-family' is specified manually as comma-separated string):
		//    1) Font name with spaces in the parameter 'font-family' will be enclosed in the quotes and no spaces after comma!
		//    2) If font-family inherit a value from the 'Main text' - specify 'inherit' as a value
		// example:
		// Correct:   'font-family' => carz_get_load_fonts_family_string( $load_fonts[0] )
		// Correct:   'font-family' => 'Roboto,sans-serif'
		// Correct:   'font-family' => '"PT Serif",sans-serif'
		// Incorrect: 'font-family' => 'Roboto, sans-serif'
		// Incorrect: 'font-family' => 'PT Serif,sans-serif'

		$font_description = esc_html__( 'Font settings for the %s of the site. To ensure that the elements scale properly on mobile devices, please use only the following units: "rem", "em" or "ex"', 'carz' );

		carz_storage_set(
			'theme_fonts', array(
				'p'       => array(
					'title'           => esc_html__( 'Main text', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'main text', 'carz' ) ),
					'font-family'     => 'Manrope,sans-serif',
					'font-size'       => '1rem',
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.7em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '0em',
					'margin-bottom'   => '1.65em',
				),
				'post'    => array(
					'title'           => esc_html__( 'Article text', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'article text', 'carz' ) ),
					'font-family'     => '',			// Example: '"PR Serif",serif',
					'font-size'       => '',			// Example: '1.286rem',
					'font-weight'     => '',			// Example: '400',
					'font-style'      => '',			// Example: 'normal',
					'line-height'     => '',			// Example: '1.75em',
					'text-decoration' => '',			// Example: 'none',
					'text-transform'  => '',			// Example: 'none',
					'letter-spacing'  => '',			// Example: '',
					'margin-top'      => '',			// Example: '0em',
					'margin-bottom'   => '',			// Example: '1.4em',
				),
				'h1'      => array(
					'title'           => esc_html__( 'Heading 1', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H1', 'carz' ) ),
					'font-family'     => 'Manrope,sans-serif',
					'font-size'       => '3.353em',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.04em',
					'margin-bottom'   => '0.43em',
				),
				'h2'      => array(
					'title'           => esc_html__( 'Heading 2', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H2', 'carz' ) ),
					'font-family'     => 'Manrope,sans-serif',
					'font-size'       => '2.765em',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.021em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '0.67em',
					'margin-bottom'   => '0.48em',
				),
				'h3'      => array(
					'title'           => esc_html__( 'Heading 3', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H3', 'carz' ) ),
					'font-family'     => 'Manrope,sans-serif',
					'font-size'       => '2.059em',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.086em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.11em',
					'margin-bottom'   => '0.64em',
				),
				'h4'      => array(
					'title'           => esc_html__( 'Heading 4', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H4', 'carz' ) ),
					'font-family'     => 'Manrope,sans-serif',
					'font-size'       => '1.647em',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.214em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.35em',
					'margin-bottom'   => '0.63em',
				),
				'h5'      => array(
					'title'           => esc_html__( 'Heading 5', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H5', 'carz' ) ),
					'font-family'     => 'Manrope,sans-serif',
					'font-size'       => '1.412em',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.417em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.33em',
					'margin-bottom'   => '0.74em',
				),
				'h6'      => array(
					'title'           => esc_html__( 'Heading 6', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H6', 'carz' ) ),
					'font-family'     => 'Manrope,sans-serif',
					'font-size'       => '1.118em',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.474em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.75em',
					'margin-bottom'   => '0.85em',
				),
				'logo'    => array(
					'title'           => esc_html__( 'Logo text', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'text of the logo', 'carz' ) ),
					'font-family'     => 'Manrope,sans-serif',
					'font-size'       => '1.647em',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.214em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'button'  => array(
					'title'           => esc_html__( 'Buttons', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'buttons', 'carz' ) ),
					'font-family'     => 'Manrope,sans-serif',
					'font-size'       => '15px',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '21px',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'input'   => array(
					'title'           => esc_html__( 'Input fields', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'input fields, dropdowns and textareas', 'carz' ) ),
					'font-family'     => 'inherit',
					'font-size'       => '16px',
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',     // Attention! Firefox don't allow line-height less then 1.5em in the select
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'info'    => array(
					'title'           => esc_html__( 'Post meta', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'post meta (author, categories, publish date, counters, share, etc.)', 'carz' ) ),
					'font-family'     => 'inherit',
					'font-size'       => '14px',  // Old value '13px' don't allow using 'font zoom' in the custom blog items
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '0.4em',
					'margin-bottom'   => '',
				),
				'menu'    => array(
					'title'           => esc_html__( 'Main menu', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'main menu items', 'carz' ) ),
					'font-family'     => 'Manrope,sans-serif',
					'font-size'       => '16px',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'submenu' => array(
					'title'           => esc_html__( 'Dropdown menu', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'dropdown menu items', 'carz' ) ),
					'font-family'     => 'Manrope,sans-serif',
					'font-size'       => '15px',
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'other' => array(
					'title'           => esc_html__( 'Other', 'carz' ),
					'description'     => sprintf( $font_description, esc_html__( 'specific elements', 'carz' ) ),
					'font-family'     => 'Manrope,sans-serif',
				),
			)
		);

		// Font presets
		carz_storage_set(
			'font_presets', array(
				'karla' => array(
								'title'  => esc_html__( 'Karla', 'carz' ),
								'load_fonts' => array(
													// Google font
													array(
														'name'   => 'Dancing Script',
														'family' => 'fantasy',
														'link'   => '',
														'styles' => '300,400,700',
													),
													// Google font
													array(
														'name'   => 'Sansita Swashed',
														'family' => 'fantasy',
														'link'   => '',
														'styles' => '300,400,700',
													),
												),
								'theme_fonts' => array(
													'p'       => array(
														'font-family'     => '"Dancing Script",fantasy',
														'font-size'       => '1.25rem',
													),
													'post'    => array(
														'font-family'     => '',
													),
													'h1'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
														'font-size'       => '4em',
													),
													'h2'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h3'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h4'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h5'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h6'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'logo'    => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'button'  => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'input'   => array(
														'font-family'     => 'inherit',
													),
													'info'    => array(
														'font-family'     => 'inherit',
													),
													'menu'    => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'submenu' => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
												),
							),
				'roboto' => array(
								'title'  => esc_html__( 'Roboto', 'carz' ),
								'load_fonts' => array(
													// Google font
													array(
														'name'   => 'Noto Sans JP',
														'family' => 'serif',
														'link'   => '',
														'styles' => '300,300italic,400,400italic,700,700italic',
													),
													// Google font
													array(
														'name'   => 'Merriweather',
														'family' => 'sans-serif',
														'link'   => '',
														'styles' => '300,300italic,400,400italic,700,700italic',
													),
												),
								'theme_fonts' => array(
													'p'       => array(
														'font-family'     => '"Noto Sans JP",serif',
													),
													'post'    => array(
														'font-family'     => '',
													),
													'h1'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h2'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h3'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h4'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h5'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h6'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'logo'    => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'button'  => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'input'   => array(
														'font-family'     => 'inherit',
													),
													'info'    => array(
														'font-family'     => 'inherit',
													),
													'menu'    => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'submenu' => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
												),
							),
				'garamond' => array(
								'title'  => esc_html__( 'Garamond', 'carz' ),
								'load_fonts' => array(
													// Adobe font
													array(
														'name'   => 'Europe',
														'family' => 'sans-serif',
														'link'   => 'https://use.typekit.net/qmj1tmx.css',
														'styles' => '',
													),
													// Adobe font
													array(
														'name'   => 'Sofia Pro',
														'family' => 'sans-serif',
														'link'   => 'https://use.typekit.net/qmj1tmx.css',
														'styles' => '',
													),
												),
								'theme_fonts' => array(
													'p'       => array(
														'font-family'     => '"Sofia Pro",sans-serif',
													),
													'post'    => array(
														'font-family'     => '',
													),
													'h1'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h2'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h3'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h4'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h5'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h6'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'logo'    => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'button'  => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'input'   => array(
														'font-family'     => 'inherit',
													),
													'info'    => array(
														'font-family'     => 'inherit',
													),
													'menu'    => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'submenu' => array(
														'font-family'     => 'Europe,sans-serif',
													),
												),
							),
			)
		);
	}
}


//--------------------------------------------
// COLOR SCHEMES
//--------------------------------------------
if ( ! function_exists( 'carz_skin_setup_schemes' ) ) {
	add_action( 'after_setup_theme', 'carz_skin_setup_schemes', 1 );
	function carz_skin_setup_schemes() {

		// Theme colors for customizer
		// Attention! Inner scheme must be last in the array below
		carz_storage_set(
			'scheme_color_groups', array(
				'main'    => array(
					'title'       => esc_html__( 'Main', 'carz' ),
					'description' => esc_html__( 'Colors of the main content area', 'carz' ),
				),
				'alter'   => array(
					'title'       => esc_html__( 'Alter', 'carz' ),
					'description' => esc_html__( 'Colors of the alternative blocks (sidebars, etc.)', 'carz' ),
				),
				'extra'   => array(
					'title'       => esc_html__( 'Extra', 'carz' ),
					'description' => esc_html__( 'Colors of the extra blocks (dropdowns, price blocks, table headers, etc.)', 'carz' ),
				),
				'inverse' => array(
					'title'       => esc_html__( 'Inverse', 'carz' ),
					'description' => esc_html__( 'Colors of the inverse blocks - when link color used as background of the block (dropdowns, blockquotes, etc.)', 'carz' ),
				),
				'input'   => array(
					'title'       => esc_html__( 'Input', 'carz' ),
					'description' => esc_html__( 'Colors of the form fields (text field, textarea, select, etc.)', 'carz' ),
				),
			)
		);

		carz_storage_set(
			'scheme_color_names', array(
				'bg_color'    => array(
					'title'       => esc_html__( 'Background color', 'carz' ),
					'description' => esc_html__( 'Background color of this block in the normal state', 'carz' ),
				),
				'bg_hover'    => array(
					'title'       => esc_html__( 'Background hover', 'carz' ),
					'description' => esc_html__( 'Background color of this block in the hovered state', 'carz' ),
				),
				'bd_color'    => array(
					'title'       => esc_html__( 'Border color', 'carz' ),
					'description' => esc_html__( 'Border color of this block in the normal state', 'carz' ),
				),
				'bd_hover'    => array(
					'title'       => esc_html__( 'Border hover', 'carz' ),
					'description' => esc_html__( 'Border color of this block in the hovered state', 'carz' ),
				),
				'text'        => array(
					'title'       => esc_html__( 'Text', 'carz' ),
					'description' => esc_html__( 'Color of the text inside this block', 'carz' ),
				),
				'text_dark'   => array(
					'title'       => esc_html__( 'Text dark', 'carz' ),
					'description' => esc_html__( 'Color of the dark text (bold, header, etc.) inside this block', 'carz' ),
				),
				'text_light'  => array(
					'title'       => esc_html__( 'Text light', 'carz' ),
					'description' => esc_html__( 'Color of the light text (post meta, etc.) inside this block', 'carz' ),
				),
				'text_link'   => array(
					'title'       => esc_html__( 'Link', 'carz' ),
					'description' => esc_html__( 'Color of the links inside this block', 'carz' ),
				),
				'text_hover'  => array(
					'title'       => esc_html__( 'Link hover', 'carz' ),
					'description' => esc_html__( 'Color of the hovered state of links inside this block', 'carz' ),
				),
				'text_link2'  => array(
					'title'       => esc_html__( 'Accent 2', 'carz' ),
					'description' => esc_html__( 'Color of the accented texts (areas) inside this block', 'carz' ),
				),
				'text_hover2' => array(
					'title'       => esc_html__( 'Accent 2 hover', 'carz' ),
					'description' => esc_html__( 'Color of the hovered state of accented texts (areas) inside this block', 'carz' ),
				),
				'text_link3'  => array(
					'title'       => esc_html__( 'Accent 3', 'carz' ),
					'description' => esc_html__( 'Color of the other accented texts (buttons) inside this block', 'carz' ),
				),
				'text_hover3' => array(
					'title'       => esc_html__( 'Accent 3 hover', 'carz' ),
					'description' => esc_html__( 'Color of the hovered state of other accented texts (buttons) inside this block', 'carz' ),
				),
			)
		);

		// Default values for each color scheme
		$schemes = array(

			// Color scheme: 'default'
			'default' => array(
				'title'    => esc_html__( 'Default', 'carz' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#EEF6FA', //ok +
					'bd_color'         => '#D3E5EE', //ok +

					// Text and links colors
					'text'             => '#6A7281', //ok +
					'text_light'       => '#8F95A0', //ok +
					'text_dark'        => '#1B2333', //ok +
					'text_link'        => '#0047FF', //ok +
					'text_hover'       => '#0040E4', //ok +
					'text_link2'       => '#E72127', //ok +
					'text_hover2'      => '#D70E14', //ok +
					'text_link3'       => '#6FBCF4', //ok +
					'text_hover3'      => '#60B4F1', //ok +

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#FFFFFF', //ok +
					'alter_bg_hover'   => '#DBE9F0', //ok +
					'alter_bd_color'   => '#D3E5EE', //ok +
					'alter_bd_hover'   => '#BDD2DE', //ok +
					'alter_text'       => '#6A7281', //ok +
					'alter_light'      => '#8F95A0', //ok +
					'alter_dark'       => '#1B2333', //ok +
					'alter_link'       => '#0047FF', //ok +
					'alter_hover'      => '#0040E4', //ok +
					'alter_link2'      => '#E72127', //ok +
					'alter_hover2'     => '#D70E14', //ok +
					'alter_link3'      => '#6FBCF4', //ok +
					'alter_hover3'     => '#60B4F1', //ok +

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#172028', //ok +
					'extra_bg_hover'   => '#242C34', //ok +
					'extra_bd_color'   => '#2D363D', //ok +
					'extra_bd_hover'   => '#424A50', //ok +
					'extra_text'       => '#B8BBC2', //ok +
					'extra_light'      => '#9DA1A9', //ok +
					'extra_dark'       => '#FFFEFE', //ok +
					'extra_link'       => '#0047FF', //ok +
					'extra_hover'      => '#FFFEFE', //ok +
					'extra_link2'      => '#E72127', //ok +
					'extra_hover2'     => '#D70E14', //ok +
					'extra_link3'      => '#6FBCF4', //ok +
					'extra_hover3'     => '#60B4F1', //ok +

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //ok +
					'input_bg_hover'   => 'transparent', //ok +
					'input_bd_color'   => '#D3E5EE', //ok +
					'input_bd_hover'   => '#BDD2DE', //ok +
					'input_text'       => '#6A7281', //ok +
					'input_light'      => '#8F95A0', //ok +
					'input_dark'       => '#1B2333', //ok +

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#FFFEFE',
					'inverse_bd_hover' => '#FFFEFE',
					'inverse_text'     => '#1B2333', //ok +
					'inverse_light'    => '#FFFEFE',
					'inverse_dark'     => '#1B2333', //ok +
					'inverse_link'     => '#FFFEFE', //ok +
					'inverse_hover'    => '#FFFEFE', //ok +

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			// Color scheme: 'dark'
			'dark'    => array(
				'title'    => esc_html__( 'Dark', 'carz' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#030D16', //ok +
					'bd_color'         => '#2D363D', //ok +

					// Text and links colors
					'text'             => '#B8BBC2', //ok +
					'text_light'       => '#9DA1A9', //ok +
					'text_dark'        => '#FFFEFE', //ok +
					'text_link'        => '#0047FF', //ok +
					'text_hover'       => '#0040E4', //ok +
					'text_link2'       => '#E72127', //ok +
					'text_hover2'      => '#D70E14', //ok +
					'text_link3'       => '#6FBCF4', //ok +
					'text_hover3'      => '#60B4F1', //ok +

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#0D161F', //ok +
					'alter_bg_hover'   => '#1C252D', //ok +
					'alter_bd_color'   => '#2D363D', //ok +
					'alter_bd_hover'   => '#424A50', //ok +
					'alter_text'       => '#B8BBC2', //ok +
					'alter_light'      => '#9DA1A9', //ok +
					'alter_dark'       => '#FFFEFE', //ok +
					'alter_link'       => '#0047FF', //ok +
					'alter_hover'      => '#0040E4', //ok +
					'alter_link2'      => '#E72127', //ok +
					'alter_hover2'     => '#D70E14', //ok +
					'alter_link3'      => '#6FBCF4', //ok +
					'alter_hover3'     => '#60B4F1', //ok +

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#172028', //ok +
					'extra_bg_hover'   => '#242C34', //ok +
					'extra_bd_color'   => '#2D363D', //ok +
					'extra_bd_hover'   => '#424A50', //ok +
					'extra_text'       => '#B8BBC2', //ok +
					'extra_light'      => '#9DA1A9', //ok +
					'extra_dark'       => '#FFFEFE', //ok +
					'extra_link'       => '#0047FF', //ok +
					'extra_hover'      => '#FFFEFE', //ok +
					'extra_link2'      => '#E72127', //ok +
					'extra_hover2'     => '#D70E14', //ok +
					'extra_link3'      => '#6FBCF4', //ok +
					'extra_hover3'     => '#60B4F1', //ok +

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //ok +
					'input_bg_hover'   => 'transparent', //ok +
					'input_bd_color'   => '#2D363D', //ok +
					'input_bd_hover'   => '#424A50', //ok +
					'input_text'       => '#B8BBC2', //ok +
					'input_light'      => '#9DA1A9', //ok +
					'input_dark'       => '#FFFEFE', //ok +

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#FFFEFE', //ok +
					'inverse_bd_hover' => '#FFFEFE', //ok +
					'inverse_text'     => '#FFFEFE', //ok +
					'inverse_light'    => '#FFFEFE', //ok +
					'inverse_dark'     => '#1B2333', //ok +
					'inverse_link'     => '#FFFEFE', //ok +
					'inverse_hover'    => '#1B2333', //ok +

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			// Color scheme: 'light'
			'light' => array(
				'title'    => esc_html__( 'Light', 'carz' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#FFFFFF', //ok +
					'bd_color'         => '#D3E5EE', //ok +

					// Text and links colors
					'text'             => '#6A7281', //ok +
					'text_light'       => '#8F95A0', //ok +
					'text_dark'        => '#1B2333', //ok +
					'text_link'        => '#0047FF', //ok +
					'text_hover'       => '#0040E4', //ok +
					'text_link2'       => '#E72127', //ok +
					'text_hover2'      => '#D70E14', //ok +
					'text_link3'       => '#6FBCF4', //ok +
					'text_hover3'      => '#60B4F1', //ok +

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#EEF6FA', //ok +
					'alter_bg_hover'   => '#DBE9F0', //ok +
					'alter_bd_color'   => '#D3E5EE', //ok +
					'alter_bd_hover'   => '#BDD2DE', //ok +
					'alter_text'       => '#6A7281', //ok +
					'alter_light'      => '#8F95A0', //ok +
					'alter_dark'       => '#1B2333', //ok +
					'alter_link'       => '#0047FF', //ok +
					'alter_hover'      => '#0040E4', //ok +
					'alter_link2'      => '#E72127', //ok +
					'alter_hover2'     => '#D70E14', //ok +
					'alter_link3'      => '#6FBCF4', //ok +
					'alter_hover3'     => '#60B4F1', //ok +

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#172028', //ok +
					'extra_bg_hover'   => '#242C34', //ok +
					'extra_bd_color'   => '#2D363D', //ok +
					'extra_bd_hover'   => '#424A50', //ok +
					'extra_text'       => '#B8BBC2', //ok +
					'extra_light'      => '#9DA1A9', //ok +
					'extra_dark'       => '#FFFEFE', //ok +
					'extra_link'       => '#0047FF', //ok +
					'extra_hover'      => '#FFFEFE', //ok +
					'extra_link2'      => '#E72127', //ok +
					'extra_hover2'     => '#D70E14', //ok +
					'extra_link3'      => '#6FBCF4', //ok +
					'extra_hover3'     => '#60B4F1', //ok +

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //ok +
					'input_bg_hover'   => 'transparent', //ok +
					'input_bd_color'   => '#D3E5EE', //ok +
					'input_bd_hover'   => '#BDD2DE', //ok +
					'input_text'       => '#6A7281', //ok +
					'input_light'      => '#8F95A0', //ok +
					'input_dark'       => '#1B2333', //ok +

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#FFFEFE',
					'inverse_bd_hover' => '#FFFEFE',
					'inverse_text'     => '#1B2333', //ok +
					'inverse_light'    => '#FFFEFE',
					'inverse_dark'     => '#1B2333', //ok +
					'inverse_link'     => '#FFFEFE', //ok +
					'inverse_hover'    => '#FFFEFE', //ok +

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			// Color scheme: 'scarlet_default'
			'scarlet_default' => array(
				'title'    => esc_html__( 'Scarlet Default', 'carz' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#F5F7F8', //ok +
					'bd_color'         => '#D3D7D8', //ok +

					// Text and links colors
					'text'             => '#797C7F', //ok +
					'text_light'       => '#A6AAAE', //ok +
					'text_dark'        => '#1C1C1C', //ok +
					'text_link'        => '#FF411C', //ok +
					'text_hover'       => '#F6320C', //ok +
					'text_link2'       => '#C00E11', //ok +
					'text_hover2'      => '#A80003', //ok +
					'text_link3'       => '#FFC638', //ok +
					'text_hover3'      => '#FFBF21', //ok +

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#FFFFFF', //ok +
					'alter_bg_hover'   => '#ECEFF0', //ok +
					'alter_bd_color'   => '#D3D7D8', //ok +
					'alter_bd_hover'   => '#BFC4C6', //ok +
					'alter_text'       => '#797C7F', //ok +
					'alter_light'      => '#A6AAAE', //ok +
					'alter_dark'       => '#1C1C1C', //ok +
					'alter_link'       => '#FF411C', //ok +
					'alter_hover'      => '#F6320C', //ok +
					'alter_link2'      => '#C00E11', //ok +
					'alter_hover2'     => '#A80003', //ok +
					'alter_link3'      => '#FFC638', //ok +
					'alter_hover3'     => '#FFBF21', //ok +

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#1A1A1C', //ok +
					'extra_bg_hover'   => '#242427', //ok +
					'extra_bd_color'   => '#2F2F34', //ok +
					'extra_bd_hover'   => '#404048', //ok +
					'extra_text'       => '#C0C0C1', //ok +
					'extra_light'      => '#9C9DA0', //ok +
					'extra_dark'       => '#FFFFFF', //ok +
					'extra_link'       => '#FF411C', //ok +
					'extra_hover'      => '#FFFFFF', //ok +
					'extra_link2'      => '#C00E11', //ok +
					'extra_hover2'     => '#A80003', //ok +
					'extra_link3'      => '#FFC638', //ok +
					'extra_hover3'     => '#FFBF21', //ok +

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //ok +
					'input_bg_hover'   => 'transparent', //ok +
					'input_bd_color'   => '#D3D7D8', //ok +
					'input_bd_hover'   => '#BFC4C6', //ok +
					'input_text'       => '#797C7F', //ok +
					'input_light'      => '#A6AAAE', //ok +
					'input_dark'       => '#1C1C1C', //ok +

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#FFFFFF',
					'inverse_bd_hover' => '#FFFFFF',
					'inverse_text'     => '#1C1C1C', //ok +
					'inverse_light'    => '#FFFFFF',
					'inverse_dark'     => '#1C1C1C', //ok +
					'inverse_link'     => '#FFFFFF', //ok +
					'inverse_hover'    => '#FFFFFF', //ok +

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			// Color scheme: 'scarlet_dark'
			'scarlet_dark'    => array(
				'title'    => esc_html__( 'Scarlet Dark', 'carz' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#07070A', //ok +
					'bd_color'         => '#2F2F34', //ok +

					// Text and links colors
					'text'             => '#C0C0C1', //ok +
					'text_light'       => '#9C9DA0', //ok +
					'text_dark'        => '#FFFFFF', //ok +
					'text_link'        => '#FF411C', //ok +
					'text_hover'       => '#F6320C', //ok +
					'text_link2'       => '#C00E11', //ok +
					'text_hover2'      => '#A80003', //ok +
					'text_link3'       => '#FFC638', //ok +
					'text_hover3'      => '#FFBF21', //ok +

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#121213', //ok +
					'alter_bg_hover'   => '#19191A', //ok +
					'alter_bd_color'   => '#2F2F34', //ok +
					'alter_bd_hover'   => '#404048', //ok +
					'alter_text'       => '#C0C0C1', //ok +
					'alter_light'      => '#9C9DA0', //ok +
					'alter_dark'       => '#FFFFFF', //ok +
					'alter_link'       => '#FF411C', //ok +
					'alter_hover'      => '#F6320C', //ok +
					'alter_link2'      => '#C00E11', //ok +
					'alter_hover2'     => '#A80003', //ok +
					'alter_link3'      => '#FFC638', //ok +
					'alter_hover3'     => '#FFBF21', //ok +

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#1A1A1C', //ok +
					'extra_bg_hover'   => '#242427', //ok +
					'extra_bd_color'   => '#2F2F34', //ok +
					'extra_bd_hover'   => '#404048', //ok +
					'extra_text'       => '#C0C0C1', //ok +
					'extra_light'      => '#9C9DA0', //ok +
					'extra_dark'       => '#FFFFFF', //ok +
					'extra_link'       => '#FF411C', //ok +
					'extra_hover'      => '#FFFFFF', //ok +
					'extra_link2'      => '#C00E11', //ok +
					'extra_hover2'     => '#A80003', //ok +
					'extra_link3'      => '#FFC638', //ok +
					'extra_hover3'     => '#FFBF21', //ok +

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //ok +
					'input_bg_hover'   => 'transparent', //ok +
					'input_bd_color'   => '#3C3F47', //ok +
					'input_bd_hover'   => '#53535C', //ok +
					'input_text'       => '#C0C0C1', //ok +
					'input_light'      => '#9C9DA0', //ok +
					'input_dark'       => '#FFFFFF', //ok +

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#FFFFFF', //ok +
					'inverse_bd_hover' => '#FFFFFF', //ok +
					'inverse_text'     => '#FFFFFF', //ok +
					'inverse_light'    => '#FFFFFF', //ok +
					'inverse_dark'     => '#1C1C1C', //ok +
					'inverse_link'     => '#FFFFFF', //ok +
					'inverse_hover'    => '#1C1C1C', //ok +

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			// Color scheme: 'scarlet_light'
			'scarlet_light' => array(
				'title'    => esc_html__( 'Scarlet Light', 'carz' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#FFFFFF', //ok +
					'bd_color'         => '#D3D7D8', //ok +

					// Text and links colors
					'text'             => '#797C7F', //ok +
					'text_light'       => '#A6AAAE', //ok +
					'text_dark'        => '#1C1C1C', //ok +
					'text_link'        => '#FF411C', //ok +
					'text_hover'       => '#F6320C', //ok +
					'text_link2'       => '#C00E11', //ok +
					'text_hover2'      => '#A80003', //ok +
					'text_link3'       => '#FFC638', //ok +
					'text_hover3'      => '#FFBF21', //ok +

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#F5F7F8', //ok +
					'alter_bg_hover'   => '#ECEFF0', //ok +
					'alter_bd_color'   => '#D3D7D8', //ok +
					'alter_bd_hover'   => '#BFC4C6', //ok +
					'alter_text'       => '#797C7F', //ok +
					'alter_light'      => '#A6AAAE', //ok +
					'alter_dark'       => '#1C1C1C', //ok +
					'alter_link'       => '#FF411C', //ok +
					'alter_hover'      => '#F6320C', //ok +
					'alter_link2'      => '#C00E11', //ok +
					'alter_hover2'     => '#A80003', //ok +
					'alter_link3'      => '#FFC638', //ok +
					'alter_hover3'     => '#FFBF21', //ok +

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#1A1A1C', //ok +
					'extra_bg_hover'   => '#242427', //ok +
					'extra_bd_color'   => '#2F2F34', //ok +
					'extra_bd_hover'   => '#404048', //ok +
					'extra_text'       => '#C0C0C1', //ok +
					'extra_light'      => '#9C9DA0', //ok +
					'extra_dark'       => '#FFFFFF', //ok +
					'extra_link'       => '#FF411C', //ok +
					'extra_hover'      => '#FFFFFF', //ok +
					'extra_link2'      => '#C00E11', //ok +
					'extra_hover2'     => '#A80003', //ok +
					'extra_link3'      => '#FFC638', //ok +
					'extra_hover3'     => '#FFBF21', //ok +

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //ok +
					'input_bg_hover'   => 'transparent', //ok +
					'input_bd_color'   => '#D3D7D8', //ok +
					'input_bd_hover'   => '#BFC4C6', //ok +
					'input_text'       => '#797C7F', //ok +
					'input_light'      => '#A6AAAE', //ok +
					'input_dark'       => '#1C1C1C', //ok +

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#FFFFFF',
					'inverse_bd_hover' => '#FFFFFF',
					'inverse_text'     => '#1C1C1C', //ok +
					'inverse_light'    => '#FFFFFF',
					'inverse_dark'     => '#1C1C1C', //ok +
					'inverse_link'     => '#FFFFFF', //ok +
					'inverse_hover'    => '#FFFFFF', //ok +

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			// Color scheme: 'cerulean_default'
			'cerulean_default' => array(
				'title'    => esc_html__( 'Cerulean Default', 'carz' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#F3F7F7', //ok +
					'bd_color'         => '#E1EAEA', //ok +

					// Text and links colors
					'text'             => '#81848C', //ok +
					'text_light'       => '#ACAFB7', //ok +
					'text_dark'        => '#1F242E', //ok +
					'text_link'        => '#408FAF', //ok +
					'text_hover'       => '#3286A8', //ok +
					'text_link2'       => '#FFAA2C', //ok +
					'text_hover2'      => '#FFA41B', //ok +
					'text_link3'       => '#F54401', //ok +
					'text_hover3'      => '#EA3800', //ok +

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#FFFFFF', //ok +
					'alter_bg_hover'   => '#EDF3F3', //ok +
					'alter_bd_color'   => '#E1EAEA', //ok +
					'alter_bd_hover'   => '#D3E1E1', //ok +
					'alter_text'       => '#81848C', //ok +
					'alter_light'      => '#ACAFB7', //ok +
					'alter_dark'       => '#1F242E', //ok +
					'alter_link'       => '#408FAF', //ok +
					'alter_hover'      => '#3286A8', //ok +
					'alter_link2'      => '#FFAA2C', //ok +
					'alter_hover2'     => '#FFA41B', //ok +
					'alter_link3'      => '#F54401', //ok +
					'alter_hover3'     => '#EA3800', //ok +

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#1F2127', //ok +
					'extra_bg_hover'   => '#34363D', //ok +
					'extra_bd_color'   => '#43454A', //ok +
					'extra_bd_hover'   => '#57585C', //ok +
					'extra_text'       => '#C9CACE', //ok +
					'extra_light'      => '#A2A2A6', //ok +
					'extra_dark'       => '#FFFFFF', //ok +
					'extra_link'       => '#408FAF', //ok +
					'extra_hover'      => '#FFFFFF', //ok +
					'extra_link2'      => '#FFAA2C', //ok +
					'extra_hover2'     => '#FFA41B', //ok +
					'extra_link3'      => '#F54401', //ok +
					'extra_hover3'     => '#EA3800', //ok +

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //ok +
					'input_bg_hover'   => 'transparent', //ok +
					'input_bd_color'   => '#E1EAEA', //ok +
					'input_bd_hover'   => '#D3E1E1', //ok +
					'input_text'       => '#81848C', //ok +
					'input_light'      => '#ACAFB7', //ok +
					'input_dark'       => '#1F242E', //ok +

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#FFFEFE',
					'inverse_bd_hover' => '#FFFEFE',
					'inverse_text'     => '#1F242E', //ok +
					'inverse_light'    => '#FFFEFE',
					'inverse_dark'     => '#1F242E', //ok +
					'inverse_link'     => '#FFFEFE', //ok +
					'inverse_hover'    => '#FFFEFE', //ok +

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			// Color scheme: 'cerulean_dark'
			'cerulean_dark'    => array(
				'title'    => esc_html__( 'Cerulean Dark', 'carz' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#101115', //ok +
					'bd_color'         => '#43454A', //ok +

					// Text and links colors
					'text'             => '#C9CACE', //ok +
					'text_light'       => '#A2A2A6', //ok +
					'text_dark'        => '#FFFEFE', //ok +
					'text_link'        => '#408FAF', //ok +
					'text_hover'       => '#3286A8', //ok +
					'text_link2'       => '#FFAA2C', //ok +
					'text_hover2'      => '#FFA41B', //ok +
					'text_link3'       => '#F54401', //ok +
					'text_hover3'      => '#EA3800', //ok +

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#171A21', //ok +
					'alter_bg_hover'   => '#27292F', //ok +
					'alter_bd_color'   => '#43454A', //ok +
					'alter_bd_hover'   => '#57585C', //ok +
					'alter_text'       => '#C9CACE', //ok +
					'alter_light'      => '#A2A2A6', //ok +
					'alter_dark'       => '#FFFEFE', //ok +
					'alter_link'       => '#408FAF', //ok +
					'alter_hover'      => '#3286A8', //ok +
					'alter_link2'      => '#FFAA2C', //ok +
					'alter_hover2'     => '#FFA41B', //ok +
					'alter_link3'      => '#F54401', //ok +
					'alter_hover3'     => '#EA3800', //ok +

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#1F2127', //ok +
					'extra_bg_hover'   => '#34363D', //ok +
					'extra_bd_color'   => '#43454A', //ok +
					'extra_bd_hover'   => '#57585C', //ok +
					'extra_text'       => '#C9CACE', //ok +
					'extra_light'      => '#A2A2A6', //ok +
					'extra_dark'       => '#FFFEFE', //ok +
					'extra_link'       => '#408FAF', //ok +
					'extra_hover'      => '#FFFEFE', //ok +
					'extra_link2'      => '#FFAA2C', //ok +
					'extra_hover2'     => '#FFA41B', //ok +
					'extra_link3'      => '#F54401', //ok +
					'extra_hover3'     => '#EA3800', //ok +

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //ok +
					'input_bg_hover'   => 'transparent', //ok +
					'input_bd_color'   => '#43454A', //ok +
					'input_bd_hover'   => '#57585C', //ok +
					'input_text'       => '#C9CACE', //ok +
					'input_light'      => '#A2A2A6', //ok +
					'input_dark'       => '#FFFEFE', //ok +

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#FFFEFE', //ok +
					'inverse_bd_hover' => '#FFFEFE', //ok +
					'inverse_text'     => '#FFFEFE', //ok +
					'inverse_light'    => '#FFFEFE', //ok +
					'inverse_dark'     => '#1F242E', //ok +
					'inverse_link'     => '#FFFEFE', //ok +
					'inverse_hover'    => '#1F242E', //ok +

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			// Color scheme: 'cerulean_light'
			'cerulean_light' => array(
				'title'    => esc_html__( 'Cerulean Light', 'carz' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#FFFFFF', //ok +
					'bd_color'         => '#E1EAEA', //ok +

					// Text and links colors
					'text'             => '#81848C', //ok +
					'text_light'       => '#ACAFB7', //ok +
					'text_dark'        => '#1F242E', //ok +
					'text_link'        => '#408FAF', //ok +
					'text_hover'       => '#3286A8', //ok +
					'text_link2'       => '#FFAA2C', //ok +
					'text_hover2'      => '#FFA41B', //ok +
					'text_link3'       => '#F54401', //ok +
					'text_hover3'      => '#EA3800', //ok +

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#F3F7F7', //ok +
					'alter_bg_hover'   => '#EDF3F3', //ok +
					'alter_bd_color'   => '#E1EAEA', //ok +
					'alter_bd_hover'   => '#D3E1E1', //ok +
					'alter_text'       => '#81848C', //ok +
					'alter_light'      => '#ACAFB7', //ok +
					'alter_dark'       => '#1F242E', //ok +
					'alter_link'       => '#408FAF', //ok +
					'alter_hover'      => '#3286A8', //ok +
					'alter_link2'      => '#FFAA2C', //ok +
					'alter_hover2'     => '#FFA41B', //ok +
					'alter_link3'      => '#F54401', //ok +
					'alter_hover3'     => '#EA3800', //ok +

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#1F2127', //ok +
					'extra_bg_hover'   => '#34363D', //ok +
					'extra_bd_color'   => '#43454A', //ok +
					'extra_bd_hover'   => '#57585C', //ok +
					'extra_text'       => '#C9CACE', //ok +
					'extra_light'      => '#A2A2A6', //ok +
					'extra_dark'       => '#FFFFFF', //ok +
					'extra_link'       => '#408FAF', //ok +
					'extra_hover'      => '#FFFFFF', //ok +
					'extra_link2'      => '#FFAA2C', //ok +
					'extra_hover2'     => '#FFA41B', //ok +
					'extra_link3'      => '#F54401', //ok +
					'extra_hover3'     => '#EA3800', //ok +

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //ok +
					'input_bg_hover'   => 'transparent', //ok +
					'input_bd_color'   => '#E1EAEA', //ok +
					'input_bd_hover'   => '#D3E1E1', //ok +
					'input_text'       => '#81848C', //ok +
					'input_light'      => '#ACAFB7', //ok +
					'input_dark'       => '#1F242E', //ok +

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#FFFEFE',
					'inverse_bd_hover' => '#FFFEFE',
					'inverse_text'     => '#1F242E', //ok +
					'inverse_light'    => '#FFFEFE',
					'inverse_dark'     => '#1F242E', //ok +
					'inverse_link'     => '#FFFEFE', //ok +
					'inverse_hover'    => '#FFFEFE', //ok +

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			// Color scheme: 'greeny_default'
			'greeny_default' => array(
				'title'    => esc_html__( 'Greeny Default', 'carz' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#F0F8F6', //ok +
					'bd_color'         => '#CBD8D5', //ok +

					// Text and links colors
					'text'             => '#797C7F', //ok +
					'text_light'       => '#A5A6AA', //ok +
					'text_dark'        => '#1F242E', //ok +
					'text_link'        => '#A6C42D', //ok +
					'text_hover'       => '#96B221', //ok +
					'text_link2'       => '#1D2297', //ok +
					'text_hover2'      => '#121789', //ok +
					'text_link3'       => '#3065F5', //ok +
					'text_hover3'      => '#2256E3', //ok +

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#FFFFFF', //ok +
					'alter_bg_hover'   => '#D9E8E5', //ok +
					'alter_bd_color'   => '#CBD8D5', //ok +
					'alter_bd_hover'   => '#BECCC9', //ok +
					'alter_text'       => '#797C7F', //ok +
					'alter_light'      => '#A5A6AA', //ok +
					'alter_dark'       => '#1F242E', //ok +
					'alter_link'       => '#A6C42D', //ok +
					'alter_hover'      => '#96B221', //ok +
					'alter_link2'      => '#1D2297', //ok +
					'alter_hover2'     => '#121789', //ok +
					'alter_link3'      => '#3065F5', //ok +
					'alter_hover3'     => '#2256E3', //ok +

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#16212A', //ok +
					'extra_bg_hover'   => '#2D3741', //ok +
					'extra_bd_color'   => '#3F4851', //ok +
					'extra_bd_hover'   => '#586169', //ok +
					'extra_text'       => '#C4C8CB', //ok +
					'extra_light'      => '#999EA1', //ok +
					'extra_dark'       => '#FFFFFF', //ok +
					'extra_link'       => '#A6C42D', //ok +
					'extra_hover'      => '#FFFFFF', //ok +
					'extra_link2'      => '#1D2297', //ok +
					'extra_hover2'     => '#121789', //ok +
					'extra_link3'      => '#3065F5', //ok +
					'extra_hover3'     => '#2256E3', //ok +

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //ok +
					'input_bg_hover'   => 'transparent', //ok +
					'input_bd_color'   => '#CBD8D5', //ok +
					'input_bd_hover'   => '#BECCC9', //ok +
					'input_text'       => '#797C7F', //ok +
					'input_light'      => '#A5A6AA', //ok +
					'input_dark'       => '#1F242E', //ok +

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#FFFEFE',
					'inverse_bd_hover' => '#FFFEFE',
					'inverse_text'     => '#1F242E', //ok +
					'inverse_light'    => '#FFFEFE',
					'inverse_dark'     => '#1F242E', //ok +
					'inverse_link'     => '#FFFEFE', //ok +
					'inverse_hover'    => '#FFFEFE', //ok +

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			// Color scheme: 'greeny_dark'
			'greeny_dark'    => array(
				'title'    => esc_html__( 'Greeny Dark', 'carz' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#0E1821', //ok +
					'bd_color'         => '#3F4851', //ok +

					// Text and links colors
					'text'             => '#C4C8CB', //ok +
					'text_light'       => '#999EA1', //ok +
					'text_dark'        => '#FFFFFF', //ok +
					'text_link'        => '#A6C42D', //ok +
					'text_hover'       => '#96B221', //ok +
					'text_link2'       => '#1D2297', //ok +
					'text_hover2'      => '#121789', //ok +
					'text_link3'       => '#3065F5', //ok +
					'text_hover3'      => '#2256E3', //ok +

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#091118', //ok +
					'alter_bg_hover'   => '#222C35', //ok +
					'alter_bd_color'   => '#3F4851', //ok +
					'alter_bd_hover'   => '#586169', //ok +
					'alter_text'       => '#C4C8CB', //ok +
					'alter_light'      => '#999EA1', //ok +
					'alter_dark'       => '#FFFFFF', //ok +
					'alter_link'       => '#A6C42D', //ok +
					'alter_hover'      => '#96B221', //ok +
					'alter_link2'      => '#1D2297', //ok +
					'alter_hover2'     => '#121789', //ok +
					'alter_link3'      => '#3065F5', //ok +
					'alter_hover3'     => '#2256E3', //ok +

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#16212A', //ok +
					'extra_bg_hover'   => '#2D3741', //ok +
					'extra_bd_color'   => '#3F4851', //ok +
					'extra_bd_hover'   => '#586169', //ok +
					'extra_text'       => '#C4C8CB', //ok +
					'extra_light'      => '#999EA1', //ok +
					'extra_dark'       => '#FFFFFF', //ok +
					'extra_link'       => '#A6C42D', //ok +
					'extra_hover'      => '#FFFFFF', //ok +
					'extra_link2'      => '#1D2297', //ok +
					'extra_hover2'     => '#121789', //ok +
					'extra_link3'      => '#3065F5', //ok +
					'extra_hover3'     => '#2256E3', //ok +

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //ok +
					'input_bg_hover'   => 'transparent', //ok +
					'input_bd_color'   => '#3F4851', //ok +
					'input_bd_hover'   => '#586169', //ok +
					'input_text'       => '#C4C8CB', //ok +
					'input_light'      => '#999EA1', //ok +
					'input_dark'       => '#FFFFFF', //ok +

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#FFFFFF', //ok +
					'inverse_bd_hover' => '#FFFFFF', //ok +
					'inverse_text'     => '#FFFFFF', //ok +
					'inverse_light'    => '#FFFFFF', //ok +
					'inverse_dark'     => '#1F242E', //ok +
					'inverse_link'     => '#FFFFFF', //ok +
					'inverse_hover'    => '#1F242E', //ok +

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			// Color scheme: 'greeny_light'
			'greeny_light' => array(
				'title'    => esc_html__( 'Greeny Light', 'carz' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#FFFFFF', //ok +
					'bd_color'         => '#CBD8D5', //ok +

					// Text and links colors
					'text'             => '#797C7F', //ok +
					'text_light'       => '#A5A6AA', //ok +
					'text_dark'        => '#1F242E', //ok +
					'text_link'        => '#A6C42D', //ok +
					'text_hover'       => '#96B221', //ok +
					'text_link2'       => '#1D2297', //ok +
					'text_hover2'      => '#121789', //ok +
					'text_link3'       => '#3065F5', //ok +
					'text_hover3'      => '#2256E3', //ok +

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#F0F8F6', //ok +
					'alter_bg_hover'   => '#D9E8E5', //ok +
					'alter_bd_color'   => '#CBD8D5', //ok +
					'alter_bd_hover'   => '#BECCC9', //ok +
					'alter_text'       => '#797C7F', //ok +
					'alter_light'      => '#A5A6AA', //ok +
					'alter_dark'       => '#1F242E', //ok +
					'alter_link'       => '#A6C42D', //ok +
					'alter_hover'      => '#96B221', //ok +
					'alter_link2'      => '#1D2297', //ok +
					'alter_hover2'     => '#121789', //ok +
					'alter_link3'      => '#3065F5', //ok +
					'alter_hover3'     => '#2256E3', //ok +

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#16212A', //ok +
					'extra_bg_hover'   => '#2D3741', //ok +
					'extra_bd_color'   => '#3F4851', //ok +
					'extra_bd_hover'   => '#586169', //ok +
					'extra_text'       => '#C4C8CB', //ok +
					'extra_light'      => '#999EA1', //ok +
					'extra_dark'       => '#FFFFFF', //ok +
					'extra_link'       => '#A6C42D', //ok +
					'extra_hover'      => '#FFFFFF', //ok +
					'extra_link2'      => '#1D2297', //ok +
					'extra_hover2'     => '#121789', //ok +
					'extra_link3'      => '#3065F5', //ok +
					'extra_hover3'     => '#2256E3', //ok +

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent', //ok +
					'input_bg_hover'   => 'transparent', //ok +
					'input_bd_color'   => '#CBD8D5', //ok +
					'input_bd_hover'   => '#BECCC9', //ok +
					'input_text'       => '#797C7F', //ok +
					'input_light'      => '#A5A6AA', //ok +
					'input_dark'       => '#1F242E', //ok +

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#FFFEFE',
					'inverse_bd_hover' => '#FFFEFE',
					'inverse_text'     => '#1F242E', //ok +
					'inverse_light'    => '#FFFEFE',
					'inverse_dark'     => '#1F242E', //ok +
					'inverse_link'     => '#FFFEFE', //ok +
					'inverse_hover'    => '#FFFEFE', //ok +

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
		);
		carz_storage_set( 'schemes', $schemes );
		carz_storage_set( 'schemes_original', $schemes );

		// Add names of additional colors
		//---> For example:
		//---> carz_storage_set_array( 'scheme_color_names', 'new_color1', array(
		//---> 	'title'       => __( 'New color 1', 'carz' ),
		//---> 	'description' => __( 'Description of the new color 1', 'carz' ),
		//---> ) );


		// Additional colors for each scheme
		// Parameters:	'color' - name of the color from the scheme that should be used as source for the transformation
		//				'alpha' - to make color transparent (0.0 - 1.0)
		//				'hue', 'saturation', 'brightness' - inc/dec value for each color's component
		carz_storage_set(
			'scheme_colors_add', array(
				'bg_color_0'        => array(
					'color' => 'bg_color',
					'alpha' => 0,
				),
				'bg_color_02'       => array(
					'color' => 'bg_color',
					'alpha' => 0.2,
				),
				'bg_color_07'       => array(
					'color' => 'bg_color',
					'alpha' => 0.7,
				),
				'bg_color_08'       => array(
					'color' => 'bg_color',
					'alpha' => 0.8,
				),
				'bg_color_09'       => array(
					'color' => 'bg_color',
					'alpha' => 0.9,
				),
				'alter_bg_color_07' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0.7,
				),
				'alter_bg_color_08' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0.8,
				),
				'alter_bg_color_04' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0.4,
				),
				'alter_bg_color_00' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0,
				),
				'alter_bg_color_02' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0.2,
				),
				'alter_bd_color_02' => array(
					'color' => 'alter_bd_color',
					'alpha' => 0.2,
				),
                'alter_dark_015'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.15,
                ),
                'alter_dark_02'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.2,
                ),
                'alter_dark_05'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.5,
                ),
                'alter_dark_08'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.8,
                ),
				'alter_link_02'     => array(
					'color' => 'alter_link',
					'alpha' => 0.2,
				),
				'alter_link_07'     => array(
					'color' => 'alter_link',
					'alpha' => 0.7,
				),
				'extra_bg_color_05' => array(
					'color' => 'extra_bg_color',
					'alpha' => 0.5,
				),
				'extra_bg_color_07' => array(
					'color' => 'extra_bg_color',
					'alpha' => 0.7,
				),
				'extra_link_02'     => array(
					'color' => 'extra_link',
					'alpha' => 0.2,
				),
				'extra_link_07'     => array(
					'color' => 'extra_link',
					'alpha' => 0.7,
				),
                'text_dark_003'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.03,
                ),
                'text_dark_005'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.05,
                ),
                'text_dark_008'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.08,
                ),
				'text_dark_015'      => array(
					'color' => 'text_dark',
					'alpha' => 0.15,
				),
				'text_dark_02'      => array(
					'color' => 'text_dark',
					'alpha' => 0.2,
				),
                'text_dark_03'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.3,
                ),
                'text_dark_05'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.5,
                ),
				'text_dark_07'      => array(
					'color' => 'text_dark',
					'alpha' => 0.7,
				),
                'text_dark_08'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.8,
                ),
                'text_link_007'      => array(
                    'color' => 'text_link',
                    'alpha' => 0.07,
                ),
				'text_link_02'      => array(
					'color' => 'text_link',
					'alpha' => 0.2,
				),
                'text_link_03'      => array(
                    'color' => 'text_link',
                    'alpha' => 0.3,
                ),
				'text_link_04'      => array(
					'color' => 'text_link',
					'alpha' => 0.4,
				),
				'text_link_07'      => array(
					'color' => 'text_link',
					'alpha' => 0.7,
				),
				'text_link2_08'      => array(
                    'color' => 'text_link2',
                    'alpha' => 0.8,
                ),
                'text_link2_007'      => array(
                    'color' => 'text_link2',
                    'alpha' => 0.07,
                ),
				'text_link2_02'      => array(
					'color' => 'text_link2',
					'alpha' => 0.2,
				),
                'text_link2_03'      => array(
                    'color' => 'text_link2',
                    'alpha' => 0.3,
                ),
				'text_link2_05'      => array(
					'color' => 'text_link2',
					'alpha' => 0.5,
				),
                'text_link3_007'      => array(
                    'color' => 'text_link3',
                    'alpha' => 0.07,
                ),
				'text_link3_02'      => array(
					'color' => 'text_link3',
					'alpha' => 0.2,
				),
                'text_link3_03'      => array(
                    'color' => 'text_link3',
                    'alpha' => 0.3,
                ),
                'inverse_text_03'      => array(
                    'color' => 'inverse_text',
                    'alpha' => 0.3,
                ),
                'inverse_link_08'      => array(
                    'color' => 'inverse_link',
                    'alpha' => 0.8,
                ),
                'inverse_hover_08'      => array(
                    'color' => 'inverse_hover',
                    'alpha' => 0.8,
                ),
				'text_dark_blend'   => array(
					'color'      => 'text_dark',
					'hue'        => 2,
					'saturation' => -5,
					'brightness' => 5,
				),
				'text_link_blend'   => array(
					'color'      => 'text_link',
					'hue'        => 2,
					'saturation' => -5,
					'brightness' => 5,
				),
				'alter_link_blend'  => array(
					'color'      => 'alter_link',
					'hue'        => 2,
					'saturation' => -5,
					'brightness' => 5,
				),
			)
		);

		// Simple scheme editor: lists the colors to edit in the "Simple" mode.
		// For each color you can set the array of 'slave' colors and brightness factors that are used to generate new values,
		// when 'main' color is changed
		// Leave 'slave' arrays empty if your scheme does not have a color dependency
		carz_storage_set(
			'schemes_simple', array(
				'text_link'        => array(),
				'text_hover'       => array(),
				'text_link2'       => array(),
				'text_hover2'      => array(),
				'text_link3'       => array(),
				'text_hover3'      => array(),
				'alter_link'       => array(),
				'alter_hover'      => array(),
				'alter_link2'      => array(),
				'alter_hover2'     => array(),
				'alter_link3'      => array(),
				'alter_hover3'     => array(),
				'extra_link'       => array(),
				'extra_hover'      => array(),
				'extra_link2'      => array(),
				'extra_hover2'     => array(),
				'extra_link3'      => array(),
				'extra_hover3'     => array(),
			)
		);

		// Parameters to set order of schemes in the css
		carz_storage_set(
			'schemes_sorted', array(
				'color_scheme',
				'header_scheme',
				'menu_scheme',
				'sidebar_scheme',
				'footer_scheme',
			)
		);

		// Color presets
		carz_storage_set(
			'color_presets', array(
				'autumn' => array(
								'title'  => esc_html__( 'Autumn', 'carz' ),
								'colors' => array(
												'default' => array(
																	'text_link'  => '#d83938',
																	'text_hover' => '#f2b232',
																	),
												'dark' => array(
																	'text_link'  => '#d83938',
																	'text_hover' => '#f2b232',
																	)
												)
							),
				'green' => array(
								'title'  => esc_html__( 'Natural Green', 'carz' ),
								'colors' => array(
												'default' => array(
																	'text_link'  => '#75ac78',
																	'text_hover' => '#378e6d',
																	),
												'dark' => array(
																	'text_link'  => '#75ac78',
																	'text_hover' => '#378e6d',
																	)
												)
							),
			)
		);
	}
}

// Enqueue extra styles for frontend
if ( ! function_exists( 'carz_trx_addons_extra_styles' ) ) {
    add_action( 'wp_enqueue_scripts', 'carz_trx_addons_extra_styles', 2060 );
    function carz_trx_addons_extra_styles() {
        $carz_url = carz_get_file_url( 'extra-styles.css' );
        if ( '' != $carz_url ) {
            wp_enqueue_style( 'carz-trx-addons-extra-styles', $carz_url, array(), null );
        }
    }
}

// Activation methods
if ( ! function_exists( 'carz_clone_activation_methods' ) ) {
    add_filter( 'trx_addons_filter_activation_methods', 'carz_clone_activation_methods', 11, 1 );
    function carz_clone_activation_methods( $args ) {
        $args['elements_key'] = true;
        return $args;
    }
}
