<?php
/**
 * The template to display the widgets area in the header
 *
 * @package CARZ
 * @since CARZ 1.0
 */

// Header sidebar
$carz_header_name    = carz_get_theme_option( 'header_widgets' );
$carz_header_present = ! carz_is_off( $carz_header_name ) && is_active_sidebar( $carz_header_name );
if ( $carz_header_present ) {
	carz_storage_set( 'current_sidebar', 'header' );
	$carz_header_wide = carz_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $carz_header_name ) ) {
		dynamic_sidebar( $carz_header_name );
	}
	$carz_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $carz_widgets_output ) ) {
		$carz_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $carz_widgets_output );
		$carz_need_columns   = strpos( $carz_widgets_output, 'columns_wrap' ) === false;
		if ( $carz_need_columns ) {
			$carz_columns = max( 0, (int) carz_get_theme_option( 'header_columns' ) );
			if ( 0 == $carz_columns ) {
				$carz_columns = min( 6, max( 1, carz_tags_count( $carz_widgets_output, 'aside' ) ) );
			}
			if ( $carz_columns > 1 ) {
				$carz_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $carz_columns ) . ' widget', $carz_widgets_output );
			} else {
				$carz_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $carz_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<?php do_action( 'carz_action_before_sidebar_wrap', 'header' ); ?>
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $carz_header_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $carz_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'carz_action_before_sidebar', 'header' );
				carz_show_layout( $carz_widgets_output );
				do_action( 'carz_action_after_sidebar', 'header' );
				if ( $carz_need_columns ) {
					?>
					</div>	<!-- /.columns_wrap -->
					<?php
				}
				if ( ! $carz_header_wide ) {
					?>
					</div>	<!-- /.content_wrap -->
					<?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
			<?php do_action( 'carz_action_after_sidebar_wrap', 'header' ); ?>
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
