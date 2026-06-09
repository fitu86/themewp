<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package CARZ
 * @since CARZ 1.0
 */

// Page (category, tag, archive, author) title

if ( carz_need_page_title() ) {
	carz_sc_layouts_showed( 'title', true );
	carz_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								carz_show_post_meta(
									apply_filters(
										'carz_filter_post_meta_args', array(
											'components' => join( ',', carz_array_get_keys_by_value( carz_get_theme_option( 'meta_parts' ) ) ),
											'counters'   => join( ',', carz_array_get_keys_by_value( carz_get_theme_option( 'counters' ) ) ),
											'seo'        => carz_is_on( carz_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$carz_blog_title           = carz_get_blog_title();
							$carz_blog_title_text      = '';
							$carz_blog_title_class     = '';
							$carz_blog_title_link      = '';
							$carz_blog_title_link_text = '';
							if ( is_array( $carz_blog_title ) ) {
								$carz_blog_title_text      = $carz_blog_title['text'];
								$carz_blog_title_class     = ! empty( $carz_blog_title['class'] ) ? ' ' . $carz_blog_title['class'] : '';
								$carz_blog_title_link      = ! empty( $carz_blog_title['link'] ) ? $carz_blog_title['link'] : '';
								$carz_blog_title_link_text = ! empty( $carz_blog_title['link_text'] ) ? $carz_blog_title['link_text'] : '';
							} else {
								$carz_blog_title_text = $carz_blog_title;
							}
							?>
							<h1 class="sc_layouts_title_caption<?php echo esc_attr( $carz_blog_title_class ); ?>"<?php
								if ( carz_is_on( carz_get_theme_option( 'seo_snippets' ) ) ) {
									?> itemprop="headline"<?php
								}
							?>>
								<?php
								$carz_top_icon = carz_get_term_image_small();
								if ( ! empty( $carz_top_icon ) ) {
									$carz_attr = carz_getimagesize( $carz_top_icon );
									?>
									<img src="<?php echo esc_url( $carz_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'carz' ); ?>"
										<?php
										if ( ! empty( $carz_attr[3] ) ) {
											carz_show_layout( $carz_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $carz_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $carz_blog_title_link ) && ! empty( $carz_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $carz_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $carz_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( ! is_paged() && ( is_category() || is_tag() || is_tax() ) ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'carz_action_breadcrumbs' );
						$carz_breadcrumbs = ob_get_contents();
						ob_end_clean();
						carz_show_layout( $carz_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
