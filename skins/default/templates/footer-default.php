<?php
/**
 * The template to display default site footer
 *
 * @package CARZ
 * @since CARZ 1.0.10
 */

?>
<footer class="footer_wrap footer_default
<?php
$carz_footer_scheme = carz_get_theme_option( 'footer_scheme' );
if ( ! empty( $carz_footer_scheme ) && ! carz_is_inherit( $carz_footer_scheme  ) ) {
	echo ' scheme_' . esc_attr( $carz_footer_scheme );
}
?>
				">
	<?php

	// Footer widgets area
	get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/footer-widgets' ) );

	// Logo
	get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/footer-logo' ) );

	// Socials
	get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/footer-socials' ) );

	// Copyright area
	get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/footer-copyright' ) );

	?>
</footer><!-- /.footer_wrap -->
