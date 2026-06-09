<?php
/**
 * The Header: Logo and main menu
 *
 * @package CARZ
 * @since CARZ 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js<?php
	// Class scheme_xxx need in the <html> as context for the <body>!
	echo ' scheme_' . esc_attr( carz_get_theme_option( 'color_scheme' ) );
?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}

	$carz_full_post_loading = ( carz_is_singular( 'post' ) || carz_is_singular( 'attachment' ) ) && carz_get_value_gp( 'action' ) == 'full_post_loading';
	$carz_prev_post_loading = ( carz_is_singular( 'post' ) || carz_is_singular( 'attachment' ) ) && carz_get_value_gp( 'action' ) == 'prev_post_loading';

	// Don't display the short links while actions 'full_post_loading' and 'prev_post_loading'
	if ( ! $carz_full_post_loading && ! $carz_prev_post_loading ) {
		// Short links to fast access to the content, sidebar and footer from the keyboard
		?><a class="skip-link carz_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'carz_filter_skip_links_tabindex', 0 ) ); ?>"><?php esc_html_e( "Skip to content", 'carz' ); ?></a><?php
		if ( carz_sidebar_present() ) {
			?><a class="skip-link carz_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'carz_filter_skip_links_tabindex', 0 ) ); ?>"><?php esc_html_e( "Skip to sidebar", 'carz' ); ?></a><?php
		}
		?><a class="skip-link carz_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'carz_filter_skip_links_tabindex', 0 ) ); ?>"><?php esc_html_e( "Skip to footer", 'carz' ); ?></a><?php
	}

	do_action( 'carz_action_before_body' );
	?>

	<div class="<?php echo esc_attr( apply_filters( 'carz_filter_body_wrap_class', 'body_wrap' ) ); ?>" <?php do_action('carz_action_body_wrap_attributes'); ?>>

		<?php do_action( 'carz_action_before_page_wrap' ); ?>

		<div class="<?php echo esc_attr( apply_filters( 'carz_filter_page_wrap_class', 'page_wrap' ) ); ?>" <?php do_action('carz_action_page_wrap_attributes'); ?>>

			<?php do_action( 'carz_action_page_wrap_start' ); ?>

			<?php

			// Don't display the header elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ! $carz_full_post_loading && ! $carz_prev_post_loading ) {

				do_action( 'carz_action_before_header' );

				// Header
				$carz_header_type = carz_get_theme_option( 'header_type' );
				if ( 'custom' == $carz_header_type && ! carz_is_layouts_available() ) {
					$carz_header_type = 'default';
				}
				get_template_part( apply_filters( 'carz_filter_get_template_part', "templates/header-" . sanitize_file_name( $carz_header_type ) ) );

				// Side menu
				if ( in_array( carz_get_theme_option( 'menu_side', 'none' ), array( 'left', 'right' ) ) ) {
					get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/header-navi-side' ) );
				}

				// Mobile menu
				if ( apply_filters( 'carz_filter_use_navi_mobile', carz_sc_layouts_showed( 'menu_button' ) || $carz_header_type == 'default' ) ) {
					get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/header-navi-mobile' ) );
				}

				do_action( 'carz_action_after_header' );

			}
			?>

			<?php do_action( 'carz_action_before_page_content_wrap' ); ?>

			<div class="page_content_wrap<?php
				if ( carz_is_off( carz_get_theme_option( 'remove_margins' ) ) ) {
					if ( empty( $carz_header_type ) ) {
						$carz_header_type = carz_get_theme_option( 'header_type' );
					}
					if ( 'custom' == $carz_header_type && carz_is_layouts_available() ) {
						$carz_header_id = carz_get_custom_header_id();
						if ( $carz_header_id > 0 ) {
							$carz_header_meta = carz_get_custom_layout_meta( $carz_header_id );
							if ( ! empty( $carz_header_meta['margin'] ) ) {
								?> page_content_wrap_custom_header_margin<?php
							}
						}
					}
					$carz_footer_type = carz_get_theme_option( 'footer_type' );
					if ( 'custom' == $carz_footer_type && carz_is_layouts_available() ) {
						$carz_footer_id = carz_get_custom_footer_id();
						if ( $carz_footer_id ) {
							$carz_footer_meta = carz_get_custom_layout_meta( $carz_footer_id );
							if ( ! empty( $carz_footer_meta['margin'] ) ) {
								?> page_content_wrap_custom_footer_margin<?php
							}
						}
					}
				}
				do_action( 'carz_action_page_content_wrap_class', $carz_prev_post_loading );
				?>"<?php
				if ( apply_filters( 'carz_filter_is_prev_post_loading', $carz_prev_post_loading ) ) {
					?> data-single-style="<?php echo esc_attr( carz_get_theme_option( 'single_style' ) ); ?>"<?php
				}
				do_action( 'carz_action_page_content_wrap_data', $carz_prev_post_loading );
			?>>
				<?php
				do_action( 'carz_action_page_content_wrap', $carz_full_post_loading || $carz_prev_post_loading );

				// Single posts banner
				if ( apply_filters( 'carz_filter_single_post_header', carz_is_singular( 'post' ) || carz_is_singular( 'attachment' ) ) ) {
					if ( $carz_prev_post_loading ) {
						if ( carz_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) != 'article' ) {
							do_action( 'carz_action_between_posts' );
						}
					}
					// Single post thumbnail and title
					$carz_path = apply_filters( 'carz_filter_get_template_part', 'templates/single-styles/' . carz_get_theme_option( 'single_style' ) );
					if ( carz_get_file_dir( $carz_path . '.php' ) != '' ) {
						get_template_part( $carz_path );
					}
				}

				// Widgets area above page
				$carz_body_style   = carz_get_theme_option( 'body_style' );
				$carz_widgets_name = carz_get_theme_option( 'widgets_above_page', 'hide' );
				$carz_show_widgets = ! carz_is_off( $carz_widgets_name ) && is_active_sidebar( $carz_widgets_name );
				if ( $carz_show_widgets ) {
					if ( 'fullscreen' != $carz_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					carz_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $carz_body_style ) {
						?>
						</div>
						<?php
					}
				}

				// Content area
				do_action( 'carz_action_before_content_wrap' );
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $carz_body_style ? '_fullscreen' : ''; ?>">

					<?php do_action( 'carz_action_content_wrap_start' ); ?>

					<div class="content">
						<?php
						do_action( 'carz_action_page_content_start' );

						// Skip link anchor to fast access to the content from keyboard
						?>
						<span id="content_skip_link_anchor" class="carz_skip_link_anchor"></span>
						<?php
						// Single posts banner between prev/next posts
						if ( ( carz_is_singular( 'post' ) || carz_is_singular( 'attachment' ) )
							&& $carz_prev_post_loading 
							&& carz_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) == 'article'
						) {
							do_action( 'carz_action_between_posts' );
						}

						// Widgets area above content
						carz_create_widgets_area( 'widgets_above_content' );

						do_action( 'carz_action_page_content_start_text' );
