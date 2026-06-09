<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package CARZ
 * @since CARZ 1.0.06
 */

$carz_header_css   = '';
$carz_header_image = get_header_image();
$carz_header_video = carz_get_header_video();
if ( ! empty( $carz_header_image ) && carz_trx_addons_featured_image_override( is_singular() || carz_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$carz_header_image = carz_get_current_mode_image( $carz_header_image );
}

$carz_header_id = carz_get_custom_header_id();
$carz_header_meta = get_post_meta( $carz_header_id, 'trx_addons_options', true );
if ( ! empty( $carz_header_meta['margin'] ) ) {
	carz_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( carz_prepare_css_value( $carz_header_meta['margin'] ) ) ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $carz_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $carz_header_id ) ) ); ?>
				<?php
				echo ! empty( $carz_header_image ) || ! empty( $carz_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
				if ( '' != $carz_header_video ) {
					echo ' with_bg_video';
				}
				if ( '' != $carz_header_image ) {
					echo ' ' . esc_attr( carz_add_inline_css_class( 'background-image: url(' . esc_url( $carz_header_image ) . ');' ) );
				}
				if ( is_single() && has_post_thumbnail() ) {
					echo ' with_featured_image';
				}
				if ( carz_is_on( carz_get_theme_option( 'header_fullheight' ) ) ) {
					echo ' header_fullheight carz-full-height';
				}
				$carz_header_scheme = carz_get_theme_option( 'header_scheme' );
				if ( ! empty( $carz_header_scheme ) && ! carz_is_inherit( $carz_header_scheme  ) ) {
					echo ' scheme_' . esc_attr( $carz_header_scheme );
				}
				?>
">
	<?php

	// Background video
	if ( ! empty( $carz_header_video ) ) {
		get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/header-video' ) );
	}

	// Custom header's layout
	do_action( 'carz_action_show_layout', $carz_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>
