<?php
/**
 * The template to display default site header
 *
 * @package CARZ
 * @since CARZ 1.0
 */

$carz_header_css   = '';
$carz_header_image = get_header_image();
$carz_header_video = carz_get_header_video();
if ( ! empty( $carz_header_image ) && carz_trx_addons_featured_image_override( is_singular() || carz_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$carz_header_image = carz_get_current_mode_image( $carz_header_image );
}

?><header class="top_panel top_panel_default
	<?php
	echo ! empty( $carz_header_image ) || ! empty( $carz_header_video ) ? ' with_bg_image' : ' without_bg_image';
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

	// Main menu
	get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/header-navi' ) );

	// Mobile header
	if ( carz_is_on( carz_get_theme_option( 'header_mobile_enabled' ) ) ) {
		get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/header-mobile' ) );
	}

	// Page title and breadcrumbs area
	if ( ! is_single() ) {
		get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/header-title' ) );
	}

	// Header widgets area
	get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/header-widgets' ) );
	?>
</header>
