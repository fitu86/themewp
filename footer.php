<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package CARZ
 * @since CARZ 1.0
 */

							do_action( 'carz_action_page_content_end_text' );
							
							// Widgets area below the content
							carz_create_widgets_area( 'widgets_below_content' );
						
							do_action( 'carz_action_page_content_end' );
							?>
						</div>
						<?php
						
						do_action( 'carz_action_after_page_content' );

						// Show main sidebar
						get_sidebar();

						do_action( 'carz_action_content_wrap_end' );
						?>
					</div>
					<?php

					do_action( 'carz_action_after_content_wrap' );

					// Widgets area below the page and related posts below the page
					$carz_body_style = carz_get_theme_option( 'body_style' );
					$carz_widgets_name = carz_get_theme_option( 'widgets_below_page', 'hide' );
					$carz_show_widgets = ! carz_is_off( $carz_widgets_name ) && is_active_sidebar( $carz_widgets_name );
					$carz_show_related = carz_is_single() && carz_get_theme_option( 'related_position', 'below_content' ) == 'below_page';
					if ( $carz_show_widgets || $carz_show_related ) {
						if ( 'fullscreen' != $carz_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $carz_show_related ) {
							do_action( 'carz_action_related_posts' );
						}

						// Widgets area below page content
						if ( $carz_show_widgets ) {
							carz_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $carz_body_style ) {
							?>
							</div>
							<?php
						}
					}
					do_action( 'carz_action_page_content_wrap_end' );
					?>
			</div>
			<?php
			do_action( 'carz_action_after_page_content_wrap' );

			// Don't display the footer elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ( ! carz_is_singular( 'post' ) && ! carz_is_singular( 'attachment' ) ) || ! in_array ( carz_get_value_gp( 'action' ), array( 'full_post_loading', 'prev_post_loading' ) ) ) {
				
				// Skip link anchor to fast access to the footer from keyboard
				?>
				<span id="footer_skip_link_anchor" class="carz_skip_link_anchor"></span>
				<?php

				do_action( 'carz_action_before_footer' );

				// Footer
				$carz_footer_type = carz_get_theme_option( 'footer_type' );
				if ( 'custom' == $carz_footer_type && ! carz_is_layouts_available() ) {
					$carz_footer_type = 'default';
				}
				get_template_part( apply_filters( 'carz_filter_get_template_part', "templates/footer-" . sanitize_file_name( $carz_footer_type ) ) );

				do_action( 'carz_action_after_footer' );

			}
			?>

			<?php do_action( 'carz_action_page_wrap_end' ); ?>

		</div>

		<?php do_action( 'carz_action_after_page_wrap' ); ?>

	</div>

	<?php do_action( 'carz_action_after_body' ); ?>

	<?php wp_footer(); ?>

</body>
</html>