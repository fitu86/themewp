<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package CARZ
 * @since CARZ 1.0
 */

$carz_args = get_query_var( 'carz_logo_args' );

// Site logo
$carz_logo_type   = isset( $carz_args['type'] ) ? $carz_args['type'] : '';
$carz_logo_image  = carz_get_logo_image( $carz_logo_type );
$carz_logo_text   = carz_is_on( carz_get_theme_option( 'logo_text' ) ) ? get_bloginfo( 'name' ) : '';
$carz_logo_slogan = get_bloginfo( 'description', 'display' );
if ( ! empty( $carz_logo_image['logo'] ) || ! empty( $carz_logo_text ) ) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( ! empty( $carz_logo_image['logo'] ) ) {
			if ( empty( $carz_logo_type ) && function_exists( 'the_custom_logo' ) && is_numeric($carz_logo_image['logo']) && (int) $carz_logo_image['logo'] > 0 ) {
				the_custom_logo();
			} else {
				$carz_attr = carz_getimagesize( $carz_logo_image['logo'] );
				echo '<img src="' . esc_url( $carz_logo_image['logo'] ) . '"'
						. ( ! empty( $carz_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $carz_logo_image['logo_retina'] ) . ' 2x"' : '' )
						. ' alt="' . esc_attr( $carz_logo_text ) . '"'
						. ( ! empty( $carz_attr[3] ) ? ' ' . wp_kses_data( $carz_attr[3] ) : '' )
						. '>';
			}
		} else {
			carz_show_layout( carz_prepare_macros( $carz_logo_text ), '<span class="logo_text">', '</span>' );
			carz_show_layout( carz_prepare_macros( $carz_logo_slogan ), '<span class="logo_slogan">', '</span>' );
		}
		?>
	</a>
	<?php
}
