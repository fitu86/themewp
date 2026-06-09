<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package CARZ
 * @since CARZ 1.0
 */

if ( carz_sidebar_present() ) {
	
	$carz_sidebar_type = carz_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $carz_sidebar_type && ! carz_is_layouts_available() ) {
		$carz_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $carz_sidebar_type ) {
		// Default sidebar with widgets
		$carz_sidebar_name = carz_get_theme_option( 'sidebar_widgets' );
		carz_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $carz_sidebar_name ) ) {
			dynamic_sidebar( $carz_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$carz_sidebar_id = carz_get_custom_sidebar_id();
		do_action( 'carz_action_show_layout', $carz_sidebar_id );
	}
	$carz_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $carz_out ) ) {
		$carz_sidebar_position    = carz_get_theme_option( 'sidebar_position' );
		$carz_sidebar_position_ss = carz_get_theme_option( 'sidebar_position_ss', 'below' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $carz_sidebar_position );
			echo ' sidebar_' . esc_attr( $carz_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $carz_sidebar_type );

			$carz_sidebar_scheme = apply_filters( 'carz_filter_sidebar_scheme', carz_get_theme_option( 'sidebar_scheme', 'inherit' ) );
			if ( ! empty( $carz_sidebar_scheme ) && ! carz_is_inherit( $carz_sidebar_scheme ) && 'custom' != $carz_sidebar_type ) {
				echo ' scheme_' . esc_attr( $carz_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php

			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<span id="sidebar_skip_link_anchor" class="carz_skip_link_anchor"></span>
			<?php

			do_action( 'carz_action_before_sidebar_wrap', 'sidebar' );

			// Button to show/hide sidebar on mobile
			if ( in_array( $carz_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$carz_title = apply_filters( 'carz_filter_sidebar_control_title', 'float' == $carz_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'carz' ) : '' );
				$carz_text  = apply_filters( 'carz_filter_sidebar_control_text', 'above' == $carz_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'carz' ) : '' );
				?>
				<a href="#" role="button" class="sidebar_control" title="<?php echo esc_attr( $carz_title ); ?>"><?php echo esc_html( $carz_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'carz_action_before_sidebar', 'sidebar' );
				carz_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $carz_out ) );
				do_action( 'carz_action_after_sidebar', 'sidebar' );
				?>
			</div>
			<?php

			do_action( 'carz_action_after_sidebar_wrap', 'sidebar' );

			?>
		</div>
		<div class="clearfix"></div>
		<?php
	}
}
