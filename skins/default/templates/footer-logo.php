<?php
/**
 * The template to display the site logo in the footer
 *
 * @package CARZ
 * @since CARZ 1.0.10
 */

// Logo
if ( carz_is_on( carz_get_theme_option( 'logo_in_footer' ) ) ) {
	$carz_logo_image = carz_get_logo_image( 'footer' );
	$carz_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $carz_logo_image['logo'] ) || ! empty( $carz_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $carz_logo_image['logo'] ) ) {
					$carz_attr = carz_getimagesize( $carz_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $carz_logo_image['logo'] ) . '"'
								. ( ! empty( $carz_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $carz_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'carz' ) . '"'
								. ( ! empty( $carz_attr[3] ) ? ' ' . wp_kses_data( $carz_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $carz_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $carz_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
