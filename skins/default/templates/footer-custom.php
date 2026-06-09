<?php
/**
 * The template to display default site footer
 *
 * @package CARZ
 * @since CARZ 1.0.10
 */

$carz_footer_id = carz_get_custom_footer_id();
$carz_footer_meta = get_post_meta( $carz_footer_id, 'trx_addons_options', true );
if ( ! empty( $carz_footer_meta['margin'] ) ) {
	carz_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( carz_prepare_css_value( $carz_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $carz_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $carz_footer_id ) ) ); ?>
						<?php
						$carz_footer_scheme = carz_get_theme_option( 'footer_scheme' );
						if ( ! empty( $carz_footer_scheme ) && ! carz_is_inherit( $carz_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $carz_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'carz_action_show_layout', $carz_footer_id );
	?>
</footer><!-- /.footer_wrap -->
