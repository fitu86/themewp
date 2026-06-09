<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package CARZ
 * @since CARZ 1.0.10
 */

// Footer sidebar
$carz_footer_name    = carz_get_theme_option( 'footer_widgets' );
$carz_footer_present = ! carz_is_off( $carz_footer_name ) && is_active_sidebar( $carz_footer_name );
if ( $carz_footer_present ) {
	carz_storage_set( 'current_sidebar', 'footer' );
	$carz_footer_wide = carz_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $carz_footer_name ) ) {
		dynamic_sidebar( $carz_footer_name );
	}
	$carz_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $carz_out ) ) {
		$carz_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $carz_out );
		$carz_need_columns = true;   //or check: strpos($carz_out, 'columns_wrap')===false;
		if ( $carz_need_columns ) {
			$carz_columns = max( 0, (int) carz_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $carz_columns ) {
				$carz_columns = min( 4, max( 1, carz_tags_count( $carz_out, 'aside' ) ) );
			}
			if ( $carz_columns > 1 ) {
				$carz_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $carz_columns ) . ' widget', $carz_out );
			} else {
				$carz_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $carz_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<?php do_action( 'carz_action_before_sidebar_wrap', 'footer' ); ?>
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $carz_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $carz_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'carz_action_before_sidebar', 'footer' );
				carz_show_layout( $carz_out );
				do_action( 'carz_action_after_sidebar', 'footer' );
				if ( $carz_need_columns ) {
					?>
					</div><!-- /.columns_wrap -->
					<?php
				}
				if ( ! $carz_footer_wide ) {
					?>
					</div><!-- /.content_wrap -->
					<?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
			<?php do_action( 'carz_action_after_sidebar_wrap', 'footer' ); ?>
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
